<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Restaurant La DIV</title>

    <!-- Déclaration des Feuilles de Styles -->
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- FontAwesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Courgette" rel="stylesheet">

</head>

<body>

    <!--    A brancher sur l'admin (table options)-->
    <header class="top">
        <div class="coordonnees">
            <h1>Restaurant La DIV</h1>
            <p>1 rue de l'avenue, 33000 Bordeaux
                <br>01.23.45.67.89</p>
        </div>
        <div class="contact">
            <a href="contact.php">Nous contacter</a>
        </div>
    </header>

    <div class="slider">
        <img src="assets/img/slider.jpg" alt="projecteur">
    </div>

    <main>

        <!--    A brancher sur l'admin (table recettes)-->
        <h2 class="title">Les recettes des chefs</h2>

        <section>
            <article class="vignette">
                <img src="assets/img/recette1.jpg" alt="Recette 1">
                <a href="view_recette.php">lire la recette</a>
            </article>

            <article class="vignette">
                <img src="assets/img/recette1.jpg" alt="Recette 1">
                <a href="view_recette.php">lire la recette</a>
            </article>

            <article class="vignette">
                <img src="assets/img/recette1.jpg" alt="Recette 1">
                <a href="view_recette.php">lire la recette</a>
            </article>
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
