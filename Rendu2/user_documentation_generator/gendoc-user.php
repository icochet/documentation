#!/usr/bin/php

<?php
    $lines = file("doc.md");

    foreach ($lines as $numLine => $line) {
        $line = rtrim($line); // supprime le \n de fin de ligne

        if ($line === "") {
            unset($lines[$numLine]); // si la ligne est vide alors elle est supprimée du tableau
        } else {
            $lines[$numLine] = $line; // sinon le tableau est mis à jour
        }
    }
    $lines = array_values($lines); // réindex le tableau (pour que les indices se suivent)

    print_r($lines);
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <title>Générateur de documentation utilisateur</title>
</head>
<body>
    <ul>
        <?php
            foreach ($lines as $numLine => $line) {
        ?>
        <li><?php echo "$numLine : $line"?></li>
        <?php
            }
        ?>
    </ul>
</body>
</html>
<?php
    $content_md = implode("\n", $lines);

    file_put_contents("doc-user-1.0.0.html", $content_md);
?>