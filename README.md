
# Project 6 - Site communautaire  SnowTricks

SnowTricks est un site web développé en utilisant Symfony 6.3 et Php 8.2 


## Déscription du projet

Jimmy Sweat est un entrepreneur ambitieux passionné de snowboard. Son objectif est la création d'un site collaboratif pour faire connaître ce sport auprès du grand public et aider à l'apprentissage des figures (tricks).

Il souhaite capitaliser sur du contenu apporté par les internautes afin de développer un contenu riche et suscitant l’intérêt des utilisateurs du site. Par la suite, Jimmy souhaite développer un business de mise en relation avec les marques de snowboard grâce au trafic que le contenu aura généré.

Pour ce projet, nous allons nous concentrer sur la création technique du site pour Jimmy.

Les fonctionnalités de ce site web:

  - Affichage de toutes les figures sur la page d'accueil.
  - Création de la page qui présente les détails d'une figure.
  - Ajout d'une page d'enregistrement.
  - Ajout d'une page de connexion.
  - Ajout/modification/suppression des figures.
  - Ajout/modification/suppression des images et des videos d'une figure.
  - Implémentation d'un espace commun de discussion.
  - Implémentation de la pagination.
## Installation

Pour installer ce projet, il faut tout d'abord installer:
- Xampp >= 8.2 
- Composer version >= 2.2


  1- Clonez le projet en local.

  2- Dans le terminal de votre éditeur, Exécuter la commande : ```bash composer install ```  pour installer les dépendances du projet (vendor, ..etc).

  3- Créez une copié du fichier .env et nommez le .env.local

  4- Dans le fichier .env.local, renseignez les données de la base de données: ```bash DATABASE_URL="mysql://user:password@127.0.0.1.3306/dbname?charset=utf8mb4" ```.

  5- Créez la base de données avec la commande: symfony console doctrine:database:create

  6- Générez la migration avec la commande: symfony console doctrine:migrations:migrate

  7- Générez le jeux de données (fixtures) avec la commande: symfony console doctrine:fixtures:load
  
  8- Pour tester l'envoi des émails du mot de passe oublié et de la confirmation de l'émail, installer maildev ou mailhog 

    
## Documentation

[Documentation Symfony](https://symfony.com/doc/current/index.html)

