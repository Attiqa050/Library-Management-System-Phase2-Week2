<?php

require_once "auth.php";

header("Content-Type: application/json");
require_once "../config/db.php";


// GET - Read Issues
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $result = $conn->query("SELECT * FROM issues ORDER BY id");

    $issues = [];

    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
    }

    echo json_encode($issues);
}


// POST - Add Issue
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $bookId = $data["bookId"];
    $memberId = $data["memberId"];
    $issueDate = $data["issueDate"];
    $dueDate = $data["dueDate"];

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

    echo json_encode([
        "id" => $conn->insert_id,
        "bookId" => $bookId,
        "memberId" => $memberId,
        "issueDate" => $issueDate,
        "dueDate" => $dueDate,
        "returnDate" => null,
        "status" => $status
    ]);
}


// PATCH - Return Book
if ($_SERVER["REQUEST_METHOD"] === "PATCH") {

    $id = $_GET["id"];
    $data = json_decode(file_get_contents("php://input"), true);

    $returnDate = $data["returnDate"];
    $status = $data["status"];

    $stmt = $conn->prepare(
        "UPDATE issues
        SET returnDate=?, status=?
        WHERE id=?"
    );

    $stmt->bind_param(
        "ssi",
        $returnDate,
        $status,
        $id
    );

    $stmt->execute();

    echo json_encode([
        "message" => "Issue updated successfully"
    ]);
}


// DELETE - Delete Issue
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

?>