<?php
session_start(); // Permet de démarrer la session
require_once '../inc/connect.php';

if(!isset($_SESSION['isLogged']) || empty($_SESSION['isLogged'])){
	header('location:index.php');
	die;
}

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
        $query->bindValue(':id_user', $_SESSION['user']['id'], PDO::PARAM_INT);

		if($query->execute()){
			$success = 'Youpi, la recette est ajoutée avec succès';			
			$displayForm = false;
			header('refresh:3;url=recipes.php');
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
<title>Ajouter une recette</title>

<style>
label {
	display: inline-block;
	min-width: 200px;
	margin-bottom: 7px;
	vertical-align: top
}
input, select, textarea {
	margin-bottom: 7px;
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
		
		<br>
		<input type="submit" value="Envoyer ma recette">
	</form>

	<p><a href="recipes.php">Retour à la liste des recettes</a></p>

	<?php endif; ?>


</body>
</html>