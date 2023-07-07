<?php

namespace Api\Model;

class ApiKeysModel extends DbConnector
{
    public const TABLE = 'api_keys';
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_API_KEY = 'api_key';
    public const COLUMN_CREATE_TIME = 'create_time';

    public function getApiKeyData(string $apiKey): array
    {
        $keyData = $this->select(2, ['*'], self::TABLE, ['=' => [self::COLUMN_API_KEY => $apiKey]]);

        return !empty($keyData) ? $keyData : [];
    }

    public function getApiKeyIdForUser(int $userId): int
    {
        $apiKeyId = $this->select(0, [self::COLUMN_ID], self::TABLE, ['=' => [self::COLUMN_USER_ID => $userId]]);

        return !empty($apiKeyId) ? $apiKeyId : 0;
    }

    public function deleteApiKey(int $id)
    {
        $delete =  $this->delete(self::TABLE, ['=' => [self::COLUMN_ID => $id]]);
        if ($delete) {
            $return = ['deleteSession' => true];
        } else {
            $return = ['deleteSession' => false];
        }

        return $return;
    }

    public function createApiKey(array $data): array
    {
        $delete =  $this->insert(self::TABLE, $data);
        if ($delete) {
            $return = ['apiKey' => true];
        } else {
            $return = ['apiKey' => false];
        }

        return $return;
    }
}