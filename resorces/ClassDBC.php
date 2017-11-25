<?php

	class DBConnection
	{
		private $ekbuser = 'lehrer';
		private $pwd ='einfach';
		private $host='localhost';
		private $db='ekb';
		private $port = 80;
		private $con;
		private static $_instance = NULL;

		public static function getInstance()
		{
			if(NULL === self :: $_instance)
			{
				self :: $_instance = new DBConnection();
			}
			return self :: $_instance;
		}

		public function __construct()
		{

		}

		//connection
		public function connectDB()
		{
			try
			{
				$this->con = new mysqli($this->host,$this->ekbuser,$this->pwd,$this->db);

			}
			catch (Exception $e)
			{
				if (mysqli_connect_error())
				{
					die('Connect Error (' . mysqli_connect_errno() . ') '
							. mysqli_connect_error());
				}
			}
		}

		//close Connection
		public function close()
		{
			$this->con->close();
		}

		//select
		public function sqlStatement($strQ)
		{
			//echo $strQ;
			$result=$this->con->query($strQ);
			return $result;
		}

		public function saveInput($txt)
		{
			return $this->con->real_escape_string($txt);	
		}

	}

?>

