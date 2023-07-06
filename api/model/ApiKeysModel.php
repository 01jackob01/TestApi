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
}