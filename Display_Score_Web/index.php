<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisation des Scores de Jeux</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Tableau des Scores</h1>

    <!-- Formulaire pour sélectionner le titre et rechercher un utilisateur -->
    <form action="index.php" method="get">
        <label for="titre">Sélectionnez le Titre :</label>
        <select name="titre" id="titre">
            <!-- Mettre une bd -->
            <option value="Attack">Attack</option>
            <option value="titre2">Titre 2</option>
            
        </select>

        <label for="utilisateur">Nom de l'Utilisateur :</label>
        <input type="text" name="utilisateur" id="utilisateur" placeholder="Entrez le nom de l'utilisateur">

        <input type="submit" value="Rechercher">
    </form>

    <?php
    
    if (isset($_GET['titre']) || isset($_GET['utilisateur'])) {
        
        include 'top_scores.php';
    }
    ?>
</body>
</html>
