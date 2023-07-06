<?php

namespace Api\Classes;

use Api\Model\UsersModel;

class NewAccount extends AppAbstract
{
    public function createNewAccount(): array
    {
        if (!empty($this->requestData['login']) && !empty($this->requestData['password'])) {
            $userModel = new UsersModel();
            $return = $userModel->addNewAccount($this->requestData['login'], $this->requestData['password']);
        } else {
            $return = ['error' => true, 'message' => 'Brak danych do utworzenia konta'];
        }

        return $return;
    }
}