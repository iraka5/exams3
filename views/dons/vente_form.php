<?php
$error = $_GET['error'] ?? '';
$raison = $_GET['raison'] ?? '';

include __DIR__ . '/../partials/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4><i class="fas fa-money-bill-wave"></i> Vente d'Article</h4>
                    <p class="mb-0">Convertir un don en argent</p>
                </div>
                
                <div class="card-body">
                    
                    <!-- Messages d'erreur -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Vente non permise !</strong>
                            <?php 
                            switch($error) {
                                case 'vente_non_permise':
                                    echo $raison ? htmlspecialchars(urldecode($raison)) : "Cet article ne peut pas être vendu.";
                                    break;
                                case 'erreur_vente':
                                    echo "Une erreur s'est produite lors de la vente.";
                                    break;
                                default:
                                    echo "Erreur inconnue.";
                            }
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Informations sur l'article -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-box"></i> Article à Vendre</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Type :</strong> <?php echo htmlspecialchars($don['type_don']); ?></p>
                                    <p><strong>Donateur :</strong> <?php echo htmlspecialchars($don['nom_donneur']); ?></p>
                                    <p><strong>Ville :</strong> <?php echo htmlspecialchars($don['ville_nom']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Quantité :</strong> <?php echo number_format($don['nombre_don'], 2); ?></p>
                                    <p><strong>Prix Original :</strong> 
                                        <span class="text-success fw-bold"><?php echo number_format($don['prix_original'], 2); ?> Ar</span>
                                    </p>
                                    <p><strong>Date Don :</strong> <?php echo date('d/m/Y', strtotime($don['created_at'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vérification de vendabilité -->
                    <div id="vendabilite-check" class="mb-4">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            <span>Vérification de la vendabilité...</span>
                        </div>
                    </div>

                    <!-- Résultat de la vérification (caché au début) -->
                    <div id="vendabilite-result" class="mb-4" style="display: none;">
                        <!-- Contenu rempli par JavaScript -->
                    </div>

                    <!-- Calcul du prix de vente -->
                    <div id="prix-calcul" class="card mb-4" style="display: none;">
                        <div class="card-header bg-warning">
                            <h6><i class="fas fa-calculator"></i> Calcul du Prix de Vente</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <h6>Prix Original</h6>
                                    <span class="fs-5 text-success"><?php echo number_format($prix_original, 2); ?> Ar</span>
                                </div>
                                <div class="col-md-4">
                                    <h6>Réduction (-<?php echo $taux_diminution; ?>%)</h6>
                                    <span class="fs-5 text-warning" id="montant-reduction">
                                        <?php echo number_format($prix_original - $prix_vente, 2); ?> Ar
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <h6>Prix de Vente</h6>
                                    <span class="fs-5 text-primary fw-bold" id="prix-final">
                                        <?php echo number_format($prix_vente, 2); ?> Ar
                                    </span>
                                </div>
                            </div>
                            
                            <div class="progress mt-3">
                                <div class="progress-bar bg-success" style="width: <?php echo $taux_diminution; ?>%"></div>
                                <div class="progress-bar bg-warning" style="width: <?php echo 100 - $taux_diminution; ?>%"></div>
                            </div>
                            <small class="text-muted">
                                Taux de diminution configuré : <?php echo $taux_diminution; ?>%
                            </small>
                        </div>
                    </div>

                    <!-- Formulaire de vente -->
                    <form method="POST" id="vente-form" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Confirmation de vente :</strong><br>
                            L'article sera marqué comme vendu et un nouveau don en argent de 
                            <strong><?php echo number_format($prix_vente, 2); ?> Ar</strong> sera créé.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill" onclick="return confirm('Confirmer la vente de cet article ?')">
                                <i class="fas fa-check"></i> Confirmer la Vente
                            </button>
                            <a href="<?php echo BASE_URL; ?>/dons" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Vérifier la vendabilité au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    verifierVendabilite();
});

function verifierVendabilite() {
    const donId = <?php echo $don['id']; ?>;
    
    fetch(`<?php echo BASE_URL; ?>/api/dons/${donId}/vendable`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('vendabilite-check').style.display = 'none';
            document.getElementById('vendabilite-result').style.display = 'block';
            
            const resultDiv = document.getElementById('vendabilite-result');
            
            if (data.vendable) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Article Vendable</strong><br>
                        ${data.raison}
                    </div>
                `;
                
                // Afficher le calcul et le formulaire
                document.getElementById('prix-calcul').style.display = 'block';
                document.getElementById('vente-form').style.display = 'block';
                
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        <strong>Article Non Vendable</strong><br>
                        ${data.raison}
                    </div>
                `;
                
                // Afficher les détails s'il y en a
                if (data.details && data.details.length > 0) {
                    let detailsHtml = '<div class="mt-3"><h6>Besoins en cours :</h6><ul>';
                    data.details.forEach(besoin => {
                        detailsHtml += `<li>${besoin.nom} - ${besoin.ville_nom} (${besoin.nombre - besoin.satisfait} restant)</li>`;
                    });
                    detailsHtml += '</ul></div>';
                    resultDiv.innerHTML += detailsHtml;
                }
                
                // Ajouter un bouton retour
                resultDiv.innerHTML += `
                    <div class="mt-3">
                        <a href="<?php echo BASE_URL; ?>/dons" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour aux Dons
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('vendabilite-check').style.display = 'none';
            document.getElementById('vendabilite-result').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Erreur lors de la vérification. Veuillez réessayer.
                </div>
            `;
            document.getElementById('vendabilite-result').style.display = 'block';
        });
}
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>