<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 3) {
    header("Location: ../index.php");
    exit();
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gestionnaire_de_tickets;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Suppression d'un utilisateur
if (isset($_POST['delete_user'])) {
    $id_utilisateur = $_POST['id_utilisateur'];
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$id_utilisateur]);
    header("Location: utilisateurs.php");
    exit();
}

// Récupérer tous les utilisateurs avec rôle 1 ou 2
$stmt = $pdo->prepare("
    SELECT u.id_utilisateur, u.Prenom, u.Nom, u.login, u.Actif, r.Nom_role
    FROM utilisateur u
    JOIN role r ON u.id_role = r.id_role
    WHERE u.id_role IN (1, 2)
");
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .actions { display: flex; gap: 5px; }
        .actions form { display: inline; }
        .actions button { padding: 5px 10px; cursor: pointer; }
        .delete-btn { background-color: #dc3545; color: white; border: none; border-radius: 4px; }
        .create-user { margin: 20px 0; text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestion des utilisateurs</h2>
        <div class="create-user">
            <a href="creer_utilisateur.php" class="button">Créer un utilisateur</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Login</th>
                    <th>Actif</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= htmlspecialchars($utilisateur['id_utilisateur']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['Prenom']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['Nom']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['login']) ?></td>
                    <td><?= $utilisateur['Actif'] ? 'Oui' : 'Non' ?></td>
                    <td><?= htmlspecialchars($utilisateur['Nom_role']) ?></td>
                    <td class="actions">
                        <form action="modifier_utilisateur.php" method="post" style="display: inline;">
                            <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur'] ?>">
                            <button type="submit">Modifier</button>
                        </form>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="id_utilisateur" value="<?= $utilisateur['id_utilisateur'] ?>">
                            <button type="submit" name="delete_user" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align: right; margin-top: 20px;">
            <a href="../traitement/accueil.php">Retour</a>
        </div>
    </div>
</body>
</html>
