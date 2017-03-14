<?php
require_once 'inc/connect.php';


if(!empty($_POST)){

    foreach($_POST as $key => $value){
        $post[$key] = trim(strip_tags($value));
    }

    if(!empty($post['recherche'])) {
        $quote = $post['recherche'];
        preg_replace('#'.preg_quote('test').'#', '<span style="color:yellow">\\0</span>', 'teste');
    }
}

$select = $bdd->prepare('SELECT * FROM options WHERE id=:id LIMIT 1');
$select->bindValue(':id', 1);
if($select->execute())
    $infos = $select->fetch(PDO::FETCH_ASSOC);

// On selectionne les colonnes id & title de la table recettes
$select = $bdd->prepare('SELECT id,title,picture FROM recipes ORDER BY id DESC');
if($select->execute()){
	$recipes = $select->fetchAll(PDO::FETCH_ASSOC);
}    
?><!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?=$infos['name'] ?> - Liste des recettes</title>

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
        <h2 class="title">Découvrez les recettes de nos chefs</h2>

        <section>
            <div class="page_list">

            <table>
                <thead>
                    <tr>
                        <th>
                            <form method="post" enctype="multipart/form-data">
                                <input type="text" name="recherche" id="recherche" placeholder="...">
                                <input type="submit" value="Rechercher une recette">
                            </form>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <!-- foreach permettant d'avoir une ligne <tr> par ligne SQL -->
                    <?php foreach($recipes as $recipe): ?>            
                        <tr>
                            <td><img src="admin/<?=$recipe['picture'];?>" alt="<?php echo $recipe['title'];?>"></td>
                            <td><?=isset($quote) ? preg_replace('#'.preg_quote($quote).'#', '<span style="background:yellow;color:black">\\0</span>', $recipe['title']) : $recipe['title']; ?></td>
                            <td><a class="link_recipe" href="view_recipe.php?id=<?=$recipe['id']; ?>">Lire la recette</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
