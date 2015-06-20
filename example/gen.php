<?php
	require_once("../src/TeXML.php");


	$texml = new TeXML();

	$result = $texml->GenerateResult();
	$percentage = $result["Percentage"];
	$score = $result["Score"];
	if ($percentage >= 50)
	{
		echo "<body style='font-size: 2em; background-color: #90EE90'>";
		echo "<br/><br/><center>YOU PASSED WITH A SCORE OF $score, WHICH MAKES UP $percentage % OF THE TEST</center>";
		echo "Congratulations you are qualified to get a PHD <a href='start.php' style='color:#6B8E23'>Click Here To Take The Test Again</a></body>";
	}
	else
	{
		echo "<body style='font-size: 2em; background-color: #FFB6C1'>";
		echo "<br/><br/><center>YOU FAILED WITH A SCORE OF $score, WHICH MAKES UP $percentage % OF THE TEST</center>";
		echo "Aim for more next time! <a href='start.php' style='color:#8B0000'>Click Here To Take The Test Again</a></body>";
	}
?>