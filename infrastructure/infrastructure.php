<?php
if (!isset($_SESSION)) {
    session_start();
}
include './../univers/alert.php';
$pdo = new PDO('mysql:host=localhost;dbname=galactique2', 'root', '');
$idJoueur = $_SESSION['idJoueur'];
$idUnivers = $_SESSION['idUnivers'];
if (isset($_POST['idPlanete'])) {
    $idPlanete = $_POST['idPlanete'];
    $_SESSION['idPlanete'] = $idPlanete;
} else {
    $idPlanete = $_SESSION['idPlanete'];
}


$query = "SELECT * FROM infrastructure WHERE idPlanete = :idPlanete";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':idPlanete', $idPlanete);
$stmt->execute();
$infrastructure = $stmt->fetch(PDO::FETCH_ASSOC);

// =================== LABORATOIRE DE RECHERCHE ===================
if (isset($_SESSION['laboratoire'])) {
    upgradeLabo($idJoueur, $idPlanete);
}


// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'laboratoire_de_recherche'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalLabo = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieLabo = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumLabo = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== CHANTIER SPATIAL ===================
if (isset($_SESSION['chantier'])) {
    upgradeChantier($idJoueur, $idPlanete);
}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'laboratoire_de_recherche'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalChantier = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieChantier  = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumChantier  = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== USINE DE NANITE ===================
if (isset($_SESSION['nanite'])) {
    upgradeNanite($idJoueur, $idPlanete);
}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'usine_de_nanites'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalNanite = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieNanite  = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumNanite  = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== MINE DE METAL ===================
if (isset($_SESSION['metal'])) {
    upgradeMineMetal($idJoueur, $idPlanete);
}

// Réucpération des coûts
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'mine_de_metal'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cout = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($cout);
// Récupérer les coûts
$coutMetalMineMetal = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieMineMetal  = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumMineMetal  = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== SYNTHETISEUR DE DEUTERIUM ===================
if (isset($_SESSION['deuterium'])) {
    upgradeSyntheDeut($idJoueur, $idPlanete);
}
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'synthetiseur_de_deuterium'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$niveauSyntheDeut = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($niveauSyntheDeut);
$coutMetalSyntheDeut = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieSyntheDeut  = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumSyntheDeut  = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== CENTRALE SOLAIRE ===================
if (isset($_SESSION['solaire'])) {
    upgradeCentraleSolaire($idJoueur, $idPlanete);
}
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'centrale_solaire'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$niveauCentraleSolaire = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($niveauCentraleSolaire);
$coutMetalCentraleSolaire = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieCentraleSolaire  = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumCentraleSolaire  = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;

// =================== CENTRALE FUSION ===================
if (isset($_SESSION['fusion'])) {
    upgradeCentraleFusion($idJoueur, $idPlanete);
}
$query = "SELECT coutMetal, coutEnergie, coutDeuterium FROM cout WHERE structureType = 'centrale_fusion'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$niveauCentraleFusion = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($niveauCentraleFusion);
$coutMetalCentraleFusion = !empty($cout['coutMetal']) ? $cout['coutMetal'] : 0;
$coutEnergieCentraleFusion  = !empty($cout['coutEnergie']) ? $cout['coutEnergie'] : 0;
$coutDeuteriumCentraleFusion  = !empty($cout['coutDeuterium']) ? $cout['coutDeuterium'] : 0;



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>infrastructure</title>
    <link rel="stylesheet" href="../style/css_index.css" />
    <link rel="stylesheet" href="../style/alert.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <form action="./labo.php" method="post">
            <h2>Laboratoire de recherche</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauLabo'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalLabo ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieLabo ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumLabo ?></p>
            <p class="resource">Temps de construction : 50 seconde</p>
            <input type="text" name="laboratoire" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonLaboratoire" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./chantier.php" method="post">
            <h2>Chantier spatial</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauChantierSpatial'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalChantier ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieChantier ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumChantier ?></p>
            <p class="resource">Temps de construction : 50 seconde</p>
            <input type="text" name="chantier" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonchantier" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./nanite.php" method="post">
            <h2>Usine de nanite</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauUsineNanite'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalNanite ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieNanite ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumNanite ?></p>
            <p class="resource">Temps de construction : 10 minute</p>
            <input type="text" name="nanite" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonNanite" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./metal.php" method="post">
            <h2>Mine de Metal</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauUsineMetal'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalMineMetal ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieMineMetal ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumMineMetal ?></p>
            <p class="resource">Temps de construction : 10 seconde</p>
            <input type="text" name="metal" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonMetal" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./deuterium.php" method="post">
            <h2>Synthétiseur de deuterium</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauSynthetiseurDeut'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalSyntheDeut ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieSyntheDeut ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumSyntheDeut ?></p>
            <p class="resource">Temps de construction : 25 seconde</p>
            <input type="text" name="deuterium" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonDeuterium" data-delai="4">Construire</button>
        </form>
    </div>
    <div>
        <form action="./solaire.php" method="post">
            <h2>Centrale solaire</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauCentraleSolaire'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalCentraleSolaire ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieCentraleSolaire ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumCentraleSolaire ?></p>
            <p class="resource">Temps de construction : 10 seconde</p>
            <input type="text" name="solaire" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonSolaire" data-delai="4">Construire</button>
        </form>
    </div>
    <form action="./fusion.php" method="post">
            <h2>Centrale a fusion</h2>
            <h3>Niveau actuel : <?php echo $infrastructure['niveauCentraleFusion'] ?>/10</h3>
            <p class="resource">Métal : <?php echo $coutMetalCentraleFusion ?></p>
            <p class="resource">Energie : <?php echo $coutEnergieCentraleFusion ?></p>
            <p class="resource">Deutérium : <?php echo $coutDeuteriumCentraleFusion ?></p>
            <p class="resource">Temps de construction : 2 minute</p>
            <input type="text" name="fusion" value="true" style="display: none">
            <input type="text" name="idPlanete" value="<?php echo $idPlanete ?>" style="display: none">
            <button type="submit" name="boutonFusion" data-delai="4">Construire</button>
        </form>
    </div>
    <form method="post" action="./../galaxie/manager.php">
        <button type="submit">Retour</button>
    </form>
</body>
</html>
