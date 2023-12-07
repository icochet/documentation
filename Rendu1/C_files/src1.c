/**
 *
 * \file src1.c
 * 
 * \brief Programme src1
 * 
 * \author Antoine Guillerm
 * 
 * \version 3.0 
 *
 * \date 5 décembre 2023 
 *
 * Ce programme propose différentes procédures et fonctions pour afficher les détails
 * d'un étudiant et d'un cours, calculer une moyenne et afficher un message de félicitation
 * 
 */

#include <stdio.h>
#include <stdlib.h>
#include <stdbool.h>


/** 
 * 
 * \def TAILLE_MAX
 * 
 * \brief constante pour la taille maximum des tableaux
 * 
 */
#define TAILLE_MAX 5

/** 
 * 
 * \def MAX_ETUDIANTS
 * 
 * \brief constante pour le maximum d'étudiants
 * 
 */
#define MAX_ETUDIANTS 3


/**
* 
* \typedef TableauPreference
*
* \brief type tableau de taille TAILLE_MAX
*
* Le type TableauPreference sert de tableau pour stocker les préférences
* 
*/
typedef int TableauPreference[TAILLE_MAX];

/**
* 
* \typedef TableauNotes
*
* \brief type tableau de taille TAILLE_MAX
*
* Le type TableauNotes sert de tableau pour stocker les notes
* 
*/
typedef float TableauNotes[TAILLE_MAX];


/**
*
* \struct Etudiant
*
* \brief structure des informations sur un étudiant
*
*/
typedef struct {
    char nom[20];       // Nom de l'étudiant.e
    int age;        // L'âge de l'étudiant.e
    TableauPreference preferences;      // Utilisation du premier type
} Etudiant;     // Structure d'un étudiant

/**
*
* \struct Cours
*
* \brief structure des informations sur un cours
*
*/
typedef struct {
    char nom_cours[30];     // Nom du cours
    TableauNotes notes;     // Utilisation du deuxième type
} Cours;        // Structure d'un cours


// Prototypes des fonctions et des procédures //
float calculerMoyenne(struct Cours cours);
void afficherDetailsEtudiant(struct Etudiant etudiant);
void afficherDetailsCours(struct Cours cours)
bool afficherMessageFelicitation(float moyenne, float seuil)

/******************************************************
 *                PROGRAMME PRINCIPAL                 *
 ******************************************************/

/**
* 
* \fn int main()
*
* \brief Le programme principal
*
* \return EXIT_SUCCESS si le programme se termine correctement
*
* Ce programme permet d'appeler les différentes fonctions et procédure 
* pour afficher les détails d'un étudiant et d'un cours, calculer 
* la moyenne et afficher un message de félicitation
*
*/
int main() {
    // Déclaration de variables
    struct Etudiant etudiants[MAX_ETUDIANTS] = {
        {"Alice", 21, {4, 2, 5, 1, 3}},
        {"Bob", 22, {1, 3, 2, 5, 4}},
        {"Charlie", 20, {5, 4, 3, 2, 1}},
    };

    struct Cours coursA = {"Mathématiques", {15.5, 12.0, 18.5, 14.0, 16.5}};

    // Appel de la procédure pour afficher les détails de l'étudiant
    for (int i = 0; i < MAX_ETUDIANTS; i++) {
        afficherDetailsEtudiant(etudiants[i]);
    }

    // Appel de la procédure pour afficher les détails du cours
    afficherDetailsCours(coursA);

    // Appel de la fonction pour calculer la moyenne des notes
    float moyenneCoursA = calculerMoyenne(coursA);
    printf("Moyenne du cours: %.2f\n", moyenneCoursA);

    // Appel de la fonction pour afficher un message de félicitations
    bool felicitation = afficherMessageFelicitation(moyenneCoursA, 15.0);

    // Utilisation de la valeur retournée
    if (felicitation) {
        printf("Bien fait !\n");
    } else {
        printf("Continuez à travailler dur.\n");
    }

    return EXIT_SUCCESS;
}


/******************************************************
 *              FONCTIONS ET PROCÉDURES               *
 ******************************************************/

/**
* 
* \fn float calculerMoyenne(struct Cours cours)
*
* \brief Fonction pour calculer la moyenne des notes d'un cours
*
* \param cours Le cours
*
* \return la moyenne 
*
* Cette fonction calcul la moyenne en parcourant chaque note
*
*/
float calculerMoyenne(struct Cours cours) {
    float somme = 0.0;
    for (int i = 0; i < TAILLE_MAX; i++) {
        somme += cours.notes[i];
    }
    return somme / TAILLE_MAX;
}

/**
* 
* \fn void afficherDetailsEtudiant(struct Etudiant etudiant)
*
* \brief Procédure pour afficher les détails d'un étudiant
*
* \param etudiant L'étudiant à afficher
*
* Cette procédure permet d'afficher le nom, l'âge et les préférences d'un étudiant
*
*/
void afficherDetailsEtudiant(struct Etudiant etudiant) {
    printf("Nom: %s\n", etudiant.nom);
    printf("Age: %d\n", etudiant.age);
    printf("Preferences: ");
    for (int i = 0; i < TAILLE_MAX; i++) {
        printf("%d ", etudiant.preferences[i]);
    }
    printf("\n");
}

/**
* 
* \fn afficherDetailsCours(struct Cours cours)
*
* \brief Procédure pour afficher les détails d'un cours
*
* \param cours Le cours
*
* Cette procédure permet d'afficher le nom du cours et les notes
*
*/
void afficherDetailsCours(struct Cours cours) {
    printf("Nom du cours: %s\n", cours.nom);
    printf("Notes: ");
    for (int i = 0; i < TAILLE_MAX; i++) {
        printf("%.2f ", cours.notes[i]);
    }
    printf("\n");
}

/**
* 
* \fn bool afficherMessageFelicitation(float moyenne, float seuil)
*
* \brief Fonction pour afficher un message de félicitations et retourner un indicateur
*
* \param moyenne La moyenne de l'étudiant
*
* \param seuil Le seuil
*
* \return true si la moyenne est supérieur au seuil, false sinon
*
* Cette fonction affiche un message de félicitation
*
*/
bool afficherMessageFelicitation(float moyenne, float seuil) {
    if (moyenne > seuil) {
        printf("Félicitations ! La moyenne est excellente.\n");
        return true;
    } else {
        printf("La moyenne est bonne, mais peut être améliorée.\n");
        return false;
    }
}