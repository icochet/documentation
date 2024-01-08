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
<main>
      <section>
        <h2>Index des fichiers</h2>
        <h3>Liste des fichiers</h3>
        <p>Liste de tous les fichiers avec une briève description :</p>
        <ul>
<?php
foreach ($fichiers as $nomFichier){

  $lignesFichier = file($nomFichier);
  $contenu = implode('', $lignesFichier);

  // Recherche du premier commentaire dans le fichier C
  preg_match('/\/\*\*(.*?)\*\//s', $contenu, $correspondancesCommentaires);

  $bref = '';
  if (isset($correspondancesCommentaires[1])) {
      preg_match('/\\\\brief (.*?) \*/s', $correspondancesCommentaires[1], $correspondancesBref);
      $bref = isset($correspondancesBref[1]) ? htmlspecialchars($correspondancesBref[1]) : 'Pas de brève description trouvée';
  } else {
      $bref = 'Pas de commentaire trouvé';
  }

?>
<li><strong> <?php echo $nomFichier ?></strong></li>
<p class="tabulation"> <?php echo $bref ?> </p>

<?php
} 
?>
</ul></section>

<h2>Documentation des fichiers</h2>

<?php
foreach ($fichiers as $nomFichier) {

  $lignesFichierSTR = file_get_contents($nomFichier);

  $lignesFichier = file($nomFichier);
  
  $contenuFichierAct = implode('', $lignesFichier);
?>
<section>
        <article>
          <h3>Référence du fichier <?php $nomFichier ?> </h3>

<?php
  // Recherche de tous les commentaires dans le fichier C
  preg_match_all('/#include.*?$/m', $lignesFichierSTR, $contenu_include);
  foreach($contenu_include[0] as $include){
?>

          <p><?php echo htmlentities($include) ?></p>

<?php
  }
?>
        </article>
        <article>
          <h4>Macros</h4>
<?php

// Recherche de tous les commentaires dans le fichier C
  preg_match_all('/\/\*.*?\*\/|\/\/.*?(?=\n)|#.*?(?=\n|$)/s', $contenuFichierAct, $correspondances_fich);

  if (!empty($correspondances_fich[0])) {
    $commentaires_fichier_actuel=$correspondances_fich[0];
    $paragraphesInclusions = '';
    foreach ($lignesFichier as $ligne) {
      if (strpos($ligne, '#define') !== false) {
        // Trouve le nom et la valeur associée au #define
        preg_match('/#define\s+(\S+)\s+(.*)/', $ligne, $correspondances);
        if (isset($correspondances[1]) && isset($correspondances[2])) {
          $nom = $correspondances[1];
          $valeur = $correspondances[2];
        }
        foreach ($commentaires_fichier_actuel as $lig){
          preg_match('/\\\\def\s+(\S+)/', $lig, $def);
          if (!empty($def && $def[1] == $correspondances[1])) {
            preg_match('/\\\\brief\s+(.*?)\s*\*/s', $lig, $brief);
            if (!empty($brief) && isset($brief[1])) {
              $commentaire = $brief[1];
            }
          }
        }
    
?>
          <p>#define <strong><?php echo htmlentities($nom)?></strong></strong> <?php echo htmlentities($valeur)?></p>
          <p class="tabulation"><?php echo htmlentities($commentaire)?></p>

<?php
      }
    }
  }
?>
        </article>
        <article>
        <h4>Structures</h4>
<?php

?>
</section>
<?php
} //Ferme la boucle qui décris tout les fichiers
?>

    </main> 
  </body>
</html>

