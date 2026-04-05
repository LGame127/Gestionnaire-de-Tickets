<?php
// Démarre une session PHP pour accéder aux données utilisateur
session_start();
// Vérifie si l'utilisateur n'est pas connecté OU si son rôle n'est pas Technicien, Admin ou Super Admin (id_role 2, 3 ou 4)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['id_role'], [2, 3, 4])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas autorisé
    header("Location: index.php");
    exit(); // Arrête l'exécution du script après la redirection
}

// Vérifie si l'ID du ticket ou l'action n'est pas présent dans l'URL
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    // Redirige vers la liste des tickets si les paramètres sont manquants
    header("Location: liste_tickets.php");
    exit();
}

// Récupère l'ID du ticket et l'action depuis l'URL
$id_ticket = $_GET['id'];
$action = $_GET['action'];

try {
    // Connexion à la base de données MySQL avec PDO
    $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
    // Configure PDO pour afficher les erreurs sous forme d'exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifie l'action demandée pour préparer la requête SQL appropriée
    if ($action == 'traiter') {
        // Si l'action est "traiter" :
        // Met à jour l'état du ticket à "En court" (id_etat = 2) et définit la date de traitement à maintenant
        $stmt = $pdo->prepare("UPDATE ticket SET id_etat = 2, date_traitement = NOW() WHERE id_ticket = :id");
    } elseif ($action == 'fermer') {
        // Si l'action est "fermer" :
        // Met à jour l'état du ticket à "Fermé" (id_etat = 3) et définit la date de résolution à maintenant
        $stmt = $pdo->prepare("UPDATE ticket SET id_etat = 3, date_resolution = NOW() WHERE id_ticket = :id");
    } else {
        // Si l'action n'est ni "traiter" ni "fermer", redirige vers la liste des tickets
        header("Location: liste_tickets.php");
        exit();
    }

    // Associe le paramètre ':id' à l'ID du ticket
    $stmt->bindParam(':id', $id_ticket);
    // Exécute la requête de mise à jour
    $stmt->execute();

    // Redirige vers la liste des tickets après la mise à jour
    header("Location: liste_tickets.php");
    exit();
} catch (PDOException $e) {
    // En cas d'erreur, affiche le message d'erreur et arrête le script
    die("Erreur : " . $e->getMessage());
}
