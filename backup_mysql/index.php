<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use Rafa\Adapters\PhpMailerAdapter;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////// DOCUMENTACIÓN DEL SERVICIO DE BACKUP DE BASE DE DATOS Y ENVÍO POR CORREO ELECTRÓNICO ////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Este script genera un respaldo de la base de datos y lo envía por correo electrónico como archivo adjunto.

// Generar un nombre de archivo único basado en la fecha y hora
$dateTime = date('Y-m-d_H-i-s'); // Formato: Año-Mes-Día_Hora-Minuto-Segundo
$backupFile = "/opt/lampp/htdocs/backup/backup_mysql/backup_{$dateTime}.sql";

// Generar respaldo de la base de datos utilizando mysqldump
shell_exec("/opt/lampp/bin/mysqldump -u root bd_bolsa > {$backupFile}");

// Verificar que el archivo de respaldo se generó correctamente
if (!file_exists($backupFile)) {
    die("El archivo de respaldo no se generó correctamente en la ruta especificada: {$backupFile}");
}

// Configurar el correo electrónico para enviar el respaldo
$mail = new PhpMailerAdapter;
$mail->setFrom(MAIL_USERNAME, 'Bolsa_System Backup'); // Configurar remitente
$mail->addAddress('holaromels@gmail.com', 'Destinatario'); // Configurar destinatario
$mail->mountContent('Respaldo de Base de Datos', 'Adjunto encontrarás el respaldo generado el ' . date('d-m-Y H:i:s')); // Asunto y cuerpo del correo

// Adjuntar el archivo de respaldo generado
$mail->addAttachment($backupFile);

// Enviar el correo electrónico
try {
    $mail->send();
    echo "El respaldo de la base de datos ha sido enviado correctamente.";
} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error: {$e->getMessage()}";
}
?>
