<!-- search_results.php -->
<?php
include 'db.php';

if (isset($_GET['titre']) && $_GET['titre'] !== 'none') {
    $selectedTitle = $_GET['titre'];
    $sql = "SELECT user, score, datetime FROM scoreboard WHERE title = '$selectedTitle' ORDER BY score DESC LIMIT 10";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        echo "<table><tr><th>Utilisateur</th><th>Score</th><th>Date/Heure</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["user"] . "</td><td>" . $row["score"] . "</td><td>" . $row["datetime"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Aucun score trouvé pour le titre : $selectedTitle.";
    }

} else {
    echo "Titre non spécifié.";
}



if (isset($_GET['utilisateur']) && !empty($_GET['utilisateur'])) {
    $selectedUser = $_GET['utilisateur'];
    echo "<h2>Scores de l'Utilisateur : $selectedUser</h2>";
    
    }
?>
