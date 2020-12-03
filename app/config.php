<?php
ini_set('session.cookie_httponly', 1);
session_start();

define("APP_NAME", "AJAX Login");

require_once 'db.php';

/*
 * Fonction qui crée une chaîne aléatoire.
 */
function random_str($length, $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")
{
    $str = '';
    $max = mb_strlen($keyspace, "8bit") - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

/*
 * Authentifiez l'utilisateur à chaque demande de page. Bon pour empêcher la falsification des valeurs de session / cookie
 */
function auth_user() {
    global $db;

    $user = null;
    if(isset($_SESSION["user"]) || isset($_COOKIE["ajax_login_user"]))
    {
        $logged_in_user = isset($_SESSION["user"]) ? $_SESSION["user"] : json_decode($_COOKIE["ajax_login_user"], true);
        $check_user = $db->prepare("SELECT id FROM users WHERE email = :email AND username = :username AND password = :password");
        $check_user->execute(array(
            "email" => $logged_in_user["email"],
            "username" => $logged_in_user["username"],
            "password" => $logged_in_user["password"]
        ));
        if($check_user->rowCount() > 0)
        {
            $user = $logged_in_user;
            $_SESSION['user_id'] = $check_user->fetch()['id'];
        }
    }

    return $user;
}

if(!isset($_SESSION["token"]))
{
    $_SESSION["token"] = random_str(60);
}
