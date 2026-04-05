<?php
// Requière le fichier "connection.php" pour continuer
require 'connexion.php';
// Démarre une session PHP pour stocker des données utilisateur entre les pages
session_start();
// Variable pour stocker les messages d'erreur
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];

    try {
        // Connexion à la base de données MySQL avec PDO
        $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
        // Configure PDO pour afficher les erreurs sous forme d'exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prépare une requête SQL sécurisée avec des paramètres nommés
        $stmt = $pdo->prepare("
            SELECT u.id_utilisateur, u.Prenom, u.Nom, u.id_role, r.Nom_role
            FROM utilisateur u
            JOIN role r ON u.id_role = r.id_role
            WHERE u.login = :login AND u.mdp = :mdp AND u.Actif = 1
        ");
        // Associe les paramètres de la requête aux variables PHP
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':mdp', $mdp);
        // Exécute la requête
        $stmt->execute();

        // Vérifie si un utilisateur correspond aux identifiants
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Stocke les informations utilisateur en session
            $_SESSION['user'] = $user;
            // Redirige selon le rôle de l'utilisateur
            if ($user['id_role'] == 4 || $user['id_role'] == 3 || $user['id_role'] == 2) {
                header("Location: traitement/accueil.php");
            } else {
                header("Location: accueil.php");
            }
            exit(); // Arrête l'exécution du script après la redirection
        } else {
            // Message d'erreur si les identifiants sont incorrects
            $error = "Login ou mot de passe incorrect.";
            echo $error;
        }
    } catch (PDOException $e) {
        // Capture les erreurs de connexion à la base de données
        $error = "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}
?>