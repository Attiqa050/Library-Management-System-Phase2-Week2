<?php

require_once "config/db.php";

$sql = "SELECT * FROM books";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Book ID: " . $row["id"] . "<br>";
        echo "Title: " . $row["title"] . "<br>";
        echo "Author: " . $row["author"] . "<br>";
        echo "Category: " . $row["category"] . "<br>";
        echo "Quantity: " . $row["quantity"] . "<br>";
        echo "Available Copies: " . $row["availableCopies"] . "<br>";
    }
} else {
    echo "No books found.";
}

$conn->close();

?>