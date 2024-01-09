/**
 * \file src2.c
 * \brief programme de facture
 * \author Froc Gabriel
 * \version 1.0
 * \date 28 Novembre 2023
*/
#include <stdio.h>
#include <stdlib.h>

/**
 * \def MAX_LONGUEUR
 * \brief constante pour la longueur max.
*/
#define MAX_LONGUEUR 50
/**
 * \def VALEUR_MAX
 * \brief constante pour la valeur max.
*/
#define VALEUR_MAX 1000
/**
 * \def TAILLE_TABLEAU
 * \brief constante pour la Taille.
*/
#define TAILLE_TABLEAU 5

/**
 * \struct Produit
 *
 * \brief Type de structure pour les informations sur un produit
 *
 * Le type Produit est utilisé pour stocker les détails spécifiques à un produit, 
 * tels que l'identifiant, le nom et le prix.
 */
struct Produit{
    int id; // Identifiant du produit 
    char nom[MAX_LONGUEUR]; // Nom du produit 
    float prix; // Prix du produit
};

/**
 * \struct Facture
 *
 * \brief Type de structure pour les informations de facturation
 *
 * Le type Facture est utilisé pour stocker les détails relatifs à une facture, 
 * tels que le code de la facture, sa description et son montant.
 */
struct Facture{
    int code; // Code de la facture
    char description[MAX_LONGUEUR]; // Description de la facture 
    double montant; // Montant de la facture
};

/**
 * 
 * \typedef TableauProduits
 *
 * \brief type tableau de TAILLE_TABLEAU de Produit
 *
 * \brief Le type TableauProduits sert de stockage pour les Produits
 *
*/
typedef Produit TableauProduits[TAILLE_TABLEAU];

/**
 * 
 * \typedef TableauFactures
 *
 * \brief type tableau de TAILLE_TABLEAU de Facture
 *
 * \brief Le type TableauFactures sert de stockage pour les Factures
 *
*/
typedef Facture TableauFactures[TAILLE_TABLEAU];

/**
 * \fn void afficherProduit(Produit p)
 * 
 * \brief Affiche les détails d'un produit
 * 
 * \param p Le produit à afficher
 */
void afficherProduit(Produit p) {
    printf("ID du produit : %d\n", p.id);
    printf("Nom du produit : %s\n", p.nom);
    printf("Prix du produit : %.2f\n", p.prix);
}

/**
 * 
 * \fn void afficherFacture(Facture f)
 * 
 * \brief Affiche les détails d'une facture
 * 
 * \param f La facture à afficher
 */
void afficherFacture(Facture f) {
    printf("Code de la facture : %d\n", f.code);
    printf("Description : %s\n", f.description);
    printf("Montant : %.2f\n", f.montant);
}

/**
 * \fn int main()
 * 
 * \brief Fonction principale
 * 
 * \return EXIT_SUCCESS en cas de succès
 */
int main() {
    // Utilisation des typedef pour les tableaux de produits et de factures
    TableauProduits mesProduits = {
        {1, "Produit 1", 10.50},
        {2, "Produit 2", 20.75},
        {3, "Produit 3", 15.25},
        {4, "Produit 4", 30.00},
        {5, "Produit 5", 12.99}
    };

    TableauFactures mesFactures = {
        {1001, "Facture 1", 150.25},
        {1002, "Facture 2", 300.50},
        {1003, "Facture 3", 75.80},
        {1004, "Facture 4", 500.00},
        {1005, "Facture 5", 100.99}
    };

    // Parcours et affichage des détails des produits
    printf("Détails des produits :\n");
    for (int i = 0; i < TAILLE_TABLEAU; ++i) {
        printf("\nProduit %d :\n", i + 1);
        afficherProduit(mesProduits[i]);
    }

    // Parcours et affichage des détails des factures
    printf("\nDétails des factures :\n");
    for (int i = 0; i < TAILLE_TABLEAU; ++i) {
        printf("\nFacture %d :\n", i + 1);
        afficherFacture(mesFactures[i]);
    }

    return EXIT_SUCCESS;
}

