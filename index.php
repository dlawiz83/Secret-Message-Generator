
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Message Generator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1> Secret Message Generator</h1>
        <p>Type a private message and get a one-time viewable secret link.</p>

        <form action="create.php" method="post">
            <label for="message">What's the secret you'd like to share?</label><br>
            <textarea id="message" name="message" cols="30" rows="4" placeholder="Your secret goes here..."></textarea><br>
            <button type="submit" name="generate">Generate Secret Link</button>
        </form>
    </div>

</body>
</html>
