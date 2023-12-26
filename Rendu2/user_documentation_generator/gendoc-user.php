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
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <title>Générateur de documentation utilisateur</title>
</head>
<body>
    <?php
		foreach ($lines as $line) {
            $fc = $line[0]; // fc = first character

            if ($fc == '#') {
                $splitLine = explode(" ", $line);
                $hashLen = strlen($splitLine[0]); // récupère le nombre de "#"
                unset($splitLine[0]); // enlève tous les "#" de la string
                $line = implode(" ", $splitLine); // la ligne mais sans les "#"

                switch ($hashLen) {
                    case 1:
    ?>
                        <h1><?php echo $line ?></h1>
    <?php
                        break;
                    case 2:
    ?>
                        <h2><?php echo $line ?></h2>
    <?php
                        break;
                    case 3:
    ?>
                        <h3><?php echo $line ?></h3>
    <?php
                        break;
                    case 4:
    ?>
                        <h4><?php echo $line ?></h4>
    <?php
                        break;
                    case 5:
    ?>
                        <h5><?php echo $line ?></h5>
    <?php
                        break;
                    case 6:
    ?>
                        <h6><?php echo $line ?></h6>
    <?php
                        break;
                }
            }
    ?>
    <?php
        }
    ?>
</body>
</html>
<?php
    // $content_md = implode("\n", $lines);

    // file_put_contents("doc-user-1.0.0.html", $content_md);
?>