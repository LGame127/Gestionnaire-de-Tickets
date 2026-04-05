<?php
// Démarre une session PHP pour accéder aux données utilisateur
session_start();
// Vérifie si l'utilisateur n'est pas connecté OU si son rôle n'est pas Super Admin (id_role = 4)
if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 4) {
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

// Initialise des variables pour les messages et résultats
$message = '';
$result = null;
$error = null;

// Vérifie si le formulaire a été soumis avec une requête SQL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sql_query'])) {
    // Récupère et nettoie la requête SQL
    $sql = trim($_POST['sql_query']);
    if (!empty($sql)) {
        try {
            // Si la requête commence par SELECT (case-insensitive)
            if (stripos($sql, 'SELECT') === 0) {
                // Exécute la requête SELECT et récupère les résultats
                $stmt = $pdo->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $message = "Requête exécutée avec succès. " . count($result) . " ligne(s) retournée(s).";
            }
            // Pour les autres types de requêtes (INSERT, UPDATE, DELETE, etc.)
            else {
                // Exécute la requête et récupère le nombre de lignes affectées
                $stmt = $pdo->exec($sql);
                $message = "Requête exécutée avec succès. " . $stmt . " ligne(s) affectée(s).";
            }
        } catch (PDOException $e) {
            // En cas d'erreur SQL, stocke le message d'erreur
            $error = "Erreur SQL : " . $e->getMessage();
        }
    } else {
        // Si la requête est vide
        $error = "Veuillez entrer une requête SQL.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console SQL - Super Admin</title>
    <style>
        /* Styles CSS pour la mise en page et l'apparence */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .sql-form {
            margin: 20px 0;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace; /* Police adaptée pour le code SQL */
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda; /* Vert clair pour les succès */
            color: #155724;
        }
        .error {
            background-color: #f8d7da; /* Rouge clair pour les erreurs */
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2; /* Fond gris pour les en-têtes */
        }
        .back-link {
            display: block;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Titre principal de la page -->
        <h2>Console SQL - Super Admin</h2>

        <!-- Affiche un message de succès si présent -->
        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <!-- Affiche un message d'erreur si présent -->
        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Formulaire pour entrer et exécuter une requête SQL -->
        <form method="post" class="sql-form">
            <textarea name="sql_query" placeholder="Entrez votre requête SQL ici..."></textarea><br>
            <button type="submit">Exécuter</button>
        </form>

        <!-- Affiche les résultats si une requête SELECT a été exécutée -->
        <?php if ($result !== null): ?>
            <h3>Résultats :</h3>
            <table>
                <thead>
                    <tr>
                        <!-- Affiche les noms des colonnes -->
                        <?php foreach ($result[0] as $key => $value): ?>
                            <th><?= htmlspecialchars($key) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Affiche chaque ligne de résultat -->
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?= htmlspecialchars($value) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Lien pour retourner au tableau de bord -->
        <a href="..\traitement\accueil.php" class="back-link">Retour au tableau de bord</a>
    </div>
</body>
</html>
