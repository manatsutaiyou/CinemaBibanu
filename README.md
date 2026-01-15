# CinemaBibanu

Aplicație web pentru gestionarea unui cinematograf.

## Funcționalități
- Autentificare / înregistrare utilizatori - cu confirmare adresa email
- Doua roluri de utilizator (user si admin)
- Rezervări bilete la film cu sloturi orare
- Limitare locuri (10 / proiecție)
- CRUD filme (admin)
- CRUD programări (admin)
- Profil utilizator - cu optiunea de stergere cont si CRUD date personale
- Statistici site (analytics)
- Formular de contact (email)
- Protecție securitate (CSRF, XSS, SQL Injection)

## Tehnologii
- PHP 8
- MySQL
- Bootstrap 5
- PHPMailer

## Instalare
1. Clonați repository-ul
2. Rulați:
composer require phpmailer/phpmailer (vendor inlaturat)
3. Completați datele DB in config/config.php
4. completati parola in core/Mailer.php -   $mail->Password   = '.....';
