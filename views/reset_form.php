<?php 
$page_title = "Réinitialisation des données";
include __DIR__ . '/partials/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4><i class="fas fa-exclamation-triangle"></i> Réinitialisation des données</h4>
                    <p class="mb-0">Cette action va remettre les données de base et supprimer toutes les données ajoutées.</p>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Succès !</strong> Les données ont été réinitialisées avec succès.
                            <?php if (isset($_GET['queries'])): ?>
                                <br><small><?= intval($_GET['queries']) ?> requêtes exécutées.</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Erreur !</strong>
                            <?php 
                            switch($_GET['error']) {
                                case 'confirmation':
                                    echo "Veuillez taper exactement 'RESET_DATA' pour confirmer.";
                                    break;
                                case 'execution':
                                    echo "Erreur lors de l'exécution : " . htmlspecialchars($_GET['message'] ?? 'Erreur inconnue');
                                    break;
                                default:
                                    echo "Une erreur est survenue.";
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Statistiques actuelles -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Données actuelles dans le système</h5>
                            <table class="table table-sm">
                                <tr><td>Régions</td><td><strong><?= $stats['regions'] ?></strong></td></tr>
                                <tr><td>Villes</td><td><strong><?= $stats['villes'] ?></strong></td></tr>
                                <tr><td>Besoins</td><td><strong><?= $stats['besoins'] ?></strong></td></tr>
                                <tr><td>Dons</td><td><strong><?= $stats['dons'] ?></strong></td></tr>
                                <tr><td>Échanges</td><td><strong><?= $stats['echanges'] ?></strong></td></tr>
                                <?php if ($stats['achats'] > 0): ?>
                                    <tr><td>Achats</td><td><strong><?= $stats['achats'] ?></strong></td></tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Aperçu des données récentes</h5>
                            
                            <?php if (!empty($besoins_recents)): ?>
                                <h6 class="text-muted">Derniers besoins:</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    <?php foreach (array_slice($besoins_recents, 0, 3) as $besoin): ?>
                                        <li class="list-group-item py-1 px-0">
                                            <small><?= htmlspecialchars($besoin['nom']) ?> 
                                            (<?= htmlspecialchars($besoin['ville_nom']) ?>)</small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            
                            <?php if (!empty($dons_recents)): ?>
                                <h6 class="text-muted">Derniers dons:</h6>
                                <ul class="list-group list-group-flush">
                                    <?php foreach (array_slice($dons_recents, 0, 3) as $don): ?>
                                        <li class="list-group-item py-1 px-0">
                                            <small><?= htmlspecialchars($don['type_don']) ?> 
                                            par <?= htmlspecialchars($don['nom_donneur']) ?>
                                            (<?= htmlspecialchars($don['ville_nom']) ?>)</small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Avertissement -->
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> Attention !</h5>
                        <p>Cette opération va :</p>
                        <ul>
                            <li>Supprimer tous les <strong>besoins, dons, échanges et achats</strong> actuels</li>
                            <li>Restaurer les <strong>données de base</strong> (besoins et dons d'exemple)</li>
                            <li>Conserver les <strong>régions et villes</strong> existantes</li>  
                            <li>Conserver les <strong>paramètres de configuration</strong></li>
                        </ul>
                        <p class="mb-0"><strong>Cette action est irréversible !</strong></p>
                    </div>
                    
                    <!-- Formulaire de confirmation -->
                    <form method="POST" action="<?= BASE_URL ?>/reset-data" onsubmit="return confirmReset()" id="resetForm">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="confirm" class="form-label">
                                    Pour confirmer, tapez exactement : <code>RESET_DATA</code>
                                </label>
                                <input 
                                    type="text" 
                                    id="confirm" 
                                    name="confirm" 
                                    class="form-control" 
                                    placeholder="RESET_DATA"
                                    required
                                >
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-danger btn-lg me-2" id="resetBtn" disabled>
                                    <i class="fas fa-redo"></i> Réinitialiser les données
                                </button>
                                <a href="<?= BASE_URL ?>/tableau-bord" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmReset() {
    const confirmation = document.getElementById('confirm').value;
    if (confirmation !== 'RESET_DATA') {
        alert('Veuillez taper exactement "RESET_DATA" pour confirmer.');
        return false;
    }
    
    return confirm('Êtes-vous absolument sûr de vouloir réinitialiser toutes les données ? Cette action est irréversible !');
}

// Activer le bouton seulement si la confirmation est correcte
document.getElementById('confirm').addEventListener('input', function() {
    const resetBtn = document.getElementById('resetBtn');
    if (this.value === 'RESET_DATA') {
        resetBtn.disabled = false;
        resetBtn.classList.remove('btn-danger');
        resetBtn.classList.add('btn-warning');
    } else {
        resetBtn.disabled = true;
        resetBtn.classList.remove('btn-warning');
        resetBtn.classList.add('btn-danger');
    }
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>