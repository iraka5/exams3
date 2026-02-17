# 📁 Dossier Public - BNGRC

Ce dossier contient tous les assets publics de l'application BNGRC (Bureau National de Gestion des Risques et Catastrophes).

## 📋 Structure

```
public/
├── css/
│   └── styles.css          # Styles CSS globaux et framework CSS personnalisé
├── js/
│   ├── app.js             # Fonctions JavaScript communes (utilitaires, AJAX, notifications)
│   └── dashboard.js       # Fonctions spécifiques au Dashboard V2 avec mise à jour temps réel
├── images/                # Images, logos, icônes de l'application
├── assets/                # Autres ressources (polices, documents, etc.)
└── README.md             # Ce fichier
```

## 🎨 CSS Framework (styles.css)

Le fichier `styles.css` fournit un framework CSS complet avec :

### Variables CSS Personnalisées
- **Couleurs** : Palette cohérente avec couleurs primaires, secondaires, d'état
- **Espacements** : Système d'espacement standardisé
- **Typographie** : Tailles de police et hiérarchie typographique
- **Ombres et bordures** : Effets visuels cohérents

### Composants Prêts à l'Emploi
- **Boutons** : `.btn`, `.btn-primary`, `.btn-success`, `.btn-warning`, `.btn-danger`
- **Cartes** : `.card`, `.card-header`, `.card-body`, `.card-footer`
- **Formulaires** : `.form-group`, `.form-label`, `.form-control`
- **Tableaux** : `.table`, `.table-container`
- **Alertes** : `.alert`, `.alert-success`, `.alert-warning`, `.alert-danger`
- **Badges** : `.badge`, `.badge-success`, `.badge-warning`
- **Barres de progression** : `.progress`, `.progress-bar`

### Classes Utilitaires
- **Texte** : `.text-center`, `.text-primary`, `.text-success`, etc.
- **Espacement** : `.mb-0` à `.mb-5`, `.mt-0` à `.mt-5`
- **Animation** : `.fade-in`, `.slide-up`, `.loading`

## ⚙️ JavaScript Modules

### app.js - Fonctions Communes

#### Configuration Globale
```javascript
const BNGRC = {
    baseUrl: '/exams3-main/exams3',
    apiUrl: '/exams3-main/exams3/api',
    // ...
};
```

#### Utilitaires de Formatage
```javascript
Format.currency(1500000)    // "1 500 000 Ar"
Format.number(15000)        // "15 000"
Format.date("2024-02-17")   // "17 février 2024"
Format.percent(85.7)        // "85.7%"
```

#### Système de Notifications
```javascript
Notifications.success("Achat créé avec succès !");
Notifications.error("Erreur lors de l'enregistrement");
Notifications.warning("Fonds insuffisants");
Notifications.info("Mise à jour disponible");
```

#### Utilitaires AJAX
```javascript
// Requête GET
const data = await Ajax.get('/api/totaux');

// Requête POST
const result = await Ajax.post('/api/achats', {
    montant: 15000,
    ville_id: 1
});
```

#### Validation de Formulaires
```javascript
const validation = Forms.validate('#monFormulaire');
if (validation.isValid) {
    // Traitement du formulaire
}
```

#### Boîtes de Confirmation
```javascript
const confirmed = await Confirm.show("Supprimer cet élément ?");
if (confirmed) {
    // Procéder à la suppression
}

// Confirmation de suppression spécialisée
await Confirm.delete("cette région", () => {
    // Logique de suppression
});
```

### dashboard.js - Dashboard V2

#### Classe Dashboard
```javascript
const dashboard = new Dashboard();
```

**Fonctionnalités :**
- Mise à jour automatique toutes les 30 secondes
- Gestion de la visibilité de la page (économie de ressources)
- Animation des mises à jour
- Gestion des alertes en temps réel
- Export des données (en développement)
- Mode plein écran

#### Vérification des Fonds
```javascript
const suffisant = await FondsChecker.verifierEtNotifier(montant);
if (suffisant) {
    // Procéder à l'achat
}
```

## 🖼️ Images et Assets

### Structure Recommandée
```
images/
├── logos/
│   ├── logo-bngrc.png
│   └── logo-madagascar.png
├── icons/
│   ├── regions.svg
│   ├── villes.svg
│   ├── besoins.svg
│   └── dons.svg
└── backgrounds/
    └── hero-bg.jpg
```

### Assets Divers
```
assets/
├── fonts/           # Polices personnalisées
├── documents/       # PDFs, guides utilisateur
└── data/           # Fichiers JSON de configuration
```

## 🔧 Utilisation dans les Templates

### Inclusion des CSS
```html
<link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
```

### Inclusion des JavaScript
```html
<!-- Fonctions communes (toujours en premier) -->
<script src="/exams3-main/exams3/public/js/app.js"></script>

<!-- Dashboard spécifique (seulement sur les pages dashboard) -->
<script src="/exams3-main/exams3/public/js/dashboard.js"></script>
```

### Exemple d'Utilisation
```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BNGRC - Gestion</title>
    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🏛️ BNGRC</h1>
            <p>Gestion des dons aux sinistrés</p>
        </div>
    </div>
    
    <nav class="nav">
        <div class="container">
            <a href="/" class="active">Accueil</a>
            <a href="/regions">Régions</a>
            <a href="/villes">Villes</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-primary" onclick="Notifications.success('Test !')">
                    Tester les notifications
                </button>
            </div>
        </div>
    </div>
    
    <script src="/exams3-main/exams3/public/js/app.js"></script>
</body>
</html>
```

## 🚀 Fonctionnalités Avancées

### Auto-actualisation du Dashboard
- Mise à jour automatique toutes les 30s
- Pause intelligent quand l'onglet n'est pas visible
- Animation des changements de valeurs
- Gestion des erreurs réseau

### Système de Notifications Toast
- 4 types : success, error, warning, info
- Fermeture automatique configurable
- Animations d'entrée/sortie
- Empilage des notifications

### Validation de Formulaires
- Validation en temps réel
- Messages d'erreur contextuels
- Styles visuels pour les champs en erreur
- Support des validations personnalisées

### Utilitaires DOM
- Sélecteurs jQuery-like légers
- Manipulation de classes
- Affichage/masquage d'éléments

## 📱 Responsive Design

Le framework CSS inclut des breakpoints responsive :
- Mobile : < 768px
- Tablette : 768px - 1024px
- Desktop : > 1024px

Toutes les classes et composants sont responsive par défaut.

## 🎨 Personnalisation

### Modifier les Couleurs
Editez les variables CSS dans `styles.css` :
```css
:root {
  --primary-color: #votre-couleur;
  --success-color: #votre-couleur;
  /* ... */
}
```

### Ajouter des Composants
Créez de nouveaux composants en suivant la convention BEM :
```css
.mon-composant {
  /* Styles de base */
}

.mon-composant--variant {
  /* Variante */
}

.mon-composant__element {
  /* Élément du composant */
}
```

---

💡 **Note :** Ce dossier public centralise tous les assets front-end pour une meilleure organisation, performance et maintenance de l'application BNGRC.