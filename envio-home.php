<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'vendor/autoload.php';


    $data['ip'] = $_SERVER['REMOTE_ADDR'];

    if (isset($_POST['nome']) && !empty($_POST['nome']))
        $data['nome'] = $_POST['nome'];


    if (isset($_POST['email']) && !empty($_POST['email']))
        $data['email'] = $_POST['email'];


    if (isset($_POST['telefone']) && !empty($_POST['telefone']))
        $data['telefone'] = $_POST['telefone'];


    if (isset($_POST['mensagem']) && !empty($_POST['mensagem']))
        $data['mensagem'] = $_POST['mensagem'];

    if (isset($_POST['setor']) && !empty($_POST['setor']))
        $data['setor'] = $_POST['setor'];



// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

//Server settings
$mail->SMTPDebug = 0;                                       // Enable verbose debug output
$mail->isSMTP();                                            // Set mailer to use SMTP
$mail->Host       = 'smtp.mailgun.org';                       // Specify main and backup SMTP servers
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'system@mailgun.michaeldeveloper.com.br';             // SMTP username
$mail->Password   = 'gmtop123';                     // SMTP password
$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
$mail->Port       = 465;                                    // TCP port to connect to
$mail->CharSet = 'utf-8';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
$twig = new \Twig\Environment($loader);

$viewRender = $twig->render('mail/mail.phtml', $data);

//Recipients
$mail->setFrom('diretoria@ansegtv.com.br', 'Formulario de contato do site - ANSEGTV');
$mail->addAddress('diretoria@ansegtv.com.br', 'Contato');     // Add a recipient
$mail->addAddress('diretoria@ansegtv.com.br');           // Name is optional
//$mail->addCC('diretoria@ansegtv.com.br');
//$mail->addBCC('bruno.silva@synapsebrasil.com.br');

//    // Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

// Content
$mail->isHTML(true); // Set email format to HTML
$mail->Subject = 'Contato do site:';
$mail->Body    = $viewRender;
$message = 'Mensagem enviada com sucesso!';


if($mail->send()) {
    echo '<script type="text/javascript">';
    echo 'alert("Mensagem enviada com sucesso!");';
    echo 'window.location.href = "index.php";';
    echo '</script>';

} else {
    echo 'Ocorreu um erro no envio da mensagem... Tente novamente mais tarde.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}

