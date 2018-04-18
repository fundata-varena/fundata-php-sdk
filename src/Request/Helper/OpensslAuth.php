<?php

namespace Varena\SDK\Request\Helper;

use Varena\SDK\Exception\SystemException;

class OpensslAuth
{
    /**
     * 生成签名
     *
     * @param $data
     * @param $privateKey
     *
     * @throws \Exception
     *
     * @return string
     */
    public function generateSignature(string $data, $privateKey): string
    {
        $res = openssl_pkey_get_private($privateKey);

        if (!openssl_sign($data, $signature, $res)) {
            throw new SystemException('open ssl error');
        }

        return base64_encode($signature);
    }

    /**
     * 验证签名是否正确
     *
     * @param  $data
     * @param  $publicKey
     * @param  $signature
     *
     * @return bool
     */
    public function verifySignature(string $data, $publicKey, string $signature): bool
    {
        $signature = base64_decode($signature);

        $res = openssl_pkey_get_public($publicKey);

        return openssl_verify($data, $signature, $res) === 1;
    }
}
