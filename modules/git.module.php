<?php

Class git {

	private $comm;
	private $commandes = ["push", "set_commit_name", "get_commit_name"];
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

		if (isset($env[4]))
			$env[4] = $this->args[0];
		else
			$env[4] = "\n".$this->args[0];
		file_put_contents($path_env->get_link(1), implode("", $env));
		return ("Commit name change success");
	}

	private function get_commit_name() {

		$path_env = new link('.env');
		$env = file($path_env->get_link(1));

		if (isset($env[4]))
			return ("Commit name : ".$env[4]);
		return ("No Commit name set");
	}

	private function push() {

		if (!isset($this->args[0]))
			return ("Usage : git.push [directory]");

		$path_env = new link('.env');
		$env = file($path_env->get_link(1));

		$path_directory = new link($this->args[0]);
		$dir = file($path_env->get_link(1));

		return ($dir);

		exec("cd ".$dir." && git add .");
		exec("cd ".$dir." && git commit -m '".$env[4]."'");
		exec("cd ".$dir." && git push");	
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>