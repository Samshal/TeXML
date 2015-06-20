<?php
	require_once("../src/TeXML.php");


	$texml = new TeXML();

	$texml->Subject("PHP");

	$question1 = array(
			"PHP",
			"How is a variable declared in PHP?",
			array("Using the + sign", "The double dollar sign is used", "The $ sign is used", "I dont know"),
			5, 3
		);
	$question2 = array(
			"PHP",
			"Is PHP Object Oriented",
			array("Yes, I guess so", "No", "Probably", "Yes! Im very sure"),
			5, 4
		);
	$question3 = array(
			"PHP",
			"Does PHP Support Object Encapsulation",
			array("No", "Yes", "I don't know", "The language isn't object oriented"),
			10, 1
		);
	$question4 = array(
			"PHP",
			"Is it possible to write a PHP program that runs on a console based computer system",
			array("Very well", "Obviously!", "This question is confusing", "No, thats not possible"),
			10, 1
		);
	$question5 = array(
			"PHP",
			"Pick the odd one out",
			array("CakePHP", "Symfony", "GTK+", "Zend"),
			10, 3
		);
	$question_array = array($question1, $question2, $question3, $question4, $question5);
	foreach ($question_array as $value) {
		$texml->NewQuestion($value[0], $value[1], $value[2], $value[3], $value[4]);
	}

	echo "Done!";
	header("Location: start.php");
?>