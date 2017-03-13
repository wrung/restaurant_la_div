<?php
    require_once 'inc/connect.php';

    $select = $bdd->prepare('SELECT * FROM options WHERE id=:id LIMIT 1');
    $select->bindValue(':id', 1);
    if($select->execute())
        $infos = $select->fetch(PDO::FETCH_ASSOC);

    $select = $bdd->prepare('SELECT * FROM recipes ORDER BY id DESC LIMIT 3');
    if($select->execute())
        $recipes = $select->fetchAll(PDO::FETCH_ASSOC);
    
?><!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?=$infos['name'] ?></title>

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
        <img src="<?=$infos['cover'] ?>" alt="projecteur">
    </div>

    <main>
        <h2 class="title">Les recettes des chefs</h2>

        <section>
        <?php foreach($recipes as $recipe) : ?>
            <article class="vignette">
                <img src="<?=$recipe['picture'] ?>" alt="<?=$recipe['title'] ?>">
                <a href="view_recette.php?id=<?=$recipe['id'] ?>">lire la recette</a>
            </article>
        <?php endforeach; ?>
        </section>

        <br>
        <br>
        <p class="text-center">
            <a class="bouton" href="recettes.php">Découvrir toutes<br>les recettes des chefs</a>
        </p>
    </main>

    <footer></footer>
</body>

</html>
