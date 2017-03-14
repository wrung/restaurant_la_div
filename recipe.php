<?php
session_start(); // Permet de démarrer la session
require_once 'inc/connect.php';
//
//if(!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] == false){
// 	// Redirection vers la page de connexion si non connecté
// 	header('Location: login.php');
// 	die; 
//}

$maxSize = (1024 * 1000) * 2; // Taille maximum du fichier
$uploadDir = 'uploads/'; // Répertoire d'upload
$mimeTypeAvailable = ['image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'];

$errors = [];
$post = [];
$displayForm = true;

if(!empty($_POST)){
	foreach($_POST as $key => $value){
		$post[$key] = trim(strip_tags($value));
	}

	if(strlen($post['title']) < 5 || strlen($post['title']) > 50){
		$errors[] = 'Le titre doit comporter entre 5 et 50 caractères';
	}

	if(strlen($post['content']) < 20){
		$errors[] = 'La description doit comporter au moins 20 caractères';
	}

	
	if(isset($_FILES['picture']) && $_FILES['picture']['error'] === 0){

		$finfo = new finfo();
		$mimeType = $finfo->file($_FILES['picture']['tmp_name'], FILEINFO_MIME_TYPE);

		$extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);

		if(in_array($mimeType, $mimeTypeAvailable)){

			if($_FILES['picture']['size'] <= $maxSize){

				if(!is_dir($uploadDir)){
					mkdir($uploadDir, 0755);
				}

				$newPictureName = uniqid('picture_').'.'.$extension;

				if(!move_uploaded_file($_FILES['picture']['tmp_name'], $uploadDir.$newPictureName)){
					$errors[] = 'Erreur lors de l\'upload de la photo';
				}
			}
			else {
				$errors[] = 'La taille du fichier excède 2 Mo';
			}

		}
		else {
			$errors[] = 'Le fichier n\'est pas une image valide';
		}
	}
	else {
		$errors[] = 'Aucune photo sélectionnée';
	}



	if(count($errors) === 0){

		if(isset($post['selected'])){
			$isSelected = 1;
		}
		else {
			$isSelected = 0;	
		}


		$query = $bdd->prepare('INSERT INTO recipes (title, content, picture, date_create, id_user) VALUES(:title, :content, :picture, NOW(), :id_user)');

		$query->bindValue(':title', $post['title']); // PDO::PARAM_STR est le paramètre par défaut et donc non obligatoire si on traite un string
		$query->bindValue(':content', $post['content']);
		$query->bindValue(':picture', $uploadDir.$newPictureName);
        $query->bindValue(':id_user', $post['id_user'], PDO::PARAM_INT);

		if($query->execute()){
			$success = 'Youpi, la recette est ajoutée avec succès';
			$displayForm = false;
		}
		else {
			// Erreur de développement
			var_dump($query->errorInfo());
			die; // alias de exit(); => die('Hello world');
		}

	}
	else {
		$errorsText = implode('<br>', $errors); 
	}
}

	

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="GIT/restaurant_la_div/assets/css/style.css">
<title>Ajouter une recette</title>

<style>
body{
    background-color: #483932 ;
}
header{
    
    background-color: #4B524D;
    text-align: center;
    margin: 0, auto;
}
label {
	display: inline-block;
	min-width: 200px;
	margin-bottom: 26px;
	margin-left: 50px;
    margin-right: auto;
    color: #4B524D;
}
.input, select, textarea {
	margin-bottom: 7px;
	text-align: center;
}

 h1{
    color: #E0D9D3;
    text-align: center;
 }
form{
	background-color: #B38367;
	margin-left: 500px;
    margin-right: 500px;
}


</style>
</head>
<body>

	<h1>Ajouter une recette</h1>

	<?php if(isset($errorsText)): ?>
		<p style="color:red;"><?php echo $errorsText; ?></p>
	<?php endif; ?>

	<?php if(isset($success)): ?>
		<p style="color:green;"><?php echo $success; ?></p>
	<?php endif; ?>


	<?php if($displayForm === true): ?>
	<form method="post" enctype="multipart/form-data">
	
		<label for="title">Titre de la recette</label>
		<input type="text" name="title" id="title">

		<br>
		<label for="content">Description</label>
		<textarea name="content" id="content"></textarea>

		<br>
		<label for="picture">Photo</label>
		<input type="file" name="picture" id="picture" accept="image/*">

		      
<!--
        <br>
		<label for="date_create">Date de creation</label>
		<input type="text" name="date_create" id="date_create">
-->
		
		<br>
		<label for="id_user">Deposeur de la recette</label>
		<input type="text" name="id_user" id="id_user">
		
		<br>
		<input type="submit" value="Envoyer ma recette">
	</form>
	<?php endif; ?>


</body>
</html>