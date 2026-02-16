<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Utilisateur - BNGRC</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.6;
            color: #333;
        }
        .login-container { 
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .login-header {
            background: #2c3e50;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        .login-form {
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .btn:hover {
            background: #2563eb;
        }
        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .alert {
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            font-size: 0.9rem;
        }
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        .warning {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fed7aa;
            padding: 0.75rem;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }
        .links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        .links a {
            display: block;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            margin: 0.5rem 0;
            transition: color 0.2s ease;
        }
        .links a:hover {
            color: #3b82f6;
        }
        .links a:first-child {
            color: #3b82f6;
            font-weight: 500;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 1rem 0;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }
        .divider span {
            padding: 0 1rem;
            color: #6b7280;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>BNGRC</h1>
            <p>Bureau National de Gestion des Risques et Catastrophes</p>
        </div>
        
        <div class="login-form">
            <div class="warning">
                <strong>Note:</strong> Chaque mot de passe ne peut être utilisé qu'une seule fois.
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php
                    switch($_GET['error']) {
                        case 'missing_fields':
                            echo "Nom, prénom et mot de passe requis.";
                            break;
                        case 'invalid_credentials':
                            echo "Nom, prénom ou mot de passe incorrect.";
                            break;
                        case 'password_used':
                            echo "Ce mot de passe a déjà été utilisé. Veuillez créer un nouveau compte.";
                            break;
                        case 'database':
                            echo "Erreur de connexion. Veuillez réessayer.";
                            break;
                        default:
                            echo "Erreur de connexion.";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    switch($_GET['success']) {
                        case 'registered':
                            echo "✅ Inscription réussie ! Vous pouvez maintenant vous connecter.";
                            break;
                        case 'logged_out':
                            echo "✅ Déconnexion réussie.";
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/exams3-main/exams3/user/login">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required placeholder="Votre nom de famille" autocomplete="family-name">
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom" autocomplete="given-name">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
                </div>

                <button type="submit" class="btn">Se connecter</button>
            </form>

            <div class="links">
                <a href="/exams3-main/exams3/user/register">Créer un nouveau compte</a>
                
                <div class="divider">
                    <span>ou</span>
                </div>
                
                <a href="/exams3-main/exams3/login">Connexion Administrateur</a>
            </div>
        </div>
    </div>
</body>
</html>