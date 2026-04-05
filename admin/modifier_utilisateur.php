<?php
// Démarre une session PHP pour accéder aux données utilisateur
session_start();
// Vérifie si l'utilisateur n'est pas connecté OU si son rôle n'est pas Admin (id_role = 3)
if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 3) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas autorisé
    header("Location: ../index.php");
    exit(); // Arrête l'exécution du script après la redirection
}

try {
    // Connexion à la base de données MySQL avec PDO
    $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
    // Configure PDO pour afficher les erreurs sous forme d'exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur de connexion, affiche le message d'erreur et arrête le script
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifie si le formulaire a été soumis (méthode POST) et si l'ID de l'utilisateur est présent
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_utilisateur'])) {
    // Récupère l'ID de l'utilisateur à modifier
    $id_utilisateur = $_POST['id_utilisateur'];
    // Prépare et exécute une requête pour récupérer les informations de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$id_utilisateur]);
    // Récupère les données de l'utilisateur sous forme de tableau associatif
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Vérifie si le formulaire de mise à jour a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Récupère les données du formulaire
    $id_utilisateur = $_POST['id_utilisateur'];
    $Prenom = $_POST['Prenom'];
    $Nom = $_POST['Nom'];
    $login = $_POST['login'];
    // Détermine si l'utilisateur est actif (coché = 1, non coché = 0)
    $Actif = isset($_POST['Actif']) ? 1 : 0;
    $id_role = $_POST['id_role'];
    // Vérifie si la réinitialisation du mot de passe est demandée
    $reset_mdp = isset($_POST['reset_mdp']) ? true : false;

    // Si la réinitialisation du mot de passe est demandée, utilise le mot de passe par défaut
    if ($reset_mdp) {
        $mdp = 'motdepasse';
    } else {
        // Sinon, récupère le mot de passe actuel de l'utilisateur
        $stmt = $pdo->prepare("SELECT mdp FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->execute([$id_utilisateur]);
        $mdp = $stmt->fetchColumn(); // Récupère uniquement la colonne 'mdp'
    }

    // Prépare une requête SQL pour mettre à jour les informations de l'utilisateur
    $stmt = $pdo->prepare("
        UPDATE utilisateur
        SET Prenom = ?, Nom = ?, login = ?, mdp = ?, Actif = ?, id_role = ?
        WHERE id_utilisateur = ?
    ");
    // Exécute la requête avec les nouvelles valeurs
    $stmt->execute([$Prenom, $Nom, $login, $mdp, $Actif, $id_role, $id_utilisateur]);

    // Redirige vers la page de gestion des utilisateurs après mise à jour
    header("Location: utilisateurs.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier utilisateur</title>
    <style>
        /* Styles CSS pour la mise en page et l'apparence */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"] {
            background-color: #28a745; /* Vert pour le bouton de soumission */
        }
        button[type="button"] {
            background-color: #dc3545; /* Rouge pour le bouton d'annulation */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Titre principal de la page -->
        <h2>Modifier utilisateur</h2>
        <!-- Formulaire de modification d'utilisateur -->
        <form method="post">
            <!-- Champ caché pour l'ID de l'utilisateur -->
            <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur'] ?>">

            <!-- Champ Prénom -->
            <label for="Prenom">Prénom :</label>
            <input type="text" id="Prenom" name="Prenom" value="<?= htmlspecialchars($utilisateur['Prenom']) ?>" required>

            <!-- Champ Nom -->
            <label for="Nom">Nom :</label>
            <input type="text" id="Nom" name="Nom" value="<?= htmlspecialchars($utilisateur['Nom']) ?>" required>

            <!-- Champ Login -->
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" value="<?= htmlspecialchars($utilisateur['login']) ?>" required>

            <!-- Case à cocher pour l'état "Actif" -->
            <div class="form-group">
                <label for="Actif">Actif :</label>
                <input type="checkbox" id="Actif" name="Actif" <?= $utilisateur['Actif'] ? 'checked' : '' ?>>
            </div>

            <!-- Liste déroulante pour sélectionner le rôle -->
            <label for="id_role">Rôle :</label>
            <select id="id_role" name="id_role" required>
                <option value="1" <?= $utilisateur['id_role'] == 1 ? 'selected' : '' ?>>Utilisateur</option>
                <option value="2" <?= $utilisateur['id_role'] == 2 ? 'selected' : '' ?>>Technicien</option>
            </select>

            <!-- Case à cocher pour réinitialiser le mot de passe -->
            <div class="form-group">
                <label for="reset_mdp">Réinitialiser le mot de passe :</label>
                <input type="checkbox" id="reset_mdp" name="reset_mdp">
            </div>

            <!-- Boutons de soumission et d'annulation -->
            <button type="submit" name="update">Mettre à jour</button>
            <button type="button" onclick="window.location.href='utilisateurs.php'">Annuler</button>
        </form>
    </div>
</body>
</html>
