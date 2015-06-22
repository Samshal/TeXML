<?php
	class FileManager
	{
		public function __construct(){ /* */ }

		static private $_modes = array(1=>"Open", 2=>"OpenOrCreate", 3=>"Truncate", 4=>"Append");
		static private $_methods = array(1=>"Read", 2=>"Write");
		static private $_supportedmodes = array("open"=>"r+", "openorcreate"=>"a+", "truncate"=>"w+", "append"=>"a+");
		/*
			Declaring The File Method
			@params:
			=> $file = full path of the file to manage
			=> $method = valid options: "Read", "Write"
			=> $mode = valid options: "Open", "OpenOrCreate", "Truncate", "Append"
			=> $opt_content = the content to write to the file, should be null or "" if reading from the file
		*/
		public function File($file, $method, $mode, $opt_content)
		{
			if (!self::__validateFileParam($method, self::$_methods) || !self::__validateFileParam($mode, self::$_modes))
			{
				return array("An Unexpected Value Was Provided, Please Try Again With The Right Value");
			}
			else
			{
				$method = strtolower($method); $mode = strtolower($mode);
				switch ($method)
				{
					case "read":
						{
							return self::FileReader($file, $mode);
							break;
						}
					case "write":
						{
							return self::FileWriter($file, $mode, $opt_content);
							break;
						}
					default:
					{
						//I wonder how the program can ever get here ;)
					}
				}
			}
		}
		private function __validateFileParam($needle, $haystack)
		{
			if (!is_array($haystack))
			{
				//an array object is expected, we need a method for handling exceptions probably (an external class that implements or inherits the base Exception methods from PHP)
				//for now we notify the user and end program execution

				die("An Array Was Expected!");

			}

			if (is_string($needle))
			{
				$needle = strtolower($needle);
			}

			foreach ($haystack as $key => $value)
			{
				$value = strtolower($value);
				$haystack[$key] = $value; 
			}

			if (!in_array($needle, $haystack))
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static private function FileReader($_file, $_mode)
		{
			$mode = self::$_supportedmodes[$_mode];
			try
			{
				$fh = fopen($_file, $mode) or die ("File Access Error!");
				$mission = file_get_contents($_file);
				if (!$mission)
				{
					return array("Unable to read file");
				}
				else
				{
					return $mission;
				}
			}
			catch(Exception $e)
			{
				return array("Unable to open / manipulate the file you provided, is the file path a valid one?", $e);
			}
			finally
			{
				if (isset($fh))
				{
					$fh = null;				
				}
			}
		}

		static private function FileWriter($_file, $_mode, $_content)
		{
			$mode = self::$_supportedmodes[$_mode];
			try
			{
				$fh = fopen($_file, $mode) or die ("File Access Error!");
				$mission = fwrite($fh, $_content);
				if (!$mission)
				{
					return array("Unable to write content to file");
				}
				else
				{
					return array(true);
				}
			}
			catch(Exception $e)
			{
				return array("Unable to open / manipulate the file you provided, is the file path a valid one?");
			}
			finally
			{
				if (isset($fh))
				{
					$fh = null;				
				}
			}			
		}
	}
?>