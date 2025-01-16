<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/PHPMailer/PHPMailer/src/Exception.php';
    require 'vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
    require 'vendor/PHPMailer/PHPMailer/src/SMTP.php';
    require 'vendor/autoload.php';

    class sendemail{
        protected $_CI;

        function __construct(){
            $this->_CI=&get_instance();

            $this->_CI->load->model('M_AllFunction');
        }

        function send($title, $message, $receiver){
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = 'smtp.hostinger.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@e-logisticspln.com';
            $mail->Password = 'Kiliki@123';
            $mail->setFrom('no-reply@e-logisticspln.com', 'No-Reply E-Logistics PLN');
            $mail->addReplyTo('no-reply@e-logisticspln.com', 'No-Reply E-Logistics PLN');
            foreach ($receiver as $r) {
                $mail->addAddress($r->email);
            }
            $mail->Subject = $title;
            $mail->Body = $message;
            $mail->IsHTML(true);
            if (!$mail->send()) {
                return false;
            } else {
                return true;
            }
        }
    }
?>