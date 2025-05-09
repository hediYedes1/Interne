# 🧑💼 Interne – Plateforme RH Symfony

Une application web de gestion RH développée avec Symfony, permettant aux utilisateurs de gérer les candidatures, les offres, les entreprises, les entretiens, les hébergements, les publications et plus encore.
## 📚 Table des matières

- [📝 Description](#-description)
- [🚀 Fonctionnalités](#-fonctionnalités)
- [⚙️ Installation](#️-installation)
- [🧪 Utilisation](#-utilisation)
- [🤝 Contribution](#-contribution)
- [🪪 Licence](#-licence)
- [🏷️ Mots-Clés et Topics](#️-mots-clés-et-topics)
## 📝 Description

Ce projet est une plateforme RH modulaire dédiée à la gestion des processus de recrutement, des utilisateurs, des offres, des entreprises, des tests techniques, des publications et des partenariats. Il s'adresse à trois types d'utilisateurs : administrateur, RH, et candidat.

### Objectif :
Faciliter le suivi et la gestion des ressources humaines au sein d’une organisation grâce à une interface moderne et des outils de matching automatisés.

## 🚀 Fonctionnalités

- 🔐 Authentification avec rôles : Admin, RH, Candidat
- 👥 Gestion des utilisateurs :  
  - amin : utilisateurs & rôles  
  - nagham : entreprises, branches et départements  
  - zaineb : offres d’emploi  
  - hedi : entretiens et tests techniques  
  - khouloud : hébergements et partenariats  
  - ahmed : publications  
- 📊 Matching CV/offres basé sur la similarité
- 📰 Gestion de contenu et publications internes
- 🧠 Tests techniques associés aux entretiens
- 🏢 Structure d’entreprise hiérarchique

## ⚙️ Installation

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/votre-utilisateur/Interne.git
   cd Interne
   git checkout main3
2.	Installez les dépendances PHP et Node.js :
bash
CopierModifier
composer install
npm install
3.	Configurez le fichier .env :
bash
CopierModifier
cp .env .env.local
# Modifier les variables d’environnement (ex: DB, MAILER, etc.)
4.	Créez et migrez la base de données :
bash
CopierModifier
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
5.	Compilez les assets :
bash
CopierModifier
npm run dev
6.	Lancez le serveur :
bash
CopierModifier
symfony server:start
________________________________________
🧪 Utilisation
L’application fonctionne avec :
•	PHP ≥ 8.1
•	Symfony CLI ≥ 5.4
•	Node.js ≥ 16
•	Composer ≥ 2
Accès au site en local :
bash
CopierModifier
http://localhost:8000
Comptes test (exemples à adapter si nécessaire) :
Utilisateur	Rôle	Email	Mot de passe
amin	User/Admin	amin@exemple.com	password
nagham	RH	nagham@entreprise.com	password
zaineb	RH	zaineb@exemple.com	password
________________________________________
🤝 Contribution
Contributions bienvenues !
1.	Fork le projet
2.	Crée une branche : git checkout -b feature/ma-fonction
3.	Commit tes modifications : git commit -am 'ajout nouvelle fonctionnalité'
4.	Push vers la branche : git push origin feature/ma-fonction
5.	Crée une Pull Request vers main3
________________________________________
🪪 Licence
Projet sous licence MIT. Voir le fichier LICENSE pour plus d’informations.
________________________________________
Mots clés et topics
•	Symfony 6
•	PHP 8.1+
•	Plateforme RH
•	Recrutement
•	Gestion des offres d’emploi
•	Matching CV / Offres
•	Gestion des interviews
•	Tests techniques
•	Publication d’articles
•	Hébergement / Partenariats
•	Twig / Doctrine ORM / Bootstrap 5
•	Mantis Admin Template
________________________________________
Développé dans le cadre du projet universitaire 2024–2025 🎓

