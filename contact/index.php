<?php
require 'vendor/autoload.php';

$log = new Monolog\Logger('aecarlosae.com');
$log->pushHandler(new Monolog\Handler\StreamHandler('../log/error.log', Monolog\Logger::DEBUG));

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    $dotenv->required([
        'RECAPTCHA_SITE_KEY',
        'RECAPTCHA_SECRET_KEY',
        'MAIL_HOST',
        'MAIL_PORT',
        'MAIL_USERNAME',
        'MAIL_PASSWORD',
        'MAIL_TO_ADDRESS',
        'MAIL_TO_NAME'
    ])->notEmpty();

    \Aecarlosae\Site\Contact::getInstance();
} catch (Exception $e) {
    $log->error($e->getMessage());
    $lang = isset($_POST['lang']) && $_POST['lang'] === 'es' ? 'es/' : '';
    header('Location: /' . $lang . '?error=' . $e->getCode() . '#contact');
    exit;
}
