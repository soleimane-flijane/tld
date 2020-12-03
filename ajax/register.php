<?php
session_start();

/* vérifier la demande AJAX */
if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {

    $errors = array();     //Tableau erreur
    $success = false;      //

    /*
     * 
     */
    $formData = array();
    parse_str($_POST["formData"], $formData);

    if(isset($_SESSION["token"]) && $_SESSION["token"] === $formData["_token"])  //
    {
        /*Vérifier si les champs affichés sont des chaînes vides (juste au cas où) - 
        par exemple l'utilisateur ne saisissant que des espaces au lieu du nom réel, de l'adresse e-mail, 
        du nom d'utilisateur et du mot de passe*/

        if(trim($formData["name"]) == "")
        {
            $errors[] = "Remplir ce champ.";
        }
        if(trim($formData["email"]) == "")
        {
            $errors[] = "Remplir ce champ.";
        }
        if(!filter_var($formData["email"], FILTER_VALIDATE_EMAIL))
        {
            $errors[] = "L'e-mail doit être une adresse e-mail valide.";
        }
        if(trim($formData["username"]) == "")
        {
            $errors[] = "Remplir ce champ.";
        }
        if(trim($formData["password"]) == "")
        {
            $errors[] = "Remplir ce champ.";
        }

        require_once '../app/db.php';

        /*S'il y a un utilisateur déjà enregistré avec l'e-mail ou le nom d'utilisateur*/
        $check_if_user_exists = $db->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
        $check_if_user_exists->execute(array(
            ":email" => $formData["email"],
            ":username" => $formData["username"]
        ));
        if($check_if_user_exists->rowCount() > 0)
        {
            $errors[] = "User with username " . $formData["username"] . " or email " . $formData["email"] . "Compte déjà éxistant";
        }

        /*Si aucune erreur, création d'utilisateur dans la base de données et connexion*/
        if(empty($errors))
        {
            $hashed_password = password_hash($formData["password"], PASSWORD_DEFAULT);
            $create_user = $db->prepare("INSERT INTO users(name, email, username, password, created_at) VALUES(:name, :email, :username, :password, NOW())");
            $create_user->execute(array(
                ":name" => $formData["name"],
                ":email" => $formData["email"],
                ":username" => $formData["username"],
                ":password" => $hashed_password
            ));
            $user_id = $db->lastInsertId();
            $_SESSION["user"] = array(
              "id" => $user_id,
              "name" => $formData["name"],
              "email" => $formData["email"],
              "username" => $formData["username"],
              "password" => $hashed_password
            );
            $success = true;
        }
    }
    echo json_encode(array("errors" => $errors, "success" => $success));
}
