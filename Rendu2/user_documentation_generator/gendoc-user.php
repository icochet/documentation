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
        $listStarted = false;
        foreach ($lines as $line) {
            if (empty($line)) {
                if ($listStarted) {
    ?>
                    </li><?php // je ferme le dernier élément de la liste ?>
                    </ul><?php // je ferme la liste ?>
    <?php
                    $listStarted = false;
                }
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
                    if ($line[1] == " ") { // c'est bien une liste
                        $lineWtDash = trim(substr($line, 1)); // supprime le "-" de la ligne et les whitespaces

                        if (!$listStarted) {
    ?>
                            <ul>
                            <li><?php echo $lineWtDash ?>
    <?php
                            $listStarted = true;
                        } else {
    ?>
                            </li>
                            <li><?php echo $lineWtDash ?>
    <?php
                        }
                    } else { // c'est juste un texte qui commence par "-"
    ?>
                        <p><?php echo $line ?></p>
    <?php
                    }
                }
                else { // alors c'est un texte normal
                    if ($listStarted) {
                        echo $line; // sans balises car il vient se mettre à la suite des autres <li>
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