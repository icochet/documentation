<?php
$fileLines = file('src2.c');

$fileContent = implode('', $fileLines);

// Recherche de tous les commentaires dans le fichier C
preg_match_all('/\/\*.*?\*\/|\/\/.*?(?=\n)|#.*?(?=\n|$)/s', $fileContent, $matches);

if (!empty($matches[0])) { // Vérifie si des commentaires ont été trouvés

    $comments = $matches[0]; // Stocke tous les commentaires correspondants

    // Enregistre les informations du premier commentaire s'il y en a
    $comment = isset($comments[0]) ? $comments[0] : '';

    // Regex pour extraire les informations de l'en-tête du commentaire
    preg_match_all('/\* \\\\(.*?) (.*?) \*/', $comment, $infoMatches);

    $headerInfo = array_combine($infoMatches[1], $infoMatches[2]);

    // Récupération de l'auteur et de la version
    preg_match_all('/\\\\author\s+(.*)/', $comment, $authorMatches);
    preg_match_all('/\\\\version\s+(.*)/', $comment, $versionMatches);

    $author = isset($authorMatches[1][0]) ? $authorMatches[1][0] : '';
    $version = isset($versionMatches[1][0]) ? $versionMatches[1][0] : '';

    // Récupération de la date complète après \date
    preg_match_all('/\\\\date\s+(.*)/', $comment, $dateMatches);
    $date = isset($dateMatches[1][0]) ? $dateMatches[1][0] : '';

    // Génération du contenu HTML avec les informations récupérées
    $htmlContent = '<!DOCTYPE html>
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
          <p>Auteur: ' . $author . '</p>
          <p>Version: ' . $version . '</p>
          <p>Date: ' . $date . '</p>
        </header>
      </body>
    </html>';

    // Sauvegarde du contenu HTML dans un fichier
    file_put_contents('documentation.html', $htmlContent);
} else {
    echo "Aucun commentaire trouvé.";
}
?>

