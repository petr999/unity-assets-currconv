<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use App\Controller\CurrConvController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

}
