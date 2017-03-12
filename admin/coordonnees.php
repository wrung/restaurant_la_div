<?php
    require_once '../inc/connect.php';

    session_start();

    if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
        header('location:connexion.php');
        die;
    }
    elseif($_SESSION['user']['role'] != 'administrateur'){
        header('location:index.php');
        die;
    }


    $post = [];
    $errors = [];
    $uploadDir = 'uploads';
    $extensionsValides = ['image/jpg', 'image/jpeg', 'image/gif', 'image/pjpeg', 'image/png'];
    $maxSize = 1048576*2;

    if(!empty($_POST)){
        // nettoyage des données
        foreach ($_POST as $key => $value)
            $post[$key] = trim(strip_tags($value));

        // gestion des erreurs
        if(empty($post['name']))
            $errors[] = 'Le Nom doit être renseigné.';
        
        if(strlen($post['street']))
            $errors[] = 'L\'Adresse doit être renseignée.';
        
        if(strlen($post['zipcode']))
            $errors[] = 'Le Code postal doit être renseigné.';
        
        if(strlen($post['city']))
            $errors[] = 'La Ville doit être renseignée.';
        
        if(!preg_match("/[0-9]{10,}/", $post['phone']))
            $errors[] = 'Le numéro de téléphone n\'est pas valide.';


        if(count($errors) !== 0){
            $errorsText = 'Erreurs : ';
            $errorsText .= implode('<br>', $errors);
        }

        // données valides
        else {

            $insert = $bdd->prepare('INSERT INTO options (name, street, zipcode, phone) VALUES(:name, :street, :zipcode, :phone)');
            $insert->bindValue(':name', $post['name']);
            $insert->bindValue(':street', $post['street']);
            $insert->bindValue(':zipcode', $post['zipcode']);
            $insert->bindValue(':phone', $post['phone']);

            if($insert->execute()) {
                $successText = 'Les coordonées ont bien été modifiés !';
                header('refresh:3;url:index.php');
            }
        }
    }

    $select = $bdd->prepare('SELECT * FROM options WHERE id=:id LIMIT 1');
    $select->bindValue(':id', 1);
    if($select->execute())
        $infos = $select->fetch(PDO::FETCH_ASSOC);

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration du site de la DIV - Coordonnées</title>
</head>
<body>

<?php if(isset($errorsText)) echo '<p style="color:red">'.$errorsText.'</p>';
      if(!isset($successText)) : ?>

    <h1>Coordonées du restaurant</h1>

    <form method="post">
        <p>
            <label for="name">Nom</label><br>
            <input type="text" name="name" id="name" value="<?=$infos['name'] ?>">
        </p>
        <p>
            <label for="street">Adresse</label><br>
            <input type="text" name="street" id="street" value="<?=$infos['street'] ?>">
        </p>
        <p>
            <label for="zipcode">Code postal</label><br>
            <input type="text" name="zipcode" id="zipcode" value="<?=$infos['zipcode'] ?>">
        </p>
        <p>
            <label for="city">Ville</label><br>
            <input type="text" name="city" id="city" value="<?=$infos['city'] ?>">
        </p>
        <p>
            <label for="phone">Téléphone</label><br>
            <input type="text" name="phone" id="phone" value="<?=$infos['phone'] ?>">
        </p>
        <p><input type="submit" value="Enregistrer les modifications">&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">Retour</a></p>
    </form>

<?php else : echo '<p style="color:green">'.$successText.'</p>'; endif;?>

</body>
</html>