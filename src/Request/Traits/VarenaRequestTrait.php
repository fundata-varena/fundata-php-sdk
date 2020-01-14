<?php

namespace Varena\SDK\Request\Traits;

use Varena\SDK\Common\InfoLevel;
use Varena\SDK\Exception\APIException;
use Varena\SDK\Response\Response;

trait VarenaRequestTrait
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function checkCode($response)
    {
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @throws APIException
     *
     * @return array
     */
    protected function parseJSON($response)
    {
        $body = $response->getBody()->getContents();

        $contents = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new APIException(
                json_last_error_msg(),
                $this->getRequestUrl(),
                $body,
                $this->getRequestOptions()
            );
        }

        return $contents;
    }

    /**
     * @param mixed $contents
     *
     * @throws APIException
     */
    protected function checkStatus(&$contents)
    {
        if (!isset($contents['retcode']) && // 来自fundata API
            !isset($contents['code'])   // 来自网关
        ) {
            throw new APIException(
                'server error',
                $this->getRequestUrl(),
                $contents,
                $this->getRequestOptions(),
                InfoLevel::ERROR
            );
        }

        // 500抛一个异常给调用方
        $retcode = isset($contents['retcode']) ? intval($contents['retcode'])
            : (isset($contents['code']) ? intval($contents['code']) : 500);

        // fundata & 网关 返回的正常的业务code
        $businessCode = [
            Response::SUCCESS,
            Response::NO_DATA,
            Response::PARAMETERS_ERROR,
            Response::INTERNAL_ERROR,
            Response::AUTH_FAILED,
            Response::NO_PERMISSION,
        ];

        if (!in_array($retcode, $businessCode)) {
            throw new APIException(
                isset($contents['message']) ? $contents['message']
                    : sprintf('internal error: illegal retcode[%s]', $retcode),
                $this->getRequestUrl(),
                $contents,
                $this->getRequestOptions(),
                InfoLevel::ERROR,
                $retcode
            );
        }

        // 网关code替换为retcode
        if (isset($contents['code'])) {
            $contents['retcode'] = $contents['code'];
            unset($contents['code']);
        }

        return;
    }
}
