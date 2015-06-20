<?php
/****
*****@Author: Samuel Adeshina
*****@Date:	   6/19/2015, 1:37PM
*****@Contact: samueladeshina73@gmail.com, https://www.facebook.com/samuel.adeshina.779
****/

	require_once("AbstractTeXML.php");

	
	class TeXML extends AbstractTeXML
	{
		static private $_file = "";

		public function __construct() { /**/ }

		public function Subject($subjectname)
		{
			self::$_file = parent::$_DIR.$subjectname.parent::$_EXT;
			if (file_exists(self::$_file))
			{
				return array("Subject Already Exists, Try A Different Name");
			}
			else
			{
				if (!self::File(self::$_file, "Write", "OpenOrCreate", null))
				{
					return array("Unable To Create Subject, This Could Be An Internal Error");
				}
				else
				{
					$question_temp = json_encode(array("Questions"=>array("Total"=>0)));
					$write = parent::File(self::$_file, "Write", "Truncate", $question_temp);
					if ($write)
					{
						unset($question_temp);
						self::$_file = null;
						return array("Subject created successfully");
					}
					else
					{
						return array("Unable to create the subject: $subjectname");
					}
				}
			}
		}

		public function NewQuestion($subjectname, $_question, $_options, $meta, $right)
		{
			if (!is_array($_options) || !is_int($meta) || !is_int($right))
			{
				return array("An Unexpected Data Type Was Encountered");
			}
			else
			{
				self::$_file = parent::$_DIR.$subjectname.parent::$_EXT;
				$base = json_decode(parent::File(self::$_file, "Read", "Open", null));
				$question_num = ++$base->Questions->Total;
				$_meta["Score"] = $meta;
				$_meta["Total"] = count($_options);
				$_meta["Right"] = $right;
				$kids = array("Options"=>$_options, "Meta"=>$_meta);
				$question_name = "Question".$question_num;
				$base->Questions->$question_name = array("Question"=>$_question, "Kids"=>$kids);
				$ret = parent::File(self::$_file, "Write", "Truncate", json_encode($base));
				return $ret;
			}
		}

		public function StartTest($subjectname)
		{
			SESSION_START();
			$_SESSION["Subject"] = $subjectname;
			self::$_file = parent::$_DIR.$subjectname.parent::$_EXT;
			$_SESSION["Total"] = json_decode(parent::File(self::$_file, "Read", "Open", null))->Questions->Total;
			$_SESSION["Unavailable"] = "";
			$_SESSION["Correct"] = "";
			$_SESSION["Incorrect"] = "";
		}

		private function ShowQuestion($rand_question)
		{
			$_SESSION["Unavailable"] .= $rand_question."|";
			$question_num = "Question".$rand_question;
			self::$_file = parent::$_DIR.$_SESSION["Subject"].parent::$_EXT;
			$questions_container = json_decode(parent::File(self::$_file, "Read", "Open", null));
			$question = $questions_container->Questions->$question_num->Question;
			$options = $questions_container->Questions->$question_num->Kids->Options;
			$display = array("Question"=>$question, "Options"=>$options);
			return $display;
		}

		public function DisplayQuestion()
		{
			SESSION_START();
			if (!isset($_SESSION["Subject"]))
			{
				return array("Please Instantiate The StartTest() Method.");
			}
			A:
			$unavailable = explode("|", $_SESSION["Unavailable"]);
			//print_r($unavailable);
			//count($unavailable);
			if (count($unavailable) == $_SESSION["Total"] + 1)
			{
				return -1;
			}
			else
			{
				$index = 1;
				$rand_question = rand($index, $_SESSION["Total"]);
				//echo $rand_question."<br/>";
				if (in_array($rand_question, $unavailable))
				{
					goto A;
				}
				else
				{
					return self::ShowQuestion($rand_question);
				}
			}

		}

		public function AcceptAnswer($option)
		{
			SESSION_START();
			$unavailable = explode("|", $_SESSION["Unavailable"]);
			$question = "Question".$unavailable[count($unavailable) - 2];
			self::$_file = parent::$_DIR.$_SESSION["Subject"].parent::$_EXT;
			$questions = json_decode(parent::File(self::$_file, "Read", "Open", null));
			$right_option = $questions->Questions->$question->Kids->Meta->Right;
			if ($option == $right_option)
			{
				$_SESSION["Correct"] .= $question."|";
				return true;
			}
			else
			{
				$_SESSION["Incorrect"] .= $question."|";
				return false;
			}
		}

		public function GenerateResult()
		{
			SESSION_START();
			$correct = explode("|", $_SESSION["Correct"]);
			$incorrect = explode("|", $_SESSION["Incorrect"]);
			self::$_file = parent::$_DIR.$_SESSION["Subject"].parent::$_EXT;
			$questions = json_decode(parent::File(self::$_file, "Read", "Open", null));
			$gained_score = $lost_score = 0;
			foreach ($correct as $question) {
				if ($question != '' && $question != "|")
				{
					$gained_score += $questions->Questions->$question->Kids->Meta->Score;					
				}
			}
			foreach ($incorrect as $question) {
				if ($question != '' && $question != "|")
				{
					$lost_score += $questions->Questions->$question->Kids->Meta->Score;					
				}
			}
			$total_score = $gained_score + $lost_score;
			$result_perc = ($gained_score / $total_score) * 100;
			SESSION_DESTROY();
			return array("Score"=>$gained_score, "Lost"=>$lost_score, "Total"=>$total_score, "Percentage"=>$result_perc);
		}
	}
?>