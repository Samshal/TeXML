<?php
	require_once("../src/TeXML.php");


	$texml = new TeXML();

	//$options = array(2, 4, 6, 20, 10);
	$question = $texml->DisplayQuestion();
	if ($question == -1)
	{
		header("Location: gen.php");
	}
	else
	{
		//print_r($question);
		$q = $question["Question"];
		echo "<body style='font-size: 2em; background-color: #FFEFD5'>";
		echo "QUESTION: ".$q."<br/><br/>";
		echo '<form action="accept.php" method="get" style="background-color: #87CEFA">';
		foreach ($question["Options"] as $key => $value) {
			$key = $key + 1;
			echo "<input type='radio' name='A' value='$key' /> $value<br/>";
		}
		echo "<center><input type='submit' value='Submit' style='width: 20%' /></center></form></body>";
	}
?>