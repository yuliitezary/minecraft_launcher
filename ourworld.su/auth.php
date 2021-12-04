<?php
// Скрипт авторизации через метод JSON/POST

header("Content-Type: application/json; charset=UTF-8");
include('engine/api/api.class.php');

 // FIX Thread Starvation, ограничение POST данных в 4Кб
ini_set('post_max_size', '4K');

//Входящие параметры
$postData = file_get_contents('php://input');
if (($data = json_decode($postData, true)) === null) {
    json_return(["error" => "Не валидные json данные"]);
}

$config = [
    'access_key' => 'phpuyh@ruvchsp0438bhy',
    'auth_ip' => '212.22.85.129'
];

if ($_SERVER['REMOTE_ADDR'] != $config['auth_ip'] || $data['apiKey'] != $config['access_key']) {
    json_return(["error" => "Отказано в доступе"]);
}

if(empty($data['username']) || empty($data['password'])) {
    json_return(["error" => "Не указан логин и/или пароль"]);
}

if ($dle_api->external_auth($data['username'], $data['password'])) {
    $name_result = $dle_api->take_user_by_name($data['username'], 'name, permissions');
    json_return([
        "username" => $name_result['name'],
        "permissions" => $name_result['permissions']
    ]);
} else {
    json_return(["error" => "Неверный логин и/или пароль"]);
}

function json_return($data)
{
    exit(json_encode($data));
}