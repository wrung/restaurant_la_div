<?php
require_once 'inc/connect.php';
$errors = [];//Declaration d'un tableau qui recupere les erreurs
$post = [];//Declaration d'un tableau qui recupere les elements du submit nettoyés
// Ecoute a quel moment est soumis le formulaire (suppert global $_POST)
if(!empty($_POST)){
	foreach($_POST as $key => $value){// A la soumission on parcour le tableau
		$post[$key] = trim(strip_tags($value));// recuperation des valeurs sous forme de string sans les espaces et les commentaires php et html
        //var_dump($post);
	}
    // verification du contenu de nom et Prenom
	if(!preg_match("#^[A-Z]#", $post['name'])) {
		$errors[] = 'Le Nom et Prénom doit commencer par une majuscule.';
	}
    
    // verification du contenu de email et le format    
    if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $post['email'])){
        $errors[] = 'L\'adresse email doit etre corrigée';
    }
    
    // verification du numero de telephone
        if(!preg_match("#^[0-9]{10}$#", $post['phone'])) {
		$errors[] = 'Le Numero de telephone doit comporter uniquement 10 chiffres';        
	}

    // verification du contenu de sujet
	if(empty($post['subject'])){
		$errors[] = 'Veuillez renseigner le Sujet de votre message.';
	}

    // verification de la date de naissance
    if(strlen($post['message']) < 10){
		$errors[] = 'Veuillez renseigner un Message.';
	}

    
    if(count($errors) === 0){
         $insert = $bdd->prepare('INSERT INTO contact_form (name, email, phone, subject, message, view) VALUE (:name, :email, :phone, :subject, :message, :view)');
			$insert->bindValue(':name', $post['name']);
			$insert->bindValue(':email', $post['email']);
			$insert->bindValue(':phone', $post['phone']);
			$insert->bindValue(':subject', $post['subject']);
            $insert->bindValue(':message', $post['message']);
            $insert->bindValue(':view', false);
			
            if($insert->execute()){
                $success = "Le message a bien été envoyé.";                   
            }
    }else{
  
        $errorsText = implode('<br>',$errors);
       
    }

}

$select = $bdd->prepare('SELECT * FROM options WHERE id=:id LIMIT 1');
$select->bindValue(':id', 1);
if($select->execute())
    $infos = $select->fetch(PDO::FETCH_ASSOC);

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$infos['name'] ?> - Contact</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
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
            <h1><?=$infos['name'] ?></h1>
            <p><?=$infos['street'] ?>,&nbsp;<?=$infos['zipcode'] ?>&nbsp;<?=$infos['city'] ?>
                <br><?=$infos['phone'] ?></p>
        </div>
        <div class="contact">
            <a href="contact.php">Nous contacter</a>
        </div>
    </header>

    <div class="slider">
        <img src="assets/img/slider.jpg" alt="projecteur">
    </div>
     <main>
        
        <section id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-push-1">
                        <form id="contact-form" class="form-horizontal" enctype="multipart/form-data"
                            action="#" method="post">
                            
                            <legend>Formulaire de contact</legend>
                            
                            <?php if(isset($errorsText)) echo '<p style="color:red">'.$errorsText.'</p>'; 
                            elseif(isset($success)) echo '<p style="color:green">'.$success.'</p>'?>
                            <!-- Nom et Prenom-->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input name="name" id="name" class="form-control" type="text" placeholder="Saisissez votre Nom et Prenom">
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="form-group">
                                
                                <div class="col-xs-12">
                                    <input name="email" id="email" class="form-control" type="email" placeholder="Saisissez votre Email">
                                </div>
                            </div> 

                             <!-- telephone -->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input name="phone" id="phone" class="form-control" type="tel" placeholder="Saisissez votre numero de telephone">
                                </div>
                            </div>
                            <!-- Sujet -->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input name="subject" id="subject" class="form-control" type="text" placeholder="Saisissez votre sujet">
                                </div>
                            </div>
                           <!-- message -->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <textarea name="message" id="message" class="form-control" type="text" placeholder="Saisissez votre message"></textarea>
                                </div>
                            </div>
                                                   
                            <!-- Bouton d'Envoi -->
                            <div class="form-group">
                                <div class="col-xs-12" >
                                    <button type="submit" class="btn btn-default center-block" name="contact" value="Envoyer ma Demande">Envoyer ma Demande</button>
                                </div>
                                
                            </div>
                            
                        </form>    
                    </div>
                </div>
            </div>
        </section>

        <br>
        <br>
        <p class="text-center">
            <a class="bouton" href="index.php">Accueil</a>
        </p>

    </main>
    
</body>
</html>