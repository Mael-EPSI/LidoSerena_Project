# 📌 Lido Serena - Système de Gestion des Commandes et des Stocks

## 📖 Introduction
Le restaurant de plage **Lido Serena** souhaite moderniser son système de prise de commande afin d'éviter les erreurs et améliorer l'efficacité du service. Cette application permet aux serveurs de prendre les commandes directement via une **tablette**, d'envoyer les commandes en cuisine et de gérer les paiements des clients. 

Une interface administrateur permet de **modifier les plats, menus et boissons** proposés à la carte, tandis qu'un **tableau de bord** aide la direction à suivre les tendances et optimiser la gestion du restaurant.


---

## 📥 Guide d'Installation

### Prérequis
- Server local
- Web
- Git
- **Du temps**

### Étapes d'installation

1. **Cloner le projet**
- Créer un dossier dans le répertoire `www` de WAMP
```bash
git clone https://github.com/Mael-EPSI/LidoSerena_Project.git
cd LidoSerena_Project
```


2. **Configuration de la base de données**
- Ouvrir phpMyAdmin (http://localhost/phpmyadmin)
- Créer une nouvelle base de données nommée "lidoserena"
- Importer le fichier `lidoserena.sql` fourni


3. **Si besoin modifier Info connexion BDD**
- Aller dans les fichiers: 
  - api/bd.php
  - rassou/admin/index.php
  - rassou/admin/graphique.php

- Modifier les Users et mots de passe si besoin


4. **Vérification de l'installation**
- Accéder à l'application via: http://localhost/LidoSerena_Project/lidoserena
- Interface admin: http://localhost/LidoSerena_Project/lidoserena/rassou/admin/index.php
- Interface Serveur: http://localhost/LidoSerena_Project/lidoserena/nathan/tablette.html
- Interface Cuisine: http://localhost/LidoSerena_Project/lidoserena/hanine/cuisine.html


---

## 🎯 Fonctionnalités

### 1️⃣ Application de commande & paiement (Serveurs)
- Sélection d'une table
- Ajout de produits à une commande (plats, boissons, menus)
- Consultation des commandes en cours
- Gestion des paiements (simulation)
- Reception Notification

### 2️⃣ Interface administrateur
- Gestion des plats, boissons et menus 
- Ajout / modification / suppression de produits
- Définition des menus avec contraintes (ex: un menu "Pizza + Boisson" ne peut contenir que des pizzas)
- Consultation d'un **tableau de bord** avec des graphiques interactifs :
  - Plats les plus commandés (Pie Chart)
  - Jours les plus fréquentés (Bar Chart)
  - Addition moyenne par personne

### 3️⃣ Application cuisine
- Affichage des commandes en cours
- Notification aux serveurs lorsque les plats sont prêts
- Possibilité de marquer les commandes comme terminées

---

## 🛠️ Technologies Utilisées
| Composant              | Technologie |
|------------------------|-------------|
| **Backend**           | PHP     |
| **Base de données**  | MySQL       |
| **Frontend Admin**    | HTML CSS Php  |
| **Application Serveurs** | HTML CSS |
| **Application Cuisine** | HTML CSS   |

---

## 📂 Architecture du Projet
```
LidoSerena/

├── RenduFinal.md
├── config.js

├── api/
  ├── .htaccess
  ├── db.php
  ├── envoyer_commande.php
  ├── get_commande.php
  ├── get_menus.php
  ├── get_notification.php
  ├── get_tout_produit.php
  ├── marquer_commande_pret.php
  ├── marquer_lu.php
  ├── marquer_produit_pret.php
  ├── notification.php
  ├── NotificationSystem.php
  ├── payer.php
  ├── produits.php
  └── status.php

├── hanine/
  └── cuisine.html

├── nathan/
  ├── tablette.css
  ├── tablette.html
  └── tablette.js

└── rassou/
  ├── login.php
  ├── admin/
  │   ├── index.php
  │   ├── login.html
  │   └── style.css
  └── graph/
      ├── graphique.php
      ├── script.js
      ├── styles.css
      └── test.html
```


---

## 🛠️ API Endpoints

### 📌 Gestion des Commandes
| Méthode | Endpoint                     | Description |
|---------|------------------------------|-------------|
| `POST`  | `/envoyer_commande.php`     | Créer/mettre à jour une commande |
| `GET`   | `/get_commande.php`         | Récupérer les commandes en cours |
| `POST`  | `/payer.php`                | Marquer une commande comme payée |
| `POST`  | `/marquer_commande_pret.php`| Marquer une commande comme prête |

### 📌 Gestion des Produits et Menus
| Méthode | Endpoint               | Description |
|---------|------------------------|-------------|
| `GET`   | `/produits.php`       | Liste des produits |
| `POST`  | `/produits.php`       | Ajouter un produit |
| `GET`   | `/get_menus.php`      | Liste des menus avec leurs catégories |
| `GET`   | `/get_tout_produit.php`| Liste détaillée des commandes en cours |

### 📌 Gestion des Notifications
| Méthode | Endpoint                    | Description |
|---------|----------------------------|-------------|
| `GET`   | `/get_notification.php`    | Récupérer les notifications non lues |
| `POST`  | `/marquer_lu.php`         | Marquer une notification comme lue |
| `POST`  | `/marquer_produit_pret.php`| Signaler qu'un produit est prêt |

Tous les endpoints retournent des réponses au format JSON avec la structure suivante :
```json
{
    "success": true/false,
    "message": "Message de réponse",
    "data": { /* Données spécifiques à l'endpoint */ }
}
```

---

## 🗂️ Planification & Suivi
La gestion du projet a été réalisée avec **Notion**.
- Division des taches par personnes
- Estimation du temps

Lien vers le Notion : [Notion du projet](https://www.notion.so/1982b74476ac80e1a2a1fa55a15f18c9?v=1982b74476ac817abba6000cd4b42d08&pvs=4)

---

## 🚧 Difficultés Rencontrées

### 1️⃣ Gestion des notifications en temps réel
- Faire le systeme de notification entre Tablette et Cuisine

### 2️⃣ Synchronisation des données
- Connection a la Base de Donnée lorsque elle etait hébergé, apres de multituple essaies, de temps et quelque solutions trouver a certain problème. Nous avons toujours eu un problème avec la connexion en Mysql disant, il fallait obligatoirement donner l'adresse IP public a l'ajouter a la WhiteListe meme en utilisant les caractères générique.

### 3️⃣ Interface Administrateur
- Affichage des graphs
- Gestion des menus / Produits 

---

## 📜 Conclusion
Ce projet répond à un besoin réel de digitalisation dans la restauration en proposant un système moderne et efficace. Il offre une meilleure gestion des commandes, des stocks et de l'analyse de performance.

Des évolutions futures pourraient inclure :
- Un module de réservation de tables
- Un système de gestion des clients fidèles
- Une interface mobile pour les clients
