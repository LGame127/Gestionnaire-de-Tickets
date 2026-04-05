<?php
// Démarre une session PHP pour accéder aux données utilisateur
session_start();
// Vérifie si l'utilisateur n'est pas connecté OU si son rôle ne permet pas de traiter les tickets (2, 3 ou 4)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['id_role'], [2, 3, 4])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas autorisé
    header("Location: ../index.php");
    exit(); // Arrête l'exécution du script après la redirection
}

try {
    // Connexion à la base de données MySQL avec PDO
    $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
    // Configure PDO pour afficher les erreurs sous forme d'exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Exécute une requête SQL pour récupérer tous les tickets avec leurs catégories et états
    // Les résultats sont triés par date de création décroissante (du plus récent au plus ancien)
    $stmt = $pdo->query("
        SELECT t.id_ticket, t.Description, t.date_creation, t.date_traitement, t.date_resolution, c.Nom_categorie, e.Nom_etat, t.id_utilisateur
        FROM ticket t
        JOIN categorie c ON t.id_categorie = c.id_categorie
        JOIN etat e ON t.id_etat = e.id_etat
        ORDER BY t.date_creation DESC
    ");
    // Récupère tous les résultats sous forme de tableau associatif
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En cas d'erreur, affiche le message d'erreur et arrête le script
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des tickets</title>
    <style>
        /* Styles CSS pour la mise en page et l'apparence */
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .actions a { margin-right: 10px; color: #007bff; text-decoration: none; }
        /* Styles pour les états */
        .tr-open {
            background-color: #f00; /* Rouge */
        }
        .tr-progress {
            background-color: #ff0; /* Jaune */
        }
        .tr-closed {
            background-color: #0f0; /* Vert */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Titre principal de la page -->
        <h2>Liste des tickets</h2>
        <!-- Lien pour retourner à la page d'accueil -->
        <a href="accueil.php">Retour à l'accueil</a>

        <!-- Tableau affichant la liste des tickets -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Catégorie</th>
                    <th>État</th>
                    <th>ID Utilisateur</th>
                    <th>Date de création</th>
                    <th>Date de traitement</th>
                    <th>Date de résolution</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                <tr class="
                    <?php
                    if ($ticket['Nom_etat'] == 'Ouvert') echo 'tr-open';
                    elseif ($ticket['Nom_etat'] == 'En court') echo 'tr-progress';
                    elseif ($ticket['Nom_etat'] == 'Fermé') echo 'tr-closed';
                    ?>
                ">
                    <td><?php echo $ticket['id_ticket']; ?></td>
                    <td><?php echo htmlspecialchars($ticket['Description']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['Nom_categorie']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['Nom_etat']); ?></td>
                    <td><?php echo $ticket['id_utilisateur']; ?></td>
                    <td><?php echo $ticket['date_creation']; ?></td>
                    <td><?php echo ($ticket['date_traitement'] ? $ticket['date_traitement'] : '-'); ?></td>
                    <td><?php echo ($ticket['date_resolution'] ? $ticket['date_resolution'] : '-'); ?></td>
                    <td class="actions">
                        <?php if ($ticket['Nom_etat'] == 'Ouvert'): ?>
                            <a href="traiter_ticket.php?id=<?php echo $ticket['id_ticket']; ?>&action=traiter">Traiter</a>
                        <?php endif; ?>
                        <?php if ($ticket['Nom_etat'] == 'En court'): ?>
                            <a href="traiter_ticket.php?id=<?php echo $ticket['id_ticket']; ?>&action=fermer">Fermer</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>