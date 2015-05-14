<?php
include '../startup.php';

if (!$_user->isLogged()) {
	header('Location: login.php');
}else {
	header('Location: home.php');
}
?>