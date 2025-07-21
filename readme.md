## EcoRide – ECF Développeur Web & Web Mobile 2025

## Description
EcoRide est une plateforme de **covoiturage écologique** développée dans le cadre de mon ECF DWWM. Elle permet aux conducteurs de proposer des trajets, et aux passagers d'y participer grâce à un système de crédits.

## 🔧 Installation
1. Cloner le projet :
```bash

   git clone https://github.com/assita-dev/ECORIDE-ECF-2025.git
## Base de données

Le fichier `ecoride_db.sql` contient la structure complète de la base de données utilisée par l'application EcoRide.

### Importation de la base
1. Ouvrir phpMyAdmin ou un terminal MySQL
2. Créer une base de données nommée `ecoride_db`
3. Importer le fichier via l'interface ou avec la commande suivante :

```bash
mysql -u root -p ecoride_db < ecoride_db.sql
Lancer XAMPP / MAMP et placer le dossier ecoride dans le dossier htdocs.

Créer une base de données nommée ecoride_db, puis importer le fichier ecoride_db.sql fourni à la racine du projet.

Configurer le fichier config.php :
$host = 'localhost';
$dbname = 'ecoride_db';
$user = 'root';
$pass = '';
Accéder au projet via :
http://localhost/ecoride

🌐 Déploiement en ligne (Heroku)
L'application est déployée ici :
🔗 https://guarded-atoll-16593-06cc68215d2f.herokuapp.com

Configuration config.php
php
Copier
Modifier
$host = 'nom_serveur_heroku'; // ex. : 'us-cdbr-east-06.cleardb.net'
$dbname = 'nom_base_heroku'; // commence souvent par heroku_***
$user = 'identifiant_utilisateur';
$pass = 'mot_de_passe';
Ces données sont fournies par JawsDB MySQL dans le tableau de bord Heroku (onglet "Resources").

##  Structure du projet
arduino
Copier
Modifier
ecoride/
│
├── admin/
│   └── accueil_admin.php ...
├── conducteur/
│   └── espace_conducteur.php ...
├── employes/
│   └── accueil_employe.php ...
├── utilisateur/
│   └── espace_utilisateur.php ...
├── css/
├── js/
├── images-ecoride/
├── uploads/
├── config.php
├── connexion.php
├── inscription.php
├── deconnexion.php
├── README.md
└── ecocide_db.sql
 Technologies utilisées
 Langage : PHP 8.2

 Base de données : MySQL (JawsDB)

Front-end : HTML5, CSS3, Bootstrap 5.3

 Déploiement : Heroku

 Outils : GitHub, phpMyAdmin, VS Code

Accès test (identifiants par rôle)
Rôle	Email	Mot de passe
Admin	admin@mail.com	azerty
Employé	employe@mail.com	azerty
Conducteur	jujudelpech@gmail.com	azerty
Passager	essai@mail.com	azerty

 Infos complémentaires
Le projet est organisé par User Stories (US1 à US13).

La gestion de projet a été faite sur Trello (voir lien dans la documentation projet PDF).

Le code est commenté et organisé pour une lecture claire.

© 2025 – Projet de formation | École Studi | Covoiturage éthique et écologique 

yaml
Copier
Modifier

---

Souhaites-tu que je te prépare aussi une **version Word ou PDF rapide** à joindre en plus dans ton dossier, avec les liens cliquables ?  
On peut aussi faire ensemble :
- La **charte graphique**
- Le **manuel d’utilisation PDF**
- La **documentation technique**

Dis-moi par quoi tu veux enchaîner 






Demander à ChatGPT
