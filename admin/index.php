<?php
    require_once '../inc/connect.php';
    session_start();

    ///////// A SUPPRIMER APRES LES TESTS ///////////
    $_SESSION['user'] = [
        'id' => 1,
        'email' => 'morganpaulparvenu@gmail.com',
        'role' => 'administrateur'
    ];
    /////////////////////////////////////////////////

    if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
        header('location:connexion.php');
        die;
    }

// preg_replace('#'.preg_quote('test').'#', '<span style="color:yellow">\\0</span>', 'teste');
var_dump(preg_replace('#'.preg_quote('test').'#', '<span style="background:yellow">\\0</span>', 'teste'));

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration du site de la DIV</title>
</head>
<body>
    <h1>Bienvenue <?=$_SESSION['user']['email'] ?> !</h1>
    
    <ul>
        <?php if($_SESSION['user']['role'] == 'administrateur') : ?>
        <li><a href="add_user.php">Créer un nouvel utilisateur</a></li>
        <li><a href="coordonnees.php">Modifier les coordonnées</a></li>
        <li><a href="cover.php">Changer l'image de couverture</a></li>
        <?php endif; ?>
        <li><a href="recipes.php">Gérer les recettes</a></li>
        <li><a href="password.php">Changer de mot de passe</a></li>
    </ul>
</body>
</html>