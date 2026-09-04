<?php

session_start();
require_once "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, name, password
         FROM users
         WHERE email=?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];

            header("Location: html/home.html");
            exit();

        } else {
            $message = "Invalid email or password.";
        }

    } else {
        $message = "Invalid email or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Librarian Login</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="form-container">

    <h1>Librarian Login</h1>

    <?php if ($message !== ""): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

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
            Login
        </button>

    </form>

</div>

</body>
</html>