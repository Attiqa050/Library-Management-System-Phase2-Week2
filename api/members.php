<?php

require_once "auth.php";
header("Content-Type: application/json");
require_once "../config/db.php";


// GET - Read Members
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $result = $conn->query("SELECT * FROM members ORDER BY id");

    $members = [];

    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    echo json_encode($members);
}


// POST - Add Member
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $name = $data["name"];
    $rollNo = $data["rollNo"];
    $email = $data["email"];
    $phone = $data["phone"];

    $stmt = $conn->prepare(
        "INSERT INTO members (name, rollNo, email, phone)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssss",
        $name, $rollNo, $email, $phone
    );

    $stmt->execute();

    echo json_encode([
        "id" => $conn->insert_id,
        "name" => $name,
        "rollNo" => $rollNo,
        "email" => $email,
        "phone" => $phone
    ]);
}


// PATCH - Update Member
if ($_SERVER["REQUEST_METHOD"] === "PATCH") {

    $id = $_GET["id"];
    $data = json_decode(file_get_contents("php://input"), true);

    $name = $data["name"];
    $rollNo = $data["rollNo"];
    $email = $data["email"];
    $phone = $data["phone"];

    $stmt = $conn->prepare(
        "UPDATE members
         SET name=?, rollNo=?, email=?, phone=?
         WHERE id=?"
    );

    $stmt->bind_param(
        "ssssi",
        $name, $rollNo, $email, $phone, $id
    );

    $stmt->execute();

    echo json_encode([
        "message" => "Member updated successfully"
    ]);
}


// DELETE - Delete Member
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {

    $id = $_GET["id"];

    $stmt = $conn->prepare(
        "DELETE FROM members WHERE id=?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode([
        "message" => "Member deleted successfully"
    ]);
}

$conn->close();

?>