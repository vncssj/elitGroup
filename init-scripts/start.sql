-- start.sql
-- CREATE DATABASE elitgroup;

CREATE TABLE IF NOT EXISTS public.tb_tipos_atendimento (
        ta_id           varchar(04)  not null,
        cv_id           integer      not null,
        ta_descricao    varchar(40)  not null,
        ta_ativo        VARCHAR(1)   DEFAULT 'S',
        CONSTRAINT tb_tipos_atendimento_pk PRIMARY KEY(ta_id, cv_id)
      );

insert into tb_tipos_atendimento (ta_id, cv_id, ta_descricao, ta_ativo) values ('ME',100,'Atendimento médico'         ,'S');
insert into tb_tipos_atendimento (ta_id, cv_id, ta_descricao, ta_ativo) values ('CX',100,'Atendimento Caixa'          ,'S');
insert into tb_tipos_atendimento (ta_id, cv_id, ta_descricao, ta_ativo) values ('AT',100,'Atendimento/Administrativo' ,'S');
insert into tb_tipos_atendimento (ta_id, cv_id, ta_descricao, ta_ativo) values ('LB',100,'Laboratório'                ,'S');