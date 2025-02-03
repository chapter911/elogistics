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
            $mail->SMTPDebug = 1;
            $mail->Host = '10.1.2.65';
            $mail->Port = "25";
            $mail->Mailer = "smtp";
            $mail->SMTPAuth = false;
            $mail->Username = 'pusat\divsti.jkt1';
            $mail->Password = 'P@ssw0rd!1';
            $mail->setFrom('no-reply@pln.co.id', 'No-Reply E-Logistics PLN');
            $mail->addReplyTo('no-reply@pln.co.id', 'No-Reply E-Logistics PLN');
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