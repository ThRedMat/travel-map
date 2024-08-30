<?php
require '../includes/db.php';

// Récupération des trajets avec les noms des villes
$sql = "SELECT trajets.id, trajets.nom, transports.type 
        FROM trajets
        JOIN transports ON trajets.transport_id = transports.id;";
$result = $conn->query($sql);

$trajets = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $trajets[] = $row;
    }
} else {
    $trajets = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Liste des trajets</title>
      <style>
      body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
      }

      .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            /* Centrer tout le contenu textuel */
      }

      h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            /* Centrer le titre */
      }

      table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
      }

      th,
      td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            /* Centrer le texte dans les cellules */
      }

      th {
            background-color: #343a40;
            color: #ffffff;
      }

      .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin: 5px;
      }

      .btn:hover {
            background-color: #0056b3;
      }

      .alert {
            padding: 15px;
            background-color: #ffc107;
            color: #856404;
            border-radius: 5px;
            margin-bottom: 20px;
            display: inline-block;
            width: 100%;
            /* Assure que l'alerte prenne toute la largeur du container */
            text-align: center;
            /* Centre le texte dans l'alerte */
      }
      </style>
</head>

<body>
      <div class="container">
            <h1>Trajets enregistrés</h1>

            <?php if (!empty($trajets)) { ?>
            <table>
                  <thead>
                        <tr>
                              <th>Numéro du trajet</th>
                              <th>Nom</th>
                              <th>Moyen de transports utilisé</th>
                              <th>Action</th>
                        </tr>
                  </thead>
                  <tbody>
                        <?php foreach ($trajets as $trajet) { ?>
                        <tr>
                              <td><?php echo htmlspecialchars($trajet['id']); ?></td>
                              <td><?php echo htmlspecialchars($trajet['nom']); ?></td>
                              <td><?php echo htmlspecialchars($trajet['type']); ?></td>
                              <td>
                                    <form action="carte.php" method="get" style="display: inline;">
                                          <input type="hidden" name="trajet_id"
                                                value="<?php echo htmlspecialchars($trajet['id']); ?>">
                                          <button type="submit" class="btn">Voir sur la carte</button>
                                    </form>
                              </td>
                        </tr>
                        <?php } ?>
                  </tbody>
            </table>
            <?php } else { ?>
            <div class="alert">
                  Aucun trajet n'est disponible.
            </div>
            <?php } ?>

            <a href="ajouter_trajet.php" class="btn" style="background-color: #28a745;">Ajouter un trajet</a>
            <!-- <a href="ajouter_voyage.php" class="btn" style="background-color: #17a2b8;">Ajouter un voyage</a>-->
      </div>
</body>

</html>