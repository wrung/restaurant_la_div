<?php
require_once 'inc/connect.php';

$select = $bdd->prepare('SELECT * FROM options WHERE id=:id LIMIT 1');
$select->bindValue(':id', 1);
if($select->execute())
    $infos = $select->fetch(PDO::FETCH_ASSOC);

$recette = [];
// view_menu.php?id=6
if(isset($_GET['id']) && !empty($_GET['id'])){

	$idRecette = (int) $_GET['id'];

	$selectOne = $bdd->prepare('SELECT recipes.*, firstname, lastname FROM recipes INNER JOIN users WHERE recipes.id_user = users.id AND recipes.id = :idRecette');
	$selectOne->bindValue(':idRecette', $idRecette, PDO::PARAM_INT);

	if($selectOne->execute()){
		$my_recipes = $selectOne->fetch(PDO::FETCH_ASSOC);
	}
	else {
		// Erreur de développement
		var_dump($selectOne->errorInfo());
		die; // alias de exit(); => die('Hello world');
	}
}


?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=$infos['name'] ?> - <?=$my_recipes['title'] ?></title>
	<!-- Déclaration des Feuilles de Styles -->
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- FontAwesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Courgette" rel="stylesheet">
</head>
<body>



    <header class="top">
        <div class="coordonnees">
            <h1><?=$infos['name'] ?></h1>
            <p><?=$infos['street'] ?>,&nbsp;<?=$infos['zipcode'] ?>&nbsp;<?=$infos['city'] ?>
                <br><?=$infos['phone'] ?></p>
        </div>
        <div class="contact">
            <a href="contact.php">Nous contacter</a>
        </div>
    </header>

    <div class="slider">
        <img src="admin/<?=$infos['cover'] ?>" alt="projecteur">
    </div>

    <main>
        <h2 class="title"><?php echo ucfirst($my_recipes['title']);?></h2>

        <section>
<div class="page_view">

<?php if(!empty($my_recipes)): ?>

	<h2></h2>

	<h3>Par <?=$my_recipes['firstname'].' '.$my_recipes['lastname'] ?></h3>

	<p> <strong>Les ingredients :</strong><br> <?php echo nl2br($my_recipes['content']); ?></p>

	<img src="admin/<?=$my_recipes['picture'];?>" alt="<?php echo $my_recipes['title'];?>">

	
<?php endif; ?>
</div>
        </section>

        <br>
        <br>
        <p class="text-center">
            <a class="bouton" href="index.php">Accueil</a>
        </p>
    </main>

    <footer></footer>
</body>
</html>