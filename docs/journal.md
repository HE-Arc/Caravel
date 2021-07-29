---
title: "Caravel : journal de travail"
keywords: [Laravel, Vue.js, TypeScript, Homework, Students, Teachers]
subtitle: Document annexe
titlepage-text-color: "000000"
titlepage-background: "resources/background-front.pdf"
toc: false
lof: false
lot: false
numbersections: false
lol: false
version: 1.0.0
...

# 26-30.07.2021
* Ajout de commentaires dans le code
* Nettoyage du code produit
* Rapport
* Adaptation des nouvelles routes Swagger

# 19-25.07.2021
* Documentation rapport
* Mise en place d'un discord pour les utilisateurs de Caravel
* Poster A3

# 18.07.2021 (Dimanche)
* Ajout de référence dans la page about
* Fix miss spell

# 17.07.2021 (Samedi)
* Fixes 
  * Bug impossible de setter une réponse dans une question
  * Chargement du plugin mavonEditor local et non plus global
* Passage de Laravel en mode production
* Ajout d'une about page
* Les questions et réactions seront caché si la tâche est privée 

# 16.07.2021
* Ajout footer
* Nettoyage et organisation des dossiers
* Fix demande de permissions pour les notifications
* Fix placeholder dans le login

# 15.07.2021
* Fixes
  * Nombre de question dans les tasklists (ils n'apparaissent plus)
  * Lors de changement de groupe, les tasks n'étaient pas à jour)
  * Case sensitive routes
  * Search filter
  * Affichage de la création d'un sujet même s'il n'y pas de texte dans l'input sujet
* Refactor du subject modal pour utiliser les promesses
* Ajout confirmation suppression d'un sujet
* Ajout d'un hint pour le bouton privé
* Modification de la licence de GSTC après mise à jour du domaine
* Ajout de la recherche de groupe dans le header
* Cleaning
* Modification couleur btn réaction 

# 14.07.2021
* Fix de l'upload de l'avatar du à la nouvelle authentification (XSRF token)
* Nettoyage des restes de Blade issue de la première version de Caravel
* Ajout de traduction pour certains champs spécifiques
* Optimisation pour le chargement PWA (lighthouse)
* Fix page de chargement bloquée

# 13.07.2021
* Séance avec MM. Grunenwald et Wohlfahrt
  * Discussion sur les statisques
    * Enlever les grilles des graphes
    * Ajout d'un lien entre la sparkline et les stats
    * Ajout d'un tooltip sur la sparkline
    * Les tooltip sur les graphies ne devraient tenir compte que de l'axe des x avec un certain delta
* Fixes 
  * Group en softdelete
  * 404 replace à la place du push
  * Correction fail XSS sur le markdown

# 12.07.2021
* Done calulating WES on frontend
* Add visual representation of WES in Calendar view
* Add WES' graph on stat tab

# 09.07.2021
* Add visual representation (Gantt - GSTC)
* Working on calculating work load in the frontend to have real time data

# 08.07.2021
* Calculation of workload
* Added cron on backend to calculate every week

# 07.07.2021
* User tests à St-Imier

# 06.07.2021
* Séance avec MM. Grunenwald et Wohlfahrt
* Done markdown upload
  * Add ctrl-c / ctrl-v support
  * Fix max size file
  * Add error field
* Add loading page
* Manage 404 errors
* Switch auth system to cookie based

# 05.07.2021
* Ajout de l'historique sur les tâches (front et back)
* Fix case sensitive sur le searchEngine
* Fix Reactivity sur les tâches (utilisation de Vue.set(...)
* Fix comptage des réactions
* Début de travail sur le markdown (wip)

# 04.07.2021 (Dimanche)
* Ajout d'icônes pour le pwa

# 03.07.2021 (Samedi)
* Ajout d'une modal de confirmation réutilisable pour les suppressions
* Refactor de la rechercher de groupe pour utiliser le SearchEngine
* Ajout indicateur groupe privé 
* Fix notification system

# 02.07.2021 
* Ajout du chargement des pages (progressbar dans le top)
* Ajout home page basique
* Ajout meta data dans les groupes
* Ajout de protection sur les routes (redirect to login)
  * ajout de redirection sur la page précédante (redirect dans l'url)

# 01.07.2021
* Ajout de l'option "Terminer pour moi" back et front
* Ajout des observers pour les questions et commentaires

# 30.06.2021
* Adaptation des fils de réponses
* Fix des réactions (problème de concurence)
* Fix problème de composant récursif sur la prod
* Fix des notifications

# 29.06.2021
* Séance avec MM. Grunenwald et Wohlfahrt
  * ordre antéchronologique
  * fils de réponses
* Travail sur le frontend question/réponse

# 28.06.2021
* Ajout du lien entre les réactions back-front
* Ajout du fallback pour l'auth
* Ajout des CRUD backend pour les questions et les commentaires

# 27.06.2021 (Dimanche)
* Reactions CRUD backend 
* Fix affichage des notifications
* Reactions visuel frontend 

# 25.06.2021
* Ajout des accès sans co-optage pour les profs
* Refactor des routes backend pour mettre les notifs comme lues afin de gérer plusieurs notifs en même temps
* Configuration du serveur pour l'utilisation de SSL
  * Ajout certificat (let's encrypt)
  * Changement route nginx
  * ajout cacert.pem pour firebase
  * Configuration paramètres spécifiques vuejs et laravel
* Ajout support PWA


# 23-24.06.2021
* Ajout au niveau du backend de route pour enregistrer les tokens FCM
* Ajout de routes pour récupérer les notifications
* Ajout de routes pour marqué les notifications comme lues
* Enrôlement des tokens au niveau du front end
  * Ajout des configurations spécifique pour FireBase 


# 22.06.2021
* Séance
  * Pourquoi les membres ne sont pas en private dans les modules vuex : car sinon l'annotation MutationAction émet une erreur.
* Modification du lien en :to pour accepter le clique mollette
* Fix problème lors de la suppression d'une tâche
* Fix update search
* Ajout barre  spécifique pour le header (quick search)

# 21.06.2021
* Ajout du check des OUs paramétrables qui définissent les professeurs 
* Configuration des paramètres pour le serveur
* Fix problème de déploiement (problème de droits)
* Fix problème de CSRF sur le serveur


# 19.06.2021 (Samedi)
* Création de la vue de recherche front-end
  * refactoring des components input (SelectMember, SelectState, SelectSubject, SelectType) (pour réutilisation)
* Refactorisation des initials (création d'un filtre pour simplification et standardisation)

# 18.06.2021
* Ajout d'un SearchEngine en backend inspiré de https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db
  * Création d'une interface pour les filtres
  * Création des filtres Subject, Author généraliste 
  * Création de filtre spécifique pour les tasks (Text, Type, isOpen, isPrivate)
  * Généralisation du search -> peut être utilisé avec n'importe quelle query

# 17.06.2021
* Ajout de la vue mois/semaine/jour (v-calendar)
* Fix du start date (rules)

# 16.06.2021
* Test LDAP (problème de connexion, tentative de débogage voir échange issue #238)
* Fix bug de déploiement (gestion des permissions, écrasement de la configuration)

# 15.06.2021
* Ajout de filtres global pour Vuejs
* Fix date format dans l'API
* Ajout de la vue qui affiche une tâche
* Fix bug lorsqu'on supprime une tâche
* Suppression des erreurs sur les inputs lorsqu'ils changent
* Séance avec MM. Grunenwald et Wohlfahrt
  * Evaluer le temps sur les modifications nécessaires pour le markdown
  * Gérer les fichiers sur le long terme

# 14.06.2021 
* Travail sur le formulaire des tâches
* Ajout de notion d'id de tâche relatif au groupe (EDF : équipe de foot -> plusieurs équipe avec un joueur possédant le numéro 1)
* Fix problème de validation rule au niveau du backend sur le nom des sujets
* Ajout des fichiers de traductions pour Laravel (validation rules, etc...)
* Globalement fix des problèmes relatifs aux id utilisées dans les Request Rules
* Fix des seeders après changement en DB
* Ajout d'une page pour gérer les sujets
* Ajout de TinyColor pour gérer la couleur des polices sur les labels (calcul de la luminance pour déterminer si noir ou blanc)
* Ajout de notion firstname et lastname en DB (avant nous n'avions que le fullname)
* fix du bug i18n pour les tests unitaires voir [référence1](http://blog.ingeniouscontraptions.com/2020/12/10/integrer-vue-i18n-dans-les-tests-jest-dun-projet-vue-js/) et [référence2](https://github.com/storybookjs/storybook/tree/master/addons/storyshots/storyshots-core)


# 13.06.2021 (CI/CD) (Dimanche)
* Déploiement 
  * Configuration du serveur
    * Installation de nginx
    * Installation de php, composer, npm, mariadb-server
    * Installation du runners github (en tant que service)
    * Configuration de la DB
    * Configuration de Nginx
    * voir la [page de déploiement](../CI-CD-Caravel-2.0) pour plus de détails
  * Configuration des github actions
    * Reprise des yaml existants pour les test sur MySQL et SQLite avec Laravel
    * Création d'un yaml pour le build/run de VueJS
    * Création d'un yaml pour le déploiement sur le serveur
      * Gestion de la configuration de l'environnement
      * Gestion des uploads
  
# 12.06.2021 (Samedi)
* Ajout de l'éditeur markdown
* Tests sur la possibilité d'hack le markdown (documentation très pauvre mais le module est très utilisé https://github.com/hinesboy/mavonEditor 5k stars)
  * Ajout de bouton custom
  * hack sur le drag & drop

# 11.06.2021
* Ajout de dépendance sur moment js pour la gestion des types date
* Ajout d'une Modal-component sujet réutilisable pour l'édition/ajout 
* Ajout d'un component select (autocomplete) généraliste pour choisir un sujet et ou en créer 
* Ajout du formulaire pour la création d'un tâche (WIP)
* Fix du language dans Vuetify
* Ajout d'une page home minimale
* Fix problème de save d'un sujet (mauvaise request dans laravel)
* Ajout de couleur en hex sur les sujets 
* Ajout des tâches lors de la récupération d'un groupe


# 10.06.2021
* Refactor des modules créées 
* Laravel : Ajout de policies pour la promotion d'un membre
* Suppression des tables pivots des données reçu par l'API (inutile)
* Ajout de messages spécifiques (language) pour l'API (api.php)
* Fix Membres des groupes -> requête custom pour récupérer le status des membres par rapport au groupe 
* Fix problème nommage des groupes
* Fix validation des formulaires du groupe (pas possible de changer le leader)
* Ajout des pages
 * Membre du groupe
 * Paramètres du groupe
 * Requête ajout membre
* Fix problème de droit sur la promotion d'un membre en leader

# 09.06.2021 
* Création de modules de gestion des objects
  * Un module par type (TaskModule, SubjectModule, etc...). Ces modules permettent la gestion centralisée des CRUDS (contact avec l'API) sur ces derniers.
    * Une tentative a été faite pour généraliser le processus à tout type de données avec du templating (typescript), l'essaie se présente dans (src/store/modules/abstract/datas.ts) du l'incompatibilité de vuejs et de vuex (à traververs typescript via vuex-class), ce n'est pas possible (les Mutations ne sont pas accessibles aux enfants que ce soit en protected ou public)
* Factorisation du component Avatar + upload, réutilisation dans les settings du group et du profile
* Fix des uploads dans la partie backend (mauvaise utilisation du storage)

# 08.06.2021
* Séance avec MM. Grunenwald et Wohlfahrt
  * Enlever les mdp syncro ldap
  * Déploiement
  * User test
  * Vuex utilisation de vuex module decorator
* Passage de vuex en class avec vuex module decorator
* Header group + tabs 

# 07.06.2021
* Vue création d'un groupe
* Vue Profile basique
* Fix picture back end 
* [Typescript problème de module](https://forum.vuejs.org/t/could-not-find-a-declaration-file-for-third-party-modules-how-to-declare-and-resolve-these-errors/34212/5)

# 04.06.2021
* Page de vue pour la recherche et la demande d'adhésion aux différents groupes
* Syncro des param dans l'url route
  * Permet de récupérer une recherche via une url
  * Travail sur une méthode +/- générique pour qu'elle puisse être utilisée 
* Fix de bug sur le filtre des groupes du backend (passage par une query custom pour avoir des infos spécifique à la table de pivot)
* Ajout de pagination

# 03.06.2021
* Création du composant "header" du site
* Gestion de la connexion/déconnexion
* Ajout de la persistance des données dans le localstorage (vuex-persist)
* Ajout du token sur toutes les requêtes axios

# 02.06.2021
* Login terminé
* Ajout du user en plus du token au niveau du backend
* Problème d'import namespace

# 01.06.2021
* Séance avec MM. Grunenwald, M'Poy et Wohlfahrt
* Ajout localisation vuejs
* Travail sur le login

# 28.05.2021 et 31.05.2021
* Migration des contrôleurs
* Ajout de Policy pour les autorisations
* Ajout générique FileUploadService
* Ajout de classe FormRequest (light controller)
* Prise en compte des commentaires de M. Visinand
* Ajout Localization au niveau du backend (message d'erreur)
* Migration des routes routes
* Init du projet frontend (vuejs)

# 27.05.2021
* Remove socialite login 
* Create docker openLDAP for local test
  * Utilisation d'Apache Directory Studio
* Add LDAP authentitication
  * Laravel Sanctum
  * LdapRecord
  * Sync with database

## Prochaine réunion 
* Demander les informations de connections pour le ldap


# 26.05.2021
* Création des fichiers de migrations pour la DB 
  * Ajustement de la DB
* Création des fichiers de modèle
* Ajustement du seeder

# 25.05.2021
* Mockup : création des pages Calendrier, timeline et stat
* Séance avec MM. Grunenwald, M'Poy et Wohlfahrt
* Ajustement de la DB
* Tri des issues
* Ajout page Stratégie de test

# 22.05.2021
* Création des mockups
  * Group home page
  * Task display page

# 21.05.2021
* Formation Figma
* Demande licence Figma étudiant

# 01.05.2021
* Rework API Swagger
* Ajout notion d'"action" dans la base de donnée

# 27.04.2021
* Séance avec MM. GrunenWald, M'Poy et Wohlfahrt
 * Discussion autour du design de l'API 
 * Revoir les routes, shrink au possible

# 24-25.04.2021
* Définition des routes backend avec Swagger

# 20.04.2021
* Séance avec MM. Grunenwald et Wohlfahrt
  * Revue de la base de donnée
  * Modification et précisions du système de notification
* Modification de la base de donnée suite à la séance
  * Ajout des précisions sur la page wiki

# 18.04.2021
* Modélisation de la base de donnée

# 13.04.2021
* Séance avec MM. Grunenwald et Wohlfahrt
  * Page about : noter les formules utilisées pour les calcules des métriques sur la charge de travail
  * Gestion des statistiques : en features ++ --> pouvoir relier les stats d'une classe à une autre (via un paramètre dans le groupe d'une classe)
* Mail à M. Visinand concernant la gestion des clés primaires composites avec Laravel et Eloquent

# 05.04.2021
* Création de la page "Gestion de la charge de travail" et première ébauche

# 04.04.2021
* Création de la page "Gestion des notifications" et première ébauche
  * Recherche associés sur PWA et Firebase

# 30.03.2021
* Séance avec MM. Grunenwald et Wohlfahrt
   * Etudier la possiblité de faire une introduction de l'application aux nouveaux utilisateurs
      * "Tour guide" avec intro.js, https://shepherdjs.dev/ ou encore https://kamranahmed.info/driver.js/ (à voir aussi celui qui s'intègre le mieux avec VueJs)
      * Créer une tâche par défaut qui contient dans la description des éléments d'utilisation de l'application (lors de la création d'un groupe)
   * Notifications
      * Voir les possibilités de PWA (android, iphone?) 
      * Possibilité de syncro les calendars avec Exchange
      * Voir M. Laoun
   * Système de réactions
      * Les réactions seront anonymes sachant que connaitre l'utilisateur n'a pas de plus value, il faudra informer l'utilisateur que ce vote est anonyme
      * Les utilisateurs pourront mettre plusieurs réactions pour permettre d'affiner la ou les problématiques rencontrées
   * Faire attention au token d'authentification
      * Pas de local Storage, Session Storage s'efface quand l'utilisateur se déconnecte
      * Voir faisabilité : cookie header only
      * Dans l'ensemble pas une priorité, les informations de Caravel n'étant pas sensible sur le principe


# 27.03.2021
* Description du système de réactions
  * Recherche dans la littérature scientifique afin de comprendre les raisons d'un échec à terminer des devoirs
* Description des filtres de recherche

# 25.03.2021
* Update Wiki Rôle et uses case
* Ajout du planning dans le wiki

# 23.03.2021
* Séance avec MM. Grunenwald et Wohlfahrt
  * Définir deux types de groupes :
     * Groupe "classe" -> cooptation pour les élèves, les profs obtiennent l'accès directe (càd sans cooptage)
     * Groupe privé -> cooptation pour tous
  * Historique d'actions visibles par les membres du groupe
  * Vue globale par classe avec les matières sous lesquelles on est abonné
* Update de Rôles et usecases suite à la séance

# 20-21.03.2021
* Ajout description des rôles 
* Création des différents use cases
   * Tâche
   * Notification
   * Sujet
   * Fil de discussion
   * Filtre
   * Groupe

### A discuter : 
* Gestion des rôles modérateurs et administrateur

# 16.03.2021
* Séance avec MM. Grunenwald et Wohlfahrt
  * Présentation du Gantt re-travaillé
  * Présentation test de Swagger avec Zest
  * Validation du CdC

# 13.03.2021
* Création mini POC Vuejs + TypeScript 
* Lecture des articles pour l'inclusion de TypeScript sur vuejs 2
  * [Adopting TypeScript in your Vue.js Application in a sane way](https://medium.com/swlh/adopting-typescript-in-your-vue-js-application-in-a-sane-way-d6bd31757fe5)
  * [Properly typed Vuex Store](https://medium.com/swlh/properly-typed-vuex-stores-427bf4c6a3d1)
  * [Modularizing the logic of your Vue.js application](https://medium.com/swlh/modularizing-the-logic-of-your-vue-js-application-5b920e17c25e)
  * [Data-driven components](https://medium.com/@vinicius0026/data-driven-components-2ab02ccbf204)
  * [Handling data at the edge of your Vue.js application](https://medium.com/@vinicius0026/handling-data-at-the-edge-of-your-vue-js-application-1872782d391a)

# 11.03.2021
* Création du rapport sur word + mise en page initiale
* Formation sur Swagger
  * Test de swagger sur un autre projet web (Zest) https://app.swaggerhub.com/apis-docs/M4n0x/Zest/ALPHA

# 09.03.2021
* Séance avec MM. Grunenwald, M'Poy et Wohlfahrt
* Modification sur du planning (Gantt)
* Informations sur swagger
* Informations sur l'auth LDAP

### Points prochaine séance
* Discussion API Documentation

# 06.03.2021
* Cahier des charges
* Planning (Gantt)

# 02.03.2021
* Séance avec MM. GrunenWald et Wohlfahrt
* Recherche documentaire (Cours)
### PV : 
* Authentification 
  * LDAP
  * voir Nicolas Sommer
  * Détails technique sur les tokens voir Alex
* Clarification sur la gestion des rôles
  * les profs seront des sortes de modérateurs
* Approvisionnement
  * pas un grand intérêt, à mettre de côté pour le moment


# 01.03.2021
* Présentation de Caravel au INF IIE3-a
* Ajout de la roadmap et réorganisation des points

# 23.02.2021
* Séance avec MM. GrunenWald, M'Poy et Wohlfahrt
  * Prise de connaissance du contexte
  * Discussions de points divers
* Recherche sur l'état de l'art
* Ré-organisation du Git pour accueillir le TB