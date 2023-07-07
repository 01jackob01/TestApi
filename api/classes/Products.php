<?php

namespace Api\Classes;

use Api\Model\ProductModel;

class Products extends AppAbstract
{
    public function addNewProduct(): array
    {
        if (!empty($this->requestData['name']) || !empty($this->requestData['description'])
            || !empty($this->requestData['quantity']) || !empty($this->requestData['price'])) {
            $productModel = new ProductModel();
            $insert = [
                $productModel::COLUMN_NAME => $this->requestData['name'],
                $productModel::COLUMN_DESCRIPTION => $this->requestData['description'],
                $productModel::COLUMN_QUANTITY => $this->requestData['quantity'],
                $productModel::COLUMN_PRICE => $this->requestData['price'],
            ];
            $return = $productModel->addNewProduct($insert);
        } else {
            $return = ['error' => true, 'message' => 'Brak danych aby dodać produkt'];
        }

        return $return;
    }

    public function updateProduct(): array
    {
        if (!empty($this->requestData['id'])) {
            $update = [];
            $productModel = new ProductModel();
            if ($this->requestData['name']) {
                $update[$productModel::COLUMN_NAME] = $this->requestData['name'];
            }
            if ($this->requestData['description']) {
                $update[$productModel::COLUMN_DESCRIPTION] = $this->requestData['description'];
            }
            if ($this->requestData['quantity']) {
                $update[$productModel::COLUMN_QUANTITY] = $this->requestData['quantity'];
            }
            if ($this->requestData['price']) {
                $update[$productModel::COLUMN_PRICE] = $this->requestData['price'];
            }
            if (!empty($update)) {
                $return = $productModel->updateProduct($update, $this->requestData['id']);
            } else {
                $return = ['error' => true, 'message' => 'Brak danych do aktualizacji'];
            }

        } else {
            $return = ['error' => true, 'message' => 'Produkt id jest pusty'];
        }

        return $return;
    }

    public function getProducts(): array
    {
        $productModel = new ProductModel();
        if (!empty($this->requestData['productId'])) {
            $return = $productModel->getProduct($this->requestData['productId']);
        } else {
            $return = $productModel->getProductsList();
        }

        return $return;
    }

    public function deleteProduct(): array
    {
        $productModel = new ProductModel();
        return $productModel->deleteProduct($this->requestData['id']);
    }
}