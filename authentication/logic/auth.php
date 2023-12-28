<?php
session_start();

if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == true) {
    header('Location: ../../main/index.php');
    die();
}

require '../../database/database-auth-system.php';
require '../../database/config.php';

define('BOT_TOKEN', $BOT_TOKEN);

if (!isset($_GET['hash'])) {
    die('Telegram hash not found');
}

function checkTelegramAuthorization($auth_data) {
    $check_hash = $auth_data['hash'];
    unset($auth_data['hash']);

    $data_check_arr = [];
    foreach ($auth_data as $key => $value) {
        $data_check_arr[] = $key . '=' . $value;
    }

    sort($data_check_arr);
    $data_check_string = implode("\n", $data_check_arr);
    $secret_key = hash('sha256', BOT_TOKEN, true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);

    if (strcmp($hash, $check_hash) !== 0) {
        throw new Exception('Data is NOT from Telegram');
    }

    if ((time() - $auth_data['auth_date']) > 86400) {
        throw new Exception('Data is outdated');
    }

    return $auth_data;
}

function userAuthentication($db, $auth_data) {
    function createNewUser($db, $auth_data) {
        $db->Insert(
            "INSERT INTO `users`
            (`first_name`, `last_name`, `telegram_id`, `telegram_username`, `profile_picture`, `auth_date`)
            VALUES (:first_name, :last_name, :telegram_id, :telegram_username, :profile_picture, :auth_date)",
            [
                'first_name'        => $auth_data['first_name'],
                'last_name'         => isset($auth_data['last_name']) ? $auth_data['last_name'] : null,
                'telegram_id'       => $auth_data['id'],
                'telegram_username' => isset($auth_data['username']) ? $auth_data['photo_url'] : null,
                'profile_picture'   => isset($auth_data['photo_url']) ? $auth_data['photo_url'] : null,
                'auth_date'         => isset($auth_data['auth_date']) ? $auth_data['auth_date'] : null
            ]
        );
    }

    function updateExistedUser($db, $auth_data) {
        $db->Update(
            "UPDATE `users`
            SET `first_name`        = :first_name,
                `last_name`         = :last_name,
                `telegram_username` = :telegram_username,
                `profile_picture`   = :profile_picture,
                `auth_date`         = :auth_date
            WHERE `telegram_id` = :telegram_id",
            [
                'first_name'        => $auth_data['first_name'],
                'last_name'         => isset($auth_data['last_name']) ? $auth_data['last_name'] : null,
                'telegram_username' => isset($auth_data['username']) ? $auth_data['photo_url'] : null,
                'profile_picture'   => isset($auth_data['photo_url']) ? $auth_data['photo_url'] : null,
                'auth_date'         => isset($auth_data['auth_date']) ? $auth_data['auth_date'] : null,
                'telegram_id'       => $auth_data['id']
            ]
        );
    }

    function checkUserExists($db, $auth_data) {
        $target_id = $auth_data['id'];

        $isUser = $db->Select(
            "SELECT `telegram_id`
            FROM `users`
            WHERE `telegram_id` = :id",
            [
                'id' => $target_id
            ]
        );

        return !empty($isUser) && $isUser[0]['telegram_id'] === $target_id;
    }

    if (checkUserExists($db, $auth_data)) {
        updateExistedUser($db, $auth_data);
    } else {
        createNewUser($db, $auth_data);
    }

    $_SESSION = [
        'logged-in'    => true,
        'telegram_id'  => $auth_data['id']
    ];
}

try {
    $auth_data = checkTelegramAuthorization($_GET);
    userAuthentication($db, $auth_data);
} catch (Exception $e) {
    die($e->getMessage());
}

header('Location: ../../user/profile.php');
