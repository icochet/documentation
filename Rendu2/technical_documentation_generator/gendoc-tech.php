#!/usr/bin/php
<!DOCTYPE html>
    <html lang="fr">
      <head>
        <meta charset="UTF-8" />
        <meta name="Documentation" content="Documentation d\'un code source" />
        <title>Documentation technique : <?php echo $argv[1]?></title>
        <link rel="stylesheet" href="style.css" />
      </head>
      <body>
        <header>
          <h1>Documentation technique : <?php echo $argv[1]?></h1>
          <h2>...<?php echo $argv[2]?></h2>
          <h2>Version : <?php echo $argv[3]?></h2>
  
<?php
$fichiers = glob("*.c");  //['src1.c', 'src2.c', 'src3.c'];

foreach ($fichiers as $nomFichier){

    $lignesFichier = file($nomFichier);

    $contenuFichier = implode('', $lignesFichier);

    // Recherche de tous les commentaires dans le fichier C
    preg_match_all('/\/\*.*?\*\/|\/\/.*?(?=\n)|#.*?(?=\n|$)/s', $contenuFichier, $correspondances_dep);

    $auteur = "Aucun trouvé";
    $version = "Aucune trouvé";
    $date = "Aucune trouvé";

    if (!empty($correspondances_dep[0])) { // Vérifie si des commentaires ont été trouvés

        $commentaires = $correspondances_dep[0]; // Stocke tous les commentaires correspondants

        // Enregistre les informations du premier commentaire s'il y en a
        $commentaire = isset($commentaires[0]) ? $commentaires[0] : '';

        // Regex pour extraire les informations de l'en-tête du commentaire
        preg_match_all('/\* \\\\(.*?) (.*?) \*/', $commentaire, $infosEnTete);

        $enteteInfo = array_combine($infosEnTete[1], $infosEnTete[2]);

        // Récupération de l'auteur et de la version
        preg_match_all('/\\\\author\s+(.*)/', $commentaire, $correspondancesAuteur);
        preg_match_all('/\\\\version\s+(.*)/', $commentaire, $correspondancesVersion);

        $auteur = isset($correspondancesAuteur[1][0]) ? $correspondancesAuteur[1][0] : '';
        $version = isset($correspondancesVersion[1][0]) ? $correspondancesVersion[1][0] : '';

        // Récupération de la date complète après \date
        preg_match_all('/\\\\date\s+(.*)/', $commentaire, $correspondancesDate);
        $date = isset($correspondancesDate[1][0]) ? $correspondancesDate[1][0] : '';
    } 
?>
<p>Fichier: <?php echo $nomFichier ?></p>
<p>Auteur: <?php echo $auteur ?></p>
<p>Version: <?php echo $version ?></p>
<p>Date: <?php echo $date ?></p>
<br>

<?php
} //Accolade qui ferme la boucle
?>
</header>
<main>
      <section>
        <h2>Index des fichiers</h2>
        <h3>Liste des fichiers</h3>
        <p>Liste de tous les fichiers avec une briève description :</p>
        <ul>
<?php
foreach ($fichiers as $nomFichier){

  $lignesFichier = file($nomFichier);
  $contenu = implode('', $lignesFichier);

  // Recherche du premier commentaire dans le fichier C
  preg_match('/\/\*\*(.*?)\*\//s', $contenu, $correspondancesCommentaires);

  $bref = '';
  if (isset($correspondancesCommentaires[1])) {
      preg_match('/\\\\brief (.*?) \*/s', $correspondancesCommentaires[1], $correspondancesBref);
      $bref = isset($correspondancesBref[1]) ? htmlspecialchars($correspondancesBref[1]) : 'Pas de brève description trouvée';
  } else {
      $bref = 'Pas de commentaire trouvé';
  }

?>
<li><strong> <?php echo $nomFichier ?></strong></li>
<p class="tabulation"> <?php echo $bref ?> </p>

<?php
} 
?>
</ul></section>

<h2>Documentation des fichiers</h2>

<?php
foreach ($fichiers as $nomFichier) {

  $lignesFichierSTR = file_get_contents($nomFichier);

  $lignesFichier = file($nomFichier);
  
  $contenuFichierAct = implode('', $lignesFichier);
?>
<section>
        <article>
          <h3>Référence du fichier <?php echo $nomFichier ?> </h3>

<?php
  // Recherche de tous les commentaires dans le fichier C
  preg_match_all('/#include.*?$/m', $lignesFichierSTR, $contenu_include);
  foreach($contenu_include[0] as $include){
?>

          <p><?php echo htmlentities($include) ?></p>

<?php
  }
?>
        </article>
        <article>
          
<?php

// Recherche de tous les commentaires dans le fichier C
  preg_match_all('/\/\*.*?\*\/|\/\/.*?(?=\n)|#.*?(?=\n|$)/s', $contenuFichierAct, $correspondances_fich);

  if (!empty($correspondances_fich[0])) {
    $commentaires_fichier_actuel=$correspondances_fich[0];
    $macro = false;
    foreach ($lignesFichier as $ligne) {
      if (strpos($ligne, '#define') !== false) {
        if ($macro == false) {
?>    
        <h4>Macros</h4>
<?php
        $macro=true;
        }
        // Trouve le nom et la valeur associée au #define
        preg_match('/#define\s+(\S+)\s+(.*)/', $ligne, $correspondances);
        if (isset($correspondances[1]) && isset($correspondances[2])) {
          $nom = $correspondances[1];
          $valeur = $correspondances[2];
        }
        foreach ($commentaires_fichier_actuel as $lig){
          preg_match('/\\\\def\s+(\S+)/', $lig, $def);
          if (!empty($def && $def[1] == $correspondances[1])) {
            preg_match('/\\\\brief\s+(.*?)\s*\*/s', $lig, $brief);
            if (!empty($brief) && isset($brief[1])) {
              $commentaire = $brief[1];
            }
          }
        }
    
?>
          <p>#define <strong><?php echo htmlentities($nom)?></strong></strong> <?php echo htmlentities($valeur)?></p>
          <p class="tabulation"><?php echo htmlentities($commentaire)?></p>

<?php
      }
    }
?>
        </article>
        <article>
<?php
      $type=false;
      foreach ($commentaires_fichier_actuel as $ligne) {
      if (preg_match('/\\\\typedef\s+(\w+)/', $ligne, $correspondances)) {
        if ($type==false) {
?>
        <h4>Définition de type</h4>
<?php
        $type=true;
        }
        preg_match('/\\\\brief\s+(.*?)\s*\*/s', $ligne, $brief);
        if (isset($brief[1])) {
          $description = $brief[1];
        } else {
          $description = "pas de brève description";
        }
?>
        <p>typedef int <strong><?php echo $correspondances[1] ?></strong></p>
        <p class="tabulation"><?php echo $description ?></p>
<?php
      }
    }
?> 
        </article>
        <article>
<?php
      $tableau_commentaires = [];

      foreach ($lignesFichier as $numero_ligne => $ligne) {
          // Recherche des commentaires de type // dans la ligne
          if (preg_match('/\/\/(.*)/', $ligne, $commentaire)) {
              $commentaire_propre = trim($commentaire[0]);
              $tuple = [$commentaire_propre, $numero_ligne]; // Ajouter 1 à l'indice pour correspondre aux numéros de ligne conventionnels
              array_push($tableau_commentaires, $tuple);
          }
      }
      // Affichage du tableau de tuples (à titre de démonstration)

      for ($j=0;$j<count($tableau_commentaires);$j++) {
          for ($i=$tableau_commentaires[$j][1]-1; $i > 0; $i--) { 
              if (strpos($lignesFichier[$i],'**')or strpos($lignesFichier[$i],'\struct')) {
                  if (strpos($lignesFichier[$i],'\struct')) {
                      preg_match('/\\\\struct\s+(\w+)/', $lignesFichier[$i], $correspondances);
                      $tableau_commentaires[$j][1]=$correspondances[1];
                  }
                  break;
              }
          }
      }
      $structure=false;
      foreach ($commentaires_fichier_actuel as $ligne) {
        if (preg_match('/\\\\struct\s+(\w+)/', $ligne, $correspondances)) {
          if ($structure==false) {
?>  
          <h4>Structures</h4>
<?php 
          $structure=true;
          }
          preg_match('/\\\\brief\s+(.*?)\s*\*/s', $ligne, $brief);
          if (isset($brief[1])) {
            $description = $brief[1];
          } else {
            $description = "pas de brève description";
          }
?>  
          <p>struct <strong><?php echo $correspondances[1] ?></strong></p>
          <p class="tabulation"><?php echo $description ?></p>
<?php 
            
            foreach ($tableau_commentaires as $valeur) {
              if ($valeur[1]==$correspondances[1]) {
                $valeur[0] = preg_replace('/\/\/\s*/', '', $valeur[0]);
?>
              <p class="tabulationplus"><?php echo $valeur[0] ?></p>
<?php

              }
            }
          }
        }

?> 
        </article>
        <article>
        <h4>Fonctions</h4>
<?php
        foreach($commentaires_fichier_actuel as $commentaire){
          preg_match('/\\\\fn\s+(\w+\s+\w+)\s*\(/', $commentaire, $fonctions);
          if (!empty($fonctions)) {
            preg_match('/\\\\brief\s+(.*?)\s*\*/s', $commentaire, $brief);
            if (!empty($brief)) {
?>
              <p><strong><?php echo htmlspecialchars($fonctions[1]) ?></strong></p>
              <p class="tabulation"><?php echo $brief[1] ?></p>
<?php
            }
          }
        }
?> 
        </article>
        <hr>
        <article>
<?php
$macro = false;
foreach ($lignesFichier as $ligne) {
  if (strpos($ligne, '#define') !== false) {
    if ($macro == false) {
?>    
    <h4>Documentation des macros</h4>
<?php
    $macro=true;
    }
    // Trouve le nom et la valeur associée au #define
    preg_match('/#define\s+(\S+)\s+(.*)/', $ligne, $correspondances);
    if (isset($correspondances[1]) && isset($correspondances[2])) {
      $nom = $correspondances[1];
      $valeur = $correspondances[2];
    }
    foreach ($commentaires_fichier_actuel as $lig){
      preg_match('/\\\\def\s+(\S+)/', $lig, $def);
      if (!empty($def && $def[1] == $correspondances[1])) {
        preg_match('/\\\\brief\s+(.*?)\s*\*/s', $lig, $brief);
        if (!empty($brief) && isset($brief[1])) {
          $commentaire = $brief[1];
        }
      }
    }

?>
      <p><strong>#define <?php echo htmlentities($nom)?> <?php echo htmlentities($valeur)?></strong></p>
      <p class="tabulation"><?php echo htmlentities($commentaire)?></p>

<?php
  }
}
?> 
        </article>
        <hr>
        <article>
<?php
            $type=false;
            foreach ($commentaires_fichier_actuel as $commentaire) {
                preg_match('/\\\\typedef\s+(\w+)/', $commentaire, $typedef);
                if (!empty($typedef)) {
                  if ($type == false) {
                    ?>    
                        <h4>Documentation des définitions de type</h4>
                    <?php
                        $type=true;
                        }
                    preg_match('/\\\\brief\s+(.*?)\s*(?:\\\\brief\s+(.*?))?\s*\*\//s', $commentaire, $brief);
                    if (!empty($brief)) {
                        $briefText = preg_replace('/^\s*\*+\s*|\s*\*+\s*$/', '', $brief[1]);
                        $briefText = preg_replace('/^\s*\*+\s*|\s*\*+\s*$/', '', $briefText);
?>
            <p><strong><?php echo htmlspecialchars($typedef[1]) ?></strong></p>
            <p class="tabulation"><?php echo htmlspecialchars($briefText) ?></p>
<?php
                    if (isset($brief[2])) {
                      $briefText2 = preg_replace('/^\s*\*+\s*|\s*\*+\s*$/', '', $brief[2]);
?>
                      <p class="tabulation"><?php echo htmlspecialchars($briefText2) ?></p>
<?php
            }
        }
    }
}

?> 
        </article>
        <hr>
        <article>
          <h4>Documentation des fonctions</h4>
<?php
        foreach($commentaires_fichier_actuel as $commentaire){
          preg_match('/\\\\fn\s+(\w+\s+\w+)\s*\(/', $commentaire, $fonctions);
          if (!empty($fonctions)) {
            preg_match('/\\\\brief\s+(.*?)\s*\*/s', $commentaire, $brief);
            if (!empty($brief)) {
              
?>
                <p><strong><?php echo htmlspecialchars($fonctions[1]) ?></strong></p>
                <p class="tabulation"><?php echo $brief[1] ?></p>
<?php
              preg_match_all('/\\\\param\s+(\w+)\s+((?:(?!\\\\param).)*)/s', $commentaire, $matches, PREG_SET_ORDER);
              foreach ($matches as $match) {
                  $paramName = $match[1]; // Nom du paramètre
                  $paramDesc = trim(str_replace(array('*', '/'), '', $match[2])); // Description du paramètre (suppression des * et /)
                  $params[] = array($paramName, $paramDesc); // Ajout du tuple au tableau
              }
              if (!empty($params)) {
                ?>
                <table>
                    <caption>
                        Paramètre(s)
                    </caption>
                    <?php
                    foreach ($params as $param) {
                        ?>
                        <tr>
                            <td><?php echo $param[0] ?> </td>
                            <td><?php echo $param[1] ?> </td>
                        </tr>
                        <?php
                    }
                    $params = array();
                    ?>
                </table>
                <?php
              }
            }
          }
          preg_match('/\\\\return\s+(.*)/', $commentaire, $return);
            if (!empty($return)) {

?>
              <p class="tabulation"><strong>Renvoie</strong></p>
              <p class="tabulationplus"><?php echo $return[1] ?></p>
<?php

            }
        }


?>


</section>
<?php
    
  }
  else{
    ?>
    <p>Pas de commentaire trouvé</p>

<?php
  }
} //Ferme la boucle qui décris tout les fichiers
?>

    </main> 
  </body>
</html>

