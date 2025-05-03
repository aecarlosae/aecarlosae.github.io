<?php
namespace Aecarlosae\Site;

use GuzzleHttp\Client as HTTPClient;
use PHPMailer\PHPMailer\PHPMailer;

class Contact
{
    private static $instance = null;

    private function __construct()
    {
        $this->run();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Run the contact form processing
     *
     * @throws \Exception
     */
    private function run()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['g-recaptcha-response'])) {
            $client = new HTTPClient();
            $response = $client->post($_ENV['RECAPTCHA_VERIFY_URL'], [
                'form_params' => [
                    'secret' => $_ENV['RECAPTCHA_SECRET_KEY'],
                    'response' => $_POST['g-recaptcha-response'],
                ],
            ]);
        
            $body = json_decode($response->getBody(), true);
        
            if ($body['success']) {
                if ($this->sendMail()) {
                    $lang = isset($_POST['lang']) && $_POST['lang'] === 'es' ? 'es/' : '';
                    header('Location: /' . $lang . '?success=1#contact');
                    exit;
                } else {
                    throw new \Exception('Failed to send the message.');
                }
            } else {
                throw new \Exception('reCAPTCHA verification failed. Please try again.', 500);
            }
        }
    }

    private function sendMail()
    {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        $mail = new PHPMailer(true);
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($_ENV['MAIL_TO_ADDRESS'], $_ENV['MAIL_TO_NAME']);
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Message';
        $mail->Body = "Name: " . $name . "<br>Email: " . $email . "<br><br>Message:<br>" . nl2br($message);
        $mail->AltBody = "Name: " . $name . "\nEmail: " . $email . "\n\nMessage:\n" . $message;

        return $mail->send();
    }
}
