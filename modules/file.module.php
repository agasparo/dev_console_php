<?php

Class file {

	private $comm;
	private $commandes = ["structure", "show"];
	private $args = [];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function structure() {

		exec("ls -R *", $rep);

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

	private function show() {

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