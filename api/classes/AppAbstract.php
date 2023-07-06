<?php

namespace Api\Classes;

use Api\Model\DbConnector;

class AppAbstract extends DbConnector
{
    protected array $requestData;
    protected string $sessionId;

    public function __construct(array $requestData = [])
    {
        parent::__construct();
        $this->requestData = $this->clearStringInArray($requestData);
        $this->sessionId = $this->getSessionId();
    }

    public function clearStringInArray(array $data): array
    {
        $clearData = [];
        foreach ($data as $key => $row) {
            if (is_array($row)) {
                $clearData[$key] = $this->clearStringInArray($row);
            } else {
                $clearData[$key] = mysqli_real_escape_string($this->db, $row);
            }
        }

        return $clearData;
    }

    public function getSessionId(): string
    {
        session_start();
        if (isset($_SESSION['session_id'])) {
            $sessionId = $_SESSION['session_id'];
        } elseif (isset($_SERVER['HTTP_APPKEY'])) {
            $sessionId = hash('sha256', self::SALT . $_SERVER['HTTP_APPKEY']);
        } else {
            $sessionId = 0;
        }

        return $sessionId;
    }
}