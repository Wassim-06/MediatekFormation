# MediatekFormation
Ce dépôt contient les fonctionnalités ajoutées au projet MediatekFormation.
Pour consulter le dépôt d'origine et obtenir une présentation complète de l'application initiale, rendez-vous ici : Dépôt d'origine.

## Fonctionnalités ajoutées
### Back-end
Gestion CRUD (Créer, Lire, Mettre à jour, Supprimer) des formations, playlists et catégories.
Mise en place de l'accès avec authentification pour sécuriser la partie admin.
Ajoute ici une image de l’interface admin (par exemple, la gestion des formations).
Exemple :

![Interface admin pour la gestion des formations](images/interface-admin.png)

### Front-end
Ajout de tris et filtres pour améliorer la navigation dans les formations.
Optimisation des pages pour une expérience utilisateur fluide.
Ajoute ici une capture d’écran montrant les tris ou filtres en action.
Exemple :

![Page de tri des formations](images/tri-formations.png)

### Automatisation
Déploiement continu configuré via GitHub Actions : chaque push met automatiquement à jour le site en ligne.
Sauvegarde manuelle de la base de données en cas de besoin.

## Mode opératoire
Installation et utilisation en local
### Prérequis :
PHP >= 8.1
Composer
Serveur MySQL
Symfony CLI (optionnel mais recommandé)

### Étapes d'installation :
Clonez le dépôt sur votre machine :

git clone https://github.com/Wassim-06/MediatekFormation.git
cd MediatekFormation
Exemple :

![Clone du dépôt dans le terminal](images/clone-depot.png)

Installez les dépendances avec Composer :

composer install
Ajoute ici une image montrant le processus de téléchargement des dépendances.
Exemple :

![Installation des dépendances avec Composer](images/composer-install.png)

Configurez le fichier .env :

Renseignez les paramètres de connexion à votre base de données locale.

DATABASE_URL="mysql://username:password@127.0.0.1:3306/mediatekformation"
Créez la base de données et exécutez les migrations :

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
Ajoute une image de la création de la base de données.
Exemple :

![Création de la base de données avec Symfony](images/create-database.png)

Lancez le serveur local :

symfony server:start
Ajoute ici une capture d'écran montrant le serveur Symfony démarré.
Exemple :

![Démarrage du serveur local avec Symfony](images/symfony-server.png)

Accédez au site sur http://127.0.0.1:8000.

## Tester l'application en ligne
Rendez-vous sur : http://mediatekformation.go.yn.fr.
Explorez les fonctionnalités disponibles :
Parcourez les formations, utilisez les filtres et tris.
Testez les différentes catégories et playlists.
Ajoute ici une capture d’écran de la page d’accueil du site en ligne.
Exemple :

![Page d'accueil en ligne](images/page-accueil.png)