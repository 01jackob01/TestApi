<?php

namespace Api\Classes;

use Api\Model\ApiKeysModel;
use Api\Model\SessionModel;

class Panel extends AppAbstract
{
    public function checkPage(): array
    {
        $check = ['pageCheck' => false];
        $sessionModel = new SessionModel();
        $sessionModel->deleteOldSession();
        $sessionData = $sessionModel->getDataForSession($this->sessionId);
        if (!empty($sessionData)) {
            $sessionModel->refreshSession($this->sessionId);
            session_start();
            $_SESSION['session_id'] = $this->sessionId;
            $_SESSION['expires_at'] = time() + ($_ENV['MAX_SESSION_TIME'] * 60);
            $check = ['pageCheck' => true];
        }

        return $check;
    }

    public function checkApiKey()
    {
        $check = ['pageCheck' => false];
        $sessionModel = new SessionModel();
        $apiKeysModel = new ApiKeysModel();
        $sessionModel->deleteOldSession();
        $keyData = $apiKeysModel->getApiKeyData($this->sessionId);
        $sessionData = $sessionModel->getDataForSession($this->sessionId);
        if (!empty($sessionData)) {
            $sessionModel->refreshSession($this->sessionId);
            $check = ['pageCheck' => true];
        } elseif (!empty($keyData)) {
            $sessionModel->createSession($keyData['api_key'], $keyData['user_id']);
            $check = ['pageCheck' => true];
        }

        return $check;
    }
}