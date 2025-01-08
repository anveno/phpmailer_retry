# REDAXO-AddOn: PHPMailer Retry

- Vorraussetzung: Versand erfolgt via rex_yform_email_template::sendMail()
- Speichert fehlgeschlagene E-Mails in einer Tabelle
- Versucht fehlgeschlagene E-Mails erneut zu versenden
- Nutzt einen Cronjob zum Versenden der E-Mails

## Todos/Bugs:

- [ ] Feature: Cronjob automatisch anlegen
- [ ] Feature: Einstellbare Anzahl an Versuchen
- [ ] Feature: Einstellbare Wartezeit zwischen Versuchen

## Version 1.1.0 - 08.01.2025
