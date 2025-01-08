<?php

// Create tables
rex_sql_table::get(rex::getTable('phpmailer_retry'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('timestamp', 'bigint(20)', true))
    ->ensureColumn(new rex_sql_column('retried', 'bigint(20)', true))
    ->ensureColumn(new rex_sql_column('name', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('mail_from', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('mail_from_name', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('mail_reply_to', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('mail_reply_to_name', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('subject', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('body', 'text'))
    ->ensureColumn(new rex_sql_column('body_html', 'text'))
    ->ensureColumn(new rex_sql_column('attachments', 'text'))
    ->ensureColumn(new rex_sql_column('mail_to', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('mail_to_name', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('email_subject', 'varchar(191)'))
    ->ensure();


// Create cronjob
$now = new DateTime();
$startdate = date('Y-m-d 00:00:00',strtotime( "tomorrow" ));

$cronjob = rex_sql::factory();
$cronjob->setDebug(true);
$cronjob->setQuery('SELECT id FROM '.rex::getTable('cronjob'). ' WHERE type LIKE "rex_cronjob_phpmailer_retry"');

if ($cronjob->getRows() == 0) {

    $cronjob = rex_sql::factory();
    $cronjob->setDebug(true);
    $cronjob->setTable(rex::getTable('cronjob'));
    $cronjob->setValue('name','PHPMailer Retry');
    $cronjob->setValue('description','Versucht fehlgeschlagene E-Mails erneut zu versenden.');
    $cronjob->setValue('type','rex_cronjob_phpmailer_retry');
    $cronjob->setValue('interval','{"minutes":[0],"hours":[0],"days":"all","weekdays":"all","months":"all"}');
    $cronjob->setValue('environment','|frontend|backend|');
    $cronjob->setValue('execution_start','1970-01-01 01:00:00');
    $cronjob->setValue('status','1');
    $cronjob->setValue('parameters','[]');
    $cronjob->setValue('nexttime',$startdate);
    $cronjob->setValue('createdate',$now->format('Y-m-d H:i:s'));
    $cronjob->setValue('updatedate',$now->format('Y-m-d H:i:s'));
    $cronjob->setValue('createuser',rex::getUser()->getLogin());
    $cronjob->setValue('updateuser',rex::getUser()->getLogin());

    try {
        $cronjob->insertOrUpdate();
        echo rex_view::success('Der Cronjob "PHPMailer Retry" wurde angelegt. ');
    } catch (rex_sql_exception $e) {
        echo rex_view::warning('Der Cronjob "PHPMailer Retry" wurde nicht angelegt.<br/>Wahrscheinlich existiert er schon.');
    }
}