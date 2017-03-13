<?php
    require_once '../inc/connect.php';

    $post = [];
    $errors = [];

    if(!empty($_POST)){
        // nettoyage des données
        foreach ($_POST as $key => $value)
            $post[$key] = trim(strip_tags($value));

        // gestion des erreurs
        if(empty($post['email']) || !filter_var($post['email'], FILTER_VALIDATE_EMAIL))
            $errors[] = 'L\'adresse email doit être au bon format';
        

        if(count($errors) !== 0){
            $errorsText = 'Erreurs : ';
            $errorsText .= implode('<br>', $errors);
        }

        // données valides
        else {
            $select = $bdd->prepare('SELECT * FROM users WHERE email=:email');
            $select->bindValue(':email', $post['email']);

            if($select->execute()) {
                $user = $select->fetch(PDO::FETCH_ASSOC);
                if(empty($user))
                    $errorsText = 'Utilisateur inexistant';
                else {

                    $to = $user['email'];
                    $subject = 'Nouveau mot de passe';

                    $message = '
                    <html>
                    <head>
                    <title>Nouveau mot de passe</title>
                    </head>
                    <body>
                    <p>Veuillez cliquer sur le lien ci-dessous pour générer un nouveau mot de passe</p>
                    <a href="'.$_SERVER['SERVER_NAME'].substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/')).'/password.php?email='.$user['email'].'&token='.$user['token'].'">Nouveau mot de passe</a>
                    </body>
                    </html>
                    ';

                    // Always set content-type when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    // More headers
                    $headers .= 'From: <webmaster@example.com>' . "\r\n";

                    mail($to,$subject,$message,$headers);
                    
                    $successText = "La procédure vous a été envoyé par mail";
                    header('refresh:3;url=index.php');
                }
            }
        }
    }
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration du site de la DIV - Mot de passe oublié</title>
</head>
<body>

<?php if(isset($errorsText)) echo '<p style="color:red">'.$errorsText.'</p>';
      if(!isset($successText)) : ?>

    <h1>Mot de passe oublié</h1>

    <form method="post" >
        <p>
            <label for="email">Email</label><br>
            <input type="text" name="email" id="email">
        </p>
        <p><input type="submit" value="Envoyer">&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">Retour</a></p>
    </form>

<?php else : echo '<p style="color:green">'.$successText.'</p>'; endif;?>

</body>
</html>