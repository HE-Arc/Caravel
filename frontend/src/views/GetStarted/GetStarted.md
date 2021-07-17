# Guide de démarrage

Table des matières
[toc]

## Rôles
Lors de votre arrivée sur Caravel un compte sera automatiquement créé à la première connexion, une synchronisation avec l'annuaire de l'He-Arc permet de déterminer le rôle de l'utilisateur : étudiant ou professeur.

Le rôle attribué est visible depuis la page profile de l'utilisateur comme sur l'image ci-dessous :
![Page profile](/img/getstarted/user-profile.png)

Ces deux rôles n'ont pas vraiment d'incidence sur l'utilisation courante de Caravel, ils permettent principalement de simplifier l'accès aux groupes pour les professeurs (nous y reviendrons dans la section suivante) ainsi que de mettre en avant le statut des professeurs dans certaines situations.

## Groupe
Les groupes peuvent représenter une classe ou un groupe de travail quelconque dépendamment de la manière dont vous souhaitez partager les informations et avec qui. Nous avons parlé des différents rôles disponibles dans la section précédente, c'est ici qu'ils vont être utile.

Il existe deux types de groupes : 

- Groupe privé
  - Pour entrer de ce type de groupe, un cooptage est nécessaire ainsi il n'est pas possible de voir son contenu sans avoir préalablement été admis dans le groupe
- Groupe de classe 
  - Ce type de groupe est similaire au groupe privé sauf que les professeurs qui souhaitent entrer dans le groupe n'ont pas besoin de cooptage, ils sont automatiquement admis du moment que la demande à été faite. Les étudiants, eux, doivent toujours être admis par cooptage

### Cooptation
Les groupes ne sont pas accessible par défaut, pour voir le contenu d'un groupe il faut préalablement faire une demande d'adhésion. Ce mécanisme passe par un système de cooptage, c'est-à-dire que seul un utilisateur déjà membre du groupe peut faire entrer un nouveau membre :

![Système de cooptage](/img/getstarted/cooptage.png)

Dans la capture ci-dessus on peut voir qu'un nouveau membre veut rejoindre notre groupe, on peut choisir de l'accepter ou de le refuser. Il sera par la suite toujours possible de changer d'avis.

### Création ou recherche d'un groupe
Il possible de créer ou de rejoindre un groupe depuis la barre du haut soit directement en tapant le nom du groupe dans la barre de recherche ou soit en cliquant sur le petit "+" tout à droite

![Ajout et recherche de groupe](/img/getstarted/header.png)

Avant de créer un groupe il est important d'être sûr qu'un autre groupe du même nom n'a pas déjà été créé par quelqu'un d'autre, c'est pour cela que la recherche et la création de groupe ont été conçus sur la même vue afin qu'une recherche soit effectuée dans tous les cas avant la création d'un groupe.

![Recherche de groupe](/img/getstarted/search-group.png)

## Tâche
Les tâches sont séparées en trois types : 

- Devoirs
- CP(test)/Examens
- Projet

Les deux premiers types sont similaires et ne diffèrent que par l'icône qui les représente tandis que pour le dernier, projet, à un champs supplémentaire : une date de début. Contrairement aux autres types qui n'ont qu'une date de rendu, le projet possède deux dates pour déterminer quand le projet débute et quand il se termine.

Voici des exemples des différents types de tâches : 

![Types de tâche](/img/getstarted/task-type.png)

### Création une tâche
Pour créer une tâche il est possible de le faire depuis le bouton d'action dans la barre du haut 

![Ajout de contenu](/img/getstarted/addcontent.png)

ou directement depuis la vue des tâches. Il est aussi possible de cliquer sur un jour dans la vue Calendrier pour directement créer une tâche au jour voulu.

### Réactions
Sur chaque tâche, il est possible de rajouter des réactions qui permettent de donner un feedback sur la tâche, ces réactions sont anonymes. 

La liste exhaustives des réactions :

* ⏲️ Trop long
* 🧠 Trop complexe 
   * manque de compétences
* 🎯 Manque d'informations
   * donnée pas claire
* 📍  Je suis perdu 
   * la préparation en cours n'est pas optimale pour entreprendre l'exercice
* 🤷 Lien avec le cours pas clair 
   * l'intérêt n'est pas clair, pas assez motivée, l'importance du devoir n'est pas comprise par l'étudiant
* 📑 Peu d'intérêt
   * Par exemple pas de feedback, l'étudiant ne voit pas d'intérêt de s'investir

### Question
Il est possible de demander de l'aide aux autres membres du groupe à travers des questions qui peuvent être posées directement sur une tâche, voici un exemple de question : 

![Question](/img/getstarted/question.png)

Une fois la questions posée, les différents membres du groupe pourront répondre à votre question via un jeu de commentaire.

#### Résolution
Lors qu'une question est résolue, il est possible pour l'auteur de la question de marquer la question comme résolue, pour cela il doit se rendre sur le commentaire qui a permis la résolution de la question, un bouton d'action est sur le commentaire et permet de marquer le commentaire comme étant la solution et ainsi de passer l'état de la question en résolu. 

## Charge de travail 
Afin de mieux estimer la charge de travail donnée aux étudiants, un score de charge est calculé par semaine, ce score permet de se rendre compte en un coup œil de la charge actuel et future du travail à fournir.

Les détails du calcul sont les suivants :

`$N_A$` = Nombre de devoirs

`$N_E$` = Nombre de Examens/CP

`$N_{PS}$` = Nombre de projet en cours (qui ne sont pas à rendre)

`$N_{PW}$` = Nombre de projet à rendre

`$C_S$` = Nombre de crédit pour le sujet (cours)

A partir de ces informations, la somme des travaux à effectuer est ensuite multiplié par le nombre de [crédit ECTS](https://fr.wikipedia.org/wiki/Syst%C3%A8me_europ%C3%A9en_de_transfert_et_d%27accumulation_de_cr%C3%A9dits) afin de pondérer l'effort à fournir sur un cours (un cours ayant un nombre de crédit élevé demandera plus d'effort qu'un cours qui en a moins). Le résultat de ce calcul donne le "Week Effort Score" abrégé WES, c'est l'unité qui est utilisée dans Caravel dans les différentes vues disponible. La formule se concrétise comme ce suit : 

Week Effort Score (WES) = `$\sum_{subjects} C_s * (N_E + N_A + N_{PW} + 2 * N_{PS})$`

Il est à noter que lors d'une semaine de rendu de projet, la tâche est comptabilisée deux fois car les projets sont considérés comme des tâches plus conséquentes qui demandent à la fin du travail un peu plus d'implication.

## Explorez !
Vous pouvez maintenant vous promener librement sur l'application, si vous n'avez pas encore de groupe [essayez de créer ou de rechercher un group](/groups) :-)