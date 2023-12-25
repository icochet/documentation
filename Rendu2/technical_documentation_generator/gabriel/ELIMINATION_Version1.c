#include<stdio.h>
#include<stdlib.h>
#include <stdbool.h>

#define N 3
#define TAILLE (N*N)

typedef struct 
{
    int valeur;
    int candidats[TAILLE];
    int nbCandidats;
} tCase1;

typedef int tGrille[TAILLE][TAILLE];
typedef tCase1 tGrille1[TAILLE][TAILLE];

void ajouterCandidat(tCase1 *c, int val) {
    if ((*c).nbCandidats < TAILLE) {
        (*c).candidats[(*c).nbCandidats] = val;
        (*c).nbCandidats++;
    }
}

void retirerCandidat(tCase1 *c, int val) {
    int i, j;
    i = 0;
    while (i < (*c).nbCandidats) {
        if ((*c).candidats[i] == val) {
            for (j = i; j < (*c).nbCandidats - 1; j++) {
                (*c).candidats[j] = (*c).candidats[j + 1];
            }
            (*c).nbCandidats--;
        } else {
            i++;
        }
    }
}

bool estCandidat(const tCase1 c, int val) {
    for (int i = 0; i < c.nbCandidats; i++) {
        if (c.candidats[i] == val) {
            return true;
        }
    }
    return false;
}

int nbCandidats(const tCase1 c) {
    return c.nbCandidats;
}

void chargerGrille(tGrille g){
    char nomFichier[30];
    FILE * fichier;
    printf("Nom du fichier ? ");
    scanf("%s", nomFichier);
    fichier = fopen(nomFichier, "rb");
    if (fichier == NULL){
        printf("\n ERREUR sur le fichier %s\n", nomFichier);
    } else {
        fread(g, sizeof(int), TAILLE * TAILLE, fichier);
    }
    fclose(fichier);
}

void afficherGrille(tGrille1 grille){
    printf("     1  2  3   4  5  6   7  8  9  \n");
    for (int ligne = 0; ligne < TAILLE; ligne++)
    {
        if (ligne%N==0)
        {
            printf("   ");
            printf("+---------+---------+---------+\n");
        }
        printf("%d  ",ligne+1);
        
        for (int col = 0; col < TAILLE; col++)
        {
            if (col%N==0)
            {
                printf("|");
            }
            
            if ((grille[ligne][col]).valeur==0){
                printf(" . ");
            }
            else 
            {
                printf(" %d ",(grille[ligne][col]).valeur);
            }
        }
        printf("|");
        printf("\n");
    }
    printf("   +---------+---------+---------+\n");
}

void retireTousCandidats(tGrille1 grille,int ligne,int colonne, int valeur){
    for (int i = 0; i < TAILLE; i++)
    {
        retirerCandidat(&grille[ligne][i],valeur);
        retirerCandidat(&grille[i][colonne],valeur);
    }
    int coinLigne = 3 * (ligne / 3);
    int coinColonne = 3 * (colonne / 3);
    for (int i = coinLigne; i < coinLigne + 3; ++i) {
        for (int j = coinColonne; j < coinColonne + 3; ++j) {
            retirerCandidat(&grille[i][j],valeur);
        }
    }
}



bool possible(tGrille1 grille, int ligne, int colonne, int valeur) {
    bool trouve =true;
    for (int j = 0; j < TAILLE; ++j) {
        if (j != colonne && grille[ligne][j].valeur == valeur) {
            trouve=false;
        }
    }
    for (int i = 0; i < TAILLE; ++i) {
        if (i != ligne && grille[i][colonne].valeur == valeur) {
            trouve=false;
        }
    }
    int coinLigne = 3 * (ligne / 3);
    int coinColonne = 3 * (colonne / 3);
    for (int i = coinLigne; i < coinLigne + 3; ++i) {
        for (int j = coinColonne; j < coinColonne + 3; ++j) {
            if (i != ligne && j != colonne && grille[i][j].valeur == valeur) {
                trouve=false;
            }
        }
    }

    return trouve;
}

void candidat(tGrille1 grille){
    for (int i = 0; i < TAILLE; i++)
    {
        for (int j = 0; j < TAILLE; j++)
        {
            for (int l = 0; l < TAILLE; l++)
            {
                if (possible(grille,i,j,l+1))
                {
                    ajouterCandidat(&grille[i][j],l+1);
                }
            }            
        }
    }
}

bool grilleEstPleine(tGrille1 grille) {
    for (int ligne = 0; ligne < TAILLE; ++ligne) {
        for (int col = 0; col < TAILLE; ++col) {
            if (grille[ligne][col].valeur == 0) {
                return false;
            }
        }
    }
    return true;
}

int main() {
    tGrille grille1;
    tGrille1 grille2;

    chargerGrille(grille1);
    for (int i = 0; i < TAILLE; i++)
    {
        for (int j = 0; j < TAILLE; j++)
        {
            (grille2[i][j]).valeur=grille1[i][j];
            (grille2[i][j]).nbCandidats=0;
        }
    }

    afficherGrille(grille2);

    candidat(grille2);
    
    while (!grilleEstPleine(grille2))
    {
        //singleuton nu
        for (int i = 0; i < TAILLE; i++)
        {
            for (int j = 0; j < TAILLE; j++)
            {
                if ((grille2[i][j]).nbCandidats==1)
                {
                    (grille2[i][j].valeur=(grille2[i][j].candidats[0]));
                    retireTousCandidats(grille2,i,j,grille2[i][j].valeur);
                }   
            }
        }
    }
    
    printf("\n");
    afficherGrille(grille2);
    return EXIT_SUCCESS;
}
