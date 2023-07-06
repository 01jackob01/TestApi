<?php

namespace Api\Model;

class SessionModel extends DbConnector
{
    public const TABLE = 'session';
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_SESSION_ID = 'session_id';
    public const COLUMN_EXPIRES_AT = 'expires_at';
    public const COLUMN_CREATE_TIME = 'create_time';

    public function getDataForSession(string $sessionId): array
    {
        $sessionData = $this->select(2, ['*'], self::TABLE, ['=' => [self::COLUMN_SESSION_ID => $sessionId]]);

        return !empty($sessionData) ? $sessionData : [];
    }
    public function createSession(string $sessionId, int $userId): void
    {
        $date = date('Y-m-d H:i:s', strtotime('+' . $_ENV['MAX_SESSION_TIME'] . ' minutes'));
        $this->insert(self::TABLE, [self::COLUMN_USER_ID => $userId, self::COLUMN_SESSION_ID => $sessionId, self::COLUMN_EXPIRES_AT => $date]);
    }

    public function deleteSession(string $sessionId): void
    {
        $this->delete(self::TABLE, ['=' => [self::COLUMN_SESSION_ID => $sessionId]]);
    }

    public function deleteOldSession(): void
    {
        $date = date('Y-m-d H:i:s');
        $this->delete(self::TABLE, ['<' => [self::COLUMN_EXPIRES_AT => $date]]);
    }

    public function refreshSession(string $sessionId): void
    {
        $date = date('Y-m-d H:i:s', strtotime('+' . $_ENV['MAX_SESSION_TIME'] . ' minutes'));
        $this->update(self::TABLE, [self::COLUMN_EXPIRES_AT => $date], ['=' => [self::COLUMN_SESSION_ID => $sessionId]]);
    }
}