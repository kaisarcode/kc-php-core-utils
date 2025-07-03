<?php

/**
 * Class SendMail
 *
 * Sends HTML emails using a simple template engine.
 * Configurable via public properties before calling send().
 */
class SendMail {

    public string $to = '';
    public string $subject = '';
    public string $template_path = '';
    public array $data = [];
    public string $from = 'no-reply@kaisarcode.com';
    public string $cache_dir = 'cache/templates/';
    public bool $cache_enabled = true;

    /**
     * Sends the email using configured properties.
     *
     * @return bool True if the mail was accepted for delivery.
     */
    public function send(): bool {
        if (!$this->to || !$this->subject || !$this->template_path) {
            return false;
        }

        if (!file_exists($this->template_path)) {
            return false;
        }

        $template = new Template([
            'cache_dir' => $this->cache_dir,
            'cache_enabled' => $this->cache_enabled,
        ]);

        $body = $template->parse($this->template_path, $this->data);

        $headers = "From: {$this->from}\r\n";
        $headers .= "Reply-To: {$this->from}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";

        return mail($this->to, $this->subject, $body, $headers);
    }
}
