<?php

class rex_cronjob_phpmailer_retry extends rex_cronjob
{

    public function execute()
    {

        $sql = rex_sql::factory();
        $sql->setDebug(false);
        $sql->setQuery('SELECT * FROM ' . rex::getTable('phpmailer_retry') . ' WHERE retried < 2 ORDER BY timestamp ASC');
        if ($sql->getRows() > 0)
        {
            while($sql->hasNext()) {
                $mail = new rex_mailer();
                $mail->AddAddress($sql->getValue('mail_to'), $sql->getValue('mail_to_name'));
                $mail->SetFrom($sql->getValue('mail_from'), $sql->getValue('mail_from_name'));
                if ($sql->getValue('mail_reply_to') != '') {
                    $mail->AddReplyTo($sql->getValue('mail_reply_to'), $sql->getValue('mail_reply_to_name'));
                }
                $mail->Subject = $sql->getValue('subject');
                $mail->Body = $sql->getValue('body');
                if ($sql->getValue('body_html') != '') {
                    $mail->Body = $sql->getValue('body_html');
                    $mail->IsHTML(true);
                }
                $attachments = json_decode($sql->getValue('attachments'), true);
                if (is_array($attachments)) {
                    foreach ($attachments as $attachment) {
                        $mail->AddAttachment($attachment['path'], $attachment['name']);
                    }
                }
                $status = $mail->Send();
                $status_sql = rex_sql::factory();
                if ($status) {
                    $status_sql->setQuery('DELETE FROM ' . rex::getTable('phpmailer_retry') . ' WHERE id = ' . $sql->getValue('id'));
                }
                else {
                    $status_sql->setQuery('UPDATE ' . rex::getTable('phpmailer_retry') . ' SET retried = retried + 1 WHERE id = ' . $sql->getValue('id'));
                }
                $sql->next();
            }
        }

        return true;
    }

    public function getTypeName()
    {
        return rex_i18n::msg('rex_cronjob_phpmailer_retry');
    }

    public function getParamFields()
    {
        return [];
    }

}