<?php
require_once 'db.php';
$messageBox = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate'])) {
    $message = trim($_POST['message']);

    if (empty($message)) {
        $messageBox = "<div class='message-box error'>❌ Message cannot be empty.</div>";
    } elseif (strlen($message) > 500) {
        $messageBox = "<div class='message-box error'>❌ Message too long! Please limit to 500 characters.</div>";
    } else {
        $token = bin2hex(random_bytes(32));
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("INSERT INTO messages(message, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $message, $token);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $link = "https://localhost/secret/secret-message-app/view.php?token=" . $token;
            $messageBox = "<div class='message-box success'>🎉 Secret link: <a href='$link'>$link</a></div>";
        } else {
            $messageBox = "<div class='message-box error'>❌ Failed to save message.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secret Link Generator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if (!empty($messageBox)) echo $messageBox; ?>

<form action="" method="post">
    <label for="message">Enter your secret message:</label><br>
    <textarea id="message" name="message" rows="4" cols="50" maxlength="500" required></textarea><br>
    <button type="submit" name="generate">Generate Secret Link</button>
</form>

</body>
</html>
