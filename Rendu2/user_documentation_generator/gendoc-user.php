#!/usr/bin/php

<?php
    $content = "Ceci est un test:d'écriture dans un fichier html.";
    $content_tab = explode(":", $content);
    $content_reformer = implode("\n", $content_tab);

    file_put_contents("doc-user-1.0.0.html", $content_reformer);
?>