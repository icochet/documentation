#!/usr/bin/php

<?php
    $content = "Ceci est un test d'écriture dans un fichier html";

    file_put_contents("doc-user-1.0.0.html", &content);
?>