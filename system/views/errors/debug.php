<!DOCTYPE html>
<html>
<head>
    <title>Error - Debug Mode</title>
    <style>
        body { font-family: 'Courier New', monospace; margin: 0; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .error-header { background: #e74c3c; color: white; padding: 20px; margin: -20px -20px 20px; }
        .error-header h1 { margin: 0; font-size: 24px; }
        .error-message { background: #2d2d2d; padding: 15px; border-left: 4px solid #e74c3c; margin: 20px 0; }
        .error-location { background: #2d2d2d; padding: 15px; margin: 20px 0; }
        .error-location strong { color: #4ec9b0; }
        pre { background: #2d2d2d; padding: 15px; overflow-x: auto; border-radius: 4px; line-height: 1.5; }
        .trace-title { color: #4ec9b0; font-size: 18px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="error-header">
        <h1>⚠️ Exception Thrown</h1>
    </div>
    
    <div class="error-message">
        <strong><?= get_class($error) ?>:</strong> <?= htmlspecialchars($error->getMessage()) ?>
    </div>
    
    <div class="error-location">
        <strong>File:</strong> <?= htmlspecialchars($error->getFile()) ?><br>
        <strong>Line:</strong> <?= $error->getLine() ?>
    </div>
    
    <div class="trace-title">Stack Trace:</div>
    <pre><?= htmlspecialchars($error->getTraceAsString()) ?></pre>
</body>
</html>

