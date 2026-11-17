# The Turning Sound

**Auteur :** Antoine [NOM]
**Cours :** CSC4101 – Développement Web PHP (Télécom SudParis)

---

## Description

**The Turning Sound** est une application web de gestion de collections de vinyles. Chaque membre possède sa propre bibliothèque (*library*) dans laquelle il référence ses disques (titre, artiste). L'application permet de consulter la liste de toutes les bibliothèques et le détail de chacune d'entre elles, la liste de tous les vinyles et le détail de chaque disque, ainsi que le profil public d'un membre à partir de son nom d'utilisateur.

---

## Stack technique

| Composant | Technologie |
|---|---|
| Langage | PHP 8.x |
| Framework | Symfony |
| ORM | Doctrine ORM |
| Base de données | SQLite (environnement de développement) |
| Moteur de templates | Twig |

---

## Modèle de données

Le modèle repose sur trois entités :

```
┌──────────────┐ 1       1 ┌──────────────┐ 1       * ┌──────────────┐
│    Member    │───────────│   Library    │───────────│    Vinyl     │
└──────────────┘           └──────────────┘           └──────────────┘
```

### `Member`
Utilisateur de l'application (implémente `UserInterface`).

| Attribut | Type | Description |
|---|---|---|
| `id` | int | Clé primaire |
| `email` | string (180), unique | Identifiant d'authentification |
| `username` | string (180), unique | Nom d'utilisateur public (utilisé dans l'URL du profil) |
| `roles` | array | Rôles Symfony (`ROLE_USER` toujours garanti) |
| `password` | string | Mot de passe haché |
| `library` | `Library` | Bibliothèque du membre |

### `Library`
Bibliothèque (inventaire) d'un membre.

| Attribut | Type | Description |
|---|---|---|
| `id` | int | Clé primaire |
| `name` | string (255) | Nom de la bibliothèque |
| `member` | `Member` | Propriétaire de la bibliothèque |
| `vinyls` | Collection\<`Vinyl`\> | Vinyles contenus dans la bibliothèque |

### `Vinyl`
Disque vinyle appartenant à une bibliothèque.

| Attribut | Type | Description |
|---|---|---|
| `id` | int | Clé primaire |
| `title` | string | Titre de l'album |
| `artist` | string | Artiste |
| `library` | `Library` | Bibliothèque contenant le disque |

### Relations

- **Member ↔ Library : `OneToOne` bidirectionnelle.** `Member` est le côté *propriétaire* (la clé étrangère `library_id` se trouve dans la table `member`, non nullable). Le `cascade: ['persist', 'remove']` permet de persister la bibliothèque en même temps que le membre.
- **Library → Vinyl : `OneToMany` / `ManyToOne`.** `Vinyl` est le côté propriétaire (clé étrangère `library_id` dans la table `vinyl`). `orphanRemoval` est activé : un vinyle retiré d'une bibliothèque est supprimé.

---

## Architecture du projet

```
TheTurningSound/
├── config/                     # Configuration Symfony (routes, services, sécurité…)
├── migrations/                 # Migrations Doctrine
├── public/                     # Point d'entrée web (index.php)
├── src/
│   ├── Controller/
│   │   ├── LibraryController.php   # Liste et détail des bibliothèques
│   │   ├── MemberController.php    # Profil d'un membre
│   │   └── VinylController.php     # Liste et détail des vinyles
│   ├── DataFixtures/
│   │   └── AppFixtures.php         # Données de test (members, libraries, vinyls)
│   ├── Entity/
│   │   ├── Member.php
│   │   ├── Library.php
│   │   └── Vinyl.php
│   └── Repository/                 # Repositories Doctrine associés aux entités
├── templates/
│   ├── base.html.twig              # Layout commun
│   ├── library/
│   │   ├── index.html.twig         # Liste des bibliothèques
│   │   └── show.html.twig          # Détail d'une bibliothèque
│   ├── member/
│   │   └── show.html.twig          # Profil d'un membre
│   └── vinyl/
│       ├── index.html.twig         # Liste des vinyles
│       └── show.html.twig          # Détail d'un vinyle
├── var/
│   └── data_dev.db                 # Base SQLite de développement
└── composer.json
```

L'application suit le modèle **MVC** de Symfony :

- **Modèle** : entités Doctrine (`src/Entity`) et repositories (`src/Repository`).
- **Vue** : templates Twig (`templates/`), héritant du layout `base.html.twig`.
- **Contrôleur** : classes de `src/Controller`, qui interrogent la base via Doctrine et transmettent les données aux templates avec `$this->render()`.

### Routes

| Route | Nom | Contrôleur | Description |
|---|---|---|---|
| `/library_list` | `library_list` | `LibraryController::index` | Liste de toutes les bibliothèques |
| `/library/{id}` | `library_show` | `LibraryController::show` | Détail d'une bibliothèque (`id` entier) |
| `/member/{username}` | `member_show` | `MemberController::show` | Profil d'un membre via son nom d'utilisateur |
| `/vinyl_list` | `vinyl_list` | `VinylController::index` | Liste de tous les vinyles |
| `/vinyl/{id}` | `vinyl_show` | `VinylController::show` | Détail d'un vinyle |


---

## Données de test

Les fixtures (`AppFixtures`) créent deux membres, chacun avec sa bibliothèque :

| Membre | Bibliothèque | Vinyles |
|---|---|---|
| `antoine@tsp.fr` (`tonio`) | Tony's Library | Thriller – Michael Jackson, L.A. Woman – The Doors |
| `rod@tsp.fr` (`rod`) | Rod's Library | (What's the Story) Morning Glory? – Oasis |
