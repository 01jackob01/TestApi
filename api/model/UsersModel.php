<?php

namespace Api\Model;

class UsersModel extends DbConnector
{
    public const TABLE = 'users';
    public const COLUMN_ID = 'id';
    public const COLUMN_LOGIN = 'login';
    public const COLUMN_PASSWORD = 'password';
    public const COLUMN_VISIBLE_PASSWORD = 'visible_password';
    public const COLUMN_CREATE_TIME = 'create_time';
    public const COLUMN_ACTIVE = 'active';

    public function checkUserAndPassword(string $login, string $password): array
    {
        $saltedPassword = self::SALT . $password;
        $hashedPassword = hash('sha256', $saltedPassword);

        $userData = $this->select(2, ['*'], self::TABLE, ['=' => [self::COLUMN_LOGIN => $login, self::COLUMN_PASSWORD => $hashedPassword]]);
        if (empty($userData) || $userData['active'] === '0') {
            $userData = [];
        }

        return $userData;
    }

    public function getAllUsers(): array
    {
        return $this->select(3, ['*'], self::TABLE);
    }

    public function getAccountDataById(int $userId): array
    {
        return $this->select(2, ['*'], self::TABLE, ['=' => [self::COLUMN_ID => $userId]]);
    }

    public function getAccountDataByLogin(string $login): array
    {
        return $this->select(2, ['*'], self::TABLE, ['=' => [self::COLUMN_LOGIN => $login]]);
    }

    public function addNewAccount(string $login, string $password): array
    {
        $saltedPassword = self::SALT . $password;
        $hashedPassword = hash('sha256', $saltedPassword);

        $insert = $this->insert(self::TABLE, [self::COLUMN_LOGIN => $login, self::COLUMN_PASSWORD => $hashedPassword, self::COLUMN_VISIBLE_PASSWORD => $password, self::COLUMN_ACTIVE => 1]);
        if ($insert) {
            $return = ['addNewUser' => true];
        } else {
            if (!empty($this->getAccountDataByLogin($login))) {
                $return = ['addNewUser' => false, 'message' => 'Konto już istnieje w systemie'];
            } else {
                $return = ['addNewUser' => false, 'message' => 'Wystąpił błąd podczas dodawania'];
            }
        }

        return $return;
    }

    public function updateUser(array $data, int $id): array
    {
        $update =  $this->update(self::TABLE, $data, ['=' => [self::COLUMN_ID => $id]]);
        if ($update) {
            $return = ['updateUser' => true];
        } else {
            $return = ['updateUser' => false];
        }

        return $return;
    }

    public function deleteUser(int $id): array
    {
        $delete =  $this->delete(self::TABLE, ['=' => [self::COLUMN_ID => $id]]);
        if ($delete) {
            $return = ['deleteUser' => true];
        } else {
            $return = ['deleteUser' => false];
        }

        return $return;
    }
}