<<<<<<< HEAD
<!DOCTYPE>

<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Liste des recettes</title>
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="GIT/restaurant_la_div/recipes/css/style.css">
	 
<style>
body{
	background-color: #483932 ;
}
header{
	
	background-color: #B58263;
	background-repeat: no-repeat;
	text-align: center;
}
 #article{
 	
 	width: 50px;
 	height: 50px;
 	float: left;

 	
 }
 #aside{
 	width: 400px;
 	height: 50px;
 	font-size: 30px;
 	float: right;
 	background-color: #B38367;
 	color: #E0D9D3;
 	f
 }
 h1{
 	color: #E0D9D3;
 }
 
</style>
</head>
<body>
<header>
<h1>Les nouvelles recettes du chef<h1>
<h3><a href="index.php">Retour accueil</a><h3>
</header>

<section class="container"> 
<div id="article" ><img src="assets/img/salade.jpg" alt="recette 1"></div>
<div id="aside">
<p><h6>
Temps de préparation : 30 minutes
Temps de cuisson : 15 minutes

Ingrédients (pour 4 personnes) :
- 500 g de spaghettini (numéro 3)
- 1 oignon rouge
- 1/2 poivron jaune
- 1/2 poivron rouge
- 1/2 aubergine
- 3 courgettes (petites)
- 1 carotte
- piment
- huile d’olive
- sel
- parmesan râpé
Couper les légumes en petits morceaux. Dans une poêle, faire revenir les légumes dans l’huile bien chaude.
Laisser rissoler en mélangeant régulièrement jusqu’à ce qu’ils soient légèrement dorés. Saler et ajouter une pincée de piment.
Cuire à part les spaghettini et bien mélanger avec les légumes avant de servir. Saupoudrer de parmesan.<h6></p></div>



<div id="article" ><img src="assets/img/spaguettis.jpg" alt="recette 2"></div>
<div id="aside">
<p><h6>Temps de préparation : 5 minutes
Temps de cuisson : 15 minutes

Ingrédients (pour 4 Personnes) :
- 4 pavés de saumon
- 3 échalotes
- 20 cl de soja cuisine 

Préparation de la recette :

Allumer le four à 180°C, déposer le saumon dans un plat allant au four, éplucher 3 grosses échalotes (cuisses de poulet).
Étaler la crème sur le poisson, parsemer les échalotes dessus, saler, mettre au four. Quand les échalotes brunissent le poisson est cuit, servir avec riz, pommes vapeur. 
En cas de poisson congelé mettre le four à 150°C. Cuisson un peu plus longue pour assurer la décongélation du poisson.
Boisson conseillée :<h6></p></div>
<div id="" ="article"> <img src="assets/img/viandegrillée.jpg" alt="recette 3"></div>
<div id="aside"><h6>
<p>Temps de préparation : 5 minutes
Temps de cuisson : 30 minutes

Ingrédients (pour 6 personnes) :
- 2 aubergines
- 4 courgettes longues (moyennes)
- 1 poivron rouge (ou jaune)
- 2 gousses d'ail
- 10 cl d'huile d'olive fruitée
- 3 cuillères à soupe de vinaigre balsamique
- 1 pincée de sel fin (pas obligatoire)

Préparation de la recette :

Passer les gousses d'ail au presse ail, ou les écraser en purée.
les ajouter à l'huile d'olive dans un bol.
Ajouter le vinaigre balsamique, et réserver.
Détailler les aubergines en fines tranches (2 à 3 mm).
Détailler les courgettes de la même façon, mais si possible en tranches plus fines que l'aubergine (avec un économe, c'est l'idéal).
Découper les poivrons en quatre, et les débarasser de leurs graines et parties blanches.<h6></p>
</div>
</div>
</section>

<footer>
	
</footer>




























</body>

=======
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
<title>Ajouter une recette</title>

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
>>>>>>> origin/herve
</html>