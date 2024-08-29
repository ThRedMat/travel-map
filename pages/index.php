<?php
require '../includes/db.php';

// Récupération des trajets avec les noms des villes
$sql = "
SELECT id, nom FROM trajets
";

$result = $conn->query($sql);

$trajets = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $trajets[] = $row;
    }
} else {
    $trajets = []; // Assurez-vous que $trajets est défini comme un tableau vide si aucun résultat
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
      <title>Liste des trajets</title>
      <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
      <h1>Trajets enregistrés</h1>
      <?php if (!empty($trajets)) { ?>
      <table border="1">
            <tr>
                  <th>Numéro du trajet</th>
                  <th>Nom</th>

            </tr>
            <?php foreach ($trajets as $trajet) { ?>
            <tr>
                  <td><?php echo htmlspecialchars($trajet['id']); ?></td>
                  <td><?php echo htmlspecialchars($trajet['nom']); ?></td>

            </tr>
            <?php } ?>
      </table>
      <?php } else { ?>
      <p>Aucun trajet n'est disponible.</p>
      <?php } ?>

      <!-- Bouton pour accéder à la carte -->
      <br>
      <form action="carte.php" method="get">
            <button type="submit">Voir la carte des trajets</button>
      </form>

      <!-- Bouton pour accéder à la page d'ajout de ville -->
      <br>
      <form action="ajouter_trajet.php" method="get">
            <button type="submit">Ajouter un trajet</button>
      </form>

      <br>
      <form action="ajouter_voyage.php" method="get">
            <button type="submit">Ajouter un voyage</button>
      </form>
</body>

</html>