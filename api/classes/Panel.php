<?php

namespace Api\Classes;

use Api\Model\ApiKeysModel;
use Api\Model\SessionModel;
use Api\Model\UsersModel;

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

    public function createApiKeyForUser()
    {
        $userModel = new UsersModel();
        $userData = $userModel->getAccountDataById($this->requestData['userId']);
        if (!empty($userData)) {
            $apiKeysModel = new ApiKeysModel();
            $apiKeyId = $apiKeysModel->getApiKeyIdForUser($this->requestData['userId']);
            if ($apiKeyId === 0) {
                $apiKey = hash('sha256', self::SALT . $userData['login'] . '-' . $_ENV['API_KEY']);
                $insert = [
                    $apiKeysModel::COLUMN_USER_ID => $userData['id'],
                    $apiKeysModel::COLUMN_API_KEY => $apiKey,
                ];
                $apiKeyInsert = $apiKeysModel->createApiKey($insert);
                if ($apiKeyInsert) {
                    $return = ['apiKey' => $userData['login'] . '-' . $_ENV['API_KEY']];
                } else {
                    $return = ['apiKey' => false];
                }
            } else {
                $return = ['error' => true, 'message' => 'Uzytkownik ma juz api key'];
            }
        } else {
            $return = ['error' => true, 'message' => 'Uzytkownik o wskazanym id nie istnieje'];
        }

        return $return;
    }
}