<?php
//send email confirming registration, eventually will be sending a verification email
function verification_email($email)
{
    $from = "noreply@joelmeister.net";
    $subject = "Thank you for registering!";
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: <".$from. ">" ;

    $body = "<html><body>
        Your registration is complete.<br/>
        <a href='javascript:void(0);'>Order now!</a>
        </body></html>";
    if(mail($email,$subject,$body,$headers))
    {
        echo 'success';
    }
    else
    {
        echo 'failure';
    }
}
