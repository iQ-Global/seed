<?php
/**
 * Authentication helper functions
 */

use Seed\Modules\Auth\Auth;

// Get auth instance or current user
function auth($callback = null) {
    return Auth::user($callback);
}

// Check if user is authenticated
function is_logged_in() {
    return Auth::check();
}

// Get current user ID
function user_id() {
    return Auth::id();
}

// Send password reset email
function send_password_reset($email) {
    return Auth::sendPasswordReset($email);
}

// Reset password with token
function reset_password($token, $newPassword) {
    return Auth::resetPassword($token, $newPassword);
}

// Verify password reset token
function verify_reset_token($token) {
    return Auth::verifyResetToken($token);
}

// Send email verification
function send_verification_email($user) {
    return Auth::sendVerificationEmail($user);
}

// Verify email with token
function verify_email($token) {
    return Auth::verifyEmail($token);
}

// Check if user's email is verified
function is_email_verified($userId = null) {
    $userId = $userId ?? user_id();
    return Auth::isEmailVerified($userId);
}

// Check if account is locked
function is_account_locked($email) {
    return Auth::isLocked($email);
}

// Unlock account
function unlock_account($email) {
    return Auth::unlock($email);
}

// Record login attempt
function record_login_attempt($email, $success = false) {
    return Auth::recordLoginAttempt($email, $success);
}

// Check remember me token (auto-login)
function check_remember_token() {
    return Auth::checkRememberToken();
}

// Forget remember me token
function forget_remember_token($userId = null) {
    return Auth::forgetRememberToken($userId);
}

