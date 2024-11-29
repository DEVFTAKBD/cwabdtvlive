<?php
session_start();

// Path to the password file
$passwordFile = __DIR__ . '/secure/password.txt';

// Redirect to the main domain if the `secure` directory is accessed directly
if (!file_exists($passwordFile)) {
    header("Location: http://farhanlivetv.app.tc");
    exit;
}

// Check login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $inputPassword = $_POST['password'];
    $storedHash = trim(file_get_contents($passwordFile));

    if (password_verify($inputPassword, $storedHash)) {
        $_SESSION['loggedin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid password!";
    }
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #222;
            color: #fff;
            text-align: center;
            padding-top: 100px;
        }
        input {
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
<?php
    exit;
endif;
?>

<!-- File Generator -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host M3U8 File Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #222;
            color: #fff;
        }
        h1 {
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: #333;
            border-radius: 10px;
        }
        textarea, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>M3U8 File Generator</h1>
    <div class="container">
        <textarea id="m3u8Link" placeholder="Paste your .m3u8 link here..."></textarea>
        <input type="text" id="fileName" placeholder="Enter file name (e.g., channel-name)">
        <button onclick="generateFile()">Generate & Host M3U8 File</button>
        <div id="result"></div>
    </div>

    <script>
        function generateFile() {
            const link = document.getElementById('m3u8Link').value.trim();
            const fileName = document.getElementById('fileName').value.trim();

            if (!link || !fileName) {
                alert("Please enter both a valid link and file name!");
                return;
            }

            const data = new FormData();
            data.append('link', link);
            data.append('fileName', fileName);

            fetch('create.php', {
                method: 'POST',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    document.getElementById('result').innerHTML = `
                        File created successfully! 
                        <a href="${result.url}" target="_blank">View File</a>
                    `;
                } else {
                    alert(result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the file.');
            });
        }
    </script>
</body>
</html>
