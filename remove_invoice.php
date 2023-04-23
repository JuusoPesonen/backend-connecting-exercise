<?php

require_once "./inc/headers.php";
require_once "./inc/functions.php";


$conn = createDbConnection();

$invoice_item_id = 1;

$sql = "DELETE FROM invoice_items WHERE InvoiceLineId = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $invoice_item_id, PDO::PARAM_INT);
$result = $stmt->execute();

if ($result) {
    echo "Invoice item removed successfully";
} else {
    echo "Error removing invoice item";
}