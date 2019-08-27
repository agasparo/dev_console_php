<?php

Class stats {

	private $comm;
	private $commandes = ["most", "less"];
	private $args = [];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function check_require() {

		$path_re = new link('.env_modules');
		$data = file($path_re->get_link(1));
		foreach ($data as $value) {
			$value = unserialize($value);
			if (key($value) == "bdd")
				return (1);
		}
		return ("You must have the bdd module to run this module");
	}

	private function most() {

		if (!isset($this->args[0]) || !isset($this->args[1]))
			return ("Usage : stats.most [table] [colum (value of this colum must be : int)]");

		$req = "SELECT * FROM ".$this->args[0]." ORDER BY ".$this->args[1]." DESC";
		$get_bdd = new bdd($this->comm, $this->args);
		return ($get_bdd->execute_req($this->args[0], $req));
	}

	private function less() {

		if (!isset($this->args[0]) || !isset($this->args[1]))
			return ("Usage : stats.less [table] [colum (value of this colum must be : int)]");

		$req = "SELECT * FROM ".$this->args[0]." ORDER BY ".$this->args[1]." ASC";
		$get_bdd = new bdd($this->comm, $this->args);
		return ($get_bdd->execute_req($this->args[0], $req));
	}

	public function execute() {

		if (($res = $this->check_require()) == 1)
			return ($this->{$this->comm}());
		return ($res);
	}
}

?>