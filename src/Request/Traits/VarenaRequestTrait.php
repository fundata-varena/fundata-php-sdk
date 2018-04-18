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
    protected function checkStatus($contents)
    {
        if (isset($contents['retcode'])) {
            $retcode = intval($contents['retcode']);
            //临时兼容前台api
            if ($retcode !== Response::SUCCESS) {
                throw new APIException(
                    $contents['message'],
                    $this->getRequestUrl(),
                    $contents,
                    $this->getRequestOptions(),
                    InfoLevel::ERROR,
                    $retcode
                );
            }
        }else{
            throw new APIException(
                'server error in error',
                $this->getRequestUrl(),
                $contents,
                $this->getRequestOptions(),
                InfoLevel::ERROR
            );
        }
    }
}
