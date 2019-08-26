<?php

Class git {

	private $comm;
	private $commandes = ["push", "set_commit_name"];
	private $args = [];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function set_commit_name() {

		if (!isset($this->args[0]))
			return ("Usage : git.set_commit_name ['new name']");

		$path_env = new link('.env');
		$env = file($path_env->get_link(1));

		$env[3] = $this->args[0];
		file_put_contents($path_env->get_link(1), implode("", $env));
		return ("Commit name change success");
	}

	private function push() {

		
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>