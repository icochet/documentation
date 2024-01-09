<?php
$params = array(); // Initialisation du tableau pour stocker les tuples (nom du paramètre, description)

$commentaires_fichier_actuel = "
/**
* 
* \fn void afficherNomAge(char* nom, Age age)
*
* \brief Affichage du nom et de l'âge d'une personne
*
* \param nom Le nom de la personne
*
* \param age Age de la personne
*
*/";

preg_match_all('/\\\\param\s+(\w+)\s+((?:(?!\\\\param).)*)/s', $commentaires_fichier_actuel, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    $paramName = $match[1]; // Nom du paramètre
    $paramDesc = trim(str_replace(array('*', '/'), '', $match[2])); // Description du paramètre (suppression des * et /)
    $params[] = array($paramName, $paramDesc); // Ajout du tuple au tableau
}

// Affichage des tuples (facultatif)
foreach ($params as $param) {
    echo "Paramètre : {$param[0]}, Description : {$param[1]}";
}
?>



