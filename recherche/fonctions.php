<?php
if (!isset($_SESSION)) {
    session_start();
}
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];
$idPlanete = $_POST['idPlanete'];

//upgradeEnergie($idJoueur, $idPlanete);
upgradeLaser($idJoueur, $idPlanete);

/*
SELECT COUNT(*) AS count: Sélectionne le nombre total de résultats et le renomme en count.

FROM contrainte_recherche cr: Spécifie la table contrainte_recherche et lui attribue l'alias cr.

INNER JOIN recherche r ON cr.idRecherche = r.id: Effectue une jointure interne entre la table contrainte_recherche et la table 
recherche en utilisant la colonne idRecherche de contrainte_recherche et la colonne id de recherche.

WHERE cr.idRechercheSouhaiter = (SELECT id FROM recherche WHERE typeRecherche = "ions" AND niveau = 0): Filtre les résultats pour 
ceux où la colonne idRechercheSouhaiter de contrainte_recherche est égale à l'ID de la recherche "ions" avec un niveau de 0. 
Cela signifie que nous recherchons les contraintes associées aux ions de niveau 0.

AND cr.idRecherche NOT IN (SELECT idRecherche FROM recherche WHERE idJoueur = :idJoueur): Exclut les résultats où la colonne 
idRecherche de contrainte_recherche est présente dans la table recherche pour le joueur spécifié par idJoueur. 
Cela permet de vérifier si le joueur possède déjà ces recherches.

La requête compte le nombre de résultats qui satisfont ces conditions. Si le résultat count est égal à 0, 
cela signifie que le joueur possède toutes les recherches requises pour obtenir les ions.
*/
// Vérification des recherches pour les ions

// Vérification si le compte des recherche demander est égale à 0
function countResearch($query, $idJoueur)
{
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['count'];
}

function checkIonsResearch($idJoueur)
{
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
    $query = "SELECT COUNT(*) AS count
                FROM contrainte_recherche cr
                INNER JOIN recherche r ON cr.idRecherche = r.id
                WHERE cr.idRechercheSouhaiter = (SELECT id FROM recherche WHERE typeRecherche = 'ions' AND niveau = 0)
                AND cr.idRecherche NOT IN (SELECT idRecherche FROM recherche WHERE idJoueur = :idJoueur)";
    // Vérification des recherches pour les ions
    $count = countResearch($query, $idJoueur);
    // Si le résultat count est égal à 0, cela signifie que le joueur possède toutes les recherches requises pour obtenir les ions.
    if ($count == 0) {
        echo "Le joueur possède les recherches nécessaires pour obtenir les ions.";
        return true;
    } else {
        echo "Le joueur ne possède pas toutes les recherches nécessaires pour obtenir les ions.";
        return false;
    }
}

function checkLaserResearch($idPlanete)
{
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
    $query = "SELECT COUNT(*) AS count
                FROM infrastructure
                WHERE niveauTechEnergie >= 5
                AND idPlanete = :idPlanete";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idPlanete);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $count = $result['count'];

    if ($count > 0) {
        echo "Le joueur possède le niveau de technologie énergie requis pour obtenir le laser.";
        return true;
    } else {
        echo "Le joueur ne possède pas le niveau de technologie énergie requis pour obtenir le laser.";
        return false;
    }
}


function checkBouclierResearch($idJoueur)
{
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
    $queryLaser = "SELECT COUNT(*) AS count
                FROM recherche jr
                INNER JOIN recherche r ON jr.idRecherche = r.id
                WHERE jr.idJoueur = :idJoueur
                AND r.typeRecherche = 'laser'
                AND r.niveau >= 5";

    $queryEnergie = "SELECT COUNT(*) AS count
                FROM recherche jr
                INNER JOIN recherche r ON jr.idRecherche = r.id
                WHERE jr.idJoueur = :idJoueur
                AND r.typeRecherche = 'energie'
                AND r.niveau >= 8";

    $countLaser = countResearch($queryLaser, $idJoueur);
    $countEnergie = countResearch($queryEnergie, $idJoueur);

    if ($countLaser == 0 && $countEnergie == 0) {
        echo "Le joueur possède les recherches nécessaires pour obtenir le bouclier.";
        return true;
    } else {
        echo "Le joueur ne possède pas toutes les recherches nécessaires pour obtenir le bouclier.";
        return false;
    }
}
// Vérification des ressource du joueur
function checkRessources($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium)
{
    $idUnivers = $_SESSION['idUnivers'];
    // Récupérer les ressources actuelles du joueur
    $query = "SELECT stockMetal, stockEnergie, stockDeuterium FROM ressource WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->bindValue(':idUnivers', $idUnivers);
    var_dump($stmt);
    $stmt->execute();
    $ressources = $stmt->fetch(PDO::FETCH_ASSOC);

    $stockMetal = $ressources['stockMetal'];
    $stockEnergie = $ressources['stockEnergie'];
    $stockDeuterium = $ressources['stockDeuterium'];

    // Vérifier si le joueur possède suffisamment de ressources
    if ($stockMetal >= $coutMetal && $stockEnergie >= $coutEnergie && $stockDeuterium >= $coutDeuterium) {
        return true;
    } else {
        return false;
    }
}
function updateRestante($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium)
{
    $idUnivers = $_SESSION['idUnivers'];
    // Récupérer les ressources actuelles du joueur
    $query = "SELECT stockMetal, stockEnergie, stockDeuterium FROM ressource WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->bindValue(':idUnivers', $idUnivers);
    $stmt->execute();
    $ressources = $stmt->fetch(PDO::FETCH_ASSOC);

    $stockMetal = $ressources['stockMetal'];
    $stockEnergie = $ressources['stockEnergie'];
    $stockDeuterium = $ressources['stockDeuterium'];

    // Calculer les ressources restantes
    $Metal = $stockMetal - $coutMetal;
    $Energie = $stockEnergie - $coutEnergie;
    $Deuterium = $stockDeuterium - $coutDeuterium;

    // Mise à jour des ressources du joueur
    $query = "UPDATE ressource SET stockMetal = :restMetal, stockEnergie = :restEnergie, stockDeuterium = :restDeuterium WHERE idJoueur = :idJoueur AND idUnivers = :idUnivers";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':restMetal', $Metal);
    $stmt->bindValue(':restEnergie', $Energie);
    $stmt->bindValue(':restDeuterium', $Deuterium);
    $stmt->bindValue(':idJoueur', $idJoueur);
    $stmt->bindValue(':idUnivers', $idUnivers);
    $stmt->execute();
}



// Verifier si le joueur peut faire la recherche pour l'Energie et effectuer la recherche
// function upgradeEnergie($idJoueur, $idPlanete)
// {
//     $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
//     echo "Debut de la vérification";
//     // Récupérer le niveau actuel du laboratoire et de la technologie énergie pour la planète donnée
//     $query = "SELECT niveauLabo, niveauTechEnergie FROM infrastructure WHERE idPlanete = :idPlanete";
//     $stmt = $pdo->prepare($query);
//     $stmt->bindValue(':idPlanete', $idPlanete);
//     $stmt->execute();
//     $infrastructure = $stmt->fetch(PDO::FETCH_ASSOC);
//     echo "Récupération des niveaux effectuée";
//     // Récupérer les niveaux
//     $niveauLabo = $infrastructure['niveauLabo'];
//     $niveauTechEnergie = $infrastructure['niveauTechEnergie'];

//     // Si le niveau de l'énergie est au maximum on annule
//     if ($niveauTechEnergie >= 10) {
//         $_SESSION['bad_alert'] = "Vous avez atteint le niveau maximum pour la technologie énergie.";
//         header('Location: ./recherche.php');
//         exit();
//     }
//     // Vérifier si le joueur peut faire la recherche pour l'énergie
//     if ($niveauLabo >= 1) {
//         // Vérifier les ressources nécessaires pour l'amélioration de l'énergie
//         $query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'recherche_energie' AND augmentationParNiveau = :niveau";
//         $stmt = $pdo->prepare($query);
//         $stmt->bindValue(':niveau', $niveauTechEnergie + 1);
//         $stmt->execute();
//         $cout = $stmt->fetch(PDO::FETCH_ASSOC);

//         // Récupérer les coûts
//         if (empty($cout['coutMetal'])) {
//             $coutMetal = 0;
//         } else {
//             $coutMetal = $cout['coutMetal'];
//         }
//         if (empty($cout['coutEnergie'])) {
//             $coutEnergie = 0;
//         } else {
//             $coutEnergie = $cout['coutEnergie'];
//         }
//         if (empty($cout['coutDeuterium'])) {
//             $coutDeuterium = 0;
//         } else {
//             $coutDeuterium = $cout['coutDeuterium'];
//         }

//         echo "le labo est bien niveau 1 ou plus";
//         // Vérifier les ressources disponibles
//         if (checkRessources($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium)) {
//             // Si oui on effectue la recherche pour l'énergie
//             echo "le joueur a les ressources";
//             // Mise à jour du niveau de la technologie énergie dans l'infrastructure
//             $query = "UPDATE infrastructure SET niveauTechEnergie = niveauTechEnergie + 1 WHERE idPlanete = :idPlanete";
//             $stmt = $pdo->prepare($query);
//             $stmt->bindValue(':idPlanete', $idPlanete);
//             $stmt->execute();

//             // Mise à jour des ressources du joueur après
//             updateRestante($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium);
//             $_SESSION['good_alert'] = "La recherche a bien été effectuée.";
//             header('Location: ./recherche.php');
//         } else {
//             $_SESSION['bad_alert'] = "Le joueur ne possède pas les ressources nécessaires pour effectuer la recherche.";
//             // renvoyer une erreur
//             header('Location: ./recherche.php');
//         }
//     } else {
//         $_SESSION['bad_alert'] = "Le joueur ne possède pas le laboratoire nécessaire pour effectuer la recherche.";
//         // renvoyer une erreur
//         header('Location: ./recherche.php');
//     }
// }


// // Verifier si le joueur peut faire la recherche pour le Laser et effectuer la recherche
// Verifier si le joueur peut faire la recherche pour le Laser et effectuer la recherche
function upgradeLaser($idJoueur, $idPlanete)
{
    $pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
    echo "Début de la vérification";
    $query = "SELECT niveauLabo, niveauTechLaser FROM infrastructure WHERE idPlanete = :idPlanete";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':idPlanete', $idPlanete);
    $stmt->execute();
    $infrastructure = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer les niveaux
    $niveauLabo = $infrastructure['niveauLabo'];
    $niveauTechLaser = $infrastructure['niveauTechLaser'];

    // Vérifier si le joueur peut faire la recherche pour le laser
    $verif = checkLaserResearch($idPlanete);
    var_dump($verif);
    if ($verif) {
        $query = "SELECT coutMetal, coutCristal, coutDeuterium FROM cout WHERE structureType = 'recherche_laser' AND augmentationParNiveau = :niveau";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':niveau', $niveauTechLaser + 1);
        $stmt->execute();
        $cout = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les coûts
        $coutMetal = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
        $coutEnergie = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
        $coutDeuterium = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

        // Vérifier les ressources disponibles
        if (checkRessources($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium)) {
            // Si oui, on effectue la recherche pour le laser
            // Mise à jour du niveau de la technologie laser dans l'infrastructure
            $query = "UPDATE infrastructure SET niveauTechLaser = niveauTechLaser + 1 WHERE idPlanete = :idPlanete";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':idPlanete', $idPlanete);
            $stmt->execute();

            // Mise à jour des ressources du joueur après
            updateRestante($pdo, $idJoueur, $coutMetal, $coutEnergie, $coutDeuterium);
            $_SESSION['good_alert'] = "La recherche pour le laser a bien été effectuée.";
            header('Location: ./recherche.php');
        } else {
            $_SESSION['bad_alert'] = "Le joueur ne possède pas les ressources nécessaires pour effectuer la recherche.";
            header('Location: ./recherche.php');
        }
    } else {
        $_SESSION['bad_alert'] = "Le joueur ne possède pas toutes les recherches nécessaires pour obtenir le laser.";
        header('Location: ./recherche.php');
    }
}


// // Verifier si le joueur peut faire la recherche pour les Ions et effectuer la recherche
// function upgradeIons() {
    
// }

// // Verifier si le joueur peut faire la recherche pour le Bouclier et effectuer la recherche
// function upgradeBouclier() {

// }

// // Verifier si le joueur peut faire la recherche pour l'armement et effectuer la recherche
// function upgradeArmement() {
    
// }