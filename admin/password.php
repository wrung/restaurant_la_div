<?php
    require_once '../inc/connect.php';
    session_start();

    $newMode = false;
    if(isset($_GET['email']) && isset($_GET['token'])) {
        $newMode = true;

        $select = $bdd->prepare('SELECT id, email, token FROM users WHERE email=:email AND token=:token');
        $select->bindValue(':email', $_GET['email']);
        $select->bindValue(':token', $_GET['token']);

        if($select->execute()){
            $user = $select->fetch(PDO::FETCH_ASSOC);
            if($user === false){
                header('location:index.php');
                die;
            }
            else {
                $email = $_GET['email'];
            }
        }
    }
    elseif(!isset($_SESSION['user']) || empty($_SESSION['user'])){
        header('location:connexion.php');
        die;
    }

    $post = [];
    $errors = [];

    if(!empty($_POST)){
        // nettoyage des données
        foreach ($_POST as $key => $value)
            $post[$key] = trim(strip_tags($value));

        // gestion des erreurs
        if(empty($post['password']))
            $errors[] = 'Le champ Mot de passe est vide.';

        if(empty($post['confirm']))
            $errors[] = 'Le champ Confirmation du mot de passe est vide.';
        elseif($post['confirm'] !== $post['password'])
            $errors[] = 'Les champs ne correspondent pas.';
        
        if(count($errors) !== 0){
            $errorsText = 'Erreurs : ';
            $errorsText .= implode('<br>', $errors);
        }

        // données valides
        else {
            if($newMode === false){
                $email = $_SESSION['user']['email'];
            }

            $update = $bdd->prepare('UPDATE users SET password=:password, token=:token WHERE email=:email');
            $update->bindValue(':email', $email);
            $update->bindValue(':password', password_hash($post['password'], PASSWORD_DEFAULT));
            $update->bindValue(':token', bin2hex(openssl_random_pseudo_bytes(16)));

            if($update->execute()) {
                $successText = 'Le mot de passe a bien été changé !';
                header('refresh:3;url=index.php');
            }
        }
    }
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration du site de la DIV - Mot de passe</title>
</head>
<body>

<?php if(isset($errorsText)) echo '<p style="color:red">'.$errorsText.'</p>';
      if(!isset($successText)) : ?>

    <h1>Changer de mot de passe</h1>

    <form method="post" >
        <p>
            <label for="password">Nouveau mot de passe</label><br>
            <input type="password" name="password" id="password">
        </p>
        <p>
            <label for="confirm">Confirmation du mot de passe</label><br>
            <input type="password" name="confirm" id="confirm">
        </p>
        <p><input type="submit" value="Enregistrer les modifications">&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">Retour</a></p>
    </form>

<?php else : echo '<p style="color:green">'.$successText.'</p>'; endif;?>

</body>
</html>