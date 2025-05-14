# Interne

## Description

**Interne** est une application web de gestion des projets RH.

### Objectif :
- Permettre une gestion complète et centralisée des processus RH pour différents types d'utilisateurs : candidats, RH et administrateurs.

### Problème résolu :
- Digitalisation et automatisation des processus liés aux ressources humaines, au recrutement et à la gestion des projets au sein d’une organisation.

### Principales fonctionnalités :
- Gestion des utilisateurs (candidat, RH, admin)
- Gestion des entreprises (branches et départements)
- Gestion des offres d’emploi et projets
- Gestion des interviews et tests techniques
- Gestion de l’hébergement et des partenariats
- Gestion des publications

### Contexte du Projet
Ce projet a été établi comme projet d'étude à **Esprit Engineering School** pour l'année scolaire **2024/2025**. Il s'inscrit dans le cadre de la formation en ingénierie et vise à appliquer des concepts avancés de développement web et de gestion de base de données.
## Table des Matières

- [Installation](#installation)
- [Utilisation](#utilisation)
- [Contributions](#contributions)
- [Mots-clés](#mots-clés)

## Installation

1. Clonez le repository :
```bash
git clone https://github.com/hediYedes1/Interne
cd Interne
```
2. Si vous utilisez XAMPP :
* Placez le projet dans le dossier htdocs (XAMPP)
* Démarrez Apache et MySQL depuis l'interface de XAMPP
* Démarrez le serveur Symfony :
 ```bash
symfony serve
```
* Accédez au projet via : https://127.0.0.1:8000/base1

## Utilisation
### Installation de MySQL
Symfony utilise une base de données pour stocker les données de l'application. MySQL est l’un des systèmes les plus couramment utilisés.
1. Téléchargez MySQL depuis le site officiel : [MySQL - Téléchargement](https://dev.mysql.com/downloads/)
2. Installez MySQL en suivant les instructions pour votre système d’exploitation.
3. Une fois installé, vérifiez que MySQL fonctionne correctement en exécutant la commande suivante dans votre terminal :
```bash
mysql -u root -p
```
### Installation de XAMPP
XAMPP est un environnement de développement Apache simple à installer, qui inclut PHP, MySQL et phpMyAdmin. 
1. Téléchargez XAMPP depuis le site officiel : [XAMPP - Téléchargement](https://www.apachefriends.org/fr/index.html)
2. Installez XAMPP en suivant les instructions d’installation.
3. Lancez le **XAMPP Control Panel** et démarrez les modules **Apache** et **MySQL**.
4. Vous pouvez accéder à phpMyAdmin via : [http://localhost/phpmyadmin](http://localhost/phpmyadmin)

### Installation de PHP
Pour utiliser ce projet, vous devez installer PHP, Voici les etapes:
1. Télécharger PHP à partir du site officiel : [ PHP-Téléchargement] ( https://www.php.net/download.php).
2. Installez PHP en suivant les instructions spécifiques à votre systeme d'exploitation:
- Pour **Windows**, vous pouvez utiliser [XAMPP] (https://apachefriends.org/fr/index.html)
3. Vérifiez l'installation de PHP en exécutant la commande suivante dans votre terminal:
```bash
php -v
```

###  Installation de Composer
Composer est un gestionnaire de dépendances indispensable pour Symfony.
1. Téléchargez Composer depuis le site officiel : [Composer - Téléchargement](https://getcomposer.org/download/)
2. Suivez les instructions d’installation selon votre système d’exploitation (Windows, macOS, Linux).
3. Une fois installé, vérifiez que Composer fonctionne correctement en exécutant la commande suivante dans votre terminal :
```bash
composer -v
```
### Installation de Symfony CLI
La Symfony CLI est un outil en ligne de commande permettant de créer, exécuter et gérer facilement des projets Symfony.
1. Téléchargez la Symfony CLI depuis le site officiel : [Symfony CLI - Installation](https://symfony.com/download)
2. Suivez les instructions d’installation selon votre système d’exploitation (Windows, macOS, Linux).
3. Vérifiez que l'installation est correcte en exécutant la commande suivante dans votre terminal :
```bash
symfony -v
```

## Contributions 
Nous remercions tous ceux qui ont contribué à ce projet !
### Contributeurs
Les personnes suivantes ont contribué à ce projet en ajoutant des fonctionnalité, en corrigeant des bugs ou en améliorant la documentation: 

- [medamine20) (https://github.com/medamine20) -Gestion de l'utilisateur et ses fonctionnalités.
- [zainebt002] (https://github.com/zainebt002) -Gestion des offres d'emploi et projets.
- [HediYedes1] (https://github.com/hediYedes1) - Gestion des interviews et test techniques.
- [naghamelaskri] (https://github.com/naghamelaskri) -Gestion des entreprises.
- [hibakhouloud-baadech] (https://github.com/hibakhouloud-baadech) -Gestion d'hébergement.
- [abelhoula] (https://github.com/abelhoula) -Gestion de publications.

Si vous souhaitez contribuer, suivez les étapes ci-dessous pour faire un **fork**, créer une nouvelle branche et soumettre une **pull request**.
### Comment contributer ?
1. **Fork le projet**: Allez sur la page Github du projet et cliquez sur le bouton **Fork** dans le coin supérieur droit pour créer une copie du projet dans votre propre compte Github.
2. **Clonez votre fork**: Clonez le fork sur votre machine locale:
```bash
git clone https://github.com/hediYedes1/Interne 
cd Interne
```
3. **Créez une nouvelle branche**
```bash
git checkout -b nomdubranche
```
4. **Committer aprés modifications pour le enregistrer**
```bash
git add . 
git commit -m 'Ajout de la fonctionnalité x'
```
5. **Pousser vos modifications**
```bash
git push origin nomdubranche
```
4. **Soumettez une Pull Request**
```bash
git pull origin nomdubranche
```
## Mots-clés
- Symfony 6  
- PHP 8.1+
- Bundle
- Service
- Doctrine
- QR Code
- Symfony Mailer
- Symfony Notifier
- RH 
- Doctrine ORM  
- Bootstrap 5  
- Mantis Admin Template   
- Composer  
- Symfony CLI  
- MySQL

