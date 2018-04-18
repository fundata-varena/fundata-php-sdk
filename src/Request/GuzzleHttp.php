<?php

namespace Varena\SDK\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttp
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(string $baseUri = '')
    {
        $this->client = new Client(['base_uri' => $baseUri]);
    }

    /**
     * @var array
     */
    private $defaultRequestOptions = [
        RequestOptions::CONNECT_TIMEOUT => 30,
        RequestOptions::TIMEOUT => 30,
    ];

    public function request(string $method, string $uri = '', array $options = []): ResponseInterface
    {
        $options = array_merge($this->defaultRequestOptions, $options);

        return $this->client->request($method, $uri, $options);
    }

    public function requestAsync(string $method, string $uri = '', array $options = []): PromiseInterface
    {
        $options = array_merge($this->defaultRequestOptions, $options);

        return $this->client->requestAsync($method, $uri, $options);
    }

    public function changConnectTimeout(float $second)
    {
        $this->setOption(RequestOptions::CONNECT_TIMEOUT, $second);

        return $this;
    }

    public function enableDebug(bool $bool)
    {
        $this->setOption(RequestOptions::DEBUG, $bool);

        return $this;
    }

    public function changTimeout(float $second)
    {
        $this->setOption(RequestOptions::TIMEOUT, $second);

        return $this;
    }

    private function setOption(string $name, $value)
    {
        $this->defaultRequestOptions[$name] = $value;
    }
}
