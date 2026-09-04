<?php

header("Content-Type: application/json");
require_once "../config/db.php";


// GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $result = $conn->query("SELECT * FROM books ORDER BY id");

    $books = [];

    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    echo json_encode($books);
}


// POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $title = $data["title"];
    $author = $data["author"];
    $category = $data["category"];
    $totalCopies = $data["totalCopies"];
    $availableCopies = $totalCopies;

    $stmt = $conn->prepare(
        "INSERT INTO books
        (title, author, category, totalCopies, availableCopies)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssii",
        $title,
        $author,
        $category,
        $totalCopies,
        $availableCopies
    );

    $stmt->execute();

    echo json_encode([
        "id" => $conn->insert_id,
        "message" => "Book added successfully"
    ]);
}


// PATCH
if ($_SERVER["REQUEST_METHOD"] === "PATCH") {

    $id = $_GET["id"];

    $data = json_decode(file_get_contents("php://input"), true);


    // Issue / Return Book
    if (isset($data["availableCopies"]) && !isset($data["title"])) {

        $availableCopies = $data["availableCopies"];

        $stmt = $conn->prepare(
            "UPDATE books
             SET availableCopies=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "ii",
            $availableCopies,
            $id
        );
    }


    // Edit Book
    else {

        $title = $data["title"];
        $author = $data["author"];
        $category = $data["category"];
        $totalCopies = $data["totalCopies"];
        $availableCopies = $data["availableCopies"];

        $stmt = $conn->prepare(
            "UPDATE books
             SET title=?, author=?, category=?,
                 totalCopies=?, availableCopies=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "sssiii",
            $title,
            $author,
            $category,
            $totalCopies,
            $availableCopies,
            $id
        );
    }

    $stmt->execute();

    echo json_encode([
        "message" => "Book updated successfully"
    ]);
}


// DELETE
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {

    $id = $_GET["id"];

    $stmt = $conn->prepare(
        "DELETE FROM books WHERE id=?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    echo json_encode([
        "message" => "Book deleted successfully"
    ]);
}


$conn->close();

?>