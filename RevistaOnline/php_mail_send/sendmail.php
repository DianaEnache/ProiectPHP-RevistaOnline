<?php

//sesssion_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';


if ($_POST['submitContact']) {

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
   
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
    {
            $secretKey = "6LcndNUqAAAAAOIYnL56bUKTEeS8i5MHOqJA7apD";
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
            $response = json_decode($verifyResponse);

            if($response->success)
            {

            


                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
                    $mail->isSMTP();  
                    $mail->SMTPAuth   = true;  
                    
                    
                    
                                                                                
                    $mail->Host       = 'smtp.gmail.com';                                            
                    $mail->Username   = 'user@gmail.com';       //adrea noua de google creata
                    $mail->Password   = 'secret';              //app password din gmail dar nu reuseeeesc sa il fac sa mearga
                                            
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //ENCRYPTION_SMTPS 465- Enable implicit TLS encryption
                    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('user@gmail.com', 'Revista Online');
                    $mail->addAddress('user@gmail.com', 'Revista Online');     
                    

                    //Attachments
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');   

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'New article posted';
                    $mail->Body    = '<h3>A new article has been posted on the website. Check it out!</h3>
                                    <h4>Fullname:'.$full_name.' </h4>
                                    <h4>Email:'.$email.' </h4>
                                    <h4>Subject:'.$subject.' </h4>
                                    <h4>Message:'.$message.' </h4>';


                    if($mail->send())
                    {
                        $_SESSION['status'] = "Thank you";
                        header("Location: {$_SERVER["HTTP_REFERER"]}");
                        exit(0);
                    }
                    else
                    {
                        $_SESSION['status'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        header("Location: {$_SERVER["HTTP_REFERER"]}");
                        exit(0);
                    }

                
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
            else
            {
                $_SESSION['status'] = "reCAPTCHA verification api : something went wrong";
                header("Location: {$_SERVER["HTTP_REFERER"]}");
                exit(0);
            }


    }
    else
    {
        $_SESSION['status'] = "Error in reCAPTCHA verification";
        header("Location: {$_SERVER["HTTP_REFERER"]}");
        exit(0);
    }
}
else{
   header('Location: contact.php');
    exit(0);
}
?>
