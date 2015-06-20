<?php
	require_once("../src/TeXML.php");


	$texml = new TeXML();

	//s$options = array(2, 4, 6, 20, 10);
	$answer = $_GET["A"];
	$question = $texml->AcceptAnswer($answer);

	echo $_SESSION["Correct"]."<br/>".$_SESSION["Incorrect"];
	header("Location: test.php");
?>