<?php
require_once 'inc/connect.php';

$recette = [];
// view_menu.php?id=6
if(isset($_GET['id']) && !empty($_GET['id'])){

	$idRecette = (int) $_GET['id'];

	// Jointure SQL permettant de récupérer la recette & le prénom & nom de l'utilisateur l'ayant publié
	$selectOne = $bdd->prepare('SELECT r.*, u.firstname, u.lastname FROM recettes AS r INNER JOIN users AS u ON r.id_user = u.id WHERE r.id = :idRecette');
	$selectOne->bindValue(':idRecette', $idRecette, PDO::PARAM_INT);

	if($selectOne->execute()){
		$recette = $selectOne->fetch(PDO::FETCH_ASSOC);
	}
	else {
		// Erreur de développement
		var_dump($query->errorInfo());
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
<?php if(!empty($recette)): ?>
	<h1>Détail d'une recette</h1>

	<h2><?php echo $recette['title'];?></h2>

	<p><?php echo nl2br($recette['content']); ?></p>

	<img src="<?=$recette['picture'];?>" alt="<?php echo $recette['title'];?>">

	<p><strong>Note :</strong> <?php echo $recette['note'];?> / 10</p>

	<?php if($recette['selected'] == 1): ?>
		<p style="color:orange">Recette sélectionnée par le chef</p>
	<?php endif; ?>

	<p>Publié par <?php echo $recette['firstname'].' '.$recette['lastname'];?></p>
<?php else: ?>

	Aucune recette trouvée !
<?php endif; ?>

</body>
</html>