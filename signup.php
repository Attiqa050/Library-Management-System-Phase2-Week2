<?php

require_once "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users (name, email, password)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        $message = "Librarian registered successfully!";
    } else {
        $message = "Signup failed!";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Librarian Sign Up</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="form-container">

        <h1>Librarian Sign Up</h1>

        <?php if ($message !== ""): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                >
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                >
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <button type="submit" class="btn">
                Sign Up
            </button>

        </form>

    </div>

</body>

</html>