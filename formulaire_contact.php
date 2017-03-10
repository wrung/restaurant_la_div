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
	if(empty($post['name'])){
		$errors[] = 'merci de mettre votre Nom et Prenom svp';
	}
    
    // verification du contenu de email et le format
    if(empty($post['email'])||!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
        $errors[] = 'L\' adresse email doit etre corrigée';
    }
    // verification du numero de telephone
	if(!isset($post['phone']) || !is_numeric($post['phone'])){
		$errors[] = 'numero de telephone doit numeric';
	}

    // verification du contenu de sujet
	if(empty($post['subject'])){
		$errors[] = 'merci de mettre votre sujet svp';
	}

    // verification de la date de naissance
    if(strlen($post['message']) < 10){
		$errors[] = 'merci de saisir votre message avec au moins 10 caractères) ';
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
					$createSuccess = true;
					header('Location: formulaire_contact.php'); // On redirige vers la page de connexion
					die();
                }
    }else{
  
         $errorsText = implode('<br>',$errors);
                echo'<strong>Erreurs :</strong>';
                echo $errorsText;
    }

    }

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restaurant la DIV</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
        h3 {
            text-align:center;

        }
    </style>
</head>
<body>
   
    <!--------------------------------------------------
                        Section : Contact
        -------------------------------------------------->
        
        <section id="contact">
            <div class="container">
                <div class="row">
                    <h3>Nouveau contact</h3>
                    <div class="col-sm-8 col-sm-push-2 col-xs-10 col-xs-push-1">
                        <form id="contact-form" class="form-horizontal" enctype="multipart/form-data"
                            action="#" method="post">
                            
                            <!-- Nom et Prenom-->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input name="name" id="name" required class="form-control" type="text" placeholder="Saisissez votre Nom et Prenom">
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="form-group">
                                
                                <div class="col-xs-12">
                                    <input name="email" id="email" required class="form-control" type="email" placeholder="Saisissez votre Email">
                                </div>
                            </div> 

                             <!-- telephone -->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input name="phone" id="phone" required class="form-control" type="tel" placeholder="Saisissez votre numero de telephone">
                                </div>
                            </div>
                            <!-- Sujet -->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input name="subject" id="subject" required class="form-control" type="text" placeholder="Saisissez votre sujet">
                                </div>
                            </div>
                           <!-- message -->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <textarea name="message" id="message" required class="form-control" type="text" placeholder="Saisissez votre message"></textarea>
                                </div>
                            </div>
                                                   
                            <!-- Bouton d'Envoi -->
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-primary" name="contact" value="Envoyer ma Demande">Envoyer ma Demande</button>
                                </div>
                            </div>
                            
                        </form>    
                    </div>
                </div>
            </div>
        </section>
</body>
</html>