<?php

namespace Varena\SDK\Request;

use Varena\SDK\Exception\APIException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;

abstract class AbstractRequest
{
    const MAX_TRY_TIMES = 3;

    protected $needRetry = true;

    /**
     * @var string
     */
    private $uri = '';

    /**
     * @var string
     */
    protected $baseUri = '';

    /**
     * @var static
     */
    protected static $_instance = null;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    protected $extraRequestOptions = [];

    /**
     * @var
     */
    protected $client;

    public function __construct()
    {
        $this->client = new GuzzleHttp($this->baseUri);
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    /**
     * 检查返回的http status code是否符合预期
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    abstract protected function checkCode($response);

    /**
     * 将返回的数据转换为可使用的格式，如array
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    abstract protected function parseJSON($response);

    /**
     * 检查返回的数据状态码是否符合预期
     * 该状态码指的是返回数据中所包含的业务状态码，与http状态码无关
     *
     * @param mixed $contents
     */
    abstract protected function checkStatus($contents);

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @throws APIException
     *
     * @return array
     */
    protected function requestAndParse($method, $uri = '', array $options = [])
    {
        $options = array_filter(
            array_merge(
                $this->extraRequestOptions ?? [],
                $options
            )
        );
        $this->uri = $uri;
        $this->options = $options;

        $tryTimes = 1;
        while (true) {
            try {
                $startAt = microtime(true);
                $response = $this->client->request($method, $uri, $options);
                $endAt = microtime(true);
                $this->checkCode($response);
                $contents = $this->parseJSON($response);
                $this->checkStatus($contents);

                return $contents['data'] ?? $contents;
            } catch (ConnectException $e) {
                throw new APIException(
                    $e->getMessage(),
                    $this->getRequestUrl(),
                    $response ?? '',
                    $this->getRequestOptions()
                );
            } catch (\Throwable $e) {
                throw new APIException(
                    $e->getMessage(),
                    $this->getRequestUrl(),
                    $response ?? '',
                    $this->getRequestOptions()
                );
            }
        }

        return [];
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return array
     */
    public function getData($uri, array $params = [])
    {
        return $this->requestAndParse('GET', $uri, [
            RequestOptions::QUERY => $params,
        ]);
    }

    /**
     * @param string $uri
     * @param array|string $params
     *
     * @return array
     */
    public function postData($uri, $params = [])
    {
        $key = is_array($params) ? RequestOptions::JSON : RequestOptions::BODY;

        return $this->requestAndParse('POST', $uri, [$key => $params]);
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return array
     */
    public function deleteData($uri, $params = [])
    {
        return $this->requestAndParse('DELETE', $uri, [
            RequestOptions::QUERY => $params,
        ]);
    }

    public static function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * @return string
     */
    public function getRequestUrl(): string
    {
        return rtrim($this->baseUri, '/ ') . '/' . ltrim($this->uri, '/ ');
    }

    /**
     * @return array
     */
    public function getRequestOptions(): array
    {
        return $this->options;
    }
}
