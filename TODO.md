# TODO – The Turning Sound

Roadmap de fin de projet : 4 jours prévus (mardi 17 → vendredi 20), échéance repoussée au dimanche 22 novembre.
Nomenclature : `[inventaire]` = `Library`, `[objet]` = `Vinyl`, `[galerie]` = `Bac`.

## Déjà fait

- [x] Étapes 1 à 7 : projet, entités `Library` / `Vinyl` / `Member`, fixtures, pages de consultation, gabarits Twig, Bootstrap + menu
- [x] Étape 8 : entité `Bac` (galerie)
- [x] Étape 9 : CRUD des bacs (`make:crud`), affichage des vinyles d'un bac

## Jour 1 : assainir et réorganiser (~3h30)

- [x] 1.1 Commiter l'état actuel (10 min)
- [x] 1.2 Créer `TODO.md` – étape 2.6 (10 min)
- [x] 1.3 Choisir entre `library` et `owner` dans `Bac` (doublon, risque d'incohérence) ; si suppression : entité, migration, fixtures, `BacType`, gabarit (45 min)
- [x] 1.4 Retirer les `dump()` des contrôleurs et gabarits (15 min) (ABANDONNE)
- [x] 1.5 Étape 11.1 : `MemberController` en Twig (`index` + `show`), liens membre ↔ bibliothèque ; corrige la route `app_library` inexistante (1 h)
- [x] 1.6 Étape 10.1 : déplacer `VinylController::show()` dans `BacController`, supprimer `VinylController`, régénérer le CRUD Vinyl (`make:crud`), corriger les liens (1 h)

## Jour 2 : contextualisation (~3h30)

- [x] 2.1 Étape 10.2 : route `/bac/{bac_id}/vinyl/{vinyl_id}` avec `#[MapEntity]`, 404 si le vinyle n'est pas dans le bac, 403 si bac privé (1 h)
- [x] 2.2 Étape 11.2 : création d'un vinyle depuis la fiche bibliothèque (`/vinyl/new/{id}`), champ bibliothèque désactivé, redirections vers la bibliothèque (1h15)
- [x] 2.3 Étapes 12.1 à 12.4 : bacs sur la fiche membre, création contextualisée, redirections, `findBy(['public' => true])` dans le contrôleur (1h15)

## Jour 3 (mercredi–jeudi) : formulaires de bac et de vinyle

- [x] 3.1 Étape 13.1 : champ `vinyls` dans `BacType` (`EntityType`, `multiple`, `expanded`, `by_reference => false`), propriétaire désactivé (1 h)
- [x] 3.2 Étape 13.2 : `query_builder` limitant les vinyles d'un bac (et les bacs d'un vinyle) au propriétaire (30 min)

## Vendredi 20 : images (~1 h)

- [ ] 4.1 Étape 14 : VichUploader pour les pochettes
  - [x] Installation, mapping `vinyl_covers`, champs `imageFile` / `imageName` / `updatedAt` dans `Vinyl`, migration
  - [x] Champ `VichImageType` dans `VinylType` (30 min)
  - [x] Affichage avec `vich_uploader_asset` sur les fiches vinyle (15 min)
  - [ ] Images dans les fixtures (annexe 19.2) : repoussé au dimanche, après 4.4

## Samedi 21 : authentification (~2h15)

- [x] 3.3 Étape 15 : `make:security:form-login`, logout, `role_hierarchy`, `default_target_path` (1 h)
- [ ] 3.4 Fixtures séparées `UserFixtures` + `AppFixtures` (`DependentFixtureInterface`), mots de passe hachés, un compte `ROLE_ADMIN` (45 min)
- [ ] 3.5 Liens login/logout contextuels dans le menu (30 min)

## Dimanche 22 : accès et finitions (~3 h)

- [ ] 4.2 Étapes 16.1 + 16.4 : `#[IsGranted]`, bac privé visible seulement par son propriétaire ou l'admin (1 h)
- [ ] 4.4 Nettoyage HTML / CSS des gabarits : structure HTML valide, mise en forme uniforme (tableaux, titres, boutons Bootstrap pour les liens d'action), libellés en français, style plus soigné (1 h)
- [ ] 4.1 (fin) Images dans les fixtures (annexe 19.2) : `assets/fixtures/covers/`, copie vers `public/images/covers/`, `setImageName()` (20 min)
- [ ] 4.3 Recette visiteur / membre / admin, recharger les fixtures, mettre à jour le README, commit final (45 min)

## En cas de retard

Abandonner dans cet ordre : 4.2, puis les images dans les fixtures (4.1), puis réduire 4.4 au minimum.
Prioritaires : 3.3, 3.4, 3.5, 4.3.

## À reprendre (petits restes)

- [ ] Entrée « Bacs » dans le menu (`bootstrap_menu.yaml`)
- [ ] `use ManagerRegistry` inutilisé dans `BacController`
- [ ] `use Doctrine\ORM\EntityRepository` inutilisé dans `VinylType`
- [ ] Références `self::XXX_LIBRARY` inutiles dans `bacGenerator` (`AppFixtures`), à faire avec la 3.4
