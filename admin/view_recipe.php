<?php
require_once '../inc/connect.php';
session_start();

if(!isset($_SESSION['isLogged']) || empty($_SESSION['isLogged'])){
	header('location:index.php');
	die;
}

$recette = [];

if(isset($_GET['id']) && !empty($_GET['id'])){

	$idRecette = (int) $_GET['id'];

	$selectOne = $bdd->prepare('SELECT * FROM recipes WHERE id = :idRecette');
	$selectOne->bindValue(':idRecette', $idRecette, PDO::PARAM_INT);

	if($selectOne->execute()){
		$my_recipes = $selectOne->fetch(PDO::FETCH_ASSOC);
	}
	else {
		// Erreur de développement
		//var_dump($query->errorInfo());
		die; // alias de exit(); => die('Hello world');
	}
}


?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Détail d'une recette</title>
</head>
<body>

<?php if(!empty($my_recipes)): ?>
	<h1>Détail d'une recette</h1>


	<h2><?php echo $my_recipes['title'];?></h2>

	<p><?php echo nl2br($my_recipes['content']); ?></p>

	<img src="<?=$my_recipes['picture'];?>" alt="<?php echo $my_recipes['title'];?>">

	
<?php else : ?>
	<p>Aucune recette trouvée</p>
<?php endif; ?>

	<p><a href="recipes.php">Retour à la liste des recettes</a></p>

</body>
</html>