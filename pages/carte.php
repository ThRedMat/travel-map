<?php
require '../includes/db.php';

// Récupération de l'ID du trajet depuis la requête GET
$trajet_id = isset($_GET['trajet_id']) ? intval($_GET['trajet_id']) : 0;

// Récupération des détails du trajet, y compris le mode de transport
$sqlTrajet = "SELECT trajets.nom, transports.type AS mode_transport 
              FROM trajets 
              JOIN transports ON trajets.transport_id = transports.id 
              WHERE trajets.id = ?";
$stmt = $conn->prepare($sqlTrajet);
$stmt->bind_param("i", $trajet_id);
$stmt->execute();
$resultTrajet = $stmt->get_result();
$trajet = $resultTrajet->fetch_assoc();



$mode_transport = isset($trajet['mode_transport']) ? $trajet['mode_transport'] : 'default'; // Mode de transport, valeur par défaut si non défini

// Récupération des étapes du trajet
$sqlEtapes = "SELECT ville_id FROM etapes WHERE trajet_id = ? ORDER BY position";
$stmt = $conn->prepare($sqlEtapes);
$stmt->bind_param("i", $trajet_id);
$stmt->execute();
$resultEtapes = $stmt->get_result();

$etapes = [];
while ($row = $resultEtapes->fetch_assoc()) {
    $etapes[] = $row['ville_id'];
}

// Récupération des coordonnées des villes
$sqlVilles = "SELECT id, nom, latitude, longitude FROM villes";
$resultVilles = $conn->query($sqlVilles);

$villesCoords = [];
if ($resultVilles->num_rows > 0) {
    while ($row = $resultVilles->fetch_assoc()) {
        $villesCoords[$row['id']] = [
            'nom' => $row['nom'],
            'coords' => [$row['latitude'], $row['longitude']]
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Carte du trajet</title>
      <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
      <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
      <style>
      /* Global Styles */
      body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
      }

      h1 {
            text-align: center;
            margin-top: 20px;
            color: #1d4e89;
            font-size: 2em;
      }

      .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
      }

      .tables-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
      }

      .details {
            flex: 1;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
      }

      table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
      }

      th,
      td {
            padding: 12px 15px;
            text-align: center;
      }

      th {
            background-color: #1d4e89;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
      }

      tr:nth-child(even) {
            background-color: #f9f9f9;
      }

      td {
            border-bottom: 1px solid #ddd;
            text-align: center;
      }

      td:first-child,
      th:first-child {
            text-align: center;
      }

      /* Map Styles */
      #map {
            height: 500px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
      }

      /* Image de transport */
      .transport-icon {
            width: 90px;
            height: auto;
            margin-left: 10px;
            margin-right: 10px;
      }

      /* Ajustement des colonnes */
      .details table th,
      .details table td {
            padding: 10px;
      }

      .details table td img {
            display: block;
            margin: 0 auto;
      }

      .details table th:nth-child(2),
      .details table th:nth-child(4),
      .details table td:nth-child(2),
      .details table td:nth-child(4) {
            width: 50px;
      }
      </style>
</head>

<body>
      <h1>Carte du trajet : <?php echo htmlspecialchars($trajet['nom']); ?></h1>
      <div class="container">
            <div id="map"></div>

            <div class="tables-container">
                  <div class="details">
                        <h2>Trajet Aller</h2>
                        <table>
                              <thead>
                                    <tr>
                                          <th>Départ</th>
                                          <th></th> <!-- Colonne pour l'image du mode de transport -->
                                          <?php if (count($etapes) > 2) : ?>
                                          <th>Correspondance</th>
                                          <th></th> <!-- Colonne pour l'image du mode de transport -->
                                          <?php endif; ?>
                                          <th>Arrivée</th>
                                    </tr>
                              </thead>
                              <tbody>
                                    <tr>
                                          <!-- Lieu de départ -->
                                          <td><?php echo htmlspecialchars($villesCoords[$etapes[0]]['nom']); ?></td>
                                          <td><img src="../images/<?php echo htmlspecialchars($mode_transport); ?>.png"
                                                      alt="<?php echo htmlspecialchars($mode_transport); ?>"
                                                      class="transport-icon"></td>
                                          <!-- Image du mode de transport -->
                                          <!-- Lieu de correspondance (si plus d'une étape) -->
                                          <?php if (count($etapes) > 2) : ?>
                                          <td>
                                                <?php
                                    for ($i = 1; $i < count($etapes) - 1; $i++) {
                                        echo htmlspecialchars($villesCoords[$etapes[$i]]['nom']) . "<br>";
                                    }
                                    ?>
                                          </td>
                                          <td><img src="../images/<?php echo htmlspecialchars($mode_transport); ?>.png"
                                                      alt="<?php echo htmlspecialchars($mode_transport); ?>"
                                                      class="transport-icon"></td>
                                          <!-- Image du mode de transport -->
                                          <?php endif; ?>
                                          <!-- Lieu d'arrivée -->
                                          <td><?php echo htmlspecialchars($villesCoords[$etapes[count($etapes) - 1]]['nom']); ?>
                                          </td>
                                    </tr>
                              </tbody>
                        </table>
                  </div>

                  <div class="details">
                        <h2>Trajet Retour</h2>
                        <table>
                              <thead>
                                    <tr>
                                          <th>Départ</th>
                                          <th></th> <!-- Colonne pour l'image du mode de transport -->
                                          <?php if (count($etapes) > 2) : ?>
                                          <th>Correspondance</th>
                                          <th></th> <!-- Colonne pour l'image du mode de transport -->
                                          <?php endif; ?>
                                          <th>Arrivée</th>
                                    </tr>
                              </thead>
                              <tbody>
                                    <tr>
                                          <!-- Lieu de départ pour le retour -->
                                          <td><?php echo htmlspecialchars($villesCoords[$etapes[count($etapes) - 1]]['nom']); ?>
                                          </td>
                                          <td><img src="../images/<?php echo htmlspecialchars($mode_transport); ?>.png"
                                                      alt="<?php echo htmlspecialchars($mode_transport); ?>"
                                                      class="transport-icon"></td>
                                          <!-- Image du mode de transport -->
                                          <!-- Lieu de correspondance pour le retour (si plus d'une étape) -->
                                          <?php if (count($etapes) > 2) : ?>
                                          <td>
                                                <?php
                                    for ($i = count($etapes) - 2; $i > 0; $i--) {
                                        echo htmlspecialchars($villesCoords[$etapes[$i]]['nom']) . "<br>";
                                    }
                                    ?>
                                          </td>
                                          <td><img src="../images/<?php echo htmlspecialchars($mode_transport); ?>.png"
                                                      alt="<?php echo htmlspecialchars($mode_transport); ?>"
                                                      class="transport-icon"></td>
                                          <!-- Image du mode de transport -->
                                          <?php endif; ?>
                                          <!-- Lieu d'arrivée pour le retour -->
                                          <td><?php echo htmlspecialchars($villesCoords[$etapes[0]]['nom']); ?></td>
                                    </tr>
                              </tbody>
                        </table>
                  </div>
            </div>
      </div>

      <script>
      // Initialisation de la carte
      var map = L.map('map').setView([48.8566, 2.3522], 2); // Centré sur Paris

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
      }).addTo(map);

      // Données des étapes et villes depuis PHP
      var etapes = <?php echo json_encode($etapes); ?>;
      var villesCoords = <?php echo json_encode($villesCoords); ?>;

      var coords = etapes.map(function(ville_id) {
            return villesCoords[ville_id].coords;
      });

      if (coords.length > 0) {
            // Affichage des marqueurs pour chaque étape
            coords.forEach(function(coord) {
                  var villeNom = villesCoords[etapes[coords.indexOf(coord)]].nom;
                  L.marker(coord).addTo(map)
                        .bindPopup(villeNom);
            });

            // Tracer la polyline reliant les étapes
            L.polyline(coords, {
                  color: 'blue'
            }).addTo(map);

            // Ajuster les vues sur les coordonnées
            map.fitBounds(L.polyline(coords).getBounds());
      }
      </script>
</body>

</html>