<?php
require_once 'inc/connect.php';

$recette = [];
// view_menu.php?id=6
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
	 <link rel="stylesheet" href="GIT/restaurant_la_div/recipes/css/style.css">
<style>
body{
    background-color: #483932 ;
}
header{
    
    background-color: #4B524D;
    background-repeat: no-repeat;
    text-align: center;
    width: auto;
    height: 50px;
}
 h1{
    color: #E0D9D3;
 }
 h2{
 	color: #E0D9D3;
 }
 p{
 	color: #E0D9D3;
 }
</style>
</head>
<body>

Félicitations
<?php if(!empty($my_recipes)): ?>
	<h1>Détail d'une recette</h1>


	<h2><?php echo $my_recipes['title'];?></h2>

	<p><?php echo nl2br($my_recipes['content']); ?></p>

	<div class="img"<img src="<?=$my_recipes['picture'];?>" alt="<?php echo $my_recipes['title'];?>"></div>

	

	
<?php endif; ?>

</body>
</html>