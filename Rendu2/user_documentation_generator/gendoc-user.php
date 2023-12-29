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
    <title>Documentation utilisateur</title>
</head>
<body>
    <?php
        $listStarted = false;
        $tableStarted = false;
        $preformatStarted = false;
        foreach ($lines as $numLine => $line) {
            if (empty($line) && !$preformatStarted) {
                if ($listStarted) {
    ?>
                    </li><?php // je ferme le dernier élément de la liste ?>
                    </ul><?php // je ferme la liste ?>
    <?php
                    $listStarted = false;
                }
                if ($tableStarted) {
    ?>
                    </tbody><?php // je ferme le corps de la table ?>
                    </table><?php // je ferme la table ?>
    <?php
                    $tableStarted = false;
                }
            } else {
                $fc = $line[0]; // fc = first character

                if ($preformatStarted && ($fc != '`')) {
                    echo $line;
                } elseif ($fc == '#') {
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
                        if ($listStarted) { // si une liste a démarrée, alors ne pas mettre de balises
                            echo $line;
                        } else {
    ?>
                            <p><?php echo $line ?></p>
    <?php
                        }
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
                        if ($listStarted) { // si une liste a démarrée, alors ne pas mettre de balises
                            echo $line;
                        } else {
    ?>
                            <p><?php echo $line ?></p>
    <?php
                        }
                    }
                }
                elseif ($fc == '|') {
                    if (!$tableStarted) {
                        $detailLine = trim($lines[$numLine + 1], '|'); // récupère la ligne d'après sans whitespaces et sans le pipe avant et après la ligne
                        if (empty($detailLine)) $detailLine = 0; // pour éviter une erreur sur la condition ($detailLineSplit[0][0] == '-'), si c'est vide on set à 0
                        $detailLineSplit = explode('|', $detailLine); // récupère tous les "-" du tableau
                        foreach ($detailLineSplit as $detailKey => $detailValue) {
                            $detailLineSplit[$detailKey] = trim($detailValue);
                        }
                        
                        if ($detailLineSplit[0][0] == '-') { // c'est bien un tableau
                            $detailLineNb = count($detailLineSplit); // nb de colonnes de détail
                            $tableTitles = explode('|', trim($line, '|'));
                            foreach ($tableTitles as $numTitle => $title) {
                                $tableTitles[$numTitle] = trim($title);
                            }
    ?>
                            <table>
                            <thead>
                            <tr>
    <?php
                            for ($i = 0; $i < $detailLineNb; $i++) {
    ?>
                                <th><?php echo $tableTitles[$i] ?></th>
    <?php
                            }
    ?>
                            </tr>
                            </thead>
                            <tbody>
    <?php
                        } else { // c'est juste un texte qui commence par "|"
                            if ($listStarted) { // si une liste a démarrée, alors ne pas mettre de balises
                                echo $line;
                            } else {
    ?>
                                <p><?php echo $line ?></p>
    <?php
                            }
                        }

                        $tableStarted = true;
                        $skip = true;
                    } else {
                        if (!$skip) {
                            $tableContent = explode('|', trim($line, '|'));
                            foreach ($tableContent as $numTitle => $title) {
                                $tableContent[$numTitle] = trim($title);
                            }
                            $tableContentLen = count($tableContent);
                            if ($tableContentLen > $detailLineNb) {
                                for ($h = $tableContentLen - 1; $h >= $tableContentLen - $detailLineNb; $h--) {
                                    unset($tableContent[$h]);
                                }
                            }
    ?>
                            <tr>
    <?php
                                for ($i = 0; $i < count($tableContent); $i++) {
    ?>
                                    <td><?php echo $tableContent[$i] ?></td>
    <?php
                                }
                                for ($j = 0; $j < ($detailLineNb - count($tableContent)); $j++) {
    ?>
                                    <td></td>
    <?php
                                }
    ?>
                            </tr>
    <?php
                        } else {
                            $skip = false;
                        }
                    }

                }
                elseif ($fc == '`') {
                    if ($line[1] == '`' && $line[2] == '`') {
                        if (!$preformatStarted) {
    ?>
                            <pre>
    <?php
                            $preformatStarted = true;
                        } else {
    ?>
                            </pre>
    <?php
                            $preformatStarted = false;
                        }
                    }
                }
                elseif ($fc == '[') {
                    
                }
                else { // alors c'est un texte normal
                    if ($listStarted) {
                        echo $line; // sans balises car il vient se mettre à la suite des autres <li>
                    } elseif ($tableStarted) {
                        if (!$skip) {
                            $tableContent = explode('|', trim($line, '|'));
                            foreach ($tableContent as $numTitle => $title) {
                                $tableContent[$numTitle] = trim($title);
                            }
    ?>
                            <tr>
    <?php
                                for ($i = 0; $i < count($tableContent); $i++) {
    ?>
                                    <td><?php echo $tableContent[$i] ?></td>
    <?php
                                }
                                for ($j = 0; $j < ($detailLineNb - count($tableContent)); $j++) {
    ?>
                                    <td></td>
    <?php
                                }
    ?>
                            </tr>
    <?php
                        } else {
                            $skip = false;
                        }
                    } else {
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