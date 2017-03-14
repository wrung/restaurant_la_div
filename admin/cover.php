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
        if ($_FILES['picture']['error'] != UPLOAD_ERR_OK)
            $errors[] = "Aucune image sélectionnée";
        else {
            $finfo = new finfo;
            if (!in_array($finfo->file($_FILES['picture']['tmp_name'], FILEINFO_MIME_TYPE),$extensionsValides)) 
                $errors[] = "Le type d'image n'est pas autorisé";

            if ($_FILES['picture']['size'] > $maxSize) 
                $errors[] = "La taille de l\'image ne doit pas excéder 2 Mo";
        }


        if(count($errors) !== 0){
            $errorsText = 'Erreurs : ';
            $errorsText .= implode('<br>', $errors);
        }

        // données valides
        else {
            if (!is_dir($uploadDir)) 
                mkdir($uploadDir, 0755);
            
            $name = uniqid('img_').'.'.pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION);
            if(!move_uploaded_file($_FILES['picture']['tmp_name'], "$uploadDir/$name"))
                $errors[] = "Erreur lors du move_uploaded_file";


            $update = $bdd->prepare('UPDATE options SET cover=:cover WHERE id=:id');
            $update->bindValue(':id', 1);
            $update->bindValue(':cover', "$uploadDir/$name");

            if($update->execute()) {
                $successText = 'La couverture a bien été modifiée !';
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
    <title>Administration du site de la DIV - Couverture</title>
</head>
<body>

<?php if(isset($errorsText)) echo '<p style="color:red">'.$errorsText.'</p>';
      if(!isset($successText)) : ?>

    <h1>Couverture du site</h1>

    <form method="post" enctype="multipart/form-data">
        <p>
            <label for="picture">Couverture</label><br>
            <input type="file" name="picture" id="picture" accept="image/*">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize ?>">
        </p>
        <p><input type="submit" value="Enregistrer les modifications">&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">Retour</a></p>
    </form>

<?php else : echo '<p style="color:green">'.$successText.'</p>'; endif;?>

</body>
</html>