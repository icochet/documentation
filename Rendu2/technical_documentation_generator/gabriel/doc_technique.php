<?php
$fichiers = ['src1.c', 'src2.c', 'src3.c'];

$lignesFichier = file($fichiers[0]);

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

    // Génération de la liste des fichiers
    $listeFichiers = '<section><h2>Index des fichiers</h2><h3>Liste des fichiers</h3><p>Liste de tous les fichiers avec une briève description :</p><ul>';
    foreach ($fichiers as $nomFichier) {
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

        $listeFichiers .= '<li><strong>' . htmlspecialchars($nomFichier) .'</strong></li>'. '<p class="tabulation">' . $bref . '</p>';
    }
    $listeFichiers .= '</ul></section>';
    
    $contenuFichiers = '';
    foreach ($fichiers as $nomFichier) {
    	$lignesFichier = file($nomFichier);
      
      $contenuFichierAct = implode('', $lignesFichier);

      // Recherche de tous les commentaires dans le fichier C
      preg_match_all('/\/\*.*?\*\/|\/\/.*?(?=\n)|#.*?(?=\n|$)/s', $contenuFichierAct, $correspondances_fich);

      if (!empty($correspondances_fich[0])) {
        $commentaires_fichier_actuel=$correspondances_fich[0];
        // Récupère les lignes contenant #include <...> et les place dans des balises <p>
    	  $paragraphesInclusions = '';
    	  foreach ($lignesFichier as $ligne) {
              if (strpos($ligne, '#include <') !== false) {
              	$paragraphesInclusions .= '<p>' . htmlspecialchars($ligne) . '</p>';
              }
    	  }

    	  // Récupère les lignes contenant #define et leurs descriptions
    	  $contenuDefines = '';
    	  foreach ($lignesFichier as $ligne) {
              if (strpos($ligne, '#define') !== false) {
              	// Trouve le nom et la valeur associée au #define
              	preg_match('/#define\s+(\S+)\s+(.*)/', $ligne, $correspondances);
              	if (isset($correspondances[1]) && isset($correspondances[2])) {
                      $contenuDefines .= '<p>#define <strong> ' . $correspondances[1] . '</strong> ' . $correspondances[2] . '</p>';
              	}
                foreach ($commentaires_fichier_actuel as $lig){
                  preg_match('/\\\\def\s+(\S+)/', $lig, $def);
                  if (!empty($def && $def[1] == $correspondances[1])) {
                    preg_match('/\\\\brief\s+(.*?)\s*\*/s', $lig, $brief);
                    if (!empty($brief) && isset($brief[1])) {
                      $contenuDefines .= '<p class="tabulation">' . htmlspecialchars($brief[1]) . '</p>';
                    }
                  }
                }
              
              }
    	  }

    	  // Construit la structure HTML avec les données extraites
    	  $contenuFichiers .= '<section>';

    	  // Premier article avec les #include
    	  $contenuFichiers .= '<article><h3>Référence du fichier ' . $nomFichier . '</h3>';
    	  $contenuFichiers .= $paragraphesInclusions; // Ajoute les lignes contenant #include <...>
    	  $contenuFichiers .= '</article>';

    	  // Deuxième article avec les #define si des #define sont trouvés
    	  if ($contenuDefines !== '') {
              $contenuFichiers .= '<article><h4>Macros</h4>';
              $contenuFichiers .= $contenuDefines;
              $contenuFichiers .= '</article>';
    	  }

    	  $contenuFichiers .= '</section>';
      }
    }
    // Génération du contenu HTML avec les informations récupérées et la liste des fichiers
    $contenuHTML = '<!DOCTYPE html>
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
          <p>Auteur: ' . $auteur . '</p>
          <p>Version: ' . $version . '</p>
          <p>Date: ' . $date . '</p>
        </header>
        <main>
          ' . $listeFichiers . '
          <h2>Documentation des fichiers</h2>
          ' . $contenuFichiers . '
        </main>
      </body>
    </html>';

    // Sauvegarde du contenu HTML dans un fichier
    file_put_contents('documentation.html', $contenuHTML);
} else {
    echo "Aucun commentaire trouvé.";
}
?>

