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
if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
	$recipe_id = (int) $_GET['id'];


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


			$update = $bdd->prepare('UPDATE recipes SET title = :title, content = :content, picture = :picture WHERE id = :id_recipe');

			$update->bindValue(':title', $post['title']); // PDO::PARAM_STR est le paramètre par défaut et donc non obligatoire si on traite un string
			$update->bindValue(':content', $post['content']);
			$update->bindValue(':picture', $uploadDir.$newPictureName);
			$update->bindValue(':id_recipe', $recipe_id, PDO::PARAM_INT);

			if($update->execute()){
				$success = 'Youpi, la recette est modifié avec succès';
				$displayForm = false;
				header('refresh:3;url=recipes.php');
			}
			else {
				// Erreur de développement
				var_dump($update->errorInfo());
				die; // alias de exit(); => die('Hello world');
			}
		}
		else {
			$errorsText = implode('<br>', $errors); 
		}
	}

	// On sélectionne l'utilisateur pour être sur qu'il existe et remplir le formulaire
	$select = $bdd->prepare('SELECT * FROM recipes WHERE id = :id');
	$select->bindValue(':id', $recipe_id, PDO::PARAM_INT);

	if($select->execute()){
		$my_recipes = $select->fetch(PDO::FETCH_ASSOC);
	}
}
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Modifier une recette</title>

<style>
label {
	display: inline-block;
	min-width: 200px;
	margin-bottom: 7px;
}
input, select, textarea {
	margin-bottom: 7px;
}
</style>
</head>
<body>

	<h1>Modifier la recette</h1>

	<?php if(isset($errorsText)): ?>
		<p style="color:red;"><?php echo $errorsText; ?></p>
	<?php endif; ?>

	<?php if(isset($success)): ?>
		<p style="color:green;"><?php echo $success; ?></p>
	<?php endif; ?>


	<?php if($displayForm === true): ?>
	<form method="post" enctype="multipart/form-data">
	
		<label for="title">Titre de la recette</label>
		<input type="text" name="title" id="title" value="<?=$my_recipes['title'];?>">

		<br>
		<label for="content">Description</label>
		<textarea name="content" id="content" value="<?=$my_recipes['content'];?>"><?=$my_recipes['content'];?></textarea>

		<br>
		<label for="picture">Photo</label>
		<input type="file" name="picture" id="picture" value="<?=$my_recipes['picture'];?>" accept="image/*">		      

		<br>
		<input type="submit" value="Modifier la recette">
	</form>

	<p><a href="recipes.php">Retour à la liste des recettes</a></p>

	<?php endif; ?>

</body>
</html>