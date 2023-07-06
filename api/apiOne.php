<?php

if ($_SERVER['HTTP_APPKEY'] == 'myszkaApi') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['value']) || empty($_POST['apiType'])) {
            $return = [
                'error'   => true,
                'message' => 'Value or api type is empty'
            ];
        } elseif (!is_numeric($_POST['value'])) {
            $return = [
                'error'   => true,
                'message' => 'Value has wrong format'
            ];
        } else {
            if ($_POST['apiType'] == 'test1') {
                $return = [
                    'value' => (4 * $_POST['value'])
                ];
            } elseif ($_POST['apiType'] == 'test2') {
                $return = [
                    'value' => (5 * $_POST['value'])
                ];
            } else {
                $return = [
                    'error'   => true,
                    'message' => 'Api type not found'
                ];
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (empty($_GET['value']) || empty($_GET['apiType'])) {
            $return = [
                'error'   => true,
                'message' => 'Value or api type is empty'
            ];
        } elseif (!is_numeric($_GET['value'])) {
            $return = [
                'error'   => true,
                'message' => 'Value has wrong format'
            ];
        } else {
            if ($_GET['apiType'] == 'test1') {
                $return = [
                    'value' => (2 * $_GET['value'])
                ];
            } elseif ($_GET['apiType'] == 'test2') {
                $return = [
                    'value' => (3 * $_GET['value'])
                ];
            } else {
                $return = [
                    'error'   => true,
                    'message' => 'Api type not found'
                ];
            }
        }
    } else {
        $return = [
            'error'   => true,
            'message' => 'Request method not found'
        ];
    }
} else {
    $return = [
        'error'   => true,
        'message' => 'Api key is wrong'
    ];
}

echo json_encode($return);