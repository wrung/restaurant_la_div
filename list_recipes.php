<?php

require_once 'inc/connect.php';

// On selectionne les colonnes id & title de la table recettes
$select = $bdd->prepare('SELECT id,title FROM recipes ORDER BY id DESC');
if($select->execute()){
	$recettes = $select->fetchAll(PDO::FETCH_ASSOC);
}
else {
	// Erreur de développement
	var_dump($select->errorInfo());
	die; // alias de exit(); => die('Hello world');
}

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Liste de recettes</title>
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
 #text{
    width: 400px;
    height: 50px;
    font-size: 30px;
    float: right;
    background-color: #B38367;
    color: #E0D9D3;
    
 }
 h1{
    color: #E0D9D3;
 }
 
</style>
</head>
<body>
	<h1>Les recettes existantes</h1>

	<br>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>titre</th>
				<th>détails</th>
			</tr>
		</thead>

		<tbody>
			<!-- foreach permettant d'avoir une ligne <tr> par ligne SQL -->
			<?php foreach($recettes as $recette): ?>
				<tr>
					<td><?=$recette['id']; ?></td>
					<td><?=$recette['title']; ?></td>
					<td>
						<!-- view_menu.php?id=6 -->
						<a href="view_recipes.php?id=<?=$recette['id']; ?>">
							Visualiser
						</a>
					</td>
					<td>
						<!-- view_menu.php?id=6 -->
						<a href="update_recipes.php?id=<?=$recette['id']; ?>">
							Update
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</body>
</html>