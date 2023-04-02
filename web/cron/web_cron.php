<?php

        ini_set('display_errors', 1);

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '2000');

        echo 'display_errors = ' . ini_get('max_execution_time') . "\n";


require_once('PHPMailer/PHPMailer.php');
require_once('PHPMailer/SMTP.php');

  $zip = new ZipArchive();
  
if( $zip->open( '../backup/arquivo.zip' , ZipArchive::CREATE )  === true){
      
    $zip->addFile(  '../backup/backup_coruja.sql' , 'backup_coruja.sql' );
      
    $zip->close();
}

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// SMTP::DEBUG_OFF = off (for production use)
// SMTP::DEBUG_CLIENT = client messages
// SMTP::DEBUG_SERVER = client and server messages
$mail->SMTPDebug = SMTP::DEBUG_SERVER;

$EMAIL_SERVIDOR = "ssl://smtp.gmail.com";
$EMAIL_PORTA = "465";
$EMAIL_USUARIO = "coruja.faeterj.rio@gmail.com";
$EMAIL_SENHA = '!71G3Nt2|08X053';

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption mechanism to use - STARTTLS or SMTPS
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = $EMAIL_USUARIO;

//Password to use for SMTP authentication
$mail->Password = $EMAIL_SENHA;

//Set who the message is to be sent from
$mail->setFrom($EMAIL_USUARIO, 'Coruja');

//Set an alternative reply-to address
$mail->addReplyTo($EMAIL_USUARIO, 'Coruja');

//Set who the message is to be sent to
$mail->addAddress($EMAIL_USUARIO, 'COruja');

//Set the subject line
$mail->Subject = 'Backup - '.date("Y-m-d");

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML("<h1>Backup -".date("Y-m-d")."</h1>");

//Replace the plain text body with one created manually
$mail->AltBody = '';

//Attach an image file
$mail->addAttachment('../backup/arquivo.zip');

//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: '. $mail->ErrorInfo;
} else {
    echo 'Message sent!';
    //Section 2: IMAP
    //Uncomment these to save your message in the 'Sent Mail' folder.
    #if (save_mail($mail)) {
    #    echo "Message saved!";
    #}
}
 
 



        /*  ini_set("include_path", '/home2/faete395/php:' . ini_get("include_path") );
        require_once "Mail.php";
        include 'Mail/mime.php' ;

        const EMAIL_SERVIDOR = "ssl://smtp.gmail.com";
        const EMAIL_PORTA = "465";
        const EMAIL_USUARIO = "coruja.faeterj.rio@gmail.com";
        const EMAIL_SENHA = '!71G3Nt2|08X053';

        $EMAIL_SERVIDOR = "ssl://smtp.gmail.com";
        $EMAIL_PORTA = "465";
        $EMAIL_USUARIO = "coruja.faeterj.rio@gmail.com";
        $EMAIL_SENHA = '!71G3Nt2|08X053';

        $emailRemetente = $EMAIL_USUARIO;
        $from = "Coruja <$emailRemetente>";
        $to = "<$EMAIL_USUARIO>";
        $subject = 'Backup - '.date("Y-m-d");
        $body = "E-MAIL AUTOMÁTICO ENVIADO PELO SISTEMA CORUJA. NÃO RESPONDA.\n\n" . $texto;

        $host = $EMAIL_SERVIDOR;
        $port = $EMAIL_PORTA;
        $username = $EMAIL_USUARIO;
        $password = $EMAIL_SENHA;

        $mime = new Mail_mime();

         if ($mime->addAttachment("../backup/backup_coruja.sql")){
            echo "attached successfully! </br>";
        } else {
            echo "Nope, failed to attache!! </br>";
        } 

        $headers = array ('From' => $from,
          'To' => $to,
          'Subject' => $subject);
        $smtp = Mail::factory('smtp',
          array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password));

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
            throw new Exception($mail->getMessage());
         } */

?>