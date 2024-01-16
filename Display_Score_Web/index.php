<?php
include 'db.php';



$games = $conn->query('select * from jeux')->fetch_all(MYSQLI_ASSOC);
$users = $conn->query('select * from utilisateurs')->fetch_all(MYSQLI_ASSOC);

$result = null;

// 1. Afficher les scores d'un titre en incluant le nom de l'utilisateur
if (!empty($_GET['titre']) && empty($_GET['utilisateur'])) {
    $nom_jeu = $_GET['titre'];
    

    $sql = "SELECT utilisateurs.Pseudo as user_name, scores.Score, scores.Date_enregistrement as date, jeux.Nom_jeu,
    RANK() OVER (ORDER BY scores.Score DESC) as rank
    FROM scores
    LEFT JOIN jeux ON scores.ID_jeu = jeux.ID_jeu
    INNER JOIN utilisateurs ON scores.ID_utilisateur = utilisateurs.ID_utilisateur
    WHERE jeux.Nom_jeu = '$nom_jeu' OR '$nom_jeu' IS NULL OR '$nom_jeu' = ''
    ORDER BY scores.Score DESC";

    $result = $conn->query($sql);
    echo $sql;
    
    
} elseif (empty($_GET['titre']) && !empty($_GET['utilisateur'])) {

    $nom_utilisateur = $_GET['utilisateur'];
    // 2. Afficher les scores d'un utilisateur pour tous les titres
    $sql = "SELECT utilisateurs.Pseudo as user_name, scores.Score, scores.Date_enregistrement as date, jeux.Nom_jeu
    FROM scores
    LEFT JOIN jeux ON scores.ID_jeu = jeux.ID_jeu
    INNER JOIN utilisateurs ON scores.ID_utilisateur = utilisateurs.ID_utilisateur
    WHERE utilisateurs.Pseudo = '$nom_utilisateur'
    ORDER BY scores.Score DESC";

    $result = $conn->query($sql);
    echo $sql;

} elseif (!empty($_GET['titre']) && !empty($_GET['utilisateur'])) {
    // 3. Afficher le score de l'utilisateur pour un titre
    $nom_utilisateur = $_GET['utilisateur'];
    $nom_jeu = $_GET['titre'];

    $sql = "SELECT scores.Score, jeux.Nom_jeu, utilisateurs.Pseudo
        FROM scores
        INNER JOIN jeux ON scores.ID_jeu = jeux.ID_jeu
        INNER JOIN utilisateurs ON scores.ID_utilisateur = utilisateurs.ID_utilisateur
        WHERE utilisateurs.Pseudo = '$nom_utilisateur' AND jeux.Nom_jeu = '$nom_jeu'";

    $result = $conn->query($sql);
    echo $sql;
    


}


?>

<form action="index.php" method="get">
    <label for="titre">Sélectionnez le Titre :</label>
    <select name="titre" id="titre">
        <option value="">None</option>
        <?php foreach ($games as $game) { ?>
            <option value="<?php echo $game['Nom_jeu']; ?>">
                <?php echo $game['Nom_jeu']; ?>
            </option>
        <?php } ?>
    </select>

    <label for="utilisateur">Nom de l'Utilisateur :</label>
    <select name="utilisateur" id="utilisateur">
        <option value="">None</option>
        <?php foreach ($users as $user) { ?>
            <option value="<?php echo $user['Pseudo']; ?>">
                <?php echo $user['Pseudo']; ?>
            </option>
        <?php } ?>
    </select>
    <input type="submit" value="Rechercher">
</form>

<?php if ($result && $result->num_rows > 0) { ?>
    <table border="1">
        <thead>
            <tr>
                <th>Rang</th>
                <th>Utilisateur</th>
                <th>Titre</th>
                <th>Score</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['rank'] ?? ''; ?></td>
                    <td><?php echo $row['user_name'] ?? ''; ?></td>
                    <td><?php echo $row['Nom_jeu'] ?? ''; ?></td>
                    <td><?php echo $row['Score'] ?? ''; ?></td>
                    <td><?php echo $row['date'] ?? ''; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>Aucun résultat trouvé.</p>
<?php } ?>

<?php


