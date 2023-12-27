#!/usr/bin/php

<?php
    $lines = file("doc.md");

    foreach ($lines as $numLine => $line) {
        // $line = rtrim($line); // supprime le \n de fin de ligne
        $lines[$numLine] = trim($line); // supprime le \n de fin de ligne et tous les whitespaces

        // if ($line === "") {
        //     unset($lines[$numLine]); // si la ligne est vide alors elle est supprimée du tableau
        // } else {
        //     $lines[$numLine] = $line; // sinon le tableau est mis à jour
        // }
    }
    // $lines = array_values($lines); // réindex le tableau (pour que les indices se suivent)
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <title>Générateur de documentation utilisateur</title>
</head>
<body>
    <?php
        foreach ($lines as $line) {
            $continue; // une variable booleenne qui servira pour les listes

            if (empty($line)) {
                $continue = false;
            } else {
                $fc = $line[0]; // fc = first character

                if ($fc == '#') {
                    $hashLen = 1; // nombre de "#"

                    while ($line[$hashLen] == '#') {
                        $hashLen++;
                    }
                    if ($line[$hashLen] == " ") { // c'est bien un titre
                        $lineWtHash = trim(substr($line, $hashLen)); // supprime les "#" de la ligne et les whitespaces

                        switch ($hashLen) {
                            case 1:
    ?>
                                <h1 style="text-align: center;"><?php echo $lineWtHash ?></h1>
    <?php
                                break;
                            case 2:
    ?>
                                <h2><?php echo $lineWtHash ?></h2>
    <?php
                                break;
                            case 3:
    ?>
                                <h3><?php echo $lineWtHash ?></h3>
    <?php
                                break;
                            case 4:
    ?>
                                <h4><?php echo $lineWtHash ?></h4>
    <?php
                                break;
                            default:
    ?>
                                <p><?php echo $line ?></p>
    <?php
                        }
                    } else { // c'est juste un texte qui commence par "#"
    ?>
                        <p><?php echo $line ?></p>
    <?php
                    }
                }
                elseif ($fc == '-') {

                }

            }
        }
    ?>
</body>
</html>
<?php
    // $content_md = implode("\n", $lines);

    // file_put_contents("doc-user-1.0.0.html", $content_md);
?>