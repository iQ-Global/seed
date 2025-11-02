<?php
/**
 * Email - Simple SMTP email sending
 */

namespace Seed\Modules\Email;

class Email {
    private $to = [];
    private $from = [];
    private $subject = '';
    private $body = '';
    private $isHtml = false;
    private $attachments = [];
    
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
    
    // Add attachment
    public function attach($path, $name = null) {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name ?: basename($path)
        ];
        return $this;
    }
    
    // Send email
    public function send() {
        // Set default from if not set
        if (empty($this->from)) {
            $this->from([
                'email' => env('MAIL_FROM_ADDRESS'),
                'name' => env('MAIL_FROM_NAME')
            ]);
        }
        
        // Build headers
        $headers = $this->buildHeaders();
        
        // Build recipients
        $recipients = $this->buildRecipients();
        
        // Send using PHP mail() for now (basic implementation)
        // Can be extended to use SMTP in the future
        $success = mail(
            $recipients,
            $this->subject,
            $this->body,
            $headers
        );
        
        return $success;
    }
    
    // Build email headers
    private function buildHeaders() {
        $headers = [];
        
        // From
        if (!empty($this->from)) {
            $fromEmail = $this->from['email'];
            $fromName = $this->from['name'];
            
            if ($fromName) {
                $headers[] = "From: {$fromName} <{$fromEmail}>";
            } else {
                $headers[] = "From: {$fromEmail}";
            }
        }
        
        // Content-Type
        if ($this->isHtml) {
            $headers[] = "Content-Type: text/html; charset=UTF-8";
        } else {
            $headers[] = "Content-Type: text/plain; charset=UTF-8";
        }
        
        // MIME version
        $headers[] = "MIME-Version: 1.0";
        
        return implode("\r\n", $headers);
    }
    
    // Build recipients string
    private function buildRecipients() {
        $recipients = [];
        
        foreach ($this->to as $recipient) {
            $email = $recipient['email'];
            $name = $recipient['name'] ?? null;
            
            if ($name) {
                $recipients[] = "{$name} <{$email}>";
            } else {
                $recipients[] = $email;
            }
        }
        
        return implode(', ', $recipients);
    }
}

