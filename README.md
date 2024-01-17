# Projet JOB BOARD

## Description

Dans ce projet, nous avons créé un site web de type job board. Ce site permet aux utilisateurs de consulter des offres d'emploi et de postuler à ces offres. Les utilisateurs peuvent également créer un compte et publier des offres d'emploi.

## Instructions

Dans ce projet, nous allons développer le site fonctionnalité par fonctionnalité. Chaque fonctionnalité sera développée dans une branche séparée. Une fois la fonctionnalité terminée, nous fusionnerons la branche avec la branche principale.

## Fonctionnalités

- [x] En tant qu'utilisateur, je veux pouvoir créer un compte sur le site afin de pouvoir me connecter et postuler à des offres d'emploi.
- [x] En tant qu'entreprise, je veux pouvoir créer un compte sur le site afin de pouvoir me connecter et publier des offres d'emploi et consulter les candidatures.
- [x] Les offres doivent être publiques par ordre décroissant de date de publication.
- [x] Les utilisateurs peuvent filtrer les offres par catégorie et par type de contrat.
- [x] L'administrateur peut créer des catégories et des types de contrat.Il peut également supprimer des offres d'emploi et desactivé des utilisateurs et des entreprises.

## Entités

- [x] User
  - id (int)
  - status (string)=>(user,entreprise)
  - username (string)
  - email (string)
  - password (string)
  - role (string)
  - createdAt (date)

## Partie 1

- [x] Dans un premier temps , nous allons faire la partie front-end du site web.

  - Mise en place de la structure HTML et CSS du site web.
  - Mise en place de la page d'accueil(Home page).Avec la navbar et le footer.
  - Mise en place de la page de connexion et d'inscription.
  - Affichage des offres d'emploi sur le page d'accueil.
  - Affichage des entreprises sur le page d'accueil.

- [x] Dans un deuxième temps, nous allons créer le controller du compte utilisateur.

  - Création du controller `accountController`
    `symfony console make:controller AccountController`

- [x] Dans un troisième temps, nous allons faire la partie inscription et connexion

  - Création du formulaire d'inscription et de connexion.
    `symfony console make:user`
    `symfony console make:auth`
    `symfony console make:registration-form`

  - Sécurisation des routes. Les utilisateurs non connectés ne peuvent pas accéder à la page account.

- [x] Dans un quatrième temps, nous allons gérer l'envoi de mail de confirmation de creation de compte. Nous allons utiliser mailtrap.io

## Partie 2

Dans cette partie, nous allons créer la partie back-end du site web.

- [x] Dans un premier temps, nous allons installer le bundle `easyadmin` pour créer un back-office.
      `composer require easycorp/easyadmin-bundle`
      `symfony console make:admin:dashboard`
      `symfony console make:admin:crud`

- [x] Dans un deuxième temps, nous allons créer une entité `HomeSetting` pour gérer les paramètres de la page d'accueil(l'image, le message et le call to action)

  - Création de l'entité `HomeSetting` qui contient les champs suivants: - id (int) - message (string) - image (string) - callToAction (string) - createdAt (datetimeimmutable)
    `symfony console make:entity HomeSetting`

- [x] Dans un troisième temps, nous allons faire le admin crud sur l'entity `HomeSetting` afin de pouvoir modifier les paramètres de la page d'accueil. et rendre la page d'accueil dynamique dans le `homeController`.

  - Création du admin crud sur l'entity `HomeSetting`
    `symfony console make:admin:crud`
  - Modification du `homeController` pour rendre la page d'accueil dynamique à partir des paramètres de la base de données.

- [x] Dans un quatrième temps, nous allons créer une entité `ContractType` pour gérer les types de contrat.Les différents types de contrat sont: CDI, CDD, Freelance, Stage, Alternance, Intérim,Autre.

  - Création de l'entité `ContractType` qui contient les champs suivants: - id (int) - name (string) - slug (string) - createdAt (datetimeimmutable)
    `symfony console make:entity ContractType`

  - Creation du crud admin sur l'entity `ContractType`
    `symfony console make:admin:crud`
    Ajout des ContratTypes dans la base de données par l'admin.

## Partie 3

- [x] Dans un premier temps, nous allons créer une entité `Tag` qui sera un ensemble de mots clés qui permettront de décrire les offres d'emploi.

  - Création de l'entité `Tag` qui contient les champs suivants: - id (int) - name (string) - slug (string)
    `symfony console make:entity Tag`

  - Installer cocur/slugify pour générer le slug.
    `composer require cocur/slugify`

  - Créatin du formulaire d'ajout de tag par l'employeur.
    `symfony console make:form`

  - Création du crud admin sur l'entity `Tag`
    `symfony console make:admin:crud`

- [x] Dans un deuxième temps, nous allons mettre à jour le `accountController` avec un sidebar qui contient un menu qui sera différent selon le status de l'utilisateur.

  - Un candidat aura un menu avec les liens suivants:
    - Mes candidatures
    - Mes informations
    - Profil
  - Un recruiteur aura un menu avec les liens suivants:
    - Mes offres d'emploi
    - Mes informations
    - Les Mot clés
    - Profil

- [x] Dans un troisième temps, nous allons créer une `UserProfil`
      qui va permettre à l'utilisateur de modifier ses informations personnelles.Cette entité aura les champs suivants: - id (int) - firstName (string) - lastName (string) - slug (string) - address (string) - city (string) - zipCode (string) - country (string) - phoneNumber (string) - jobSought(string) => poste recherché ==>nullable - presentation (text) - availability (string) => disponibilité - website (string) - picture (string) - user (User) => OneToOne
      `symfony console make:entity UserProfil`

- [x] Création du controller et du formulaire d'ajout de `UserProfil` par l'utilisateur.
      `symfony console make:form`

- [x] Créer la route `user/profil/{slug}` qui affiche le profil de l'utilisateur.
- [x] Créer la route `user/profil/{slug}/edit` qui permet à l'utilisateur de modifier son profil.

- [x] Dans un quatrième temps, nous allons créer une entité `EntrepriseProfil` qui contient les informations de l'entreprise.

  - Création de l'entité `EntrepriseProfil` qui contient les champs suivants: - id (int) - name (string) - slug (string) - address (string) - city (string) - zipCode (string) - country (string) - phoneNumber (string) - activityArea (string) => secteur d'activité - email (string) - description (text) - logo (string) - website (string) - user (User) => OneToOne
    `symfony console make:entity EntrepriseProfil`

- Créer la route `/entreprise/profil/{slug}` qui affiche le profil de l'entreprise.

- Créer la route `/Tag` qui affiche la liste des tags disponibles sur le site sur le profil de l'entreprise et qui inclut un formulaire d'ajout de tag.(Seul l'admin peut supprimer des tags)

## Partie 4

- [x] Dans un premier temps, nous allons créer un controller `OfferController` qui va gérer les offres d'emploi.

  - Création du controller `OfferController`
    `symfony console make:controller OfferController`

- [x] Dans un deuxième temps, nous allons créer une entité `Offer` qui contient les offres d'emploi.

  - Création de l'entité `Offer` qui contient les champs suivants: - id (int) - title (string) - slug (string) - shortDescription (string) - content (text) - createdAt (datetimeimmutable) - salary (int) - location (string) - contractType (ContractType) => ManyToOne - entreprise (Entreprise) => ManyToOne - tags (Tag) => ManyToMany - isActive (boolean)
    `symfony console make:entity Offer`

  - Création du formulaire d'ajout d'offre d'emploi par l'employeur.
    `symfony console make:form`

  - Création de la route `/entreprise/offer/new` qui permet à l'employeur de créer une offre d'emploi.
  - Création de la route `/entreprise/offer/` qui permet à l'employeur de voir ses offres d'emploi.

  - Création du crud admin sur l'entity `Offer`. L'admin peut desactiver une offre d'emploi.
    `symfony console make:admin:crud`
