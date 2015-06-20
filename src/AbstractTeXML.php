<?php
	if (!file_exists("../FileManager/file_manager.php"))
	{
		die("You need to import the file_manager class into this package");
	}
	require_once("../FileManager/file_manager.php");

	abstract class AbstractTeXML extends FileManager
	{
		public static $_DIR = "Subjects/";
		public static $_EXT = ".json";
		public function __construct(){ }

		abstract function Subject($subjectname);

		abstract function NewQuestion($subject, $question, $options, $score, $correct);

		abstract function StartTest($subjectname);

		abstract function DisplayQuestion();

		abstract function AcceptAnswer($option);

		abstract function GenerateResult();
	}
?>