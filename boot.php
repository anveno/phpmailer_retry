<?php
// CRONJOB REGISTER
if (rex_addon::get('cronjob')->isAvailable()) {
    rex_cronjob_manager::registerType('rex_cronjob_phpmailer_retry');
}

// rex_extension::registerPoint(new rex_extension_point('YFORM_EMAIL_SENT_FAILED', $template_name, $template, true)); // read only
// siehe yform/plugins/email/lib/yform_email_template.php
// $mail->ErrorInfo; wird nicht übermittelt

///////////////////////////////////////////////////////////////////////////////////////////////////
// PHPMailer Retry: Fehler abfangen via Extension Point "YFORM_EMAIL_SENT_FAILED"

rex_extension::register('YFORM_EMAIL_SENT_FAILED', function (rex_extension_point $ep) {
    rex_logger::factory()->info('PHPMailer Retry: Fehler beim Versenden der E-Mail.');
    //dump($ep->getParams());
    //rex_logger::factory()->log('logevent', 'Mein Text zum Event');
    phpmailerretry::saveMailWithErrorToDatabase($ep->getParams());
});