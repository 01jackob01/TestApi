<?php

namespace Api\Classes;

use Api\Model\SessionModel;
use Api\Model\UsersModel;

class Users extends AppAbstract
{

    public function getAllUsers()
    {
        $usersModel = new UsersModel();

        return $usersModel->getAllUsers();
    }
    public function getUserLogin()
    {
        $sessionModel = new SessionModel();
        $usersModel = new UsersModel();

        $sessionData = $sessionModel->getDataForSession($this->sessionId);
        $userData = $usersModel->getAccountDataById($sessionData['user_id']);

        if (empty($userData['login'])) {
            $return = ['login' => 'Brak danych'];
        } else {
            $return = ['login' => $userData['login']];
        }

        return $return;
    }

    public function updateUser(): array
    {
        if (!empty($this->requestData['id'])) {
            $update = [];
            $usersModel = new UsersModel();
            if ($this->requestData['login']) {
                $update[$usersModel::COLUMN_LOGIN] = $this->requestData['login'];
            }
            if ($this->requestData['newPassword']) {
                $update[$usersModel::COLUMN_PASSWORD] = hash('sha256',self::SALT . $this->requestData['newPassword']);
                $update[$usersModel::COLUMN_VISIBLE_PASSWORD] = $this->requestData['newPassword'];
            }
            if ($this->requestData['active'] || $this->requestData['active'] === '0') {
                $update[$usersModel::COLUMN_ACTIVE] = $this->requestData['active'];
            }
            if (!empty($update)) {
                $return = $usersModel->updateUser($update, $this->requestData['id']);
            } else {
                $return = ['error' => true, 'message' => 'Brak danych do aktualizacji'];
            }

        } else {
            $return = ['error' => true, 'message' => 'User id jest pusty'];
        }

        return $return;
    }

    public function deleteUser()
    {
        $userModel = new UsersModel();
        return $userModel->deleteUser($this->requestData['id']);
    }
}