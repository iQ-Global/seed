<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2c3e50;">Password Reset Request</h2>
        
        <p>You are receiving this email because we received a password reset request for your account.</p>
        
        <p>Click the button below to reset your password:</p>
        
        <div style="margin: 30px 0;">
            <a href="<?= esc($resetUrl) ?>" 
               style="background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Reset Password
            </a>
        </div>
        
        <p>This password reset link will expire in <?= $expiration ?> minutes.</p>
        
        <p>If you did not request a password reset, no further action is required.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #999;">
            If you're having trouble clicking the button, copy and paste the URL below into your web browser:
            <br>
            <a href="<?= esc($resetUrl) ?>" style="color: #3498db;"><?= esc($resetUrl) ?></a>
        </p>
    </div>
</body>
</html>

