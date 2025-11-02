<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            line-height: 1.6;
        }
        h1 { color: #2c3e50; }
        p { color: #555; }
        .success { color: #27ae60; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
        nav { margin: 20px 0; padding: 10px 0; border-bottom: 1px solid #eee; }
        nav a { margin-right: 20px; }
    </style>
</head>
<body>
    <nav>
        <a href="/">Home</a>
        <a href="/user/123">Route Parameters</a>
        <a href="/api">JSON API</a>
        <a href="https://github.com/iQ-Global/seed" target="_blank">GitHub</a>
    </nav>
    
    <h1><?= esc($title) ?></h1>
    <p class="success">✓ <?= esc($message) ?></p>
    
    <h2>🎉 Seed Framework v1.0.0</h2>
    
    <p><strong>Status:</strong> ✅ Production Ready!</p>
    
    <h3>Features</h3>
    <ul>
        <li>✅ Router with middleware support</li>
        <li>✅ Request & Response handling</li>
        <li>✅ MVC architecture (Controllers, Models, Views)</li>
        <li>✅ Database (MySQL/PostgreSQL)</li>
        <li>✅ Authentication & Validation</li>
        <li>✅ Security (CSRF, XSS, rate limiting)</li>
        <li>✅ Session & Flash messages</li>
        <li>✅ HTTP Client & Email</li>
        <li>✅ CLI commands</li>
        <li>✅ 40+ Helper functions</li>
    </ul>
    
    <h3>Quick Links</h3>
    <ul>
        <li><a href="/user/123">See route parameters in action</a></li>
        <li><a href="/api">Test JSON API response</a></li>
        <li><a href="https://github.com/iQ-Global/seed" target="_blank">View on GitHub</a></li>
    </ul>
    
    <p style="margin-top: 40px; color: #888; font-size: 14px;">
        <strong>Seed Framework</strong> - A minimal PHP framework that helps you grow without taking over.<br>
        MIT License • By iQ
    </p>
</body>
</html>

