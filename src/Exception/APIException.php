<?php

namespace Varena\SDK\Exception;

use Varena\SDK\Common\InfoLevel;
use Varena\SDK\Exception\Base\AbstractBaseException;

class APIException extends AbstractBaseException
{
    private $url;
    private $response;
    private $request;

    private $level;

    /**
     * @param string $message
     * @param string $url
     * @param mixed $response
     * @param array $request
     * @param int $level
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(string $message, string $url, $response, array $request, int $level = InfoLevel::ERROR, int $code = 0, \Exception $previous = null)
    {
        $this->url = $url;
        $this->response = $response;
        $this->request = $request;
        $this->level = $level;

        parent::__construct($message, $code, $previous);
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getLogMessage(): string
    {
        return json_encode([
            'message' => $this->getMessage(),
            'url' => $this->url,
            'request' => $this->request,
            'response' => $this->response,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
