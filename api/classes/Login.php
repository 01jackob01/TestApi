<?php

namespace Api\Classes;

use Api\Model\SessionModel;
use Api\Model\UsersModel;

class Login extends AppAbstract
{
    public function loginToPanel()
    {
        if (!empty($this->requestData['login']) && !empty($this->requestData['password'])) {
            if (isset($_SERVER['HTTP_APPKEY'])) {
                $sessionId = hash('sha256', $_SERVER['HTTP_APPKEY']);
            } else {
                $sessionId = hash('sha256', uniqid());
            }

            $usersModel = new UsersModel();
            $userData = $usersModel->checkUserAndPassword($this->requestData['login'], $this->requestData['password']);

            if (!empty($userData)) {
                $loginData = ['login' => 'true'];
                $sessionModel = new SessionModel();
                $sessionModel->deleteOldSession();
                $sessionModel->createSession($sessionId, $userData['id']);
                session_start();
                $_SESSION['session_id'] = $sessionId;
                $_SESSION['expires_at'] = time() + ($_ENV['MAX_SESSION_TIME'] * 60);
            } else {
                $loginData = ['error' => true, 'message' => 'Bledne dane do logowania'];
            }
        } else {
            $loginData = ['error' => true, 'message' => 'Brak danych do logowania'];
        }


        return $loginData;
    }

    public function logoutFromPanel(): array
    {
        $sessionModel = new SessionModel();
        $sessionModel->deleteSession($this->sessionId);
        session_start();
        unset($_SESSION['session_id']);
        unset($_SESSION['expires_at']);

        return ['logout' => true];
    }
}