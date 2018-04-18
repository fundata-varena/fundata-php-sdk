<?php

namespace Varena\SDK\Request\Traits;

use Varena\SDK\Request\Helper\UniqueRequest;

trait AuthTrait
{
    /**
     * 设置当前auth_headers
     * @param string $key
     * @param string $uri
     * @param array $params
     * @param string $secert
     */
    private function setExtraRequestOptions(string $key,string $uri,array $params,string $secert)
    {
        $timestamp = time();
        $nonce = $this->generateNonce($timestamp);
        $sig = $this->generateSig($timestamp,$nonce,$secert,$uri,$params);
        $this->extraRequestOptions = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept-ApiKey' => $key,
                'Accept-ApiNonce' => $nonce,
                'Accept-ApiTime' => $timestamp,
                'Accept-ApiSign' => $sig,
                'uuid' => UniqueRequest::getId(),
            ],
        ];
    }

    /**
     * 生成随机数 nonce
     * @param string $timestamp
     *
     * @return string
     */
    private function generateNonce(string $timestamp):string
    {
        $rand = rand(5,10);
        $str = substr(md5($timestamp),8,$rand);
        return $str;
    }

    /***
     * @param string $timestamp
     * @param string $nonce
     * @param string $secert
     * @param string $uri
     * @param array $params
     * @return bool|string
     * Author: JV
     */
    private function generateSig(string $timestamp,string $nonce,string $secert,string $uri,array $params):string
    {
        ksort($params);
        $params_str = http_build_query($params);
        $uri = '/'.trim($uri, '/ ');
        $str = implode('|',[$nonce,$secert,$timestamp,$uri,$params_str]);
        return md5($str);
    }
}
