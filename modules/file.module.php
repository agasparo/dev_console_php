<?php

Class file {

	private $comm;
	private $commandes = ["structure", "show", "update"];
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

			if ($rep[$i][strlen($rep[$i]) -1] == ":")
				$str .= "<br>".$rep[$i]."<br>";
			else if (empty($rep[$i]))
				$str .= "<br>";
			else
				$str .= $rep[$i]." ";

			$i++;
		}

		return ($str);
	}

	private function show() {

		if (!isset($this->args[0]))
			return ("Usage : file.show [file to read]");
		
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>