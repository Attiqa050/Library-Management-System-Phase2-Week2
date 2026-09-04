<?php

require_once "auth.php";

header("Content-Type: application/json");
require_once "../config/db.php";


// GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $result = $conn->query("SELECT * FROM issues ORDER BY id");

    $issues = [];

    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
    }

    echo json_encode($issues);
}


// POST - Issue Book
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $bookId = $data["bookId"];
    $memberId = $data["memberId"];
    $issueDate = $data["issueDate"];
    $dueDate = $data["dueDate"];

    // Check available copies
    $stmt = $conn->prepare(
        "SELECT availableCopies FROM books WHERE id=?"
    );

    $stmt->bind_param("i", $bookId);
    $stmt->execute();

    $book = $stmt->get_result()->fetch_assoc();

    if (!$book || $book["availableCopies"] <= 0) {

        http_response_code(400);

        echo json_encode([
            "message" => "Book not available"
        ]);

        exit();
    }

    // Add Issue
    $status = "Issued";

    $stmt = $conn->prepare(
        "INSERT INTO issues
        (bookId, memberId, issueDate, dueDate, status)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "iisss",
        $bookId,
        $memberId,
        $issueDate,
        $dueDate,
        $status
    );

    $stmt->execute();

    // Reduce available copies
    $stmt = $conn->prepare(
        "UPDATE books
         SET availableCopies = availableCopies - 1
         WHERE id=?"
    );

    $stmt->bind_param("i", $bookId);
    $stmt->execute();

    echo json_encode([
        "id" => $conn->insert_id,
        "message" => "Book issued successfully"
    ]);
}


// PATCH - Return Book
if ($_SERVER["REQUEST_METHOD"] === "PATCH") {

    $id = $_GET["id"];
    $data = json_decode(file_get_contents("php://input"), true);

    $returnDate = $data["returnDate"];

    // Find issued book
    $stmt = $conn->prepare(
        "SELECT bookId, status FROM issues WHERE id=?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $issue = $stmt->get_result()->fetch_assoc();

    if ($issue && $issue["status"] !== "Returned") {

        $stmt = $conn->prepare(
            "UPDATE issues
             SET returnDate=?, status='Returned'
             WHERE id=?"
        );

        $stmt->bind_param("si", $returnDate, $id);
        $stmt->execute();

        $stmt = $conn->prepare(
            "UPDATE books
             SET availableCopies = availableCopies + 1
             WHERE id=?"
        );

        $stmt->bind_param("i", $issue["bookId"]);
        $stmt->execute();
    }

    echo json_encode([
        "message" => "Book returned successfully"
    ]);
}


// DELETE
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {

    $id = $_GET["id"];

    $stmt = $conn->prepare(
        "DELETE FROM issues WHERE id=?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode([
        "message" => "Issue deleted successfully"
    ]);
}

$conn->close();