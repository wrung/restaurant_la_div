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
<header>
<h1>Les nouvelles recettes du chef<h1>
<h3><a href="index.php">Retour accueil</a><h3>
</header>
<body>
<?php if(!empty($recette)): ?>
    <h1>Détail de la recette</h1>
<div class="article">

    <h2><?php echo $recette['title'];?></h2>
<div class="text"><p><?php echo nl2br($recette['content']); ?></p>></div>

    <img src="<?=$recette['picture'];?>" alt="<?php echo $recette['title'];?>">

    <p><strong>Note :</strong> <?php echo $recette['note'];?> / 10</p>

    <?php if($recette['selected'] == 1): ?>
        <p style="color:orange">Recette sélectionnée par le chef</p>
    <?php endif; ?>

    <p>Publié par <?php echo $recette['firstname'].' '.$recette['lastname'];?></p>
<?php else: ?>
</div>

    Aucune recette trouvée !
<?php endif; ?>

</body>
</html>
