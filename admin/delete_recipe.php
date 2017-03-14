<?php
    require_once '../inc/connect.php';
    session_start();

    if(!isset($_SESSION['isLogged']) || empty($_SESSION['isLogged'])){
        header('location:index.php');
        die;
    }
    
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {

        $idRecipe = (int) $_GET['id'];

        $select = $bdd->prepare('SELECT * FROM recipes WHERE id = :id');
        $select->bindValue(':id', $idRecipe, PDO::PARAM_INT);

        if($select->execute())
            $my_recipe = $select->fetch(PDO::FETCH_ASSOC);            

        if(!empty($_POST)) {

            if(isset($_POST['action']) && $_POST['action'] === 'delete') {
                $delete = $bdd->prepare('DELETE FROM recipes WHERE id = :idRecipe');
                $delete->bindValue(':idRecipe', $idRecipe, PDO::PARAM_INT);

                if($delete->execute()) {
                    $success = 'La recette a bien été supprimé';
                    header( "refresh:3;url=recipes.php");
                }
                else {
                    var_dump($res->errorInfo());
                    die;
                }

            }
        }        
    }
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer une recette</title>
</head>
<body>

<?php if(!isset($my_recipe) || empty($my_recipe)) : ?> 
    <p style="color:red">Aucune recette trouvée</p>
<?php elseif(isset($success)) : ?>
    <p style="color:green"><?=$success ?></p>
<?php else : ?>
    <p>Voulez-vous supprimer : <?=$my_recipe['title']; ?></p>

    <form method="post">

        <input type="hidden" name="action" value="delete">

        <button type="button" onclick="history.back();">Annuler</button>
        <input type="submit" value="Supprimer cette recette">
    </form>
<?php endif; ?>

</body>
</html>