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

// Vérifie si le formulaire a été soumis (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $Prenom = $_POST['Prenom'];
    $Nom = $_POST['Nom'];
    $login = $_POST['login'];
    $mdp = 'motdepasse'; // Mot de passe par défaut pour tous les nouveaux utilisateurs
    // Détermine si l'utilisateur est actif (coché = 1, non coché = 0)
    $Actif = isset($_POST['Actif']) ? 1 : 0;
    $id_role = $_POST['id_role']; // Rôle sélectionné dans le formulaire

    // Prépare une requête SQL pour insérer un nouvel utilisateur
    $stmt = $pdo->prepare("
        INSERT INTO utilisateur (Prenom, Nom, login, mdp, Actif, id_role)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    // Exécute la requête avec les valeurs récupérées du formulaire
    $stmt->execute([$Prenom, $Nom, $login, $mdp, $Actif, $id_role]);

    // Redirige vers la page de gestion des utilisateurs après création
    header("Location: utilisateurs.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer utilisateur</title>
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
        <h2>Créer un nouvel utilisateur</h2>
        <!-- Formulaire de création d'utilisateur -->
        <form method="post">
            <!-- Champ Prénom -->
            <label for="Prenom">Prénom :</label>
            <input type="text" id="Prenom" name="Prenom" required>

            <!-- Champ Nom -->
            <label for="Nom">Nom :</label>
            <input type="text" id="Nom" name="Nom" required>

            <!-- Champ Login -->
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" required>

            <!-- Case à cocher pour l'état "Actif" -->
            <div class="form-group">
                <label for="Actif">Actif :</label>
                <input type="checkbox" id="Actif" name="Actif" checked>
            </div>

            <!-- Liste déroulante pour sélectionner le rôle -->
            <label for="id_role">Rôle :</label>
            <select id="id_role" name="id_role" required>
                <option value="1">Utilisateur</option>
                <option value="2">Technicien</option>
            </select>

            <!-- Boutons de soumission et d'annulation -->
            <button type="submit">Créer</button>
            <button type="button" onclick="window.location.href='utilisateurs.php'">Annuler</button>
        </form>
    </div>
</body>
</html>
