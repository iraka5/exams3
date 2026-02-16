# 🏛️ Application BNGRC - Suivi des Dons aux Sinistrés

## 📋 Installation

### 1. Base de données
1. Ouvrez phpMyAdmin ou votre client MySQL
2. Exécutez le script `database/init.sql` pour créer la base de données
3. La base `4191_4194_4222` sera créée avec des données de test

### 2. Configuration
- La configuration de la base de données est dans `config/config.php`
- Modifiez si nécessaire les paramètres de connexion MySQL

### 3. Server Web
- Placez les fichiers dans votre serveur web (XAMPP/WAMP)
- Accédez à l'application via votre navigateur

## 🚀 URLs de l'Application

### Pages principales (Tâches d'Alintsoa)
- **Accueil** : `http://localhost/exams3/` → Redirige vers le tableau de bord
- **Régions** : `http://localhost/exams3/regions`
- **Villes** : `http://localhost/exams3/villes`
- **Tableau de Bord** : `http://localhost/exams3/tableau-bord`

### Fonctionnalités Régions
- Liste des régions : `/regions`
- Ajouter région : `/regions/create`
- Voir région : `/regions/{id}`
- Modifier région : `/regions/{id}/edit`

### Fonctionnalités Villes
- Liste des villes : `/villes`
- Filtrer par région : `/villes?region_id={id}`
- Ajouter ville : `/villes/create`
- Voir ville : `/villes/{id}`
- Modifier ville : `/villes/{id}/edit`

## 🎯 Fonctionnalités Implémentées (Alintsoa)

✅ **Base de données (30 min)**
- Script SQL complet avec tables : user, regions, ville, besoins, dons, echange
- Données de test incluses
- Relations entre tables correctement définies

✅ **Pages Régions (30 min)**
- Liste des régions avec compteur de villes
- Création, modification, suppression de régions
- Vue détaillée avec villes associées

✅ **Pages Villes (30 min)**
- Liste des villes avec filtrage par région
- Création, modification, suppression de villes
- Vue détaillée avec besoins et dons
- Tableau de bord avec statuts (rouge/orange/vert)

✅ **Adaptation des routes (30 min)**
- Routes FlightPHP complètes pour CRUD régions et villes
- Navigation intégrée dans toutes les pages

✅ **Controllers (40 min)**
- RegionController : gestion complète des régions
- VilleController : gestion complète des villes + tableau de bord
- Logique métier avec validation des données

## 🎨 Design & Interface

- Interface responsive et moderne
- Navigation cohérente sur toutes les pages
- Codes couleur pour le tableau de bord :
  - 🔴 Rouge : Besoins > Dons disponibles
  - 🟡 Orange : Dons partiels (50-99%)
  - 🟢 Vert : Dons suffisants

## 🗄️ Structure de la Base de Données

### Tables créées :
- `regions` : Liste des régions de Madagascar
- `ville` : Villes rattachées aux régions  
- `besoins` : Besoins des sinistrés par ville
- `dons` : Dons reçus par ville
- `echange` : Attribution des dons aux besoins
- `user` : Utilisateurs du système

### Données de test incluses :
- 4 régions (Analamanga, Vakinankaratra, Itasy, Bongolava)
- 6 villes réparties dans les régions
- Exemples de besoins et dons

## ⏱️ Temps de Réalisation

- **Total estimé** : 2h40 (selon planning)
- **Implémenté** : Toutes les fonctionnalités demandées pour Alintsoa

## 🔧 Technologies Utilisées

- **Backend** : PHP 7+ avec FlightPHP Framework
- **Base de données** : MySQL
- **Frontend** : HTML5, CSS3 (design intégré)
- **Serveur** : XAMPP/Apache recommandé