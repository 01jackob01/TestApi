<?php

namespace Api\Model;

class OrdersModel extends DbConnector
{
    public const TABLE = 'orders';
    public const COLUMN_ID = 'id';
    public const COLUMN_PRODUCT_ID = 'product_id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_QUANTITY = 'quantity';
    public const COLUMN_SUM_PRICE = 'sum_price';
    public const COLUMN_CREATE_TIME = 'create_time';

    public function getOrderById(int $orderId): array
    {
        $order =  $this->select(2, ['*'], self::TABLE, ['=' => [self::COLUMN_ID => $orderId]]);

        return !empty($order) ? $order : [];
    }

    public function getAllOrdersBetweenDate(string $startDate = '', string $endDate = ''): array
    {
        $where = [];
        if (!empty($startDate)) {
            $where['>'] = ['create_time' => $startDate];
        }
        if (!empty($endDate)) {
            $where['<'] = ['create_time' => $endDate];
        }

        $orders = $this->select(3, ['*'], self::TABLE, $where);

        return !empty($orders) ? $orders : [];
    }

    public function addNewOrder($data): array
    {
        $insert =  $this->insert(self::TABLE, $data);
        if ($insert) {
            $return = ['addOrder' => true];
        } else {
            $return = ['addOrder' => false];
        }

        return $return;
    }

    public function updateOrder(array $data, int $id): array
    {
        $update =  $this->update(self::TABLE, $data, ['=' => [self::COLUMN_ID => $id]]);
        if ($update) {
            $return = ['updateOrder' => true];
        } else {
            $return = ['updateOrder' => false];
        }

        return $return;
    }

    public function deleteOrder(int $id): array
    {
        $delete =  $this->delete(self::TABLE, ['=' => [self::COLUMN_ID => $id]]);
        if ($delete) {
            $return = ['deleteOrder' => true];
        } else {
            $return = ['deleteOrder' => false];
        }

        return $return;
    }
}