<?php
/**
 * Email - SMTP email sending with PHPMailer
 */

namespace Seed\Modules\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Email {
    private $mailer;
    private $to = [];
    private $cc = [];
    private $bcc = [];
    private $from = [];
    private $replyTo = [];
    private $subject = '';
    private $body = '';
    private $altBody = '';
    private $isHtml = false;
    private $attachments = [];
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configureSMTP();
    }
    
    // Set recipient(s)
    public function to($email, $name = null) {
        if (is_array($email)) {
            $this->to = $email;
        } else {
            $this->to[] = ['email' => $email, 'name' => $name];
        }
        return $this;
    }
    
    // Set sender
    public function from($email, $name = null) {
        $this->from = [
            'email' => $email ?: env('MAIL_FROM_ADDRESS'),
            'name' => $name ?: env('MAIL_FROM_NAME')
        ];
        return $this;
    }
    
    // Set subject
    public function subject($subject) {
        $this->subject = $subject;
        return $this;
    }
    
    // Set body
    public function body($body, $html = false) {
        $this->body = $body;
        $this->isHtml = $html;
        return $this;
    }
    
    // Set HTML body
    public function html($body) {
        $this->body = $body;
        $this->isHtml = true;
        return $this;
    }
    
    // Set CC
    public function cc($email, $name = null) {
        if (is_array($email)) {
            $this->cc = $email;
        } else {
            $this->cc[] = ['email' => $email, 'name' => $name];
        }
        return $this;
    }
    
    // Set BCC
    public function bcc($email, $name = null) {
        if (is_array($email)) {
            $this->bcc = $email;
        } else {
            $this->bcc[] = ['email' => $email, 'name' => $name];
        }
        return $this;
    }
    
    // Set Reply-To
    public function replyTo($email, $name = null) {
        $this->replyTo = ['email' => $email, 'name' => $name];
        return $this;
    }
    
    // Set alternative plain-text body
    public function altBody($body) {
        $this->altBody = $body;
        return $this;
    }
    
    // Add attachment
    public function attach($path, $name = null) {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name ?: basename($path)
        ];
        return $this;
    }
    
    // Configure SMTP settings
    private function configureSMTP() {
        try {
            $mailer = env('MAIL_MAILER', 'smtp');
            
            if ($mailer === 'smtp') {
                $this->mailer->isSMTP();
                $this->mailer->Host = env('MAIL_HOST', 'smtp.mailtrap.io');
                $this->mailer->SMTPAuth = true;
                $this->mailer->Username = env('MAIL_USERNAME', '');
                $this->mailer->Password = env('MAIL_PASSWORD', '');
                $this->mailer->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
                $this->mailer->Port = env('MAIL_PORT', 587);
            }
            
            // Charset
            $this->mailer->CharSet = 'UTF-8';
        } catch (PHPMailerException $e) {
            log_error('Email SMTP configuration failed', ['error' => $e->getMessage()]);
        }
    }
    
    // Send email
    public function send() {
        try {
            // Set default from if not set
            if (empty($this->from)) {
                $this->from = [
                    'email' => env('MAIL_FROM_ADDRESS'),
                    'name' => env('MAIL_FROM_NAME')
                ];
            }
            
            // Set from
            $this->mailer->setFrom($this->from['email'], $this->from['name'] ?? '');
            
            // Add recipients
            foreach ($this->to as $recipient) {
                $this->mailer->addAddress($recipient['email'], $recipient['name'] ?? '');
            }
            
            // Add CC
            foreach ($this->cc as $recipient) {
                $this->mailer->addCC($recipient['email'], $recipient['name'] ?? '');
            }
            
            // Add BCC
            foreach ($this->bcc as $recipient) {
                $this->mailer->addBCC($recipient['email'], $recipient['name'] ?? '');
            }
            
            // Set reply-to
            if (!empty($this->replyTo)) {
                $this->mailer->addReplyTo($this->replyTo['email'], $this->replyTo['name'] ?? '');
            }
            
            // Set subject and body
            $this->mailer->Subject = $this->subject;
            
            if ($this->isHtml) {
                $this->mailer->isHTML(true);
                $this->mailer->Body = $this->body;
                $this->mailer->AltBody = $this->altBody ?: strip_tags($this->body);
            } else {
                $this->mailer->isHTML(false);
                $this->mailer->Body = $this->body;
            }
            
            // Add attachments
            foreach ($this->attachments as $attachment) {
                $this->mailer->addAttachment($attachment['path'], $attachment['name']);
            }
            
            // Send
            $success = $this->mailer->send();
            
            // Clear for next email
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->clearCCs();
            $this->mailer->clearBCCs();
            $this->mailer->clearReplyTos();
            
            return $success;
            
        } catch (PHPMailerException $e) {
            log_error('Email send failed', [
                'error' => $e->getMessage(),
                'to' => $this->to,
                'subject' => $this->subject
            ]);
            return false;
        }
    }
    
    // Get last error message
    public function getError() {
        return $this->mailer->ErrorInfo;
    }
}

