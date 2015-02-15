<?php
require_once('../includes/config.php');

session_start();

$_SESSION['kbuser']['id'] = null;
unset($_SESSION['kbuser']);

header('Location: '.$config['site_url'].'login.php?ref=adm/');
exit;

?>