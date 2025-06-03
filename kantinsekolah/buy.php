<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $itemName = $_POST['item'];
  $qty = $_POST['qty'];
  if ($qty < 1) {
  echo "Jumlah pembelian tidak boleh kurang dari 1.";
  echo "<br><button onclick='window.location.href=\"index.php\"'>Kembali</button>";
  exit;
  }

  $stmt = $conn->prepare("SELECT id, price, stock from menu_items where item = ?");
  $stmt->bind_param("s", $itemName);
  $stmt->execute();
  $result = $stmt->get_result();

  $item = $result->fetch_assoc();
  $itemId = $item['id'];
  $price = $item['price'];
  $stock = $item['stock'];
  $total = $qty * $price;

  if ($stock < $qty){
    echo "Stock tidak mencukupi. Stock tersisa $stock.";
    echo "<br><button onclick = 'window.location.href=\"index.php\"'>Selesai</button>";
    exit;
  }

  $newStock = $stock-$qty;
  $updateStock = $conn->prepare("UPDATE menu_items set stock = ? where id = ?");
  $updateStock->bind_param("ii", $newStock, $itemId);
  $updateStock->execute();

  $insertOrder = $conn->prepare("INSERT INTO orders (item, quantity, total_price) values (?, ?, ?)");
  $insertOrder->bind_param("sii", $itemName, $qty, $total);
  $insertOrder->execute();

  echo "Terimakasih telah membeli $itemName sebanyak $qty porsi. Totalnya adalah $total. Stock tersisa $newStock.";
  echo "<br><img src = 'images/dummy-qr.png'>";
  echo "<br><button onclick = 'window.location.href=\"index.php\"'>Selesai</button>";
}
?>