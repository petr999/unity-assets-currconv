<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use App\Controller\CurrConvController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use UnityAssets\CurrConv;

class CurrConvFuncTest extends WebTestCase
{
    public function testHttpCodeAndHeader(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v0');
        $response =  $client->getResponse();
        $statusCode = $response->getStatusCode();
        $this->assertEquals(404, $statusCode);

        $client->request('GET', '/api/v0/curr/conv');
        $response =  $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

        // baseSum validate
        $client->request('GET', '/api/v0/curr/conv?baseCurr=RUB&targCurr=USD&baseSum=a');
        $response =  $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

        $client->request('GET', '/api/v0/curr/conv?baseCurr=RUB&targCurr=USD&baseSum=0');
        $response =  $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

        // baseCurr type validate
        $client->request('GET', '/api/v0/curr/conv?baseCurr=RUB&targCurr=USD&baseSum=1');
        $response =  $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

        $client->request('GET', '/api/v0/curr/conv?baseCurr=&targCurr=USD&baseSum=1');
        $response =  $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

        // targCurr type validate
        $client->request('GET', '/api/v0/curr/conv?baseCurr=RUB&targCurr=USD&baseSum=2');
        $response =  $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

        $client->request('GET', '/api/v0/curr/conv?baseCurr=RUB&targCurr=&baseSum=2');
        $response =  $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

    }

    public function testCurrConvFunc(): void
    {
        $client = static::createClient();

        // targCurr nonnexistent
        $client->request('GET', '/api/v0/curr/conv?baseCurr=RUB&targCurr=xUSD&baseSum=2');
        $response =  $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

        // baseCurr nonexistent
        $client->request('GET', '/api/v0/curr/conv?baseCurr=xRUB&targCurr=USD&baseSum=2');
        $response =  $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $contentType = $response->headers->get('content-type');
        $this->assertEquals('application/json', $contentType);

        $client->request('GET', '/api/v0/curr/conv?baseCurr=RUB&targCurr=USD&baseSum=120.15');
        $response =  $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content =  0 + $response->getContent();
        $this->assertTrue( is_numeric( $content ) );
        $this->assertTrue( $content < 120.15  ); // RUB is always cheaper than USD

        $client->request('GET', '/api/v0/curr/conv?baseCurr=GBP&targCurr=USD&baseSum=12011.155');
        $response =  $client->getResponse();
        $this->assertEquals( 200, $response->getStatusCode() );
        $content =  0 + $response->getContent();
        $this->assertTrue( is_numeric( $content ) );
        $this->assertTrue( $content > 12011.15  ); // USD is always cheaper than GBP
    }

    public function testCurrConv(): void
    {
        $currConv = new CurrConv();
        $this->assertTrue( is_a( $currConv, 'UnityAssets\CurrConv' ) );
        // $currConv->convertBaseTargSum( 'RUB', 'USD' , 1 );
    }
}
