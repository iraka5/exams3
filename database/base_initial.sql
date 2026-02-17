

1. **Table user**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - nom VARCHAR(100) NOT NULL
   - password VARCHAR(255) NOT NULL
   - email VARCHAR(100) UNIQUE NOT NULL
   - created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

2. **Table regions**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - nom VARCHAR(100) NOT NULL UNIQUE
   - created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

3. **Table ville**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - id_regions INT NOT NULL (FOREIGN KEY REFERENCES regions.id)
   - nom VARCHAR(100) NOT NULL
   - created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

4. **Table besoins**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - nom VARCHAR(100) NOT NULL
   - nombre DECIMAL(10,2) NOT NULL
   - id_ville INT NOT NULL (FOREIGN KEY REFERENCES ville.id)
   - created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   - **AJOUT** : prix_unitaire DECIMAL(10,2) DEFAULT 0 AFTER nombre
   - **AJOUT** : type_besoin VARCHAR(50) DEFAULT 'nature' AFTER nom

5. **Table dons**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - nom_donneur VARCHAR(100) NOT NULL
   - type_don VARCHAR(100) NOT NULL
   - nombre_don DECIMAL(10,2) NOT NULL
   - id_ville INT NOT NULL (FOREIGN KEY REFERENCES ville.id)
   - created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   - **AJOUT** : statut VARCHAR(20) DEFAULT 'disponible' AFTER id_ville

6. **Table echange**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - id_besoins INT NOT NULL (FOREIGN KEY REFERENCES besoins.id)
   - id_don INT NOT NULL (FOREIGN KEY REFERENCES dons.id)
   - nombre_echangee DECIMAL(10,2) NOT NULL
   - date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP

7. **Table achats**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - id_besoin INT NOT NULL (FOREIGN KEY REFERENCES besoins.id)
   - quantite DECIMAL(10,2) NOT NULL
   - prix_unitaire DECIMAL(10,2) NOT NULL
   - montant DECIMAL(10,2) NOT NULL
   - date_achat DATETIME NOT NULL
   - statut VARCHAR(50) DEFAULT 'En cours'

8. **Table ventes**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - id_don INT NOT NULL (FOREIGN KEY REFERENCES dons.id)
   - prix_original DECIMAL(10,2) NOT NULL
   - prix_vente DECIMAL(10,2) NOT NULL
   - taux_applique DECIMAL(5,2) NOT NULL
   - date_vente DATETIME NOT NULL
   - type_don VARCHAR(50) NOT NULL
   - ville_nom VARCHAR(100) NOT NULL
   - ville_id INT NOT NULL (FOREIGN KEY REFERENCES ville.id)
   - quantite INT NOT NULL
   - nom_donneur VARCHAR(255) NOT NULL

9. **Table parametres**
   - id INT AUTO_INCREMENT PRIMARY KEY
   - cle VARCHAR(50) UNIQUE NOT NULL
   - valeur TEXT NOT NULL
   - description TEXT
   - date_maj TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

---

## INSERTIONS DE DONNEES

### Regions
- Analamanga
- Vakinankaratra
- Itasy
- Bongolava

### Villes
- Antananarivo (id_regions: 1)
- Ambohidratrimo (id_regions: 1)
- Antsirabe (id_regions: 2)
- Betafo (id_regions: 2)
- Miarinarivo (id_regions: 3)
- Tsiroanomandidy (id_regions: 4)

### Besoins initiaux
- Riz, 500, id_ville: 1
- Huile, 100, id_ville: 1
- Tôle, 200, id_ville: 2
- Clou, 50, id_ville: 2
- Argent, 1000000, id_ville: 3

 Dons initiaux
- Jean Dupont, Riz, 300, id_ville: 1
- Marie Claire, Huile, 80, id_ville: 1
- ONG Solidarité, Tôle, 150, id_ville: 2
- Entreprise ABC, Argent, 500000, id_ville: 3



 Parametres
 taux_diminution_vente = 10 (valeur par défaut)




ALTER TABLE user ADD role** ENUM('admin','user') NOT NULL DEFAULT 'user'
ALTER TABLE besoins ADD COLUMN prix_unitaire** DECIMAL(10,2) DEFAULT 0 AFTER nombre
ALTER TABLE besoins ADD COLUMN type_besoin** VARCHAR(50) DEFAULT 'nature' AFTER nom
ALTER TABLE dons ADD COLUMN statut** VARCHAR(20) DEFAULT 'disponible' AFTER id_ville