<?php

require_once 'inc/connect.php';

if(isset($_GET['id']) && !empty($_GET['id'])){
    $messageId = $_GET['id'];

    $query = $bdd->prepare('UPDATE contact_form SET view=:view WHERE id=:id');

    // Lier les valeurs de nos données dans la base de données
    $query->bindValue(':view', true); // PDO::PARAM_STR est le paramètre par défaut et donc non
    // obligatoire si on traitre un string
    $query->bindValue(':id', $messageId);

    // Exécution de la requête
    if($query->execute()){
        header("Location:messages.php");
    } else{
        // Erreur de dévellopement
        var_dump($query->errorInfo());
        die; // alias de exit(); => die('Hello World');
    }
}
?>
