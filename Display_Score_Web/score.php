<?php
include 'db.php';

// Sélectionnez les scores depuis la base de données
$sql = "SELECT * FROM scoreboard";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Affichez les scores dans un tableau HTML
    echo "<table><tr><th>ID</th><th>Utilisateur</th><th>Titre</th><th>Score</th><th>Date/Heure</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["user"]."</td><td>".$row["title"]."</td><td>".$row["score"]."</td><td>".$row["datetime"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "Aucun score trouvé.";
}

// Fermez la connexion
$conn->close();
?>