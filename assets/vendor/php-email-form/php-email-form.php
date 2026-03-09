<?php
/**
 * PHP Email Form library
 * Author: BootstrapMade.com
 * Website: https://bootstrapmade.com
 * License: https://bootstrapmade.com/license/
 */

class PHP_Email_Form {
  public $to;
  public $from_name;
  public $from_email;
  public $subject;
  public $smtp;
  public $ajax;
  public $messages = array();

  public function add_message($content, $label = '', $length = 0) {
    if (!empty($content)) {
      $this->messages[] = "$label: " . nl2br(htmlspecialchars($content));
    }
  }

  public function send() {
    $message_body = implode("<br><br>", $this->messages);

    $headers = "From: ". $this->from_name ." <". $this->from_email .">\r\n";
    $headers .= "Reply-To: ". $this->from_email ."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Use SMTP if defined
    if (!empty($this->smtp)) {
      // You can integrate PHPMailer here if needed.
      return json_encode(array('status' => 'error', 'message' => 'SMTP not implemented in this version.'));
    }

    $send_mail = mail($this->to, $this->subject, $message_body, $headers);

    if ($send_mail) {
      return 'Message sent successfully!';
    } else {
      return 'Failed to send message.';
    }
  }
}
