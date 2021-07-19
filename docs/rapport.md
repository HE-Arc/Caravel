## Résumé

## abstract

\newpage

# Introduction

## Contexte de développement

* Visual Code
* Docker

\newpage

# Cahier des charges

Cette section décrit le cadre initial du projet ainsi que ses buts visés à la fin du développement

## Situation initiale

Ce projet fait suite à un travail réalisé par trois étudiants dans le cadre du cours de Développement Web. Dans le cadre de ce projet les trois étudiants ont réalisé une plateforme Web permettant aux élèves d'inscrire de manière collaborative les différents tâches (devoirs/CP/Projet) à faire.

## Buts visés

Avec Caravel l'idée est que les devoirs soient gérés par les membres d'une classe, chaque membre de la classe a donc la possibilité de renseigner un devoir sur la plateforme. Si celui-ci manque de précision, le professeur ou un élève peut y apporter une modification en tout temps afin d'éclaircir son contenu. Dans cette optique autant les professeurs que les élèves peuvent être une source de devoirs ce qui change la dynamique des élèves par rapport aux applications habituelles.

Un des but de Caravel est de réunir tous les devoirs en un seul endroit, avec l'apparition des différents outils comme Moodle, Teams, l'intranet ou encore les dossiers partagés, il est parfois très difficile de savoir où chercher l'information concernant un devoir. L'idée est donc de permettre aux membres d'une classe de réunir les informations pertinentes en un seul endroit afin de gagner du temps. La contribution collaborative permet de faciliter la transition à l'utilisation de Caravel, si par exemple un prof continue de distribuer ses exercices sous format PDF par des canaux différents, il suffit qu'un seul élève soit au courant de l'information pour la transmettre aux autres en les ajoutant sur Caravel.

De plus Caravel pourra permettre d'ouvrir des fils de discussion au sein d'une tâche afin de demander de l'aide ou des éclaircissement sur une tâche, l'idée est de permettre aux élèves de s'entraider et de partager en un seul lieu les différentes informations (questions/réponses).

Enfin, l'outil devra permettre d'avoir une vue de la charge de travail d'une classe afin de placer au mieux les prochains CP/devoirs, également il sera possible pour un élève de réagir sur les différentes tâches pour alerter les professeurs sur un devoir qui serait inadapté (temps de travail, complexité, manque d'informations, etc...)

Succinctement les buts sont les suivants :

* Placer au mieux les devoirs et CP pour lisser la charge et savoir si la charge de travail est correcte
* Réunir en un seul endroit toutes les informations relatives au travail qui doit être effectuer par l'étudiant et ainsi éviter l'utilisation de différents canaux
* Aider les élèves à mieux s'organiser en ayant une place qui réuni toutes les informations nécessaires à l'exécution correcte de leur travail
* Proposer une plateforme de collaboration entre étudiants et professeurs sur des tâches via des échanges questions/réponses

### Contraintes éventuelles

Pas de contraintes

### Priorisation des tâches

Une priorisation des tâches a été effectuées enfin de déterminé les éléments importants du projet. Cette priorisation est basée sur la @MoScoW.

#### Must have

* Traduction en français
  * Prévoir la possibilité d'ajouter d'autres langues facilement (localisation)
* Filtres sur les tâches dans les différentes vues
  * Filtres par titre, par sujet, par auteur
* Rôles étudiant/professeur
  * Les profs pourront ajouter des tâches, elles ne seront alors pas modifiables par les élèves
* Ajout d'une vue en mois style Outlook
  * Permet de naviguer sur d'anciennes semaines
* Séparation front/back end
  * Passage en Vue.js, Laravel en backend
* Analyse & intégration des feedbacks reçus pendant le semestre de printemps
* Réactions aux différentes tâches
  * Pouvoir réagir une tâche (trop long, trop complexe, etc...)
* Ajout de notion de crédit sur les sujets

#### Should have

* Système de notifications
  * par ex. une tâche a été ajoutée ou modifiée, réponse à un commentaire
* Authentification interne (LDAP)
* Système de sujets(fil) dans les commentaires au niveau des tâches
  * Possibilité d'éditer les commentaires
* Ajout de la représentation de la charge de travail

#### Could have

* PWA
  * Possibilité de notification push
* Possibilité pour un élève de mettre une tâche comme terminée pour lui uniquement
* Partage des tâches privées avec certains membres
* Enrichir l'éditeur de texte
  * ajouter des mentions type \@membre, #idTache
* Gestion des paramètres de notifications
* Onglet "Mes tâches" pour qu'un utilisateur puisse retrouver facilement ses tâches créées
* Contrôle édition d'une tâche simultanée (simpliste)
  * Empêcher la soumissions d'un formulaire si la date de modification a changé entre temps
* Approvisionnement automatique des groupes de classes (étudier la faisabilité)
  * Préremplis avec certains professeurs comme membre
* Vue Semestrielle (voir Gantt)
* Une vue récap/statistique globale pour un groupe

#### Won't have

* Pas de gestion d'édition collaborative simultanée (web socket, style google docs)
* Pas de gestion des tâches transversales (sur plusieurs groupes)

\newpage

# Analyse

La section suivante décrit la partie d'analyse et de conception qui a été faite en amont pendant le dernier semestre de troisième année.

## Problématique

La problématique est divisée en deux parties, une partie concernant les professeurs et une autre les élèves.

### Pour les professeurs

#### Problème 1

Placer au mieux les devoirs et CP pour lisser la charge et savoir si la charge de travail est correcte

##### Solution

Elle repose sur deux propositions, dans un premier temps fournir une vue qui permette au mieux de placer un CP ou un devoir (tout en discutant avec les élèves). Dans un deuxième temps il sera possible pour un élève de réagir sur un devoir à l'aide de réactions qui permettront d'évaluer un devoir (trop long/trop complexe/etc...). Les professeurs pourront alors voir si un devoir a occasionné beaucoup de réactions et donc s'il était adapté ou non. Ces réactions pourront entrainer par la suite des discussions avec les élèves pour améliorer le devoir et à fortiori la participation des élèves.

#### Problème 2

Les profs distribuent souvent des consignes de manière orale ou alors sur des supports spécifiques, avec les différents outils disponibles, rendre les informations sur toutes les différentes plateformes peut être éreintant

##### Solution

Possibilité de déléguer cette tâche aux élèves (ex. le devoir peut être donné de manière orale et être rapporter par un des élèves), étant plus nombreux il est plus facile pour les élèves de centraliser les informations qui leur sont nécessaires pour un devoir plutôt que de laisser cette action à une seule et unique personne.

### Pour les élèves

#### Problème

Les devoirs sont notés et éparpillés sur plusieurs supports (physique ou digital) parmi les élèves, on se retrouve souvent avec un élève qui détient une partie de l'information et non toute l'information. Les élèves ont donc du mal à visualiser tous les devoirs en cours donnés par les professeurs. Il est alors compliqué de prévoir sa charge de travail avec des informations incomplètes

##### Solution

Apporter une vue centralisée dans laquelle il est facile de visualiser les tâches à faire, l'accès collaboratif permet de réunir les fragments d'informations détenus par chaque élève en un seul endroit afin d'obtenir une information complète.

## Etat de l'art

Cette section décrit l'actuel état des applications dans le domaine de gestion des tâches liés à des études, elle décrit notamment quelques tests effectués sur certaines de ces applications et ultimement le positionnement de Caravel par rapport à l'état de l'art.

### Applications testées

* Google Classroom (https://classroom.google.com/)
* MyHomework (https://myhomeworkapp.com/home)
* MyStudyLife (https://app.mystudylife.com/)

#### Google Classroom

L'outil Google Classroom est très axé cours, les professeurs créent leur cours et les élèves suivent les cours qui leurs sont nécessaires (principe universitaire où les élèves peuvent choisir leur cours). L'idée est que les ajouts tels que les devoirs soient introduits uniquement par le professeur. La notion de classe à proprement parler n'existe pas.

![Google Classroom : Liste des types de devoirs](assets/108860975-06675180-75ef-11eb-816e-bf46913694e6.png){width=250}

![Google Classroom : Gestion de liste d'élèves](assets/108861533-9b6a4a80-75ef-11eb-914c-976bde9a6695.png){width=250}

![Google Classroom : Permet de faire des annonces](assets/108861613-b5a42880-75ef-11eb-9eba-af0017bea98e.png){width=400}

![Google Classroom : Vue limité à la semaine](assets/108861942-0d429400-75f0-11eb-97ac-7555cf34c23d.png){width=400}

Fonctionnalités intéressantes :

* Des annonces peuvent être faites pour la classe
* Il y a une notion de groupes, les devoirs peuvent être distribué à toute la classe ou alors à un groupe plus restreint
* **Les devoirs sont synchronisés directement avec l'agenda Google**

#### MyHomework

MyHomework est une WebApp qui permet la gestion des devoirs personnels.

![MyHomework : Ajout d'un devoir](assets/108901513-404e4d00-761b-11eb-8924-6487a2137068.png){width=300}

![MyHomework : Vue centrale simple et efficace](assets/108901691-7986bd00-761b-11eb-9ea9-803365636ba8.png){width=400}

![MyHomework : Vue calendrier (mensuelle)](assets/108901732-87d4d900-761b-11eb-82e4-f8c2be4f5d42.png){width=400}

![MyHomework : Vue semaine](assets/108901820-a3d87a80-761b-11eb-9dc1-5b3bf28ff36b.png){width=400}

L'application rend le service minimum (ajout de devoirs) mais ne permet pas la collaboration, il y a beaucoup d'options qui se relèvent pas très utiles dans la majorité des cas.

Fonctionnalités intéressantes :

* Affichage en semaine concise
* Possibilité de rajouter des rappels
* Ajout d'une notion "terminé"

### MyStudyLife

MyStudyLife est une WebApp (disponible aussi sur mobile) qui permet la gestion des horaires de cours ainsi que des tâches à effectuer.

![MyStudyLife : Gestion des tâches](assets/108906096-cb7e1180-7620-11eb-9f05-3580c77d39b3.png){width=400}

![MyStudyLife : Gestion des différents sujets](assets/108906133-d638a680-7620-11eb-8034-b9398d023f81.png){width=300}

![MyStudyLife : Gestion des cours](assets/108906275-fe280a00-7620-11eb-94ea-af56b245a805.png){width=400}

Fonctionnalités intéressantes :

* Gestion des horaires de cours très flexible
* Permet de lier des tâches à un cours et d'effectuer des rappels avant le début du cours
* Permet de gérer les vacances
* Permet de gérer les cours sur différents semestres
* Les notifications push disponibles avec l'application sont très appréciable
* Possède une version web et mobile

### Synthèse

Le marché est partagé en deux catégories : les applications de gestion de devoirs dans lesquelles c'est l'étudiant qui entre les devoirs et l'autre catégorie où ce sont les professeurs qui ajoutent les devoirs.

Pour la première catégorie, il existe actuellement beaucoup d'applications (surtout mobile) qui permettent à un étudiant de gérer ses devoirs mais celles-ci ne permettent pas la collaboration entre étudiants, en outre il n'est pas possible de partager les devoirs avec quelqu'un d'autre.

Pour la seconde catégorie, il existe quelques applications qui permettent à un professeur d'entrer des devoirs pour un groupe d'élèves, ceux-ci peuvent donc alors tous consulter les devoirs. Le problème repose sur le fait que seul le professeur peut entrer les devoirs, cela limite la marge de manœuvre des élèves ainsi que leur implication. Dans ces applications un professeur est souvent responsable de sa matière et ne peut donc pas forcément rajouter des devoirs dans une autre matière (voir Google Classroom).

### Positionnement de Caravel

Dans Caravel l'idée est de se positionner entre les deux mondes, les devoirs seront gérées par les membres d'une classe, chaque membre de la classe a donc la possibilité de renseigner un devoir sur la plateforme. Si celui-ci manque de précision, le professeur ou un élève peut y apporter une modification en tout temps afin d'éclaircir son contenu. Dans cette optique autant les profs que les élèves peuvent être une source de devoir ce qui change la dynamique des élèves par rapport aux applications habituelles.

Un des souhaits de Caravel est aussi de réunir tous les devoirs en un seul endroit, avec l'apparition des différents outils comme Moodle, Teams, l'intranet ou encore les dossiers partagés, il est parfois très difficile de savoir où chercher l'information concernant un devoir. L'idée est donc de permettre aux membres de la classe de réunir les informations pertinentes en un seul endroit afin de gagner du temps.

De plus Caravel pourra permettre d'ouvrir des fils de discussion au sein d'une tâche afin de demander de l'aide ou des éclaircissement sur un détail spécifique, l'idée est de permettre aux élèves de s'entraider sur une question et de partager en un seul lieu les différentes informations sur un devoir, évidemment les professeurs peuvent aussi répondre aux différents fils de discussion.

Enfin, l'outil permet aux élèves de toujours être à jour quant aux tâches à faire, en effet il arrive souvent qu'un élève oublie de noter un devoir ou de détailler certains éléments, Caravel permet de réunir les connaissances des différents élèves et des professeurs pour obtenir une base solide d'informations.

## Conception

### Rôles et use cases

#### Rôles fonctionnels

Il y a un seul rôle fonctionnel qui donc celui d'administrateur du groupe, en général il s'agit du créateur du groupe mais ce droit peut être transmis.

##### Administrateur du groupe

Permet de gérer les paramètres du groupe (suppression, renommage, etc...) ainsi que de gérer les différents membres du groupes (suppression d'un membre).

#### Rôles sémantiques

Les rôles suivants n'auront pas de droits particuliers le but de l'application étant de permettre la collaboration directe entre les deux différents rôles, cependant les actions des professeurs seront mises en avant typiquement dans les fils de discussions. De plus les professeurs seront admis d'office dans les groupes dit de "classe" lors d'une demande d'adhésion.

Les deux rôles seront donc les suivants :

* Professeur
* Elève
  * Rôle par défaut

### Use cases

Pour la bonne compréhension des schémas qui vont suivre, il tenir compte du fait qu'un "Utilisateur" est un "Membre du groupe". De plus l'utilisateur est aussi considéré comme "auteur".

#### Tâche

Use cases concernant les différentes actions possibles sur les tâches.

![Use case : gestion des tâches](assets/112157598-a8c32680-8be7-11eb-85b6-6a07eaa12ff4.png)

#### Sujet

Use cases concernant les différentes actions possibles sur les sujets.

![Use case : gestion des sujets](assets/111871279-5e446e80-8989-11eb-996f-35bf6b6afe45.png)

#### Fil de discussion

Use cases concernant les différentes actions possibles sur les fils de discussion.

![Use case : gestion des fils de discussion](assets/111871717-efb4e000-898b-11eb-94bb-bb041c166cf8.png)

#### Notification

Les paramètres de notifications seront globaux et non spécifique à une tâche ou groupe

![Use case : gestion des notifications](assets/111880242-927c5700-89aa-11eb-9156-614bebfd27a0.png)

#### Filtre

Use cases concernant les différentes actions possibles avec les filtres.

![Use case : gestion des filtres](assets/111908970-96b47d00-8a5b-11eb-8eb6-e88bdbc9e12b.png)

#### Login

Use cases concernant les différentes actions possibles lors de l'authentification.

![Use case : gestion du login](assets/111917134-a0031100-8a7e-11eb-9688-709244b89570.png)

*Les différents schémas ont été produits avec l'outil "Visual Paradigm community edition"*

### Gestion des filtres

Les filtres permettent de filtrer la liste des tâches disponibles. Ils permettent de retrouver une tâche aisément et de retrouver une tâche passée ce qui n'est pas possible sur l'ancienne de version de Caravel.

#### Liste des filtres

Voici une liste exhaustives des filtres qui sont disponibles dans l'application :

* Par sujet
* Par auteur
* Par texte (texte entré par l'utilisateur, recherche dans le titre ainsi que la description)
* Par type de tâches (projet, devoir, CP/examen)
* Par état (clos/ouvert -> passé/futur)
* Par privé/publique

Il sera possible en tout temps de réinitialiser les filtres appliqués pour retrouver l'affichage standard.

### Système de réactions

Le système de réactions sur les tâches a pour but de signaler la présence d'un problème sur cette dernière. L'idée derrière ces réactions est de donner une direction sur la réflexion à entreprendre pour évaluer la problématique d'une tâche dans cette optique les réactions ont pour but de juger de manière qualitative (en terme méthodologique) une tâche.

Pour ce faire nous allons définir une base de réactions qui sera la même sur toutes les tâches et que les élèves pourront utiliser. Il est important que cette base soit commune à toutes les tâches afin que les élèves puissent bien appréhender correctement leur utilisation. En effet des réactions spécifiques à chaque sujet ou groupe demanderait un effort d'assimilation trop conséquent et placerait l'élève dans une situation d'incertitude quant au choix de la réaction ce qui serait contre productif. Le système doit rester simple et pouvoir être assimiler facilement.

La liste exhaustives des réactions :

* ![](assets/20210719_161303_image.png){width=20} Trop long
* ![](assets/20210719_161954_image.png){width=20} Trop complexe
  * manque de compétences
* ![](assets/20210719_162236_image.png){width=20} Manque d'informations
  * donnée pas claire
* ![](assets/20210719_162253_image.png){width=20} Je suis perdu
  * la préparation en cours n'est pas optimale pour entreprendre l'exercice
* ![](assets/20210719_162314_image.png){width=20} Lien avec le cours pas clair
  * l'intérêt n'est pas clair, pas assez motivée, l'importance du devoir n'est pas comprise par l'étudiant
* ![](assets/20210719_162336_image.png){width=20} Peu d'intérêt
  * Par exemple pas de feedback, l'étudiant ne voit pas d'intérêt de s'investir

Plusieurs références ont été utilisées pour déterminer ces réactions :

* Un article écrit dans le American Journal of Engineering Education (AJEE)  @reaction1
* Ainsi que deux autres articles @reaction2 et @reaction3 disponibles en ligne

#### Participation aux réactions

Afin de pousser les utilisateurs à réagir sur les différentes tâches, un système de trigger pourra être mis en place, en substance, il s'agit de regarder les tâches terminées dans un certains laps de temps très court (1-2 jours) selon un taux de probabilité défini : notifier l'utilisateur afin qu'il réagisse à une tâche, la réaction n'est pas obligatoire.

#### Anonymisation

La question s'est posée quant à l'anonymisation des résultats, une réflexion a été portée en ce sens : le fait d'anonymiser les résultats n'apporte pas de désavantage tandis que l'inverse peut freiner les utilisateurs à donner leur avis. Le choix s'est donc porter sur des réactions anonymes.

### Système de notifications

La construction des notifications au niveau de Laravel sera basée sur le système de notification issue de la [documentation officiel](https://laravel.com/docs/8.x/notifications)

#### Canaux de distribution

Les différents canaux de distribution visés, sont :

* PWA / interne à l'application
* Email

#### Déclenchement des notifications

La liste exhaustive des actions qui peuvent déclencher une notification :

* Ajout d'une tâche
* Modification d'une tâche
* Suppression d'une tâche
* Ajout d'une question dans une tâche
* Ajout d'un commentaire si abonné ou si auteur
  * (par défaut si un utilisateur répond à une question ou s'il est auteur, il devient automatiquement abonné)
* Demande d'ajout au groupe
* Accepté dans un groupe
* Refusé d'un groupe

Les différentes notifications seront paramétrable depuis le compte de l'utilisateur.

![Maquette des paramètres de notifications](assets/113505354-86fa6580-953e-11eb-8eaf-ce3290dca0f9.png){width=350}

#### Référence pour l'implémentation

* Intégration de PWA avec Vue.js, @notif1.
* Intégration de Firebase Cloud Message avec Laravel et Vue.js, @notif2.
* Documentation officiel de Firebase Cloud Message, @notif3.

### Gestion de la charge de travail

Afin de mieux estimer la charge de travail chaque branche accueillera un nouveau paramètre, le nombre de crédits ETCS (un crédit représente une charge de travail d'environ 25 à 30 heures de travail). Ces crédits servent de critère de pondération pour les différentes branches.

#### Indicateurs

A partir de ces données une cotation **par semaine** est créée : le Work Load Score (WLS). Il s'agit d'un ratio basé sur un autre indicateur, le "Week Effort Score" (WES) et de sa médiane définie par les semaines déjà effectuées.

Les détails des calculs sont donnés par les formules suivantes :

$$N_A = Nombre\ de\ devoirs$$
$$N_E = Nombre\ de\ Examens\ ou\ CP$$
$$N_{PS} = Nombre\ de\ projet\ en\ cours\ (qui\ ne\ sont\ pas\ à\ rendre)$$
$$N_{PW} = Nombre\ de\ projet\ à\ rendre$$
$$C_S = Nombre\ de\ crédit\ pour\ le\ sujet\ (cours)$$

$$Week\ Effort\ Score\ (WES) = \sum_{subjects} C_s * (N_E + N_A + N_{PW} + 2 * N_{PS})$$

$$Work\ Load\ Score (WLS) = \frac{WES}{\widetilde{WES}}$$

#### Comptabilisation des projets

Les projets sont calculés de manières différentes car on comptabilise un projet sur lequel on doit travailler mais qui n'est pas à rendre cette semaine et un projet qui est à rendre dans dans le courant de la semaine, ce qui engendre en général plus de travail.

#### Normalisation

Le nombre de crédit n'est pas normalisé car tous les cours ne seront pas forcément présents en tout temps et donc il n'est pas possible d'avoir une normalisation linéaire si des sujets viennent s'ajouter au fur et à mesure (dans le cadre où ces informations ne sont pas calculées en temps réel)

#### Gestion des extremums

Le score de certaines semaines risque de poser des problèmes, il faut donc éviter les extremums afin d'avoir une tendance qui soit plus cohérente. Pour éviter ça, la médiane des semaines est utilisée afin d'évaluer si une semaine est plus ou moins chargée

\newpage

### Modélisation de la base de données

![Modélisation de la base de données](assets/117077549-9e449280-ad38-11eb-9a7e-5beaba01432c.png)

\newpage

### Système d'authentification

## Définitions des routes

swagger

## Maquettes

## Planning

voir annexe

## Méthodologie de travail

Gitlab, git flow

\newpage

# Développement

## DevOps CI/CD

## Configuration de production

## Firebase Cloud Messaging

## Authentification

## Frontend

`typescript`

### Vuex

### Pagination

### Localisation

### Notifications

### Composants

#### Pagination

#### Inputs

### Filtres

### Gestion des erreurs Axios

### Problème de réactivité

Vue.set(...)

### Gestion du chargement

## Backend

### Policies

### Validation des requêtes

`FormRequest`

### Localisation

### Notifications

`Observer`

### Moteur de recherche

### Gestion de clés locales

# User tests

## Scénario

## Résultats

\newpage

# Améliorations

\newpage

# Conclusion

\newpage

# Annexes

\newpage
