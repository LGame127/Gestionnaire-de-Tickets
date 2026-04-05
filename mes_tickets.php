<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$user = $_SESSION['user'];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT t.id_ticket, t.Description, t.date_creation, c.Nom_categorie, e.Nom_etat
        FROM ticket t
        JOIN categorie c ON t.id_categorie = c.id_categorie
        JOIN etat e ON t.id_etat = e.id_etat
        WHERE t.id_utilisateur = :id_utilisateur
    ");
    $stmt->bindParam(':id_utilisateur', $user['id_utilisateur']);
    $stmt->execute();
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Tickets</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .action { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mes Tickets</h2>
        <a href="accueil.php">Retour à l'accueil</a>
        <?php if (count($tickets) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Catégorie</th>
                        <th>État</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?= htmlspecialchars($ticket['id_ticket']) ?></td>
                            <td><?= htmlspecialchars($ticket['Description']) ?></td>
                            <td><?= htmlspecialchars($ticket['Nom_categorie']) ?></td>
                            <td><?= htmlspecialchars($ticket['Nom_etat']) ?></td>
                            <td><?= htmlspecialchars($ticket['date_creation']) ?></td>
                            <td>
                                <a href="supprimer_ticket.php?id=<?= $ticket['id_ticket'] ?>" class="action" onclick="return confirm('Voulez-vous vraiment supprimer ce ticket ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun ticket trouvé.</p>
        <?php endif; ?>
    </div>
</body>
</html>