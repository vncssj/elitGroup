<?php

namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;

class TiposAtendimentosControllerTest extends TestCase
{
    protected $http;


    public function setUp(): void
    {
        $this->http = new \GuzzleHttp\Client(['http_errors' => false, 'base_uri' => 'http://localhost:80']);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    public function testRoot()
    {
        $response = $this->http->request('GET', '/', [
            'headers' => [
                'Authorization' =>
                'Bearer ZWxpdGdyb3VwOjEyMzQ1Ng=='

            ]
        ]);
        $this->assertEquals('Wellcome, access the route /tiposAtendimentos to get all types of attendments', $response->getBody()->getContents());
        $this->assertEquals(200, $response->getStatusCode());
    }

    function testGetAll()
    {
        $response = $this->http->request('GET', '/tiposAtendimentos', [
            'headers' => [
                'Authorization' =>
                'Bearer ZWxpdGdyb3VwOjEyMzQ1Ng=='
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
