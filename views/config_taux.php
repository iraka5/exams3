<?php 
$page_title = "Configuration du taux de diminution";
include __DIR__ . '/partials/header.php';

// Récupérer le taux actuel
$taux_actuel = 10; // valeur par défaut
foreach ($parametres as $param) {
    if ($param['cle'] === 'taux_diminution_vente') {
        $taux_actuel = (float)$param['valeur'];
        break;
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-cogs"></i> Configuration du système de vente</h4>
                    <p class="mb-0">Gérer le pourcentage de diminution appliqué lors de la vente d'articles</p>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Succès !</strong> Le taux de diminution a été mis à jour.
                            <?php if (isset($_GET['ancien']) && isset($_GET['nouveau'])): ?>
                                <br><small>Ancien taux : <?= htmlspecialchars($_GET['ancien']) ?>% → Nouveau taux : <?= htmlspecialchars($_GET['nouveau']) ?>%</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Erreur !</strong>
                            <?php 
                            switch($_GET['error']) {
                                case 'taux_invalide':
                                    echo "Le taux doit être un nombre entre 0 et 100.";
                                    break;
                                case 'erreur_sauvegarde':
                                    echo "Erreur lors de la sauvegarde des paramètres.";
                                    break;
                                default:
                                    echo "Une erreur est survenue.";
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <!-- Configuration du taux -->
                        <div class="col-md-6">
                            <h5>Configuration du taux</h5>
                            
                            <form method="POST" action="<?= BASE_URL ?>/config-taux" id="configForm">
                                <div class="mb-3">
                                    <label for="taux_diminution" class="form-label">
                                        Taux de diminution lors de la vente (%)
                                    </label>
                                    <div class="input-group">
                                        <input 
                                            type="number" 
                                            id="taux_diminution" 
                                            name="taux_diminution" 
                                            class="form-control" 
                                            value="<?= $taux_actuel ?>"
                                            min="0" 
                                            max="100" 
                                            step="0.1"
                                            required
                                        >
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="form-text">
                                        Pourcentage appliqué automatiquement lors de la conversion d'un don en argent.
                                        <br><strong>Exemple :</strong> Un article de 100 000 Ar avec <?= $taux_actuel ?>% de diminution sera vendu à <?= number_format(100000 * (1 - $taux_actuel/100), 0, ',', ' ') ?> Ar.
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Sauvegarder
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetToDefault()">
                                        <i class="fas fa-undo"></i> Valeur par défaut (10%)
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Simulateur temps réel -->
                            <div class="mt-4">
                                <h6>Simulateur de prix</h6>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Prix original</span>
                                    <input type="number" id="prix_simulation" class="form-control" value="100000" step="1000">
                                    <span class="input-group-text">Ar</span>
                                </div>
                                <div class="alert alert-info mb-0" id="resultat_simulation">
                                    Prix de vente : <strong><?= number_format(100000 * (1 - $taux_actuel/100), 0, ',', ' ') ?> Ar</strong>
                                    <br>Perte : <strong><?= number_format(100000 * $taux_actuel/100, 0, ',', ' ') ?> Ar</strong>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistiques et historique -->
                        <div class="col-md-6">
                            <h5>Statistiques des ventes</h5>
                            
                            <?php if ($stats_vente && $stats_vente['total_ventes'] > 0): ?>
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="h4 text-primary"><?= $stats_vente['total_ventes'] ?></div>
                                                <small>Articles vendus</small>
                                            </div>
                                            <div class="col-6">
                                                <div class="h4 text-success"><?= number_format($stats_vente['valeur_vente_totale'], 0, ',', ' ') ?> Ar</div>
                                                <small>Valeur totale récupérée</small>
                                            </div>
                                        </div>
                                        <div class="row text-center mt-2">
                                            <div class="col-6">
                                                <div class="h6 text-warning"><?= number_format($stats_vente['valeur_originale_totale'] - $stats_vente['valeur_vente_totale'], 0, ',', ' ') ?> Ar</div>
                                                <small>Perte totale</small>
                                            </div>
                                            <div class="col-6">
                                                <div class="h6 text-info"><?= number_format($stats_vente['taux_moyen_realise'], 1) ?>%</div>
                                                <small>Taux moyen appliqué</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (!empty($dernieres_ventes)): ?>
                                    <h6>Dernières ventes</h6>
                                    <div class="table-responsive" style="max-height: 300px;">
                                        <table class="table table-sm">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th>Article</th>
                                                    <th>Prix orig.</th>
                                                    <th>Prix vente</th>
                                                    <th>Taux</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($dernieres_ventes as $vente): ?>
                                                    <tr>
                                                        <td>
                                                            <small><?= htmlspecialchars($vente['type_don']) ?></small>
                                                            <br><small class="text-muted"><?= htmlspecialchars($vente['ville_nom']) ?></small>
                                                        </td>
                                                        <td><?= number_format($vente['prix_original'], 0, ',', ' ') ?></td>
                                                        <td><?= number_format($vente['prix_vente'], 0, ',', ' ') ?></td>
                                                        <td>
                                                            <span class="badge bg-<?= $vente['taux_applique'] > 15 ? 'danger' : ($vente['taux_applique'] > 10 ? 'warning' : 'success') ?>">
                                                                <?= number_format($vente['taux_applique'], 1) ?>%
                                                            </span>
                                                        </td>
                                                        <td><small><?= date('d/m H:i', strtotime($vente['date_vente'])) ?></small></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                                    <p>Aucune vente enregistrée pour le moment.</p>
                                    <small>Les statistiques apparaîtront après les premières ventes d'articles.</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Actions rapides -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-between">
                                <div>
                                    <a href="<?= BASE_URL ?>/dons" class="btn btn-outline-primary">
                                        <i class="fas fa-gifts"></i> Voir les dons
                                    </a>
                                    <a href="<?= BASE_URL ?>/reset-data" class="btn btn-outline-warning">
                                        <i class="fas fa-redo"></i> Réinitialiser données
                                    </a>
                                </div>
                                <a href="<?= BASE_URL ?>/tableau-bord" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetToDefault() {
    document.getElementById('taux_diminution').value = 10;
    updateSimulation();
}

function updateSimulation() {
    const prix = parseFloat(document.getElementById('prix_simulation').value) || 0;
    const taux = parseFloat(document.getElementById('taux_diminution').value) || 0;
    
    const prixVente = prix * (1 - taux/100);
    const perte = prix * taux/100;
    
    document.getElementById('resultat_simulation').innerHTML = 
        `Prix de vente : <strong>${prixVente.toLocaleString('fr-FR')} Ar</strong><br>` +
        `Perte : <strong>${perte.toLocaleString('fr-FR')} Ar</strong>`;
}

// Mettre à jour la simulation en temps réel
document.getElementById('prix_simulation').addEventListener('input', updateSimulation);
document.getElementById('taux_diminution').addEventListener('input', updateSimulation);

// Validation du formulaire
document.getElementById('configForm').addEventListener('submit', function(e) {
    const taux = parseFloat(document.getElementById('taux_diminution').value);
    if (isNaN(taux) || taux < 0 || taux > 100) {
        e.preventDefault();
        alert('Le taux doit être un nombre entre 0 et 100.');
        return false;
    }
    
    if (taux > 50) {
        if (!confirm(`Attention ! Un taux de ${taux}% est très élevé. Continuer ?`)) {
            e.preventDefault();
            return false;
        }
    }
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>