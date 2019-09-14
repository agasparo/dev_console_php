<?php

Class git {


	/**
	 * @comm String
	 */
	private $comm;

	/**
	 * @commandes Array
	 */
	private $commandes = ["push", "set_commit_name", "get_commit_name"];

	/**
	 * @args Array
	 */
	private $args = [];

	public function __Construct(String $commande, Array $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function set_commit_name() : String {

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

	private function get_commit_name() : String {

		$path_env = new link('.env');
		$env = file($path_env->get_link(1));

		if (isset($env[4]))
			return ("Commit name : ".$env[4]);
		
		return ("No Commit name set");
	}

	private function push() : String {

		if (!isset($this->args[0]))
			return ("Usage : git.push [path to directory] for you maybe : ".$this->link_proj());

		$path_env = new link('.env');
		$env = file($path_env->get_link(1));

		if (!file_exists($this->args[0]))
			return ("The file : ".$this->args[0]." doesn't exist, you would say : ".$this->link_proj()." ? ");

		exec("cd ".$this->args[0]." && git add .", $add);
		exec("cd ".$this->args[0]." && git commit -m '".$env[4]."'", $commit);
		exec("cd ".$this->args[0]." && git push", $push);

		return (implode("\n", $add).implode("\n", $commit).implode("\n", $push));

	}

	private function link_proj() : String {

		$serv = explode("/", str_replace($_SERVER['HTTP_ORIGIN'], '', $_SERVER['HTTP_REFERER']));
		return ($_SERVER['DOCUMENT_ROOT']."/".$serv[1]);
	}

	public function execute() : String {

		return ($this->{$this->comm}());
	}
}

?>