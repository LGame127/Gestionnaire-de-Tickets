<?php
// Démarre une session PHP pour accéder aux données utilisateur
session_start();
// Vérifie si l'utilisateur n'est pas connecté (pas de variable 'user' en session)
if (!isset($_SESSION['user'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header("Location: index.php");
    exit(); // Arrête l'exécution du script après la redirection
}

// Vérifie si la requête HTTP est de type POST (formulaire soumis)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $id_categorie = $_POST['categorie']; // ID de la catégorie sélectionnée
    $description = $_POST['description']; // Description du ticket

    try {
        // Connexion à la base de données MySQL avec PDO
        $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
        // Configure PDO pour afficher les erreurs sous forme d'exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prépare une requête SQL pour insérer un nouveau ticket
        $stmt = $pdo->prepare("
            INSERT INTO ticket (Description, date_creation, date_traitement, date_resolution, id_categorie, id_etat, id_utilisateur)
            VALUES (:description, NOW(), NULL, NULL, :id_categorie, 1, :id_utilisateur)
        ");
        // Associe les paramètres de la requête aux variables PHP
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->bindParam(':id_utilisateur', $_SESSION['user']['id_utilisateur']);
        // Exécute la requête
        $stmt->execute();

        // Redirige vers la page d'accueil avec un paramètre de succès
        header("Location: accueil.php?success=1");
        exit(); // Arrête l'exécution du script après la redirection
    } catch (PDOException $e) {
        // En cas d'erreur, affiche le message d'erreur et arrête le script
        die("Erreur : " . $e->getMessage());
    }
} else {
    // Si la requête n'est pas de type POST, redirige vers la page d'accueil
    header("Location: accueil.php");
    exit();
}
?>