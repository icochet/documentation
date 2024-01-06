#!/usr/bin/php
<!DOCTYPE html>
    <html lang="fr">
      <head>
        <meta charset="UTF-8" />
        <meta name="Documentation" content="Documentation d\'un code source" />
        <title>Documentation</title>
        <link rel="stylesheet" href="style.css" />
      </head>
      <body>
        <header>
          <h1>Documentation</h1>
  
<?php
$fichiers = ['src1.c', 'src2.c', 'src3.c'];

foreach ($fichiers as $nomFichier){

    $lignesFichier = file($nomFichier);

    $contenuFichier = implode('', $lignesFichier);

    // Recherche de tous les commentaires dans le fichier C
    preg_match_all('/\/\*.*?\*\/|\/\/.*?(?=\n)|#.*?(?=\n|$)/s', $contenuFichier, $correspondances_dep);

    if (!empty($correspondances_dep[0])) { // Vérifie si des commentaires ont été trouvés

        $commentaires = $correspondances_dep[0]; // Stocke tous les commentaires correspondants

        // Enregistre les informations du premier commentaire s'il y en a
        $commentaire = isset($commentaires[0]) ? $commentaires[0] : '';

        // Regex pour extraire les informations de l'en-tête du commentaire
        preg_match_all('/\* \\\\(.*?) (.*?) \*/', $commentaire, $infosEnTete);

        $enteteInfo = array_combine($infosEnTete[1], $infosEnTete[2]);

        // Récupération de l'auteur et de la version
        preg_match_all('/\\\\author\s+(.*)/', $commentaire, $correspondancesAuteur);
        preg_match_all('/\\\\version\s+(.*)/', $commentaire, $correspondancesVersion);

        $auteur = isset($correspondancesAuteur[1][0]) ? $correspondancesAuteur[1][0] : '';
        $version = isset($correspondancesVersion[1][0]) ? $correspondancesVersion[1][0] : '';

        // Récupération de la date complète après \date
        preg_match_all('/\\\\date\s+(.*)/', $commentaire, $correspondancesDate);
        $date = isset($correspondancesDate[1][0]) ? $correspondancesDate[1][0] : '';
    }  
?>
<p>Auteur: <?php echo $auteur ?></p>
<p>Version: <?php echo $version ?></p>
<p>Date: <?php echo $date ?></p>
<br>

<?php
} //Accolade qui ferme la boucle
?>
</header>



