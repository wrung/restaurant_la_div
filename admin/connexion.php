<?php

require_once '../inc/connect.php';

$post = [];
$errors = [];

if(isset($_GET['message']) && !empty($_GET['message'])){
    $message = $_GET['message'];
    echo $message;
}


if(!empty($_POST)){
        // On nettoie les données en retirant les espaces en début et fin de chaine(trim)
        // puis en supprimant les balises html et php 

    foreach($_POST as $key => $value){
        $post[$key] = trim(strip_tags($value));
    }

    if(empty($post['password'])){
            $errors[] = 'Le mot de passe ne convient pas';
    }

    if(empty($post['email'])){
        $errors[] = 'L\'username ne correspond pas';
    }

    if(count($errors) === 0){
        $query = $bdd->prepare('SELECT * FROM users WHERE email = :dataEmail');

        $query->bindValue(':dataEmail', $post['email']);
        
        if($query->execute()){
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if (password_verify($post['password'], $user['password']))
                { 
                    session_start();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                  //  $_SESSION['prenom'] = $user['prenom'];

                    header("Location: ./index.php");
                }
                else
                {
                    echo "password incorrect";
                }
            }else{
                echo "Email incorrect";
            }

        } else {
                // Erreur de dévellopement
            var_dump($query->errorInfo());
                die; // alias de exit(); => die('Hello World');
            }
        }
    }

    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Connexion</title>
        <style type="text/css">
            label {
                display: inline-block;
                min-width: 120px;
                margin-top: 10px;
            }
            input {
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <h1>Connexion</h1>

        <form method="post">
            <br>
            <label for="email">Email</label>
            <input type="text" name="email" id="email">

            <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password"> 

            <br>
            <input type="submit" value="Se connecter">
        </form>
        
    </body>
    </html>
