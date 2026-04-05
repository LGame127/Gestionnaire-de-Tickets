<?php
// Démarre une session PHP pour accéder aux données utilisateur
session_start();
// Vérifie si l'utilisateur n'est pas connecté OU si son rôle n'est pas technicien (2, 3 ou 4)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['id_role'], [2, 3, 4])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas autorisé
    header("Location: ../index.php");
    exit(); // Arrête l'exécution du script après la redirection
}
// Récupère les informations de l'utilisateur depuis la session
$user = $_SESSION['user'];
?>

<?php
// Vérifie si l'utilisateur a le rôle Technicien (2) supérieur pour afficher le bouton de visualisation des tickets
if (in_array($user['id_role'], [2, 3, 4])): ?>
    <div class="menu">
        <a href="liste_tickets.php">Voir les tickets</a><br>
    </div>
<?php endif; ?>

<?php
// Vérifie si l'utilisateur a le rôle Admin (3) ou supérieur pour afficher le bouton de gestion des utilisateurs
if (in_array($user['id_role'], [3, 4])): ?>
    <div class="menu">
        <a href="../admin/utilisateurs.php">Gérer les utilisateurs</a>
    </div>
<?php endif; ?>

<?php
// Vérifie si l'utilisateur a le rôle Super Admin (4) pour afficher le bouton d'Accès à la base de données'
if ($user['id_role'] == 4): ?>
    <div class="menu">
        <a href="../super_admin/admin_bdd.php" style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 4px;">Accès Base de Données</a>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Traitement - Gestionnaire de Tickets</title>
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
        <!-- Titre principal de la page Traitement-->
        <h2>Tableau de bord<br>Traitment</h2>
        <!-- Section d'accueil personnalisée avec le nom et le rôle de l'utilisateur -->
        <div class="welcome">
            <!-- htmlspecialchars() protège contre les attaques XSS -->
            <p>Bonjour, <?php echo htmlspecialchars($user['Prenom'] . ' ' . $user['Nom']); ?> (<?php echo htmlspecialchars($user['Nom_role']); ?>)</p>
            <!-- Bouton pour modifier les éléments possible du profil -->
            <a href="modifier_profil.php" style="margin-left: 10px;">Modifier mon profil</a>
        </div>
        <!-- Lien de déconnexion -->
        <div class="logout">
            <a href="../deconnexion.php">Se déconnecter</a>
        </div>
    </div>
</body>
</html>
