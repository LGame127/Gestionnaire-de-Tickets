<?php
// Démarre une session PHP pour accéder aux données utilisateur stockées
session_start();
// Vérifie si l'utilisateur n'est pas connecté (pas de variable 'user' en session)
if (!isset($_SESSION['user'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header("Location: index.php");
    exit(); // Arrête l'exécution du script après la redirection
}

// Vérifie si l'ID du ticket n'est pas présent dans l'URL
if (!isset($_GET['id'])) {
    // Redirige vers la page "mes_tickets.php" si l'ID est manquant
    header("Location: mes_tickets.php");
    exit();
}

// Récupère l'ID du ticket depuis l'URL
$id_ticket = $_GET['id'];
// Récupère les informations de l'utilisateur depuis la session
$user = $_SESSION['user'];

try {
    // Connexion à la base de données MySQL avec PDO
    $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
    // Configure PDO pour afficher les erreurs sous forme d'exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prépare une requête SQL pour vérifier que le ticket appartient bien à l'utilisateur connecté
    $stmt = $pdo->prepare("
        SELECT id_utilisateur FROM ticket WHERE id_ticket = :id_ticket
    ");
    // Associe le paramètre ':id_ticket' à l'ID du ticket récupéré depuis l'URL
    $stmt->bindParam(':id_ticket', $id_ticket);
    // Exécute la requête
    $stmt->execute();
    // Récupère le résultat sous forme de tableau associatif
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si le ticket existe ET appartient bien à l'utilisateur connecté
    if ($ticket && $ticket['id_utilisateur'] == $user['id_utilisateur']) {
        // Prépare une requête SQL pour supprimer le ticket
        $stmt = $pdo->prepare("
            DELETE FROM ticket WHERE id_ticket = :id_ticket
        ");
        // Associe le paramètre ':id_ticket' à l'ID du ticket
        $stmt->bindParam(':id_ticket', $id_ticket);
        // Exécute la requête de suppression
        $stmt->execute();
    }

    // Redirige vers la page "mes_tickets.php" après traitement
    header("Location: mes_tickets.php");
    exit();
} catch (PDOException $e) {
    // En cas d'erreur, affiche le message d'erreur et arrête le script
    die("Erreur : " . $e->getMessage());
}
?>