<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Email;

use PHPMailer\PHPMailer\PHPMailer;
use Qalis\Shared\Domain\Email\SendFromSystem\EmailSender;
use Qalis\Shared\Domain\Email\SystemEmailValueObject;
use Qalis\Shared\Infrastructure\Config\LaravelConfigProvider;

class PHPMailerEmailSender implements EmailSender {

    public function sendSystemEmail(SystemEmailValueObject $email): void
    {
        $configProvider = new LaravelConfigProvider();

        $mail = new PHPMailer(true);
        //$mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = $configProvider->systemEmailHost();
        $mail->SMTPAuth = 1;
        $mail->Username = $configProvider->systemEmailSender();
        $mail->Password = base64_decode($configProvider->systemEmailPassword());
        $mail->SMTPSecure = $configProvider->systemEmailSecurity();
        $mail->Port = $configProvider->systemEmailPort();

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom($configProvider->systemEmailSender(), $configProvider->systemEmailSenderName());
        $mail->addAddress($email->recipient(), $email->recipientName());

        foreach($email->ccs() as $cc) $mail->addCC($cc);

        foreach($email->bccs() as $bcc) $mail->addBCC($bcc);

        foreach($email->attachments() as $attachment) $mail->addAttachment($attachment);

        //Content
        $mail->isHTML($email->isHtml());
        $mail->Subject = $email->subject();
        $mail->Body = $email->body();
        $mail->AltBody = strip_tags($email->body());
        $mail->CharSet = $email->charset();
        $mail->send();

    }

}
