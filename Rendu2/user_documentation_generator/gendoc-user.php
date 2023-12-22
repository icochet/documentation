#!/usr/bin/php

<?php
    $lines = file("doc.md");

    foreach ($lines as $numLine => $line) {
        $lines[$numLine] = rtrim($line);
    }
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <title>Générateur de documentation utilisateur</title>
</head>
<body>
    <?php
        foreach ($lines as $numLine => $line) {
    ?>

    <?php
        }
    ?>
</body>
</html>
<?php
    $content_md = implode("\n", $lines);

    file_put_contents("doc-user-1.0.0.html", $content_md);
?>