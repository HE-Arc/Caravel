---
title: "Caravel : guide d'installation"
keywords: [Laravel, Vue.js, TypeScript, Homework, Students, Teachers]
subtitle: Document annexe
titlepage-background: "resources/background-front.pdf"
titlepage-text-color: "000000"
lof: false
lot: false
version: 1.0.0
...

# Introduction
Ce document explique en détail l'installation et la configuration du projet Caravel.

# Pré-requis
Les sections suivantes ont besoin que les éléments qui suivent soient installés sur le système cible :

* [Composer](https://getcomposer.org/)
* [NPM](https://www.npmjs.com/)
* MariaDB ou PostgreSQL
* Git
* PHP (version >= 7.4)
* Accès LDAP

# Installation
Dans un premier temps il faut cloner le projet depuis le GitHub avec la commande 

> git clone https://github.com/HE-Arc/Caravel.git .

Une fois le projet cloné, deux dossiers vont particulièrement nous intéresser, ce sont les dossiers frontend et backend.

## Backend
Naviguez dans le dossier backend

> cd backend

### Configuration de l'environnement
Avant de lancer toutes les commandes nécessaire, il faut configurer l'application, pour cela éditez le fichier `.env` et modifiez les éléments selon votre configuration, notamment les variables de DB, LDAP ainsi que les informations sur l'application tel que `APP_URL`.

#### LDAP 
Pour la partie LDAP, si vous ne disposez pas d'un LDAP, vous pouvez laissez la configuration par défaut et utilisez un LDAP de test 

> docker run -d --rm -p 10389:10389 -p 10636:10636 rroemhild/test-openldap

Lorsque le LDAP est configuré il est important de savoir de quelle implémentation il s'agit (Active Direction, OpenLDAP, etc...) car les connecteurs ne sont pas forcément les mêmes pour l'authentification. 

Il faut donc éditer le fichier `config\auth.php` et adapter la ligne model 

```{.php caption="configuration de la connexion LDAP"}
...

'providers' => [
    'users' => [
        'driver' => 'ldap',
        'model' => LdapRecord\Models\OpenLDAP\User::class, // ligne à adapter
        'rules' => [],

...
```

Attention il ne faut pas oublier d'activer le module ldap dans le `php.ini` utilisé par votre système.


### Installation
Dans un premier temps il faut installer toutes les dépendances nécessaires au bon fonctionnement du backend avec composer

> composer install

Une fois l'installation terminée, il faut créer la base de donnée avec la commande 

> php artisan migrate

La base de donnée a besoin d'être nourrie pour les types de tâches, il faut donc utiliser un seeder avec la commande suivante :

> php artisan db:seed --class=TaskTypeSeeder

L'installation est terminée

pour vérifier que tout fonctionne, on peut lancer directement le serveur avec la commande 

> php artisan serve

## Frontend
Il faut se rendre maintenant dans le dossier du frontend, si vous étiez dans le dossier backend faites un : 

> cd ../frontend

### Configuration 
Comme pour le backend il existe à la racine du projet un fichier `.env` qu'il s'agit d'éditer pour faire correspondre la configuration à votre contexte.

### installation
Les commandes sont assez simple pour la partie frontend, il suffit de lancer l'installation avec la commande 

> npm install

puis lorsque l'installation est terminée on peut lancer le serveur avec la commande suivante : 

> npm run serve


# Configuration php.ini
Une configuration du `php.ini` est nécessaire pour le bon fonctionnement de Caravel.

## Taille de téléchargement
Selon la taille configurée depuis le frontend (`VUE_APP_MARKDOWN_FILE_MAX_SIZE`) il faut adapter la taille de fichier que le serveur peut recevoir, pour cela dans le fichier php.ini, il faut adapter les deux paramètres suivants : 

> post_max_size
> upload_max_filesize

# Lancement des applications 
Une fois l'installation terminée le lancement de l'application se résume à 2-3 commandes : 

> cd backend \
> php artisan server

pour le backend 

> cd frontend \
> npm run serve

Eventuellement si vous avez besoin d'un serveur LDAP il faut lancer 

> docker run -d --rm -p 10389:10389 -p 10636:10636 rroemhild/test-openldap
