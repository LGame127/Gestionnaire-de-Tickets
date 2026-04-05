<?php
// Démarre une session PHP pour accéder aux données utilisateur stockées
session_start();
// Vérifie si l'utilisateur n'est pas connecté (pas de variable 'user' en session)
if (!isset($_SESSION['user'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header("Location: index.php");
    exit(); // Arrête l'exécution du script après la redirection
}
// Récupère les informations de l'utilisateur depuis la session
$user = $_SESSION['user'];

// Initialise des variables pour stocker les messages d'erreur et de succès
$error = "";
$success = "";

// Vérifie si le formulaire a été soumis (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère le nouveau mot de passe depuis le formulaire
    $newMdp = $_POST['mdp'];

    try {
        // Connexion à la base de données MySQL avec PDO
        $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
        // Configure PDO pour afficher les erreurs sous forme d'exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prépare une requête SQL pour mettre à jour le mot de passe de l'utilisateur
        $stmt = $pdo->prepare("
            UPDATE utilisateur
            SET mdp = :mdp
            WHERE id_utilisateur = :id
        ");
        // Associe le paramètre ':mdp' au nouveau mot de passe
        $stmt->bindParam(':mdp', $newMdp);
        // Associe le paramètre ':id' à l'ID de l'utilisateur connecté
        $stmt->bindParam(':id', $user['id_utilisateur']);
        // Exécute la requête
        $stmt->execute();

        // Stocke un message de succès
        $success = "Votre mot de passe a été mis à jour avec succès.";
    } catch (PDOException $e) {
        // En cas d'erreur, stocke le message d'erreur
        $error = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon mot de passe</title>
    <style>
        /* Styles CSS pour la mise en page et l'apparence */
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; }
        .success { color: green; }
        .info { margin-bottom: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Titre principal de la page -->
        <h2>Modifier mon mot de passe</h2>
        <!-- Section d'information affichant le prénom et le nom de l'utilisateur -->
        <div class="info">
            <p><strong>Prénom :</strong> <?php echo htmlspecialchars($user['Prenom']); ?></p>
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['Nom']); ?></p>
        </div>
        <!-- Affiche un message d'erreur si présent -->
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <!-- Affiche un message de succès si présent -->
        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        <!-- Formulaire pour modifier le mot de passe -->
        <form method="post">
            <div class="form-group">
                <!-- Champ pour le nouveau mot de passe -->
                <label for="mdp">Nouveau mot de passe :</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <!-- Bouton de soumission du formulaire -->
            <button type="submit">Mettre à jour</button>
        </form>
        <!-- Lien pour retourner à la page d'accueil -->
        <div style="text-align: right; margin-top: 20px;">
            <a href="accueil.php">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>