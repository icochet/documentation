/**
 * \file src3.c
 * \brief Programme affichant l'identité d'une personne et sa voiture
 * \author Morgan LOUAISIL
 * \version 1.0
 * \date 08/12/23
*/


#include <stdio.h>
#include <stdlib.h>


/**
 * \def T_NOM
 * \brief constante pour la longueur max du nom de la personne.
*/
#define T_NOM 50


/**
 * \def T_MARQUE
 * \brief constante pour la longueur max du nom de la marque.
*/
#define T_MARQUE 30


/**
 * \def T_MODELE
 * \brief constante pour la longueur max du nom du modèle.
*/
#define T_MODELE 20

/**
 * \typedef Age
 *
 * \brief Type de l'age de la personne
 */
typedef int Age;

/**
 * \typedef Modele
 *
 * \brief Type du modèle du véhicule
 *
 * \brief Le modèle doit être une chaine de caractère de T_MODELE max caractère
 */
typedef char Modele[T_MODELE];

/**
*
* \struct Personne
*
* \brief structure des informations sur une personne
*
*/
struct Personne {
    char nom[T_NOM];
    int age;
};


/**
*
* \struct Voiture
*
* \brief structure des informations sur une voiture
*
*/
struct Voiture {
    char marque[T_MARQUE];
    char modele[T_MODELE];
};


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
*/
void afficherNomAge(char* nom, Age age);


/**
* 
* \fn void afficherVoiture(char* marque, Modele modele)

* \brief Affichage de la marque de la voiture et du modèle de la voiture
*
* \param marque La marque de la voiture
*
* \param modele Le modèle de la voiture
*
*/
void afficherVoiture(char* marque, Modele modele);


/**
* 
* \fn int main()
*
* \brief Le programme principal
*
* \return EXIT_SUCCESS si le programme se termine correctement
* 
* Ce programme permet d'appeler les différentes procédure
* 
*/
int main() {

    struct Personne personne1 = {"Alice", 25};
    struct Voiture voiture1 = {"Toyota", "Camry"};

    afficherNomAge(personne1.nom, personne1.age);
    afficherVoiture(voiture1.marque, voiture1.modele);

    return EXIT_SUCCESS;
}



void afficherNomAge(char* nom, Age age) {
    printf("Personne : %s, %d ans\n", nom, age);
}


void afficherVoiture(char* marque, Modele modele) {
    printf("Voiture : %s %s\n", marque, modele);
} 