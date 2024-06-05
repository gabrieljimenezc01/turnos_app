<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $caja_id = $_POST['caja_id'];

  $stmt = $conn->prepare("SELECT * FROM turnos WHERE caja_id = ? AND estado = 'espera' ORDER BY id ASC LIMIT 1");
  $stmt->execute([$caja_id]);
  $turno = $stmt->fetch();

  if ($turno) {
    $stmt = $conn->prepare("UPDATE turnos SET estado = 'atendido' WHERE id = ?");
    $stmt->execute([$turno['id']]);
    echo "Turno atendido: " . $turno['numero'];
  } else {
    echo "No hay turnos en espera para esta caja.";
  }
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
    <button type="submit">Avanzar Turno</button>
  </form>
<?php
}
?>
