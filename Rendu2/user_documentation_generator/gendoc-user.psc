<?php
    $lignes = ouvrir(doc.md); // tableau de n lignes

    pour chaque $ligne de $lignes:
        $ligne = rtrim(ligne); // on supprime le \n de chaque ligne
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <title>Générateur de documentation utilisateur</title>
</head>
<body>
    <?php
        pour chaque $ligne de $lignes:
            $listStarted; // une variable booleenne qui servira pour les listes
            si la ligne est vide (ou avec juste des espaces) alors:
                $listStarted = faux;
            sinon
                $fc = ligne[0]; // je prend le premier caractère de la ligne (fc = first character)
                si c'est un "#" alors:
                    $hashLen = 1; // nombre de "#"
                    tant que ($ligne[$hashLen] == '#') faire
                        $hashLen++;
                    finfaire
                    si $ligne[$hashLen] == " " alors: // c'est bien un titre
                        switch ($hashLen):
                            si 1: <h1 style="text-align: center;">le contenu</h1>;
                            si 2: <h2>le contenu</h2>;
                            si 3: <h3>le contenu</h3>;
                            si 4: <h4>le contenu</h4>;
                            default: <p>le contenu</p>// alors il y a plus de 4 "#" donc c'est considéré comme du texte classique
                    sinon: // c'est juste un texte qui commence par "#"
                        <p>le contenu</p>

                sinonsi c'est un "-" alors:
                    si $ligne[1] == " " alors: // c'est bien une liste
                        $listStarted = vrai; // tant qu'on ne trouve pas une ligne vide alors la liste continue



                    sinon: // c'est juste un texte qui commence par "-"
                        <p>le contenu</p>

                sinonsi c'est un "|" alors:

                sinonsi c'est un "`" alors:

                sinonsi c'est un "[" alors:

                sinon: // alors c'est un texte normal
    ?>
</body>
</html>