<?php

require_once '../inc/connect.php';
session_start();

if(!isset($_SESSION['isLogged']) || empty($_SESSION['isLogged'])){
	header('location:index.php');
	die;
}

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

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even){background-color: #f2f2f2}
    </style>
</head>
<body>
	<h1>Les recettes existantes</h1>

	<p><a href="add_recipe.php">Ajouter une recette</a><p>

	<?php if(empty($recettes)) : echo 'Aucune recette n\'a été trouvée';
		  else : ?>
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
						<a href="view_recipe.php?id=<?=$recette['id']; ?>">
							Visualiser
						</a>
					</td>
					<td>
						<a href="update_recipe.php?id=<?=$recette['id']; ?>">
							Modifier
						</a>
					</td>
					<td>
						<a href="delete_recipe.php?id=<?=$recette['id']; ?>">
							Supprimer
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<p><a href="index.php">Retour</a></p>
	
	<?php endif; ?>
</body>
</html>