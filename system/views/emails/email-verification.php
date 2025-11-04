<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2c3e50;">Verify Your Email Address</h2>
        
        <p>Thank you for creating an account! Please verify your email address by clicking the button below:</p>
        
        <div style="margin: 30px 0;">
            <a href="<?= esc($verifyUrl) ?>" 
               style="background-color: #27ae60; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Verify Email Address
            </a>
        </div>
        
        <p>If you did not create an account, no further action is required.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #999;">
            If you're having trouble clicking the button, copy and paste the URL below into your web browser:
            <br>
            <a href="<?= esc($verifyUrl) ?>" style="color: #27ae60;"><?= esc($verifyUrl) ?></a>
        </p>
    </div>
</body>
</html>

