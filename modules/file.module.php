<?php

Class file {

	/**
	 * @comm String
	 */
	private $comm;

	/**
	 * @commandes Array
	 */
	private $commandes = ["structure", "show"];

	/**
	 * @args Array
	 */
	private $args = [];

	public function __Construct(String $commande, Array $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function structure() : String {

		if (!isset($this->args[0]))
			return ("Usage : file.structure [ path ]");

		if ($this->args[0][strlen($this->args[0]) - 1] != "/")
			$this->args[0] .= "/";

		$path_file = $this->search_path();
		if (!file_exists($path_file))
			return ($path_file);

		exec("ls -R ".$path_file."*", $rep);

		$i = 0;
		$str = "";
		while (isset($rep[$i])) {

			if (!empty($rep[$i])) {

				if ($rep[$i][strlen($rep[$i]) - 1] == ":")
					$str .= "<br>".$rep[$i]."<br>";
				else if (empty($rep[$i]))
					$str .= "<br>";
				else
					$str .= $rep[$i]." ";
			} else
				$str .= "<br>";

			$i++;
		}

		return ($str);
	}

	private function search_path() : String {

		$try = 0;

		$actual_path = getcwd().'/'.$this->args[0];
		while (!file_exists($actual_path)) {

			if ($try > 10)
				return ("error : Directory can't be found");
			
			$ex_path = explode('/', $actual_path);
			unset($ex_path[4]);
			$actual_path = implode('/', $ex_path);
			$try++;
		}


		return ($actual_path);
	}

	private function show() : String {

		if (!isset($this->args[0]))
			return ("Usage : file.show [file to read]");

		if (!file_exists($this->args[0]))
			return ("The file : ".$this->args[0]." doesn't exist");

		$path_env = new link($this->args[0]);
		$env = str_replace("<?php", "", file_get_contents($path_env->get_link(1)));

		return ($env);
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>