<?php
	require_once("../src/TeXML.php");


	$texml = new TeXML();

	$texml->StartTest("PHP");
	header("Location: test.php");
?>