<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Utilisateur - BNGRC</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            margin: 0; 
            padding: 0; 
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.6;
            color: #333;
        }
        .container { 
            max-width: 480px; 
            width: 90%; 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: #16a34a;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 { 
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .header p { 
            margin: 0.5rem 0 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .form-group { 
            margin-bottom: 1.5rem; 
        }
        label { 
            display: block; 
            margin-bottom: 0.5rem; 
            color: #374151; 
            font-weight: 500; 
        }
        input { 
            width: 100%; 
            padding: 0.75rem; 
            border: 2px solid #e5e7eb; 
            border-radius: 12px; 
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }
        input:focus {
            outline: none;
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }
        .btn { 
            width: 100%; 
            padding: 0.75rem; 
            border: none; 
            border-radius: 12px; 
            cursor: pointer; 
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }
        .btn-primary { 
            background: #16a34a; 
            color: white; 
        }
        .btn-primary:hover { 
            background: #15803d; 
        }
        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.2);
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
        .links { 
            text-align: center; 
            margin-top: 1.5rem; 
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        .links a { 
            color: #6b7280; 
            text-decoration: none; 
            font-size: 0.9rem;
            margin: 0.5rem;
            transition: color 0.2s ease;
        }
        .links a:hover { 
            color: #16a34a; 
        }
        .password-info {
            font-size: 12px;
            color: #e74c3c;
            margin-top: 5px;
            background: #fff5f5;
            padding: 8px;
            border-radius: 4px;
            border-left: 3px solid #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BNGRC</h1>
            <p>Inscription Utilisateur</p>
        </div>
        
        <div style="padding: 2rem;">
            <div class="password-info" style="background: #fef3c7; border: 1px solid #fcd34d; color: #d97706; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <strong>Important:</strong> Ce mot de passe ne pourra être utilisé qu'une seule fois. 
                Après votre première connexion, vous devrez créer un nouveau compte pour vous reconnecter.
            </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?php
                switch($_GET['error']) {
                    case 'missing_fields':
                        echo "Tous les champs sont obligatoires.";
                        break;
                    case 'invalid_email':
                        echo "Adresse email invalide.";
                        break;
                    case 'weak_password':
                        echo "Le mot de passe doit contenir au moins 6 caractères.";
                        break;
                    case 'email_exists':
                        echo "Cette adresse email est déjà utilisée.";
                        break;
                    case 'database':
                        echo "Erreur lors de l'inscription. Veuillez réessayer.";
                        break;
                    default:
                        echo "Erreur inconnue.";
                }
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/exams3-main/exams3/user/register">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required placeholder="Votre nom de famille">
            </div>

            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required placeholder="votre@email.com">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <input type="password" id="password" name="password" required placeholder="Minimum 6 caractères">
                <div class="password-info">
                    ⚠️ <strong>Important:</strong> Ce mot de passe ne pourra être utilisé qu'une seule fois. 
                    Après votre première connexion, vous devrez créer un nouveau compte pour vous reconnecter.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>

        <div class="links">
            <a href="/exams3-main/exams3/user/login">Déjà inscrit ? Se connecter</a><br><br>
            <a href="/exams3-main/exams3/login">Connexion Administrateur</a>
        </div>
    </div>
</body>
</html>