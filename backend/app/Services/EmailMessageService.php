<?php

namespace App\Services;

use App\Models\EmailMessage;
use PHPMailer\PHPMailer\PHPMailer;

class EmailMessageService
{

    public static function send(string $to, string $subject, string $message, object $user)
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'mail.nebulasend.com';
        $mail->SMTPAuth = true;
        $mail->Username = $user->name . env('EMAIL_END');
        $mail->Password = env('EMAIL_PEPPER') . $user->email_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom($user->name . env('EMAIL_END'), $user->recipient_name);
        $mail->addAddress($to, $to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
    }

    public static function getSmtpMessages()
    {
        $user = auth()->user();

        $username = $user->name . env('EMAIL_END');
        $password = env('EMAIL_PEPPER') . $user->email_pass;
        $hostname = '{mail.nebulasend.com:993/imap/ssl/novalidate-cert}INBOX';

        try {
            $inbox = imap_open($hostname, $username, $password);

            $emails = imap_search($inbox, 'UNSEEN');

            return self::handleSmtpMessages($emails, $inbox, $user->id);
        } catch (\Error $e) {
            http_response_code(500);
            return [];
        }
    }

    private static function handleSmtpMessages(mixed $emails, object $inbox, string $id)
    {
        if (!$emails) {
            return [];
        }

        foreach ($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $message = imap_fetchbody($inbox, $email_number, 2);

            if (empty($message)) {
                $message = imap_fetchbody($inbox, $email_number, 1);
            }

            list($recipient, $email, $to) = self::handleSmtpMessage($overview);
            list($message, $summary) = self::handleSmtpMessageContent($message);

            EmailMessage::create([
                'user_id' => $id,
                'sent_by' => $email,
                'sent_to' => $to,
                'subject' => mb_decode_mimeheader($overview[0]->subject),
                'recipient' => mb_decode_mimeheader($recipient),
                'message' => $message,
                'summary' => $summary,
                'important' => $overview[0]->flagged,
                'sent_at' => strtotime($overview[0]->date)
            ]);
        }

        imap_close($inbox);
    }

    private static function handleSmtpMessage(array $overview): array
    {
        $from = $overview[0]->from;

        $recipient = rtrim(strchr($from, "<", true));
        $email = trim(strchr($from, "<"), "<>");

        $to = $overview[0]->to;

        if (preg_match('/<([^>]+)>/', $to, $matches)) {
            $to = $matches[1];
        }

        return [$recipient, $email, $to];
    }

    private static function handleSmtpMessageContent(string $message): array
    {
        $message = str_replace('3D\\"', '', $message);
        $message = quoted_printable_decode($message);
        $message = htmlspecialchars_decode($message);
        $message = stripslashes($message);

        $pattern = '/<a((?:(?!target="_blank").)*?)>/i';
        $replacement = '<a$1 target="_blank">';
        $message = preg_replace($pattern, $replacement, $message);

        $summary = new \Html2Text\Html2Text($message);

        $summary = $summary->getText();
        $summary = trim(strlen($summary) > 150 ? substr($summary, 0, 150) : $summary);

        return [$message, $summary];
    }
}

?>