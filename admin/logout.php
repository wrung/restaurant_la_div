<?php 
	session_start();

    if(isset($_GET['action']) && $_GET['action'] == 'yes') {

        session_destroy();
        header("Location:index.php");
    }

?><!DOCTYPE html>
<html>
<head>
	<title>Deconnexion</title>
</head>
<body>
	<h1>Confirmer la déconnexion ?</h1>
	<p><a href="logout.php?action=yes"><input type="button" value="Se déconnecter"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><input type="button" value="Annuler"></a></p>
	<p></p>
</body>
</html>