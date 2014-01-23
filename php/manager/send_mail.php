<?php

require "../../lib/PHPmailer/PHPMailerAutoload.php";
include("../../lib/PHPmailer/class.phpmailer.php");
include("../../lib/PHPmailer/class.smtp.php");

function send_mail($mail_register, $cuerpo) {
    $mail = new PHPMailer();

    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->Username = "puertopromiscuo@gmail.com";
    $mail->Password = "jepama13";


    $mail->From = "administracion@Quedamos.com";
    $mail->FromName = "Quedamos";

    $mail->addAddress($mail_register);
    $mail->WordWrap = 50;
    $mail->isHTML(true);
    $mail->Subject = "Confirmción de tu cuenta en Quedamos.com";
    $mail->Body = $cuerpo;
    $mail->AltBody = "This is the body in plain text for non-HTML mail clients";

    if (!$mail->send()) {
        echo "Message could not be sent.";
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
    return true;
}
//send_mail('jesusgraficap@gmail.com','eyeysyysysysysy');
?>