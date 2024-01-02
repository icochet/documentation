#!/usr/bin/php

<?php
    $lines = file("doc.md");

    foreach ($lines as $numLine => $line) {
        $lines[$numLine] = trim($line); // supprime le \n de fin de ligne et tous les whitespaces
    }

    // Fonctions

    function specialFormat($line) {
        $line = preg_replace('/\*\*\*(.*?)\*\*\*/', '<b><i>$1</i></b>', $line); // bold + italic
        $line = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $line); // bold
        $line = preg_replace('/\*(.*?)\*/', '<i>$1</i>', $line); // italic
        $line = preg_replace('/`([^`]+)`/', '<span style="font-family: \'Courier New\', Courier, monospace;">$1</span>', $line); // backticks

        return $line;
    }
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
        $paragraphStarted = false;
        foreach ($lines as $numLine => $line) {
            if (empty($line) && !$preformatStarted) {
                if ($listStarted) {
    ?>
                    </li><?php // ferme le dernier élément de la liste ?>
                    </ul><?php // ferme la liste ?>
    <?php
                    $listStarted = false;
                }
                if ($tableStarted) {
    ?>
                    </tbody><?php // ferme le corps de la table ?>
                    </table><?php // ferme la table ?>
    <?php
                    $tableStarted = false;
                }
                if ($paragraphStarted) {
    ?>
                    </p><?php // ferme le paragraphe ?>
    <?php
                    $paragraphStarted = false;
                }
            } else {
                !empty($line) ? $fc = $line[0] : $fc = ""; // fc = first character
                // echo $line;

                if ($preformatStarted && ($fc != '`')) {
                    echo $line . "\n";
                }
                elseif ($fc == '#') {
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
                        $line = specialFormat($line);

                        if ($listStarted) { // si une liste a démarrée, alors ne pas mettre de balises
                            echo $line;
                        } elseif ($paragraphStarted) {
                            echo $line;
                        } else {
        ?>
                            <p><?php echo $line ?>
        <?php
                            $paragraphStarted = true;
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
                        $line = specialFormat($line);

                        if ($listStarted) { // si une liste a démarrée, alors ne pas mettre de balises
                            echo $line;
                        } elseif ($paragraphStarted) {
                            echo $line;
                        } else {
        ?>
                            <p><?php echo $line ?>
        <?php
                            $paragraphStarted = true;
                        }
                    }
                }
                elseif ($fc == '|') {
                    $line = specialFormat($line);
                    
                    if (!$tableStarted) {
                        $detailLine = trim($lines[$numLine + 1], '|'); // récupère la ligne d'après sans whitespaces et sans le pipe avant et après la ligne
                        if (empty($detailLine)) $detailLine = 0; // pour éviter une erreur sur la condition ($detailLineSplit[0][0] == '-'), si c'est vide on set à 0
                        $detailLineSplit = explode('|', $detailLine); // récupère tous les "-" du tableau
                        foreach ($detailLineSplit as $detailKey => $detailValue) {
                            $detailLineSplit[$detailKey] = trim($detailValue);
                        }
                        
                        if (($detailLineSplit[0][0] == '-') && !$listStarted) { // c'est bien un tableau
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
                            $tableStarted = true;
                            $skip = true;
                        } else { // c'est juste un texte qui commence par "|"
                            $line = specialFormat($line);

                            if ($listStarted) { // si une liste a démarrée, alors ne pas mettre de balises
                                echo $line;
                            } elseif ($paragraphStarted) {
                                echo $line;
                            } else {
            ?>
                                <p><?php echo $line ?>
            <?php
                                $paragraphStarted = true;
                            }
                        }
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
                            <pre style="font-family: 'Courier New', Courier, monospace;">
    <?php
                            $preformatStarted = true;
                        } else {
    ?>
                            </pre>
    <?php
                            $preformatStarted = false;
                        }
                    } else { // alors c'est juste un texte qui commence par "`"
                        $line = specialFormat($line);

                        if ($listStarted) {
                            echo $line; // sans balises car il vient se mettre à la suite des autres <li>
                        } elseif ($paragraphStarted) {
                            echo $line;
                        } else {
        ?>
                            <p><?php echo $line ?>
        <?php
                            $paragraphStarted = true;
                        }
                    }
                }
                elseif ($fc == '[') {
                    $linkPattern = '/\[([^\]]+)\]\(([^)]+)\)/'; // regex pour extraire le texte et le lien
                    preg_match($linkPattern, $line, $linkMatches);

                    if (count($linkMatches) == 3) { // alors c'est bien un lien
                        $linkText = $linkMatches[1];
                        $link = $linkMatches[2];
    ?>
                        <a href="<?php echo $link ?>"><?php echo $linkText ?></a>
    <?php
                    } else { // alors c'est juste un texte qui commence par "["
                        $line = specialFormat($line);
                        
                        if ($listStarted) {
                            echo $line; // sans balises car il vient se mettre à la suite des autres <li>
                        } elseif ($paragraphStarted) {
                            echo $line;
                        } else {
    ?>
                            <p><?php echo $line ?>
    <?php
                            $paragraphStarted = true;
                        }
                    }
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
                    } elseif ($paragraphStarted) {
                        $line = specialFormat($line);
                        echo $line;
                    } else {
                        $line = specialFormat($line);
    ?>
                        <p><?php echo $line ?>
    <?php
                        $paragraphStarted = true;
                    }
                }
            }
        }
    ?>
</body>
</html>