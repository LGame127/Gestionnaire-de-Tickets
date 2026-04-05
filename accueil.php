<?php
session_start();
// Vérifie si l'utilisateur n'est pas connecté (pas de variable 'user' en session)
if (!isset($_SESSION['user'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header("Location: index.php");
    exit(); // Arrête l'exécution du script après la redirection
}
// Récupère les informations de l'utilisateur depuis la session
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestionnaire de Tickets</title>
    <style>
        /* Styles CSS pour la mise en page et l'apparence */
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        .welcome { margin-bottom: 20px; }
        .logout { text-align: right; margin-top: 20px; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Titre principal de la page -->
        <h2>Bienvenue sur le gestionnaire de tickets</h2>
        <!-- Section d'accueil personnalisée avec le nom et le rôle de l'utilisateur -->
        <div class="welcome">
            <p>Bonjour, <?php echo htmlspecialchars($user['Prenom'] . ' ' . $user['Nom']); ?> (<?php echo htmlspecialchars($user['Nom_role']); ?>)</p>
            <!-- Bouton pour modifier les éléments possible du profil -->
            <a href="modifier_profil.php" style="margin-left: 10px;">Modifier mon profil</a>
        </div>
        <!-- Lien de déconnexion -->
        <div class="logout">
            <a href="deconnexion.php">Se déconnecter</a>
        </div>
    </div>
    <!-- Titre de la section de création de ticket -->
    <h3>Créer un nouveau ticket</h3>
    <!-- Formulaire pour créer un nouveau ticket -->
    <form method="post" action="creer_ticket.php">
        <!-- Champ de sélection pour la catégorie du ticket -->
        <div class="form-group">
            <label for="categorie">Catégorie :</label>
            <select id="categorie" name="categorie" required>
                <option value="">-- Choisir une catégorie --</option>
                <option value="1">Site</option>
                <option value="2">Jeux</option>
                <option value="3">Infra</option>
                <option value="4">Droit</option>
            </select>
        </div>
        <!-- Zone de texte pour la description du ticket -->
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        <!-- Bouton de soumission du formulaire -->
        <button type="submit">Créer le ticket</button>
    </form>
    <div class="my-tickets">
        <a href="mes_tickets.php">Voir mes tickets</a>
    </div>
</body>
</html>