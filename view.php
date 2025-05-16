<?php
require_once 'db.php';
$message = null;
$expired = false;
$invalid = false;


  $db = new Database();
  $conn = $db->connect();

  
  $cleanup = $conn->prepare("DELETE FROM messages WHERE created_at < NOW() - INTERVAL 1 DAY");
$cleanup->execute();


if(isset($_GET['token'])){
    $token = $_GET['token'];
   
  $stmt = $conn->prepare("SELECT message FROM messages WHERE token = ?");

  $stmt->bind_param("s" , $token);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $message = $row['message'];
}else {
        $expired = true; 
    }
$deleteStmt = $conn->prepare("DELETE FROM messages WHERE token = ?");
$deleteStmt->bind_param("s", $token);
$deleteStmt->execute();

  
}else {
    
    $invalid = true;
}





?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Secret Message</title>
  <link rel="stylesheet" href="style.css">

</head>
<body>
  <div class="message-container">
  <?php if (!empty($message)): ?>
    <h2>🔐 Your Secret Message:</h2>
    <div class="message-box">
  <p id="secretMessage"><?= htmlspecialchars($message) ?></p>
  <button id="copyBtn">Copy to Clipboard</button>
  <span id="copyStatus" style="color: green; display: none;">Copied!</span>
</div>
     <?php elseif ($expired): ?>
      <p>⏳ This secret message has expired or was already viewed.</p>
<?php elseif ($invalid): ?>
      <p>❌ Invalid or missing secret token. Please check your link.</p>

  <?php else: ?>
    <p>❌ This message has already been viewed or doesn't exist.</p>
  <?php endif; ?>
</div>
<script>
  const copyBtn = document.getElementById('copyBtn');
  const secretMessage = document.getElementById('secretMessage');
  const copyStatus = document.getElementById('copyStatus');

  if(copyBtn) {
    copyBtn.addEventListener('click', () => {
      navigator.clipboard.writeText(secretMessage.textContent)
        .then(() => {
          copyStatus.style.display = 'inline';
          setTimeout(() => { copyStatus.style.display = 'none'; }, 2000);
        })
        .catch(err => {
          alert('Failed to copy: ' + err);
        });
    });
  }
</script>


</body>
</html>
