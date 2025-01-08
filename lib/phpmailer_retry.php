<?php
class phpmailerretry
{
    public static function saveMailWithErrorToDatabase($params): bool
    {
        $sql = rex_sql::factory();
        $sql->setDebug(false);
        $sql->setTable(rex::getTable('phpmailer_retry'));
        $sql->setValue('timestamp', time());
        $sql->setValue('retried', 0);
        $sql->setValue('name', $params['name']);
        $sql->setValue('mail_from', $params['mail_from']);
        $sql->setValue('mail_from_name', $params['mail_from_name']);
        $sql->setValue('mail_reply_to', $params['mail_reply_to']);
        $sql->setValue('mail_reply_to_name', $params['mail_reply_to_name']);
        $sql->setValue('subject', $params['subject']);
        $sql->setValue('body', $params['body']);
        $sql->setValue('body_html', $params['body_html']);
        $sql->setValue('attachments', json_encode($params['attachments']));
        $sql->setValue('mail_to', $params['mail_to']);
        $sql->setValue('mail_to_name', $params['mail_to_name']);
        $sql->setValue('email_subject', $params['email_subject']);

        try {
            $sql->insert();
        }
        catch (Exception $e) {
            rex_logger::factory()->info('PHPMailer Retry: Fehler beim Speichern der E-Mail in Datenbank.');
            return false;
        }

        rex_logger::factory()->info('PHPMailer Retry: Speichern der E-Mail in Datenbank erfolgreich.');
        return true;
    }

}