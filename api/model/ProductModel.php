<?php

namespace Api\Model;

class ProductModel extends DbConnector
{
    public const TABLE = 'product';
    public const COLUMN_ID = 'id';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_QUANTITY = 'quantity';
    public const COLUMN_PRICE= 'price';


    public function getProductsList()
    {
        return $this->select(3, ['*'], self::TABLE);
    }

    public function getProduct(int $productId): array
    {
        return $this->select(3, ['*'], self::TABLE, ['=' => [self::COLUMN_ID => $productId]]);
    }
    public function addNewProduct(array $data): array
    {
        $insert =  $this->insert(self::TABLE, $data);
        if ($insert) {
            $return = ['addProduct' => true];
        } else {
            $return = ['addProduct' => false];
        }

        return $return;
    }

    public function updateProduct(array $data, int $id): array
    {
        $update =  $this->update(self::TABLE, $data, ['=' => [self::COLUMN_ID => $id]]);
        if ($update) {
            $return = ['updateProduct' => true];
        } else {
            $return = ['updateProduct' => false];
        }

        return $return;
    }

    public function deleteProduct(int $id): array
    {
        $delete =  $this->delete(self::TABLE, ['=' => [self::COLUMN_ID => $id]]);
        if ($delete) {
            $return = ['deleteProduct' => true];
        } else {
            $return = ['deleteProduct' => false];
        }

        return $return;
    }
}