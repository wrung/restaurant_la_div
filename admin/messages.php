<?php

require_once 'inc/connect.php';


// On selectionne les colonnes id & nom de la table users
$select = $bdd->prepare('SELECT * FROM contact_form ORDER BY id');
if($select->execute()){
	$messages = $select->fetchAll(PDO::FETCH_ASSOC);
}
else {
	// Erreur de développement
	var_dump($query->errorInfo());
	die; // alias de exit(); => die('Hello world');
}

$select = $bdd->prepare('SELECT COUNT(id) AS nombre FROM contact_form WHERE view= :view');

    $select->bindValue(':view', 0);
    if($select->execute()){
        $nonlus = $select->fetch(PDO::FETCH_ASSOC)["nombre"];
  }




?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Boite de réception <?=!empty($nonlus) ? '('.$nonlus.')' : '' ?></title>
</head>
<body>
	<h1>Messages<?=!empty($nonlus) ? ' (<strong>'.$nonlus.'</strong>)' : '' ?></h1>

	<br>
	<form method="post">
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Nom</th>
					<th>Email</th>
					<th>Sujet du Message</th>
					<th>Message</th>
					<th>Etat</th>
					
				</tr>
			</thead>

			<tbody>
				<!-- foreach permettant d'avoir une ligne <tr> par ligne SQL -->
				<?php foreach($messages as $message): ?>
					<tr>
						<td><?=$message['id']; ?></td>
						<td><?=$message['name']; ?></td>
						<td><?=$message['email']; ?></td>
						<td><?=$message['subject']; ?></td>
						<td><?=$message['message']; ?></td>
						<td><?=!$message['view'] ? '<a href="readmessages.php?id='.$message['id'].'">Non lu</a>' : 'Lu' ?></td>

					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</form>

</body>
</html>