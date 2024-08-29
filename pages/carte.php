<?php
require '../includes/db.php';

// Récupération des trajets et étapes
$sqlTrajets = "SELECT id, nom FROM trajets";
$resultTrajets = $conn->query($sqlTrajets);

$trajets = [];
if ($resultTrajets->num_rows > 0) {
    while ($row = $resultTrajets->fetch_assoc()) {
        $trajets[$row['id']] = ['nom' => $row['nom'], 'etapes' => []];
    }
}

$sqlEtapes = "SELECT trajet_id, ville_id, position FROM etapes ORDER BY position";
$resultEtapes = $conn->query($sqlEtapes);

if ($resultEtapes->num_rows > 0) {
    while ($row = $resultEtapes->fetch_assoc()) {
        $trajets[$row['trajet_id']]['etapes'][] = $row['ville_id'];
    }
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
<html>

<head>
      <title>Carte des trajets</title>
      <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
      <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body>
      <div id="map" style="height: 500px;"></div>

      <script>
      // Initialisation de la carte
      var map = L.map('map').setView([48.8566, 2.3522], 2); // Centré sur Paris

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
      }).addTo(map);

      // Données des trajets et villes depuis PHP
      var trajets = <?php echo json_encode($trajets); ?>;
      var villesCoords = <?php echo json_encode($villesCoords); ?>;

      // Affichage des trajets
      for (var trajet_id in trajets) {
            var etapes = trajets[trajet_id].etapes.map(function(ville_id) {
                  return villesCoords[ville_id].coords;
            });

            if (etapes.length > 0) {
                  // Affichage des marqueurs pour chaque étape
                  etapes.forEach(function(coords, index) {
                        L.marker(coords).addTo(map).bindPopup(villesCoords[trajets[trajet_id].etapes[index]]
                              .nom);
                  });

                  // Tracer la polyline reliant les étapes
                  L.polyline(etapes, {
                        color: 'blue'
                  }).addTo(map);
            }
      }
      </script>
</body>

</html>