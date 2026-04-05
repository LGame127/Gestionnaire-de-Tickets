<?php
// Démarre une session PHP pour accéder aux données de session existantes
session_start();

// Supprime toutes les variables de session enregistrées
// (efface le tableau $_SESSION mais conserve la session active)
session_unset();

// Détruit la session actuelle, supprimant ainsi tous les données de session côté serveur
// et libérant l'identifiant de session
session_destroy();

// Redirige l'utilisateur vers la page de connexion (index.php)
header("Location: index.php");

// Arrête l'exécution du script pour éviter toute opération après la redirection
exit();
?>