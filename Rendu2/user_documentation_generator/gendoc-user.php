#!/usr/bin/php
<?php
    $lines = file("doc.md");

    foreach ($lines as $numLine => $line) {
        $lines[$numLine] = rtrim($line); // supprime le \n de fin de ligne
    }

    // Variables globales

    $listStarted = false;
    $tableStarted = false;
    $preformatStarted = false;
    $paragraphStarted = false;
    $detailLineNb;
    $skip;

    // Fonctions

    function specialFormats($line) {
        $line = preg_replace('/\*\*\*(.*?)\*\*\*/', '<b><i>$1</i></b>', $line); // bold + italic
        $line = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $line); // bold
        $line = preg_replace('/\*(.*?)\*/', '<i>$1</i>', $line); // italic
        $line = preg_replace('/`([^`]+)`/', '<span style="font-family: \'Courier New\', Courier, monospace;">$1</span>', $line); // simple backticks

        return $line;
    }
    function convertSimpleText($line) {
        global $listStarted;
        global $tableStarted;
        global $paragraphStarted;
        global $detailLineNb;
        global $skip;

        $line = specialFormats($line);

        if ($listStarted) {
            echo $line; // sans balises car il vient se mettre à la suite du contenu
        }
        elseif ($tableStarted) {
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
                <td><?= $tableContent[$i] ?></td>
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
        elseif ($paragraphStarted) {
            echo $line; // sans balises car il vient se mettre à la suite du contenu
        }
        else {
?>
            <p><?= $line ?>
<?php
            $paragraphStarted = true;
        }

    }
    function convertTitle($line) {
        $hashLen = 1; // nombre de "#"

        while ($line[$hashLen] == '#') {
            $hashLen++;
        }
        if ($line[$hashLen] == " ") { // c'est bien un titre
            $lineWithoutHash = trim(substr($line, $hashLen)); // supprime les "#" de la ligne et les whitespaces

            switch ($hashLen) {
                case 1:
?>
<h1 style="text-align: center;"><?= $lineWithoutHash ?></h1>
<?php
                    break;
                case 2:
?>
    <h2><?= $lineWithoutHash ?></h2>
<?php
                    break;
                case 3:
?>
    <h3><?= $lineWithoutHash ?></h3>
<?php
                    break;
                case 4:
?>
<h4><?= $lineWithoutHash ?></h4>
<?php
                    break;
                default:
?>
    <p><?= $line ?></p>
<?php
            }
        }
        else { // c'est juste un texte qui commence par "#"
            convertSimpleText($line);
        }
    }
    function convertList($line) {
        global $listStarted;

        $line = specialFormats($line);

        if ($line[1] == " ") { // c'est bien une liste
            $lineWithoutDash = trim(substr($line, 1)); // supprime le "-" de la ligne et les whitespaces

            if (!$listStarted) {
?>
    <ul>
        <li><?= $lineWithoutDash ?>
<?php
                $listStarted = true;
            }
            else {
?>
</li>
        <li><?= $lineWithoutDash ?>
<?php
            }
        }
        else { // c'est juste un texte qui commence par "-"
            convertSimpleText($line);
        }
    }
    function convertTable($lines, $numLine ,$line) {
        global $listStarted;
        global $tableStarted;
        global $detailLineNb;
        global $skip;

        $line = specialFormats($line);

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
                <th><?= $tableTitles[$i] ?></th>
<?php
                }
?>
            </tr>
        </thead>
        <tbody>
<?php
                $tableStarted = true;
                $skip = true;
            }
            else { // c'est juste un texte qui commence par "|"
                convertSimpleText($line);
            }
        }
        else {
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
                <td><?= $tableContent[$i] ?></td>
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
            }
            else {
                $skip = false;
            }
        }
    }
    function convertPreformat($line) {
        global $preformatStarted;

        if ($line[1] == '`' && $line[2] == '`') {
            if (!$preformatStarted) {
?>
<pre style="font-family: 'Courier New', Courier, monospace;">
<?php
                $preformatStarted = true;
            }
            else {
?>
</pre>
<?php
                $preformatStarted = false;
            }
        } else { // c'est juste un texte qui commence par "`"
            convertSimpleText($line);
        }
    }
    function convertLink($line) {
        preg_match('/\[([^\]]+)\]\(([^)]+)\)/', $line, $matches); // regex pour extraire le texte et le lien

        if (count($matches) == 3) { // c'est bien un lien (base + texte + lien = 3)
            $text = $matches[1];
            $link = $matches[2];
?>
            <a href="<?= $link ?>"><?= $text ?></a>
<?php
        } else { // c'est juste un texte qui commence par "["
            convertSimpleText($line);
        }
    }
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <title>Documentation utilisateur</title>
</head>
<body>

    <?php
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
            }
            else {
                !empty($line) ? $fc = $line[0] : $fc = ""; // fc = first character

                if ($preformatStarted && ($fc != '`')) {
                    echo $line . "\n";
                }
                elseif ($fc == '#') { // titres
                    convertTitle($line);
                }
                elseif ($fc == '-') { // listes
                    convertList($line);
                }
                elseif ($fc == '|') { // tableaux
                    convertTable($lines, $numLine ,$line);
                }
                elseif ($fc == '`') { // code
                    convertPreformat($line);
                }
                elseif ($fc == '[') { // URL
                    convertLink($line);
                }
                else { // textes
                    convertSimpleText($line);
                }
            }
        }
    ?>

</body>
</html>