<?php
require '../includes/db.php';

// Ajout d'un nouveau voyage
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $depart = $_POST['depart'];
    $arrivee = $_POST['arrivee'];

    $stmt = $conn->prepare("INSERT INTO voyages (depart, arrivee) VALUES (?, ?)");
    $stmt->bind_param('ss', $depart, $arrivee);

    if ($stmt->execute()) {
        echo "Voyage ajouté avec succès.";
    } else {
        echo "Erreur : " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Ajouter un Voyage</title>
</head>

<body>
      <h1>Ajouter un Nouveau Voyage</h1>
      <form action="ajouter_ville.php" method="post">
            <label for="depart">Ville de Départ:</label>
            <input type="text" id="depart" name="depart" required>
            <br>
            <label for="arrivee">Ville d'Arrivée:</label>
            <input type="text" id="arrivee" name="arrivee" required>
            <br>
            <button type="submit">Ajouter</button>
      </form>
</body>

</html>