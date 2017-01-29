<?php
//send email confirming registration, eventually will be sending a verification email
define('MAIL_LOG','/home1/joelmeis/public_html/brewCom/logs/mail.log');
define('DEFAULT_EMAIL','joelmeister1209@gmail.com');
define('NOREPLY_EMAIL','noreply@joelmeister.net');
function order_confirmation_email($information)
{
    //$body = file_get_contents('../views/penrose/email_confirmation.html');
    require '../mustache.php/src/Mustache/Autoloader.php';
    Mustache_Autoloader::register();

    $options =  array('extension' => '.html');
    $mustache = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader('../views/penrose', $options),
        ));

    $tpl = $mustache->loadTemplate('email_confirmation');
    //$body = $tpl->render(array('order_number' => 'test number'));
    $body = $tpl->render($information);
    $subject = 'order received';
    return send_mail($information['email'], $subject, $body);
}
function verification_email($email)
{
    $subject = "Thank you for registering!";
    $body = "<html><body>
        Your registration is complete.<br/>
        <a href='javascript:void(0);'>Order now!</a>
        </body></html>";
    if(send_mail($email,$subject,$body))
    {
        echo 'success';
    }
    else
    {
        echo 'failure';
    }
}
function contact_us($values)
{
    $name = $values['name'];
    $email = $values['email'];
    $subject = isset($values['subject']) 
        ? html_entity_decode($values['subject']) 
            : "B2B contact: $name: $email";
    $message = "<html><body>Email from: $name: $email<br/>";
    $message .= html_entity_decode($values['message']);
    $message .= "</body></html>";
    return send_mail(DEFAULT_EMAIL,$subject,$message);
}
function send_mail($to,$subject,$text,$from = NOREPLY_EMAIL, $additional_headers = NULL)
{
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: <".$from.">\r\n";
    $headers .= $additional_headers;
    return mail($to,$subject,$text,$headers);
}
