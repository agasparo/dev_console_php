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

		$env[4] = $this->args[0];
		file_put_contents($path_env->get_link(1), implode("", $env));
		return ("Commit name change success");
	}

	private function push() {

		if (!isset($this->args[0]))
			return ("Usage : git.push [directory]");

		$path_env = new link('.env');
		$env = file($path_env->get_link(1));

		exec("cd ".$this->args[0]." && git add .");
		exec("cd ".$this->args[0]." && git commit -m '".$env[4]."'");
		exec("cd ".$this->args[0]." && git push");	
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>