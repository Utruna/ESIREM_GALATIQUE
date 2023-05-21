<?php
if (!isset($_SESSION)) {
	session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');

if (isset($_POST['email']) && isset($_POST['password'])) {
	// Récupération des données du formulaire
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password = hash("sha512", $password);
	$idUnivers = $_POST['idUnivers'];
	$_SESSION['idUnivers'] = $idUnivers;

	// Vérification si l'email existe dans la base de données
	$rep = $pdo->prepare('SELECT * FROM joueur WHERE email = :email');
	$rep->execute(['email' => $email]);
	$joueur = $rep->fetch();

	if ($joueur && $password == $joueur['password']) {
		// Le joueur a été trouvé et le mot de passe est correct
		$_SESSION['idJoueur'] = $joueur['id'];
		firstConnexion($joueur['id'], $idUnivers);
		header('Location:../galaxie/galaxie.php');
		exit();
	} else {
		// Le joueur n'a pas été trouvé ou le mot de passe est incorrect
		header('Location:index.php');
		$_SESSION['erreur'] = 'Email ou mot de passe incorrect';
	}
}

function firstConnexion($idPlayer, $idUnivers)
{
	$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
	var_dump($idUnivers);
	//Je récupère toutes les planètes existante dans cette univers qui n'appartiennent à personne
	$requestSearchAllPlaneteAsUnivers =
		" SELECT id FROM `planete` WHERE idjoueur=0 AND idSystemeSolaire IN 
            (SELECT id FROM `systeme_solaire` WHERE idGalaxie IN 
             ( SELECT id FROM `galaxie` WHERE idUnivers = :id) );";

	$rep = $pdo->prepare($requestSearchAllPlaneteAsUnivers);
	$rep->execute(['id' => $idUnivers]);
	$planetes = $rep->fetchAll();

	//Je tire un nombre aleatoire pour fournir une nouvelle plannete a notre nouveau joueur
	$idPlanete = $planetes[rand(0, sizeof($planetes))][0];
	var_dump($idPlanete);
	var_dump($idPlayer);
	//Attribution de la planete
	$rep = $pdo->prepare('UPDATE planete SET idJoueur = :idJoueur WHERE id = :idPlanete');
	$rep->execute(['idPlanete' => $idPlanete, 'idJoueur' => $idPlayer]);

	//Attribution d'une table ressources au joueur
	$query = "INSERT INTO ressource (idUnivers, idJoueur) VALUES (:idUnivers, :idJoueur)";
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':idUnivers', $idUnivers);
	$stmt->bindValue(':idJoueur', $idPlayer);
	$stmt->execute();
}
