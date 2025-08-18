<?php
// php/mailer_config.php
return [
  // SMTP del proveedor (para Gmail):
  'host'       => 'smtp.gmail.com',
  'port'       => 587,          // 465 si usas SMTPS (ssl)
  'secure'     => 'tls',        // 'ssl' para 465
  'username'   => 'emiliojosue321@gmail.com',        // <- tu correo
  'password'   => 'artemail321',// <- 16 chars (no tu password normal)
  'from_email' => 'emiliojosue321@gmail.com',        // mismo del SMTP para evitar SPF/DMARC
  'from_name'  => 'RabinalArts',
  'to_email'   => 'destino@dominio.com',        // <- donde quieres recibir
  'to_name'    => 'Contacto RabinalArts'
];
