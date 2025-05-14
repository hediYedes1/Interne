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

## Table des Matières

- [Installation](#installation)
- [Utilisation](#utilisation)
- [Contributions](#contributions)
- [Mots-clés et Topics](#mots-clés-et-topics)

## Installation

1. Clonez le repository :
```bash
git clone https://github.com/hediYedes1/Interne
cd Interne
2. Si vous utilisez XAMPP :
* Placez le projet dans le dossier htdocs (XAMPP)
* Démarrez Apache et MySQL depuis l'interface de XAMPP
* Démarrez le serveur Symfony :
 ```bash
symfony serve
* Accédez au projet via : https://127.0.0.1:8000/base1

## Utilisation
### Installation de PHP
Pour utiliser ce projet, vous devez installer PHP, Voici les etapes:
1. Télécharger PHP à partir du site officiel : [ PHP-Téléchargement] ( https://www.php.net/download.php).
2. Installez PHP en suivant les instructions spécifiques à votre systeme d'exploitation:
- Pour **Windows**, vous pouvez utiliser [XAMPP] (https://apachefriends.org/fr/index.html)
3. Vérifiez l'installation de PHP en exécutant la commande suivante dans votre terminal:
```bash
php -v

##Contributions 
Nous remercions tous ceux qui ont contribué à ce projet !
### Contributeurs
Les personnes suivantes ont contribué à ce projet en ajoutant des fonctionnalité, en corrigeant des bugs ou en améliorant la documentation: 
-[medamine20) (https://github.com/medamine20) -Gestion de l'utilisateur et ses fonctionnalités
-[zainebt002] (https://github.com/zainebt002) -Gestion des offres d'emploi et projets
- [HediYedes1] (https://github.com/hediYedes1) - Gestion des interviews et test techniques
-[naghamelaskri] (https://github.com/naghamelaskri) -Gestion des entreprises
- [hibakhouloud-baadech] (https://github.com/hibakhouloud-baadech) -Gestion d'hébergement
-[abelhoula] (https://github.com/abelhoula) -Gestion de publications
Si vous souhaitez contribuer, suivez les étapes ci-dessous pour faire un **fork**, créer une nouvelle branche et soumettre une **pull request**.
### Comment contributer ?
1. **Fork le projet**: Allez sur la page Github du projet et cliquez sur le bouton **Fork** dans le coin supérieur droit pour créer une copie du projet dans votre propre compte Github.
2 **Clonez votre fork**: Clonez le fork sur votre machine locale:
```bash
git clone https://github.com/hediYedes1/Interne 
cd Interne
3. Créez une nouvelle branche
```bash
git checkout -b nomdubranche
4. Committer aprés modifications pour le enregistrer
```bash
git add . 
git commit -m 'Ajout de la fonctionnalité x'
5. Pousser vos modifications
```bash
git push origin nomdubranche
4. Soumettez une Pull Request
```bash
git pull origin nomdubranche

## mots-clés-et-topics
Symfony 6 , PHP 8.1+ , Plateforme RH, Recrutement, Gestion des offres d’emploi, Gestion des interviews, Tests techniques, Publication d’articles, Twig / Doctrine ORM / Bootstrap 5, Mantis Admin Template
