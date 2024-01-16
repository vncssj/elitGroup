#Teste ElitGroup

## 1. Inserir um código em Javascript no site de usuários da BigCommerce
### Existem algumas formas de inserir scripts num storefront page, uma delas por meio do painel de controle e outra por meio da API REST que é nosso objetivo.
Conforme a documentação oficial do bigcommerce na página [BigCommerce Rest Scripts](https://developer.bigcommerce.com/docs/rest-management/scripts)

Deve ser feita uma requisição usando o método http **POST** na url "https://api.bigcommerce.com/stores/{store_hash}/v3/content/scripts".  

Conforme descrito nas requisições do teste adicionei o atributo opicionais:   

`load_method` como _"async"_ pois confome o manual de integração do Popconvert o script deve ser adicionado assincronamente para não atrapalhar o fluxo de carregamento das página do cliente,   
`location` como _"head"_ pois conforme as instruções do manual de integração do Popconvert o script deve ser adicionado do `head` das páginas do site,  
`visibility` como _"all_pages"_ pois conforme os requisitos os script deve ser carregado em todas as páginas,  
`kind` como _"script_tag"_ pois conforme o manual do popconcert o conteúdo disponibilizado para ser inserido possui código JS,
`html` nessa tag deve ser incluido o script disponibilizado no sistema da popconvert

```
{
  "name": "Popconvert",
  "description": "Add popconvert on your ecommerce",
  "auto_uninstall": true,
  "load_method": "async",
  "location": "head",
  "visibility": "all_pages",
  "kind": "script_tag",
  "html": "
    <script>
  (function (w, d, s, o, f, js, fjs) {
    w.PopConvert = o;
    w[o] = w[o] || function () {
      (w[o].q = w[o].q || []).push(arguments)
    };
    js = d.createElement(s), fjs = d.getElementsByTagName(s)[0];
    js.id = o;
    js.src = f;
    js.async = 1;
    fjs.parentNode.insertBefore(js, fjs)
  }(window, document, 'script', 'pcw', 'https://cdn.popconvert.com.br/widget/popconvert.js'))
</script>
  "
}
```

## 2. Salvar o email e nome de leads obtidos pela Popconvert na BigCommerce como inscritos

Após coletar os leads fornecidos pela Popconvert (talvez por uma api ou uma planilha), deve-se rodar um script com uma iteração na rota "https://api.bigcommerce.com/stores/{store_hash}/v3/customers/subscribers" usando o método **POST**,  
coforme documentação do BigCommerce na página [BigCommerce Rest Subscribers](https://api.bigcommerce.com/stores/{store_hash}/v3/customers/subscribers). Segundo os requisitos, devem ser levados em consideração picos de obtenção de leads, e caso os serviços funcionem em sincronia (Assim que a lead é capturada é feita uma requisição para cadastro de subscriber na BigCommerce), deve ser considerados serviços de filas, para que o servidor faça o máximo de requisições possíveis, sem sofrer de lentidões e para que as leads não sejam perdidas.

Conforme descrito na documentação do BigCommerce esse deve ser o corpo da requisição (como nenhum deles é obrigatório coloquei apenas aqueles que fazem sentido pra mim agora, pois os campos opicionais podem ter valores ou não de acordo com a lógica de negócios):

```
{
  "email": "joao@email.com",
  "first_name": "Paulo",
  "last_name": "Vieira",
  "source": "PopConvert"
}
```

## 3. Refletir na Popconvert operações feitas sobre cupons na Bigcommerce e vice-versa. Essas operações incluem criação, edição, e deleção de cupons.

Conforme a documentação do Bigcommerce na página [BigCommerce Rest Promotions](https://developer.bigcommerce.com/beta/promotions/rest-management).

Antes de criar um cupom deve ser criada uma promoção com no minimo um nome e suas regras, para a partir dela serem criados os cupons.

A rota para criar uma promoção é "https://api.bigcommerce.com/stores/{store_hash}/v3/promotions", e via **POST** deve ser enviado um JSON como esse:
```
{
  "name": "Buy Product X Get Free Shipping",
  "channels": [
    {
      "id": 1
    }
  ],
  "customer": {
    "group_ids": [
      1,
      2,
      3
    ],
    "minimum_order_count": 0,
    "excluded_group_ids": [
      1,
      2,
      3
    ],
    "segments": {
      "id": [
        "ccec121a-f9bc-4a04-809e-1fe0d8ae7fdd"
      ]
    }
  },
  "rules": [
    {
      "action": {
        "cart_value": {
          "discount": {
            "fixed_amount": "12.95"
          }
        }
      },
      "apply_once": true,
      "stop": true,
      "condition": {
        "cart": {
          "items": {
            "brands": [
              1,
              2,
              3
            ]
          },
          "minimum_spend": "12.95",
          "minimum_quantity": 1
        }
      }
    }
  ],
  "max_uses": 10,
  "status": "ENABLED",
  "start_date": "2005-12-30T01:02:03+00:00",
  "end_date": "2025-12-30T01:02:03+00:00",
  "stop": false,
  "can_be_used_with_other_promotions": false,
  "currency_code": "USD",
  "notifications": [
    {
      "content": "Congratulations! Youʼve received a free %ACTION.FREE_PRODUCT%!",
      "type": "UPSELL",
      "locations": [
        "HOME_PAGE",
        "PRODUCT_PAGE",
        "CART_PAGE",
        "CHECKOUT_PAGE"
      ]
    }
  ],
  "shipping_address": {
    "countries": [
      {
        "iso2_country_code": "US"
      }
    ]
  },
  "schedule": {
    "week_frequency": 2,
    "week_days": [
      "Monday"
    ],
    "daily_start_time": "01:20:00",
    "daily_end_time": "23:59:00"
  },
  "coupon_overrides_automatic_when_offering_higher_discounts": false
}
```

A partir de uma promoção criada, podemos criar um cupom fazendo uma requisição **POST** na rota https://api.bigcommerce.com/stores/{store_hash}/v3/promotions/{promotion_id}/codes
e no body pode conter os seguintes atributos:

```
{
  "code": "string",
  "max_uses": 10,
  "max_uses_per_customer": 5
}
```
 Então sempre que um cliente a partir do sistema da Popconver criar uma promoção e consequentemente um cupom essas requisições devem ser feitas para que sejam inseridos também na plataforma da BigCommerce.

 Já para serem obter os cupons criados a partir do BigCommerce, devem ser criadas rotinas para atualização do sistema da Popconvert a partir das rotas:

 [Para pegar todas as promoções](https://api.bigcommerce.com/stores/{store_hash}/v3/promotions)  
 [Para pegar apenas uma promoção](https://api.bigcommerce.com/stores/{store_hash}/v3/promotions/{id})  
 [Para pegar todos os cupons criados](https://api.bigcommerce.com/stores/{store_hash}/v3/promotions/{promotion_id}/codes)  
 
Deve ser levado em consideração a frequencia que as promoções e os cupons são criados pela plataforma da BigCommerce para que seja analisada a possibilidade de implementação de um cache das promoções e dos cupons.

Além dessas rotas existem rotas para alteração e exclusão de promoções e cupons na documentação

* Para o acesso de qualquer um dos endpoints da BigCommerce, previamente deve ser feita a devida autenticação, e estar em posse do "X-Auth-Token" e da "store_hash" para que se tenha sucesso nas solicitações. 
