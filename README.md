#Les abeilles de Tallyos
## Contexte
Test technique pour le compte de Tallyos.
Le but de l'exercice est de créer une application web (front & back) pour la gestion de ruches connectées.

## Installation
1. Créer un base de données mysql afin d'accueillir les données
2. Dérouler le script `installation.sql`. Il créera les tables et les remplira avec des données afin de tester l'application.
3. Renseigner les informations de connexion dans le script `dbConnect.php`
4. Servir le dossier `public` à l'aide de votre serveur favori (nginx, apache, ...)

## Travail effectué
J'ai choisi de ne pas utiliser de framework pour le back et j'utilise Boostrap 5 et Datatables en front, afin de rester au plus proche du rendu de la maquette.
J'ai aussi choisi de partir sur Bootstrap 5 au lieu du 4 utilisé dans la maquette, afin de découvrir les changements de la nouvelle version, au prix de quelques différences par rapport à la maquette.

### Structure
J'ai choisi de partir sur un modèle SPA (Single Page App), car je n'ai jamais eu l'occasion de travailler avec jusqu'à maintenant.
Le badge dans l'onglet *Ruche* indique le nombre de ruche dans la base de données. Il est servi en PHP au chargement de la page, puis manipulé en Javascript au fur et à mesure des ajouts/suppressions.
Les onglets s'activent au changement de page.

### Page Ruches
La page présente un tableau listant les ruches présentes en base.
Le traitement des données est effectué en front. Les CRUD sur les ruches passent par l'API dédiée

### Page Informations
La page présente un tableau listant les données collectées.
Le tri, le filtre et la pagination sont effectués en backend, afin de faciliter le traitement d'un volume de données important.

### Back-end
J'ai choisi d'utiliser PHP orienté objet pour gérer les modèles de données.
J'ai aussi créé les routes d'API en PHP vanilla.

### Bonus: Page d'accueil
J'ai créé la page d'accueil telle que vu dans la maquette.

### Temps passé :
- 2h de mise en place de l'environnement:
  Installation de WSL2, Git et MySql, puis changement de plan et installation de Wamp et Git pour Windows.
  Installation de PHPStorm.
  Planification et création de l'arborescence des dossiers.- 
- 3h pour la création de la base du projet: 
  Première route et modèle en back, Architecture SPA en front, installation de Boostrap et Datatables
- 3h pour la vue Ruche: Fonctionnement de Datatables en mode client-side, Moodales CRUD pour les ruches
- 4h pour la vue Informations: Fonctionnement de Datatables en client-side, puis choix technique de passer en server-side processing. 
  Travail sur l'API pour gérer les paramètres de filtre et tri nécessaires à Datatables.
  Plusieurs problèmes rencontrés sur le format d'échange qui est plutôt mal documenté.
- 3h pour la vue Accueil: Uniquement du travail front, les formulaires ne sont reliés à rien. La partie Reactive a été particulièrement soignée en compensation.
- 1h pour la création des données SQL, afin d'avoir des données crédibles
- 1h pour la rédaction du compte rendu