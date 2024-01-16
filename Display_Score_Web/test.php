<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scoring_manager";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$chemin_fichier="C:/Site/ListVPX.txt";

$lines = file($chemin_fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$insert_query = $conn->prepare("INSERT INTO jeux (Nom_jeu) VALUES (?)");

foreach ($lines as $line) {
    $titre = trim($line); // Supprimer les espaces blancs
    $insert_query->bind_param("s", $titre);
    $insert_query->execute();
}


$insert_query->close();
$conn->close();


?>

<?php
/*
// Requête SQL pour récupérer les scores de l'utilisateur sur tous les jeux
$sql = "SELECT scores.Score, jeux.Nom_jeu
        FROM scores
        INNER JOIN jeux ON scores.ID_jeu = jeux.ID_jeu
        INNER JOIN utilisateurs ON scores.ID_utilisateur = utilisateurs.ID_utilisateur
        WHERE utilisateurs.Pseudo = '$nom_utilisateur'";

$result = $conn->query($sql);

// Vérification des résultats et affichage
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Jeu: " . $row["Nom_jeu"] . " - Score: " . $row["Score"] . "<br>";
    }
} else {
    echo "Aucun score trouvé pour cet utilisateur.";
}

// Fermeture de la connexion à la base de données
$conn->close();
*/
?>


<?php
/*
1. Afficher les scores d'un titre en incluant le nom de l'utilisateur
$sql = "SELECT scores.Score, utilisateurs.Pseudo
        FROM scores
        INNER JOIN jeux ON scores.ID_jeu = jeux.ID_jeu
        INNER JOIN utilisateurs ON scores.ID_utilisateur = utilisateurs.ID_utilisateur
        WHERE jeux.Nom_jeu = '$nom_jeu'";

$result = $conn->query($sql);

// Vérification des résultats et affichage
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Utilisateur: " . $row["Pseudo"] . " - Score: " . $row["Score"] . "<br>";
    }
} else {
    echo "Aucun score trouvé pour ce jeu.";
}


$conn->close();
*/
?>