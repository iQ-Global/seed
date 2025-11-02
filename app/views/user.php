<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        a { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <p><a href="/">← Back to Home</a></p>
    
    <h1><?= esc($title) ?></h1>
    <p>✓ Route parameters working!</p>
    <p><strong>User ID:</strong> <?= esc($userId) ?></p>
</body>
</html>

