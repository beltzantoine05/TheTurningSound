# TODO – The Turning Sound

Roadmap de fin de projet : 4 jours, 3 à 4 h par jour (~14 h au total).
Nomenclature : `[inventaire]` = `Library`, `[objet]` = `Vinyl`, `[galerie]` = `Bac`.

## Déjà fait

- [x] Étapes 1 à 7 : projet, entités `Library` / `Vinyl` / `Member`, fixtures, pages de consultation, gabarits Twig, Bootstrap + menu
- [x] Étape 8 : entité `Bac` (galerie)
- [x] Étape 9 : CRUD des bacs (`make:crud`), affichage des vinyles d'un bac

## Jour 1 : assainir et réorganiser (~3h30)

- [x] 1.1 Commiter l'état actuel (10 min)
- [x] 1.2 Créer `TODO.md` – étape 2.6 (10 min)
- [ ] 1.3 Choisir entre `library` et `owner` dans `Bac` (doublon, risque d'incohérence) ; si suppression : entité, migration, fixtures, `BacType`, gabarit (45 min)
- [ ] 1.4 Retirer les `dump()` des contrôleurs et gabarits (15 min)
- [ ] 1.5 Étape 11.1 : `MemberController` en Twig (`index` + `show`), liens membre ↔ bibliothèque ; corrige la route `app_library` inexistante (1 h)
- [ ] 1.6 Étape 10.1 : déplacer `VinylController::show()` dans `BacController`, supprimer `VinylController`, régénérer le CRUD Vinyl (`make:crud`), corriger les liens (1 h)

## Jour 2 : contextualisation (~3h30)

- [ ] 2.1 Étape 10.2 : route `/bac/{bac_id}/vinyl/{vinyl_id}` avec `#[MapEntity]`, 404 si le vinyle n'est pas dans le bac, 403 si bac privé (1 h)
- [ ] 2.2 Étape 11.2 : création d'un vinyle depuis la fiche bibliothèque (`/vinyl/new/{id}`), champ bibliothèque désactivé, redirections vers la bibliothèque (1h15)
- [ ] 2.3 Étapes 12.1 à 12.4 : bacs sur la fiche membre, création contextualisée, redirections, `findBy(['public' => true])` dans le contrôleur (1h15)

## Jour 3 : formulaire de bac et authentification (~4 h)

- [ ] 3.1 Étape 13.1 : champ `vinyls` dans `BacType` (`EntityType`, `multiple`, `expanded`, `by_reference => false`), propriétaire désactivé – prévoir du débogage (1 h)
- [ ] 3.2 Étape 13.2 : `query_builder` limitant les vinyles à ceux du propriétaire (30 min)
- [ ] 3.3 Étape 15 : `make:security:form-login`, logout, `role_hierarchy`, `default_target_path` (1 h)
- [ ] 3.4 Fixtures séparées `UserFixtures` + `AppFixtures` (`DependentFixtureInterface`), un compte `ROLE_ADMIN` (45 min)
- [ ] 3.5 Liens login/logout contextuels dans le menu + commit (30 min)

## Jour 4 : images, accès et finitions (~3h30)

- [ ] 4.1 Étape 14 : VichUploader pour les pochettes, upload dans le CRUD, affichage sur la fiche vinyle, images dans les fixtures (annexe 19.2) (1h45)
- [ ] 4.2 Étapes 16.1 + 16.4 (optionnel) : `#[IsGranted]`, bac privé visible seulement par son propriétaire ou l'admin (1 h)
- [ ] 4.3 Recette visiteur / membre / admin, recharger les fixtures, mettre à jour le README, commit final (45 min)

## En cas de retard

Abandonner dans cet ordre : 4.2, puis les fixtures d'images de 4.1, puis 3.2.
Prioritaires : 3.1, 3.3, 3.4, 3.5.
