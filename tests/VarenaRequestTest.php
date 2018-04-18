<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Varena\SDK\Request\VarenaRequest;

class VarenaRequestTest extends TestCase
{

    public function testRequest()
    {
        $guzzleHttp = new VarenaRequest('81bbd364ac54473','23fefce72003404fb81bb2a9180d41e');
        $response = $guzzleHttp->getData('/data-service/dota2/pro/league/ti/rank-player',[]);

        $this->assertArrayHasKey('data',$response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGuzzle()
    {
        $guzzleHttp = new GuzzleHttp('www.baidu.com');

        $response = $guzzleHttp->enableDebug(true)->request('get');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
