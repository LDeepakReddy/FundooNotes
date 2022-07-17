<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Queue\SerializesModels;
use PHPUnit\Framework\Constraint\ExceptionMessage;

class Mailer 
{
    

    public function sendEmail($user, $token)
    {
        $subject = 'Forgot Password';
        $data = "Hi, " . $user->first_name . " " . $user->last_name . "<br>Your Password Reset Token:<br>" . $token;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';
            $mail->Port       = env('MAIL_PORT');
            $mail->setFrom(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            $mail->addAddress($user->email);
            $mail->isHTML(true);
            $mail->Subject =  $subject;
            $mail->Body    = $data;
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
        } catch (ExceptionMessage $e) {
            return back()->with('error', 'Message could not be sent.');
        }
    }
}
