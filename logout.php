<?php
session_start();
session_unset();
session_destroy();

// Redireciona para o login
header("Location: ong-login.php");
exit;
