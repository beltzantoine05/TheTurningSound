================================================================================
                              THE TURNING SOUND
================================================================================

Auteur : Antoine Beltz
Cours  : CSC4101 - Architectures et applications Web (Télécom SudParis)
Thème  : collections de disques vinyles

--------------------------------------------------------------------------------
1. PRÉSENTATION
--------------------------------------------------------------------------------

The Turning Sound est une application Symfony de gestion de collections de
vinyles. Chaque membre possède une bibliothèque dans laquelle il référence ses
disques (avec leur pochette), et peut composer des « bacs » : des sélections
thématiques de vinyles de sa bibliothèque, publiques ou privées.

Les visiteurs non connectés peuvent parcourir les bacs publics. Les membres
connectés accèdent aux bibliothèques, aux membres et aux vinyles, et gèrent
leurs propres données.

Stack : PHP >= 8.2, Symfony 7.4, Doctrine ORM, SQLite, Twig, Bootstrap 5
(thème Start Bootstrap « Shop Homepage »), VichUploaderBundle (images),
camurphy/bootstrap-menu-bundle (menu).

L'avancement détaillé par rapport au guide est suivi dans le fichier TODO.md.

--------------------------------------------------------------------------------
2. NOMENCLATURE
--------------------------------------------------------------------------------

  Guide du projet     Projet          Description
  ---------------     -------------   ------------------------------------------
  [inventaire]        Library         Bibliothèque d'un membre (une par membre)
  [objet]             Vinyl           Disque vinyle, rangé dans une bibliothèque
  [galerie]           Bac             Sélection de vinyles faite par un membre
  membre              Member          Utilisateur (authentification)

Le mot « bac » fait référence aux bacs des disquaires dans lesquels on fouille
les disques.

--------------------------------------------------------------------------------
3. MODÈLE DE DONNÉES
--------------------------------------------------------------------------------

(schéma également disponible dans DataModel.png)

  Member 1 ---- 1 Library 1 ---- * Vinyl * ---- * Bac * ---- 1 Member

- Member <-> Library : OneToOne. Chaque membre a exactement une bibliothèque,
  créée et supprimée avec lui (cascade persist / remove).
- Library -> Vinyl : OneToMany. Un vinyle appartient à une bibliothèque
  (orphanRemoval : un vinyle retiré de sa bibliothèque est supprimé).
- Member -> Bac : OneToMany. Un bac appartient à un membre (son propriétaire).
- Bac <-> Vinyl : ManyToMany. Un bac contient des vinyles de la bibliothèque de
  son propriétaire ; un vinyle peut être dans plusieurs bacs.

Attributs principaux :
- Member  : email (identifiant de connexion), username (unique, utilisé dans
            l'URL du profil), roles, password (haché), biography
- Library : name
- Vinyl   : title, artist, year, type, label, prodCountry, prodYear, signed,
            description, imageName (pochette, gérée par VichUploader)
- Bac     : name, description, public (booléen)

--------------------------------------------------------------------------------
4. INSTALLATION ET LANCEMENT
--------------------------------------------------------------------------------

Depuis le dossier extrait de l'archive :

    rm -fr composer.lock symfony.lock vendor/ var/cache/
    symfony composer install
    symfony server:start

Puis ouvrir l'adresse affichée (par défaut https://127.0.0.1:8000/).
La page d'accueil redirige vers la liste des bacs publics (/bac).

Base de données : l'archive contient déjà la base SQLite remplie
(var/data_dev.db) et les pochettes correspondantes (public/images/covers/).

Pour repartir d'une base propre (recommandé si des tests ont modifié les
données) :

    symfony console doctrine:migrations:migrate -n
    symfony console doctrine:fixtures:load -n

Le chargement des fixtures recopie les pochettes depuis fixtures/covers/ vers
public/images/covers/.

--------------------------------------------------------------------------------
5. COMPTES DE TEST ET RÔLES
--------------------------------------------------------------------------------

Tous les comptes ont le même mot de passe : password
On se connecte avec l'email (menu « Connexion »).

  Email             Nom     Rôle         Bibliothèque          Vinyles  Bacs
  ---------------   -----   ----------   -------------------   -------  -----------------
  antoine@tsp.fr    tonio   ROLE_ADMIN   Tony's Library        22       3 (dont 1 privé)
  rod@tsp.fr        rod     ROLE_USER    Rod's Library         8        3 (dont 1 privé)
  clara@tsp.fr      clara   ROLE_USER    Clara's Crates        7        3 (dont 1 privé)
  lucas@tsp.fr      lucas   ROLE_USER    Lucas' Groove Vault   8        3 (dont 1 privé)
  emma@tsp.fr       emma    ROLE_USER    Emma's Record Room    8        3 (dont 1 privé)
  hugo@tsp.fr       hugo    ROLE_USER    Hugo's Collection     7        3 (dont 1 privé)

Rôles (role_hierarchy : ROLE_ADMIN hérite de ROLE_USER) :
- Visiteur (non connecté) : liste des bacs publics, consultation d'un bac
  public et de ses vinyles. Les autres pages redirigent vers la connexion.
- ROLE_USER (membre) : consultation des bibliothèques, membres et vinyles ;
  ajout de vinyles et de bacs ; consultation de ses propres bacs privés.
- ROLE_ADMIN (administrateur) : comme un membre, et consultation des bacs
  privés de tous les membres.

Les boutons « Modifier », « Supprimer » et « Ajouter » ne sont affichés qu'au
propriétaire des données ou à l'administrateur.

Les comptes sont créés dans src/DataFixtures/UserFixtures.php, les vinyles et
les bacs dans src/DataFixtures/AppFixtures.php.

Pour distinguer les cas, utiliser rod pour un membre simple et tonio pour
l'administrateur. Bacs privés utiles aux tests : « Singles » (tonio) et
« À réécouter » (rod).

--------------------------------------------------------------------------------
6. PARCOURS DE TEST (éléments évalués)
--------------------------------------------------------------------------------

Consultation d'un [objet] (Vinyl)
  - Connecté : menu Vinyles > Voir, ou depuis une bibliothèque.
  - Visiteur : menu Bacs > Voir un bac > Voir un vinyle
    (route /bac/{bac_id}/vinyl/{vinyl_id} ; 404 si le vinyle n'est pas dans
    ce bac).

Liste des [objets] d'un [inventaire] et navigation vers un [objet]
  - Connecté : menu Bibliothèques > Voir : tableau des vinyles de la
    bibliothèque, bouton « Voir » vers la fiche de chaque vinyle.

Mise en forme Bootstrap
  - Toutes les pages : gabarit commun (templates/base.html.twig), menu,
    tableaux, boutons, formulaires (thème bootstrap_5_layout).

Entité [galerie] (Bac) et association M-N avec [objet]
  - Fiche d'un bac : liste de ses vinyles. Fiche d'édition d'un vinyle :
    cases à cocher des bacs de son propriétaire.

CRUD des [galeries]
  - Connecté en rod : menu Membres > rod > « Ajouter un bac » (création),
    « Voir » (consultation), « Modifier » (modification ; le bouton
    « Supprimer » est sur la fiche et sur la page de modification).
  - Les vinyles proposés dans un bac sont uniquement ceux de la bibliothèque
    de son propriétaire.

Création d'un [objet] contextualisée à l'[inventaire]
  - Connecté en rod : Bibliothèques > Rod's Library > « Ajouter un vinyle ».
    La bibliothèque est fixée (champ grisé) ; retour à la bibliothèque après
    l'enregistrement.

Mise en ligne des images
  - 18 vinyles ont une pochette (photos de vinyles personnels), par exemple
    Thriller ou Master of Puppets.
  - Connecté en tonio : modifier un vinyle > champ « Pochette » pour envoyer
    une image ; case « Supprimer la pochette » pour la retirer.

Authentification
  - Menu Connexion / Déconnexion, nom de l'utilisateur affiché dans la barre
    de navigation (avec un badge « admin » pour tonio).

Éléments optionnels réalisés
  - Pages Bibliothèques, Membres et Vinyles réservées aux membres connectés
    (#[IsGranted('IS_AUTHENTICATED_FULLY')] sur les contrôleurs).
  - Création, modification et suppression de bacs réservées aux membres
    connectés (#[IsGranted('ROLE_USER')]).
  - Bac privé : accessible seulement à son propriétaire ou à un administrateur
    (403 sinon), y compris pour la consultation de ses vinyles.
    Exemple : rod ouvre « Singles » de tonio -> 403 ; tonio ouvre
    « À réécouter » de rod -> autorisé (admin).
  - Ajout d'un vinyle dans des bacs depuis le formulaire du vinyle, et choix
    des vinyles d'un bac depuis le formulaire du bac.

--------------------------------------------------------------------------------
7. ORGANISATION DU CODE
--------------------------------------------------------------------------------

src/Controller/
  BacController.php       Bacs : liste publique, CRUD, consultation d'un
                          vinyle dans un bac, contrôle d'accès aux bacs privés
  LibraryController.php   Bibliothèques : liste et consultation
  MemberController.php    Membres : liste et profil (/member/{username})
  VinylController.php     Vinyles : CRUD, création depuis une bibliothèque
  SecurityController.php  Connexion / déconnexion
src/Entity/               Member, Library, Vinyl, Bac
src/Form/                 BacType, VinylType (formulaires des CRUD)
src/DataFixtures/         UserFixtures (comptes), AppFixtures (vinyles, bacs)
templates/                Gabarits Twig (base.html.twig + un dossier par entité)
fixtures/covers/          Pochettes utilisées par les fixtures
config/packages/          security.yaml (authentification, rôles),
                          vich_uploader.yaml (images), bootstrap_menu.yaml (menu)

Principales routes :

  /                                   redirection vers /bac
  /bac                                bacs publics
  /bac/{id}                           consultation d'un bac
  /bac/new/{id}                       création d'un bac pour le membre {id}
  /bac/{id}/edit                      modification d'un bac
  /bac/{bac_id}/vinyl/{vinyl_id}      vinyle consulté depuis un bac
  /library_list, /library/{id}        bibliothèques
  /member_list, /member/{username}    membres
  /vinyl, /vinyl/{id}                 vinyles
  /vinyl/new/{id}                     ajout d'un vinyle dans la bibliothèque {id}
  /vinyl/{id}/edit                    modification d'un vinyle
  /login, /logout                     authentification
