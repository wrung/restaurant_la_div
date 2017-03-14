<?php
require_once '../inc/connect.php';
   
    $post = []; 
    $errors = []; 
    $displayForm = true;
   
    if(!empty($_POST)){

        foreach($_POST as $key => $value){
            $post[$key] = trim(strip_tags($value));
        }

        if(empty($post['lastname'])){
            $errors[] = 'Veuillez entrer un Nom';
        }

        if(empty($post['firstname'])){
            $errors[] = 'Veuillez entrer un Prénom';
        }

        if(empty($post['email']) || !preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $post['email'])){
            $errors[] = 'L\'email n\'est pas valide';
        }

        if(empty($post['password']) ||!preg_match('#^.{8,20}$#', $post['password'])){
            $errors[] = 'Le mot de passe doit contenir entre 8 et 20 caractères';
        }
        if(empty($post['role'])){
            $errors[] = 'Veuillez sélectionner un rôle';        }

        
        if(count($errors) === 0){

            $query = $bdd->prepare('INSERT INTO users (lastname, firstname, email, password, role, token) VALUES(:dataLastname, :dataFirstname, :dataEmail, :dataPassword, :dataRole, :dataToken)');
            $query->bindValue(':dataLastname', $post['lastname']);
            $query->bindValue(':dataFirstname', $post['firstname']);
            $query->bindValue(':dataEmail', $post['email']);
            $query->bindValue(':dataPassword', password_hash($post['password'], PASSWORD_DEFAULT));
            $query->bindValue(':dataRole', $post['role']);
            $query->bindValue(':dataToken', bin2hex(openssl_random_pseudo_bytes(16)));
            
            if($query->execute()){
                $success = 'Votre ajout s\'est bien déroulée';
                $displayForm = false;
               header('refresh:3;url=index.php');
            }            
        }
        else {
            echo '<p style="color:red">'.implode('<br>', $errors).'</p>';
            
        }
    }
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <style type="text/css">
        label {
            display: inline-block;
            min-width: 120px;
            margin-top: 10px;
        }
        input {
            margin-top: 10px;
        }
    </style>
</head>
<body>

  <h1>Ajouter un utilisateur</h1>
  <?php if(isset($errorsText)): ?>
      <p style="color:red;"><?php echo $errorsText; ?></p>
  <?php endif; ?>
  <?php if(isset($success)): ?>
      <p style="color:green;"><?php echo $success; ?></p>
  <?php endif; ?>

  <?php if($displayForm === true): ?>

  <form method="post">

      <br>
      <label for="lastname">Nom</label>
      <input type="text" name="lastname" id="lastname">

      <br>
      <label for="firstname">Prénom</label>
      <input type="text" name="firstname" id="firstname">

      <br>
      <label for="email">Email</label>
      <input type="text" name="email" id="email">

      <br>
      <label for="password">Password</label>
      <input type="password" name="password" id="password">

      <br>
      <label for="role">Rôle</label>
      <select name="role">
          <option value="" selected disabled>--Sélectionnez--</option>
          <option value="administrateur">Administrateur</option>
          <option value="editeur">Editeur</option>
      </select>

      <br>
      <input type="submit" value="Ajouter">

      <p><a href="index.php">Retour</a></p>
  </form>
  <?php endif; ?>
</body>
</html>