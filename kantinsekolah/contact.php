<?php
require 'config.php'; // pastikan nama file config sesuai (bukan connection.php)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  $insertOrder = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
  $insertOrder->bind_param("sss", $name, $email, $message);

  if ($insertOrder->execute()) {
   echo "Pesan dari $name telah terkirim.";
   echo "<br><button class='btn btn-primary' onclick=\"window.location.href='index'\">Selesai</button>";

  } else {
    echo "Gagal mengirim pesan: " . $conn->error;
  }
}
?>
