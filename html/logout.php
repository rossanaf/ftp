<?php

	include_once ("header.php");

	if (!loginClass::checkLoginState($db))
	{
		header("location:index.php"); 
		exit();
	}

	loginClass::deleteCookie();
	header("location:nav.php");

?>