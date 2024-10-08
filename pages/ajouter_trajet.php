<?php
require '../includes/db.php';

// Récupérer la liste des villes
$sql = "SELECT id, nom FROM villes";
$result = $conn->query($sql);

$villes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $villes[$row['id']] = $row['nom'];
    }
}

// Récupérer la liste des types de transport
$sql = "SELECT id, type FROM transports";
$result = $conn->query($sql);

$transports = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transports[$row['id']] = $row['type'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $etapes = $_POST['etapes']; // Tableau d'IDs de villes
    $transport_id = $_POST['transport']; // ID du transport sélectionné

    if (empty($nom) || empty($etapes) || empty($transport_id)) {
        echo "Le nom du trajet, les étapes, et le type de transport doivent être remplis.";
    } else {
        // Insertion du trajet
        $sqlTrajet = "INSERT INTO trajets (nom, transport_id) VALUES (?, ?)";
        $stmtTrajet = $conn->prepare($sqlTrajet);
        $stmtTrajet->bind_param("si", $nom, $transport_id);

        if ($stmtTrajet->execute()) {
            $trajet_id = $stmtTrajet->insert_id;

            // Insertion des étapes
            $sqlEtape = "INSERT INTO etapes (trajet_id, ville_id, position) VALUES (?, ?, ?)";
            $stmtEtape = $conn->prepare($sqlEtape);

            foreach ($etapes as $position => $ville_id) {
                $stmtEtape->bind_param("iii", $trajet_id, $ville_id, $position);
                $stmtEtape->execute();
            }

            echo "Trajet ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du trajet : " . $stmtTrajet->error;
        }

        $stmtTrajet->close();
        $stmtEtape->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
      <title>Ajouter un trajet</title>
</head>

<body>
      <h1>Ajouter un nouveau trajet</h1>
      <form method="post" action="ajouter_trajet.php">
            <label for="nom">Nom du trajet :</label>
            <input type="text" id="nom" name="nom" required>
            <br><br>

            <!-- Sélection du type de transport -->
            <label for="transport">Type de transport :</label>
            <select id="transport" name="transport" required>
                  <option value="">Sélectionnez un type de transport</option>
                  <?php foreach ($transports as $id => $type) { ?>
                  <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($type); ?></option>
                  <?php } ?>
            </select>
            <br><br>

            <!-- Sélection des villes -->
            <label for="etapes">Étapes du trajet :</label>
            <div id="etapes-container">
                  <div class="etape">
                        <select name="etapes[]" required>
                              <option value="">Sélectionnez une ville</option>
                              <?php foreach ($villes as $id => $nom) { ?>
                              <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($nom); ?></option>
                              <?php } ?>
                        </select>
                  </div>
            </div>
            <button type="button" id="ajouter-etape">Ajouter une étape</button>
            <br><br>
            <button type="submit">Ajouter le trajet</button>
      </form>

      <script>
      // Ajoute un champ de sélection pour une nouvelle étape
      document.getElementById('ajouter-etape').addEventListener('click', function() {
            var container = document.getElementById('etapes-container');
            var div = document.createElement('div');
            div.classList.add('etape');
            div.innerHTML = '<select name="etapes[]" required>' +
                  '<option value="">Sélectionnez une ville</option>' +
                  <?php foreach ($villes as $id => $nom) { ?> '<option value="<?php echo $id; ?>"><?php echo htmlspecialchars($nom); ?></option>' +
                  <?php } ?> '</select>';
            container.appendChild(div);
      });
      </script>
</body>

</html>