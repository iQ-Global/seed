<?php
/**
 * Auth - Simple authentication
 */

namespace Seed\Modules\Auth;

use Seed\Core\Session;

class Auth {
    private static $userKey = 'auth_user_id';
    
    // Hash password
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Verify password
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    // Log user in
    public static function login($userId, $remember = false) {
        Session::set(self::$userKey, $userId);
        Session::regenerate(true);
        
        // Handle remember me
        if ($remember) {
            self::createRememberToken($userId);
        }
    }
    
    // Log user out
    public static function logout() {
        Session::remove(self::$userKey);
        Session::regenerate(true);
    }
    
    // Check if user is logged in
    public static function check() {
        return Session::has(self::$userKey);
    }
    
    // Get current user ID
    public static function id() {
        return Session::get(self::$userKey);
    }
    
    // Get current user (requires user model or callback)
    public static function user($callback = null) {
        $userId = self::id();
        
        if (!$userId) {
            return null;
        }
        
        if ($callback && is_callable($callback)) {
            return $callback($userId);
        }
        
        return $userId;
    }
    
    // Attempt login with credentials
    // $checkCallback should verify credentials and return user ID if valid, false otherwise
    public static function attempt($checkCallback) {
        $result = $checkCallback();
        
        if ($result) {
            self::login($result);
            return true;
        }
        
        return false;
    }
    
    // =============================================================================
    // PASSWORD RESET
    // =============================================================================
    
    // Send password reset email
    public static function sendPasswordReset($email) {
        // Generate secure token
        $token = bin2hex(random_bytes(32));
        $expiration = env('AUTH_PASSWORD_RESET_EXPIRATION', 3600);
        $expiresAt = date('Y-m-d H:i:s', time() + $expiration);
        
        // Store token in database
        $db = db();
        $db->insert('password_resets', [
            'email' => $email,
            'token' => hash('sha256', $token),
            'expires_at' => $expiresAt
        ]);
        
        // Clean old tokens for this email
        $db->query("DELETE FROM password_resets WHERE email = ? AND expires_at < NOW()", [$email]);
        
        // Send email with reset link
        $resetUrl = url('/reset-password?token=' . $token);
        
        email()
            ->to($email)
            ->subject('Password Reset Request')
            ->body(view('system/emails/password-reset', [
                'resetUrl' => $resetUrl,
                'expiration' => round($expiration / 60)
            ]))
            ->send();
        
        return true;
    }
    
    // Verify password reset token
    public static function verifyResetToken($token) {
        $db = db();
        $hashedToken = hash('sha256', $token);
        
        $reset = $db->queryOne(
            "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()",
            [$hashedToken]
        );
        
        return $reset ? $reset : false;
    }
    
    // Reset password with token
    public static function resetPassword($token, $newPassword) {
        $reset = self::verifyResetToken($token);
        
        if (!$reset) {
            return false;
        }
        
        // Update user password
        $db = db();
        $hashedPassword = self::hashPassword($newPassword);
        $db->update('users', ['password' => $hashedPassword], ['email' => $reset->email]);
        
        // Delete used token
        $db->delete('password_resets', ['token' => hash('sha256', $token)]);
        
        return true;
    }
    
    // =============================================================================
    // EMAIL VERIFICATION
    // =============================================================================
    
    // Send email verification
    public static function sendVerificationEmail($user) {
        $userId = is_array($user) ? $user['id'] : $user->id;
        $email = is_array($user) ? $user['email'] : $user->email;
        
        // Generate secure token
        $token = bin2hex(random_bytes(32));
        
        // Store token in database
        $db = db();
        $db->insert('email_verifications', [
            'user_id' => $userId,
            'email' => $email,
            'token' => hash('sha256', $token)
        ]);
        
        // Send email with verification link
        $verifyUrl = url('/verify-email?token=' . $token);
        
        email()
            ->to($email)
            ->subject('Verify Your Email Address')
            ->body(view('system/emails/email-verification', [
                'verifyUrl' => $verifyUrl,
                'user' => $user
            ]))
            ->send();
        
        return true;
    }
    
    // Verify email with token
    public static function verifyEmail($token) {
        $db = db();
        $hashedToken = hash('sha256', $token);
        
        $verification = $db->queryOne(
            "SELECT * FROM email_verifications WHERE token = ? AND verified_at IS NULL",
            [$hashedToken]
        );
        
        if (!$verification) {
            return false;
        }
        
        // Mark as verified
        $db->update('email_verifications', ['verified_at' => date('Y-m-d H:i:s')], ['id' => $verification->id]);
        
        // Update user email_verified_at field (if exists)
        $db->query(
            "UPDATE users SET email_verified_at = NOW() WHERE id = ? AND email_verified_at IS NULL",
            [$verification->user_id]
        );
        
        return true;
    }
    
    // Check if user email is verified
    public static function isEmailVerified($userId) {
        $db = db();
        $user = $db->queryOne("SELECT email_verified_at FROM users WHERE id = ?", [$userId]);
        
        return $user && $user->email_verified_at !== null;
    }
    
    // =============================================================================
    // ACCOUNT LOCKOUT
    // =============================================================================
    
    // Record login attempt
    public static function recordLoginAttempt($email, $success = false) {
        if (!env('AUTH_LOCKOUT_ENABLED', true)) {
            return;
        }
        
        $db = db();
        $db->insert('login_attempts', [
            'email' => $email,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'success' => $success ? 1 : 0
        ]);
        
        // Clean old attempts (older than lockout duration)
        $lockoutDuration = env('AUTH_LOCKOUT_DURATION', 900);
        $db->query(
            "DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL ? SECOND)",
            [$lockoutDuration]
        );
    }
    
    // Check if account is locked
    public static function isLocked($email) {
        if (!env('AUTH_LOCKOUT_ENABLED', true)) {
            return false;
        }
        
        $db = db();
        $maxAttempts = env('AUTH_LOCKOUT_ATTEMPTS', 5);
        $lockoutDuration = env('AUTH_LOCKOUT_DURATION', 900);
        
        // Count failed attempts within lockout window
        $attempts = $db->queryOne(
            "SELECT COUNT(*) as count FROM login_attempts 
             WHERE email = ? 
             AND success = 0 
             AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND)",
            [$email, $lockoutDuration]
        );
        
        return $attempts && $attempts->count >= $maxAttempts;
    }
    
    // Unlock account manually
    public static function unlock($email) {
        $db = db();
        $db->delete('login_attempts', ['email' => $email, 'success' => 0]);
        return true;
    }
    
    // =============================================================================
    // REMEMBER ME
    // =============================================================================
    
    // Create remember me token
    protected static function createRememberToken($userId) {
        $token = bin2hex(random_bytes(32));
        $duration = env('AUTH_REMEMBER_DURATION', 604800); // 7 days default
        $expiresAt = date('Y-m-d H:i:s', time() + $duration);
        
        // Store token in database
        $db = db();
        $db->insert('remember_tokens', [
            'user_id' => $userId,
            'token' => hash('sha256', $token),
            'expires_at' => $expiresAt
        ]);
        
        // Set cookie
        setcookie('remember_token', $token, time() + $duration, '/', '', false, true);
        
        return $token;
    }
    
    // Check remember me token
    public static function checkRememberToken() {
        if (!isset($_COOKIE['remember_token'])) {
            return false;
        }
        
        $token = $_COOKIE['remember_token'];
        $hashedToken = hash('sha256', $token);
        
        $db = db();
        $remember = $db->queryOne(
            "SELECT * FROM remember_tokens WHERE token = ? AND expires_at > NOW()",
            [$hashedToken]
        );
        
        if ($remember) {
            // Auto-login user
            self::login($remember->user_id);
            return true;
        }
        
        // Invalid or expired token - delete cookie
        setcookie('remember_token', '', time() - 3600, '/');
        return false;
    }
    
    // Revoke remember me token
    public static function forgetRememberToken($userId = null) {
        if ($userId) {
            $db = db();
            $db->delete('remember_tokens', ['user_id' => $userId]);
        }
        
        // Delete cookie
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

