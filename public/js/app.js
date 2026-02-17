/**
 * BNGRC - Fonctions JavaScript Communes
 * Système de gestion des dons aux sinistrés
 */

// Configuration globale
const BNGRC = {
    baseUrl: '/exams3-main/exams3',
    apiUrl: '/exams3-main/exams3/api',
    
    // Configuration des notifications
    notifications: {
        duration: 5000,
        position: 'top-right'
    },
    
    // Configuration des tableaux
    tables: {
        pageSize: 10,
        language: 'fr'
    }
};

/**
 * Utilitaires de formatage
 */
const Format = {
    /**
     * Formate un montant en devise malgache
     */
    currency: function(amount) {
        if (typeof amount !== 'number') amount = parseFloat(amount) || 0;
        return new Intl.NumberFormat('fr-FR').format(Math.round(amount)) + ' Ar';
    },
    
    /**
     * Formate un nombre avec séparateurs
     */
    number: function(num) {
        if (typeof num !== 'number') num = parseFloat(num) || 0;
        return new Intl.NumberFormat('fr-FR').format(num);
    },
    
    /**
     * Formate une date
     */
    date: function(dateString, options = {}) {
        const defaultOptions = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        const opts = { ...defaultOptions, ...options };
        return new Date(dateString).toLocaleDateString('fr-FR', opts);
    },
    
    /**
     * Formate une date et heure
     */
    datetime: function(dateString) {
        return new Date(dateString).toLocaleString('fr-FR');
    },
    
    /**
     * Formate un pourcentage
     */
    percent: function(value, decimals = 1) {
        if (typeof value !== 'number') value = parseFloat(value) || 0;
        return value.toFixed(decimals) + '%';
    }
};

/**
 * Système de notifications
 */
const Notifications = {
    container: null,
    
    /**
     * Initialise le container des notifications
     */
    init: function() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'notifications-container';
            this.container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(this.container);
        }
    },
    
    /**
     * Affiche une notification
     */
    show: function(message, type = 'info', duration = null) {
        this.init();
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };
        
        const colors = {
            success: { bg: '#d1fae5', border: '#a7f3d0', text: '#065f46' },
            error: { bg: '#fee2e2', border: '#fecaca', text: '#991b1b' },
            warning: { bg: '#fef3c7', border: '#fde68a', text: '#92400e' },
            info: { bg: '#dbeafe', border: '#bfdbfe', text: '#1e40af' }
        };
        
        const color = colors[type] || colors.info;
        
        notification.style.cssText = `
            background: ${color.bg};
            border: 1px solid ${color.border};
            color: ${color.text};
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideInRight 0.3s ease;
            cursor: pointer;
        `;
        
        notification.innerHTML = `
            <span style="font-size: 18px;">${icons[type] || icons.info}</span>
            <div style="flex: 1;">
                <div style="font-weight: 600; margin-bottom: 4px;">
                    ${type.charAt(0).toUpperCase() + type.slice(1)}
                </div>
                <div>${message}</div>
            </div>
            <button style="background: none; border: none; font-size: 18px; cursor: pointer; opacity: 0.6;" onclick="this.parentElement.remove()">×</button>
        `;
        
        // Animation CSS
        if (!document.getElementById('notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'notification-styles';
            styles.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(styles);
        }
        
        this.container.appendChild(notification);
        
        // Auto-fermeture
        const autoCloseDuration = duration || BNGRC.notifications.duration;
        if (autoCloseDuration > 0) {
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }
            }, autoCloseDuration);
        }
        
        // Fermeture au clic
        notification.addEventListener('click', () => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        });
    },
    
    success: function(message, duration) { this.show(message, 'success', duration); },
    error: function(message, duration) { this.show(message, 'error', duration); },
    warning: function(message, duration) { this.show(message, 'warning', duration); },
    info: function(message, duration) { this.show(message, 'info', duration); }
};

/**
 * Utilitaires AJAX
 */
const Ajax = {
    /**
     * Requête GET
     */
    get: async function(url, options = {}) {
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                ...options
            });
            
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Erreur Ajax GET:', error);
            throw error;
        }
    },
    
    /**
     * Requête POST
     */
    post: async function(url, data = {}, options = {}) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                body: JSON.stringify(data),
                ...options
            });
            
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Erreur Ajax POST:', error);
            throw error;
        }
    },
    
    /**
     * Requête PUT
     */
    put: async function(url, data = {}, options = {}) {
        return this.post(url, data, { ...options, method: 'PUT' });
    },
    
    /**
     * Requête DELETE
     */
    delete: async function(url, options = {}) {
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                ...options
            });
            
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Erreur Ajax DELETE:', error);
            throw error;
        }
    }
};

/**
 * Utilitaires de validation de formulaire
 */
const Validate = {
    /**
     * Valide un email
     */
    email: function(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    },
    
    /**
     * Valide un montant
     */
    currency: function(amount) {
        return !isNaN(parseFloat(amount)) && isFinite(amount) && parseFloat(amount) >= 0;
    },
    
    /**
     * Valide un nombre entier positif
     */
    positiveInteger: function(num) {
        return Number.isInteger(parseInt(num)) && parseInt(num) > 0;
    },
    
    /**
     * Valide une chaîne non vide
     */
    required: function(text) {
        return typeof text === 'string' && text.trim().length > 0;
    },
    
    /**
     * Valide la longueur minimale
     */
    minLength: function(text, length) {
        return typeof text === 'string' && text.length >= length;
    }
};

/**
 * Utilitaires de manipulation du DOM
 */
const DOM = {
    /**
     * Sélecteur jQuery-like
     */
    $: function(selector) {
        return document.querySelector(selector);
    },
    
    /**
     * Sélecteur multiple
     */
    $$: function(selector) {
        return document.querySelectorAll(selector);
    },
    
    /**
     * Ajoute une classe
     */
    addClass: function(element, className) {
        if (typeof element === 'string') element = this.$(element);
        if (element) element.classList.add(className);
    },
    
    /**
     * Supprime une classe
     */
    removeClass: function(element, className) {
        if (typeof element === 'string') element = this.$(element);
        if (element) element.classList.remove(className);
    },
    
    /**
     * Bascule une classe
     */
    toggleClass: function(element, className) {
        if (typeof element === 'string') element = this.$(element);
        if (element) element.classList.toggle(className);
    },
    
    /**
     * Affiche/masque un élément
     */
    toggle: function(element) {
        if (typeof element === 'string') element = this.$(element);
        if (element) {
            element.style.display = element.style.display === 'none' ? '' : 'none';
        }
    },
    
    /**
     * Masque un élément
     */
    hide: function(element) {
        if (typeof element === 'string') element = this.$(element);
        if (element) element.style.display = 'none';
    },
    
    /**
     * Affiche un élément
     */
    show: function(element) {
        if (typeof element === 'string') element = this.$(element);
        if (element) element.style.display = '';
    }
};

/**
 * Utilitaires de confirmation
 */
const Confirm = {
    /**
     * Boîte de confirmation moderne
     */
    show: function(message, title = 'Confirmation', onConfirm = null, onCancel = null) {
        return new Promise((resolve) => {
            // Créer le modal
            const modal = document.createElement('div');
            modal.className = 'confirm-modal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                animation: fadeIn 0.3s ease;
            `;
            
            const dialog = document.createElement('div');
            dialog.style.cssText = `
                background: white;
                border-radius: 12px;
                padding: 24px;
                max-width: 400px;
                width: 90%;
                box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
                animation: slideUp 0.3s ease;
            `;
            
            dialog.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <h3 style="margin: 0 0 12px 0; color: #1e293b; font-size: 18px;">${title}</h3>
                    <p style="margin: 0; color: #64748b; line-height: 1.5;">${message}</p>
                </div>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button class="cancel-btn" style="
                        padding: 8px 16px;
                        border: 1px solid #e2e8f0;
                        background: white;
                        color: #64748b;
                        border-radius: 6px;
                        cursor: pointer;
                        font-weight: 500;
                    ">Annuler</button>
                    <button class="confirm-btn" style="
                        padding: 8px 16px;
                        border: none;
                        background: #dc2626;
                        color: white;
                        border-radius: 6px;
                        cursor: pointer;
                        font-weight: 500;
                    ">Confirmer</button>
                </div>
            `;
            
            // Ajouter les styles d'animation
            if (!document.getElementById('confirm-styles')) {
                const styles = document.createElement('style');
                styles.id = 'confirm-styles';
                styles.textContent = `
                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                    
                    @keyframes slideUp {
                        from { transform: translateY(20px); opacity: 0; }
                        to { transform: translateY(0); opacity: 1; }
                    }
                    
                    .cancel-btn:hover { background: #f8fafc; }
                    .confirm-btn:hover { background: #b91c1c; }
                `;
                document.head.appendChild(styles);
            }
            
            modal.appendChild(dialog);
            
            // Gestionnaires d'événements
            const cleanup = () => {
                modal.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => modal.remove(), 300);
            };
            
            dialog.querySelector('.cancel-btn').addEventListener('click', () => {
                cleanup();
                if (onCancel) onCancel();
                resolve(false);
            });
            
            dialog.querySelector('.confirm-btn').addEventListener('click', () => {
                cleanup();
                if (onConfirm) onConfirm();
                resolve(true);
            });
            
            // Fermer avec Escape
            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    cleanup();
                    document.removeEventListener('keydown', escHandler);
                    resolve(false);
                }
            });
            
            document.body.appendChild(modal);
        });
    },
    
    /**
     * Confirmation de suppression
     */
    delete: function(itemName = 'cet élément', onConfirm = null) {
        return this.show(
            `Êtes-vous sûr de vouloir supprimer ${itemName} ? Cette action est irréversible.`,
            'Confirmer la suppression',
            onConfirm
        );
    }
};

/**
 * Gestionnaire de formulaires
 */
const Forms = {
    /**
     * Valide un formulaire
     */
    validate: function(form) {
        if (typeof form === 'string') form = DOM.$(form);
        
        let isValid = true;
        const errors = [];
        
        // Supprimer les anciennes erreurs
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.error').forEach(el => DOM.removeClass(el, 'error'));
        
        // Validation des champs requis
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'Ce champ est obligatoire');
                isValid = false;
                errors.push(`${field.name || field.id}: Ce champ est obligatoire`);
            }
        });
        
        // Validation des emails
        form.querySelectorAll('input[type="email"]').forEach(field => {
            if (field.value && !Validate.email(field.value)) {
                this.showFieldError(field, 'Email invalide');
                isValid = false;
                errors.push(`${field.name || field.id}: Email invalide`);
            }
        });
        
        // Validation des montants
        form.querySelectorAll('.currency').forEach(field => {
            if (field.value && !Validate.currency(field.value)) {
                this.showFieldError(field, 'Montant invalide');
                isValid = false;
                errors.push(`${field.name || field.id}: Montant invalide`);
            }
        });
        
        return { isValid, errors };
    },
    
    /**
     * Affiche une erreur sur un champ
     */
    showFieldError: function(field, message) {
        DOM.addClass(field, 'error');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.cssText = 'color: #dc2626; font-size: 12px; margin-top: 4px;';
        errorDiv.textContent = message;
        
        field.parentElement.appendChild(errorDiv);
    },
    
    /**
     * Sérialise un formulaire en objet
     */
    serialize: function(form) {
        if (typeof form === 'string') form = DOM.$(form);
        
        const data = {};
        const formData = new FormData(form);
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        return data;
    }
};

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips si nécessaire
    // Initialiser les composants dynamiques
    console.log('BNGRC - Fonctions JavaScript chargées');
});

// Exposition globale
window.BNGRC = BNGRC;
window.Format = Format;
window.Notifications = Notifications;
window.Ajax = Ajax;
window.Validate = Validate;
window.DOM = DOM;
window.Confirm = Confirm;
window.Forms = Forms;