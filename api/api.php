<?php

require __DIR__ . '/../vendor/autoload.php';

use Api\Classes\Panel;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postData = (array)json_decode(file_get_contents('php://input'));
    if (!in_array($postData['c'], ['NewAccount', 'Login'])) {
        $pageCheck = checkApp($postData);
    } else {
        $pageCheck = true;
    }
    if ($pageCheck) {
        if (!empty($postData['c']) && !empty($postData['f'])) {
            $className = 'Api\\Classes\\' . $postData['c'];
            $apiClass = new $className($postData);
            $functionName = $postData['f'];
            $return = $apiClass->$functionName();
        } else {
            $return = ['error' => true, 'message' => 'Brak wymaganych danych'];
        }
    } else {
        $return = ['error' => true, 'message' => 'Blad polaczenia api'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (checkApp($_GET)) {
        if (!empty($_GET['c']) && !empty($_GET['f'])) {
            $className = 'Api\\Classes\\' . $_GET['c'];
            $apiClass = new $className($_GET);
            $functionName = $_GET['f'];
            $return = $apiClass->$functionName();
        } else {
            $return = ['error' => true, 'message' => 'Brak wymaganych danych'];
        }
    } else {
        $return = ['error' => true, 'message' => 'Blad polaczenia api'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE' || $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $postData = (array)json_decode(file_get_contents('php://input'));
    if (checkApp($postData)) {
        $id = getIdFromUrl();
        if (!empty($id) && is_int($id)) {
            if (!empty($postData['c']) && !empty($postData['f'])) {
                $postData['id'] = $id;
                $className = 'Api\\Classes\\' . $postData['c'];
                $apiClass = new $className($postData);
                $functionName = $postData['f'];
                $return = $apiClass->$functionName();
            } else {
                $return = ['error' => true, 'message' => 'Brak wymaganych danych'];
            }
        } else {
            $return = ['error' => true, 'message' => 'Brak id'];
        }
    } else {
        $return = ['error' => true, 'message' => 'Blad polaczenia api'];
    }
} else {
    $return = ['error' => true, 'message' => 'Bledny requqst method'];
}

echo json_encode($return);

function checkApp($data): bool
{
    $panel = new Panel($data);
    if (isset($_SERVER['HTTP_APPKEY'])) {
        $return = $panel->checkApiKey();
        $pageCheck = $return['pageCheck'];
    } else {
        $return = $panel->checkPage();
        $pageCheck = $return['pageCheck'];
    }

    return $pageCheck;
}

function getIdFromUrl()
{
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($url, PHP_URL_PATH);

    return basename($path);
}