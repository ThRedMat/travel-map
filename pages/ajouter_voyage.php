<?php
require '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $depart = trim($_POST['depart']);
    $correspondance = trim($_POST['correspondance']);
    $arrivee = trim($_POST['arrivee']);

    // Validation des données
    if (empty($depart) || empty($arrivee) || empty($correspondance)) {
        echo "Les champs de départ et d'arrivée doivent être remplis.";
    } else {
        // Insertion du voyage dans la base de données
        $sql = "INSERT INTO voyages (depart, correspondance, arrivee) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $depart, $correspondance, $arrivee);

        if ($stmt->execute()) {
            echo "Voyage ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du voyage : " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
      <title>Ajouter un voyage</title>
      <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
      <h1>Ajouter un nouveau voyage</h1>
      <form method="post" action="ajouter_voyage.php">
            <label for="depart">Ville de départ :</label>
            <input type="text" id="depart" name="depart" required>
            <br><br>
            <label for="correspondance"> Ville de correspondance : </label>
            <input type="text" name="correspondance" id="correspondance">
            <br><br>
            <label for="arrivee">Ville d'arrivée :</label>
            <input type="text" id="arrivee" name="arrivee" required>
            <br><br>
            <button type="submit">Ajouter le voyage</button>
      </form>
</body>

</html>