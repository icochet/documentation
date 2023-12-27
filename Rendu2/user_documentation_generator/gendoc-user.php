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
            $continue; // une variable booleenne qui servira pour les listes et les tableaux

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
                        $splitLine = explode(" ", $line);
                        unset($splitLine[0]); // supprime tous les "#" de la ligne
                        $line = implode(" ", $splitLine); // la ligne mais sans les "#"

                        switch ($hashLen) {
                            case 1:
    ?>
                                <h1 style="text-align: center;"><?php echo $line ?></h1>
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
            }
        }
    ?>
</body>
</html>
<?php
    // $content_md = implode("\n", $lines);

    // file_put_contents("doc-user-1.0.0.html", $content_md);
?>