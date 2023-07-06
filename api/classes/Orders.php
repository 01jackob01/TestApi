<?php

namespace Api\Classes;

use Api\Model\OrdersModel;
use Api\Model\ProductModel;

class Orders extends AppAbstract
{
    public function createOrder()
    {
        if (!empty($this->requestData['productId']) || !empty($this->requestData['userId']) || !empty($this->requestData['quantity'])) {
            $productModel = new ProductModel();
            $ordersModel = new OrdersModel();

            $productData = $productModel->getProduct($this->requestData['productId']);
            $productData = reset($productData);

            if ($productData[$productModel::COLUMN_QUANTITY] >= $this->requestData['quantity']) {
                $insert = [
                    $ordersModel::COLUMN_PRODUCT_ID => $this->requestData['productId'],
                    $ordersModel::COLUMN_USER_ID => $this->requestData['userId'],
                    $ordersModel::COLUMN_QUANTITY => $this->requestData['quantity'],
                    $ordersModel::COLUMN_SUM_PRICE => ($this->requestData['quantity'] * $productData[$productModel::COLUMN_PRICE]),
                ];
                $return = $ordersModel->addNewOrder($insert);
                if ($return['addOrder']) {
                    $update = [
                        $productModel::COLUMN_QUANTITY => ($productData[$productModel::COLUMN_QUANTITY] - $this->requestData['quantity'])
                    ];
                    $productModel->updateProduct($update, $this->requestData['productId']);
                }
            } else {
                $return = ['error' => true, 'message' => 'Brak wystarczającej ilosci produktu'];
            }
        } else {
            $return = ['error' => true, 'message' => 'Brak danych aby dodac zamowienie'];
        }

        return $return;
    }
    public function getAllOrders(): array
    {
        $startDate = '';
        $endDate = '';
        if (!empty($this->requestData['startDate'])) {
            $startDate = date('Y-m-d H:i:s', $this->requestData['startDate']);
        }
        if (!empty($this->requestData['endDate'])) {
            $endDate = date('Y-m-d H:i:s', $this->requestData['endDate']);
        }

        $ordersModel = new OrdersModel();
        return $ordersModel->getAllOrdersBetweenDate($startDate, $endDate);
    }

    public function deleteOrder(): array
    {
        $ordersModel = new OrdersModel();
        return $ordersModel->deleteOrder($this->requestData['id']);
    }
}