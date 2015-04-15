<?php
require 'libraries/PHPMailer/PHPMailerAutoload.php';
$destination = $_POST['dest'];
$name = $_POST['fname'];

$email = new PHPMailer;

//set SMTP
$email->isSMTP();                                      // Set mailer to use SMTP
$email->Host = 'smtp.gmail.com';  					   // Specify main SMTP server
$email->SMTPAuth = true;                               // Enable SMTP authentication
$email->Username = 'ubitraffic2015@gmail.com';         // SMTP username
$email->Password = 'dlrigjy5867';                      // SMTP password
$email->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$email->Port = 587;    

//select from and to
$email->From      = 'ubitraffic2015@gmail.com';
$email->FromName  = 'UbiTraffic';
$email->addAddress($destination);

//add attachment
$email->addAttachment('images/png/'.$name.'.png', 'UbiTraffic_map.png');
$email->isHTML(true);  


//set subject and body of the e-mail
$email->Subject   = 'UbiTraffic downloaded map '.date('d.m.Y');
$email->Body      = 'Hi!<br /><br />Please find your requested map image attached to this e-mail.<br /><br />Best Regards:<br /> UbiTraffic Team';
$email->AltBody = 'Hi! Please find your requested map image attached to this e-mail. Best Regards: UbiTraffic Team';


if(!$email->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $email->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>