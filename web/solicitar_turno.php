<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $caja_id = $_POST['caja_id'];

  $stmt = $conn->prepare("SELECT MAX(numero) AS max_numero FROM turnos WHERE caja_id = ?");
  $stmt->execute([$caja_id]);
  $result = $stmt->fetch();
  $nuevo_turno = $result['max_numero'] + 1;

  $stmt = $conn->prepare("INSERT INTO turnos (caja_id, numero) VALUES (?, ?)");
  $stmt->execute([$caja_id, $nuevo_turno]);

  echo "Tu turno es: " . $nuevo_turno;
} else {
  $stmt = $conn->query("SELECT * FROM cajas");
  $cajas = $stmt->fetchAll();
?>
  <form method="post">
    <label for="caja_id">Selecciona Caja:</label>
    <select name="caja_id">
      <?php foreach ($cajas as $caja): ?>
        <option value="<?= $caja['id'] ?>"><?= $caja['nombre'] ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit">Solicitar Turno</button>
  </form>
<?php
}
?>
