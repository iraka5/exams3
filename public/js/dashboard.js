/**
 * Dashboard V2 - Fonctions JavaScript 
 * Gestion des mises à jour en temps réel et des graphiques
 */

class Dashboard {
    constructor() {
        this.updateInterval = null;
        this.updateFrequency = 30000; // 30 secondes
        this.charts = {};
        
        this.init();
    }
    
    /**
     * Initialisation du dashboard
     */
    init() {
        this.setupEventListeners();
        this.loadInitialData();
        this.startAutoUpdate();
    }
    
    /**
     * Configuration des événements
     */
    setupEventListeners() {
        // Bouton d'actualisation
        const refreshBtn = DOM.$('.refresh-btn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.refreshData();
            });
        }
        
        // Gestion de la visibilité de la page pour économiser les ressources
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopAutoUpdate();
            } else {
                this.startAutoUpdate();
                this.refreshData(); // Mise à jour immédiate quand on revient sur la page
            }
        });
        
        // Nettoyage au déchargement de la page
        window.addEventListener('beforeunload', () => {
            this.stopAutoUpdate();
        });
    }
    
    /**
     * Chargement initial des données
     */
    async loadInitialData() {
        await this.refreshData();
    }
    
    /**
     * Actualisation des données
     */
    async refreshData() {
        const refreshBtn = DOM.$('.refresh-btn');
        const refreshIcon = DOM.$('#refreshIcon');
        
        try {
            // Animation de chargement
            if (refreshIcon) {
                refreshIcon.innerHTML = '<div class="loading"></div>';
            }
            if (refreshBtn) {
                refreshBtn.disabled = true;
            }
            
            // Récupération des données
            const response = await Ajax.get('/api/dashboard-v2');
            
            if (response.success) {
                this.updateStatistics(response.totaux);
                this.updateVillesTable(response.villes);
                this.updateAlerts(response.totaux);
                this.updateLastUpdateTime();
                
                // Mettre à jour les graphiques si nécessaire
                if (typeof this.updateCharts === 'function') {
                    this.updateCharts(response);
                }
            } else {
                throw new Error(response.error || 'Erreur inconnue');
            }
            
        } catch (error) {
            console.error('Erreur lors de l\'actualisation:', error);
            Notifications.error('Erreur lors de l\'actualisation des données: ' + error.message);
        } finally {
            // Restaurer le bouton
            if (refreshIcon) {
                refreshIcon.innerHTML = '🔄';
            }
            if (refreshBtn) {
                refreshBtn.disabled = false;
            }
        }
    }
    
    /**
     * Met à jour les statistiques principales
     */
    updateStatistics(totaux) {
        const updates = {
            '#besoinsTotal': totaux.besoins_total,
            '#besoinsSatisfaits': totaux.besoins_satisfaits,
            '#donsRecus': totaux.dons_recus,
            '#fondsRestants': totaux.fonds_restants,
            '#tauxSatisfaction': (totaux.taux_satisfaction || 0).toFixed(1)
        };
        
        Object.entries(updates).forEach(([selector, value]) => {
            const element = DOM.$(selector);
            if (element) {
                // Animation de mise à jour
                element.style.transform = 'scale(1.05)';
                element.style.transition = 'transform 0.2s ease';
                
                setTimeout(() => {
                    if (selector === '#tauxSatisfaction') {
                        element.textContent = value + '%';
                    } else {
                        element.textContent = Format.number(value);
                    }
                    
                    element.style.transform = 'scale(1)';
                }, 100);
            }
        });
        
        // Coloration spéciale pour les fonds restants
        const fondsElement = DOM.$('#fondsRestants');
        if (fondsElement && totaux.fonds_restants !== undefined) {
            const parentCard = fondsElement.closest('.stat-card');
            if (parentCard) {
                // Supprimer les anciennes classes de couleur
                parentCard.classList.remove('fonds-low', 'fonds-critical');
                
                if (totaux.fonds_restants < 0) {
                    parentCard.classList.add('fonds-critical');
                    parentCard.style.setProperty('--accent-color', '#dc2626');
                } else if (totaux.fonds_restants < 10000) {
                    parentCard.classList.add('fonds-low');
                    parentCard.style.setProperty('--accent-color', '#f59e0b');
                } else {
                    parentCard.style.setProperty('--accent-color', '#10b981');
                }
            }
        }
    }
    
    /**
     * Met à jour le tableau des villes
     */
    updateVillesTable(villes) {
        const tbody = DOM.$('#villesTableau');
        if (!tbody) return;
        
        if (villes && villes.length > 0) {
            tbody.innerHTML = villes.map(ville => {
                const tauxSatisfaction = parseFloat(ville.taux_satisfaction) || 0;
                let statusColor = '#ef4444'; // Rouge par défaut
                
                if (tauxSatisfaction >= 100) {
                    statusColor = '#10b981'; // Vert
                } else if (tauxSatisfaction >= 50) {
                    statusColor = '#f59e0b'; // Orange
                }
                
                return `
                    <tr class="fade-in">
                        <td><strong>${ville.region_nom}</strong></td>
                        <td>${ville.ville_nom}</td>
                        <td style="text-align: center;">
                            <span class="badge badge-info">
                                ${ville.nb_achats}
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 600; color: #059669;">
                            ${Format.currency(ville.montant_achats)}
                        </td>
                        <td style="text-align: right;">
                            ${Format.currency(ville.besoins_totaux)}
                        </td>
                        <td style="text-align: right; color: #3b82f6;">
                            ${Format.currency(ville.dons_recus)}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="progress" style="flex: 1;">
                                    <div class="progress-bar" style="width: ${Math.min(tauxSatisfaction, 100)}%; background: ${statusColor};"></div>
                                </div>
                                <span style="font-weight: 600; color: ${statusColor}; min-width: 50px; font-size: 12px;">
                                    ${Format.percent(tauxSatisfaction)}
                                </span>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted" style="padding: 40px;">Aucune donnée disponible</td></tr>';
        }
    }
    
    /**
     * Met à jour les alertes
     */
    updateAlerts(totaux) {
        const alerteFonds = DOM.$('#alerteFonds');
        if (alerteFonds) {
            if (totaux.alerte_fonds) {
                alerteFonds.style.display = 'flex';
                alerteFonds.classList.add('slide-up');
            } else {
                alerteFonds.style.display = 'none';
                alerteFonds.classList.remove('slide-up');
            }
        }
        
        // Alerte si taux de satisfaction très bas
        const tauxSatisfaction = parseFloat(totaux.taux_satisfaction) || 0;
        if (tauxSatisfaction < 25 && tauxSatisfaction > 0) {
            Notifications.warning(
                `Le taux de satisfaction global n'est que de ${Format.percent(tauxSatisfaction)}. Considérez solliciter plus de dons.`,
                8000
            );
        }
        
        // Alerte si fonds négatifs
        if (totaux.fonds_restants < 0) {
            Notifications.error(
                `Attention ! Les dépenses dépassent les dons reçus de ${Format.currency(Math.abs(totaux.fonds_restants))}.`,
                0 // Pas de fermeture automatique
            );
        }
    }
    
    /**
     * Met à jour l'heure de dernière mise à jour
     */
    updateLastUpdateTime() {
        const lastUpdateElement = DOM.$('#lastUpdate');
        if (lastUpdateElement) {
            lastUpdateElement.textContent = Format.datetime(new Date());
        }
    }
    
    /**
     * Démarre la mise à jour automatique
     */
    startAutoUpdate() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
        
        this.updateInterval = setInterval(() => {
            this.refreshData();
        }, this.updateFrequency);
        
        console.log(`Dashboard: Mise à jour automatique activée (${this.updateFrequency/1000}s)`);
    }
    
    /**
     * Arrête la mise à jour automatique
     */
    stopAutoUpdate() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
            console.log('Dashboard: Mise à jour automatique désactivée');
        }
    }
    
    /**
     * Change la fréquence de mise à jour
     */
    setUpdateFrequency(seconds) {
        this.updateFrequency = seconds * 1000;
        
        // Redémarrer avec la nouvelle fréquence
        this.stopAutoUpdate();
        this.startAutoUpdate();
        
        Notifications.info(`Fréquence de mise à jour changée: ${seconds} secondes`);
    }
    
    /**
     * Export des données en CSV
     */
    exportCSV() {
        // Cette fonction sera implémentée si nécessaire
        Notifications.info('Fonctionnalité d\'export en cours de développement');
    }
    
    /**
     * Mode plein écran pour le dashboard
     */
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().then(() => {
                Notifications.success('Mode plein écran activé');
            }).catch(() => {
                Notifications.error('Impossible d\'activer le mode plein écran');
            });
        } else {
            document.exitFullscreen().then(() => {
                Notifications.info('Mode plein écran désactivé');
            });
        }
    }
}

/**
 * Utilitaires pour la vérification des fonds
 */
const FondsChecker = {
    /**
     * Vérifie si les fonds sont suffisants pour un achat
     */
    async verifierFonds(montant) {
        try {
            const response = await Ajax.get(`/api/verifier-fonds?montant=${montant}`);
            return response;
        } catch (error) {
            console.error('Erreur lors de la vérification des fonds:', error);
            return { success: false, error: error.message };
        }
    },
    
    /**
     * Interface de vérification avec notification
     */
    async verifierEtNotifier(montant) {
        const result = await this.verifierFonds(montant);
        
        if (result.success) {
            if (result.fonds_suffisants) {
                Notifications.success(
                    `Fonds suffisants ! Montant demandé: ${Format.currency(montant)}. Fonds restants après achat: ${Format.currency(result.fonds_restants - montant)}`,
                    5000
                );
                return true;
            } else {
                Notifications.error(
                    `Fonds insuffisants ! Montant demandé: ${Format.currency(montant)}. Fonds disponibles: ${Format.currency(result.fonds_restants)}`,
                    8000
                );
                return false;
            }
        } else {
            Notifications.error('Erreur lors de la vérification des fonds: ' + result.error);
            return false;
        }
    }
};

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le dashboard seulement sur la page dashboard-v2
    if (window.location.pathname.includes('dashboard-v2')) {
        window.dashboard = new Dashboard();
        console.log('Dashboard V2 initialisé');
    }
});

// Fonctions globales pour compatibilité avec le HTML
window.actualiserDashboard = function() {
    if (window.dashboard) {
        window.dashboard.refreshData();
    }
};

window.toggleAutoUpdate = function() {
    if (window.dashboard) {
        if (window.dashboard.updateInterval) {
            window.dashboard.stopAutoUpdate();
            Notifications.info('Mise à jour automatique désactivée');
        } else {
            window.dashboard.startAutoUpdate();
            Notifications.success('Mise à jour automatique réactivée');
        }
    }
};

// Exposition globale
window.Dashboard = Dashboard;
window.FondsChecker = FondsChecker;