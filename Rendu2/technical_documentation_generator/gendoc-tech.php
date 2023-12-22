#!/usr/bin/php

<?php

    function read() {
        static $stdin = null;

        if ($stdin === null) {
            $stdin = fopen('php://stdin', 'r');
        }

        return rtrim(fgets($stdin));
    }

    $nomFichier = read();

    $lines = file($nomFichier);
    
    foreach($lines as $line){

        echo $line;

    }

?>








