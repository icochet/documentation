<?php       
            $ressource = fopen('ELIMINATION_Version1.c', 'rb');
            echo fread($ressource, filesize('ELIMINATION_Version1.c'));
        ?>