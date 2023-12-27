<?php
    lignes = ouvrir(doc.md); // tableau de n lignes

    pour chaque ligne de lignes:
        ligne = rtrim(ligne); // on supprime le \n de chaque ligne
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <title>Générateur de documentation utilisateur</title>
</head>
<body>
    <?php
        pour chaque ligne de lignes:
            continue; // une variable booleenne qui servira pour les listes et les tableaux
            si la ligne est vide (ou avec juste des espaces) alors:
                continue = faux;
            sinon
                fc = ligne[0]; // je prend le premier caractère de la ligne (fc = first character)
                si c'est un "#" alors:

                sinonsi c'est un "-" alors:

                sinonsi c'est un "|" alors:

                sinonsi c'est un "`" alors:

                sinonsi c'est un "[" alors:

                sinon: // alors c'est un texte normal
    ?>
</body>
</html>