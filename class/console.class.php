<?php

class console {

	private $commandes = [];
	private $modules = [];

	private $html;
	private $res;
	private $var;

	public function __Construct($comm, $anc) {

		$this->get_infs_modules();
		$this->init = file_get_contents('../dev_console/template/console_init.html');

		if ($comm == "init") {

			$this->html = file_get_contents('../dev_console/template/console.html');
			$path = new link('dev_console/js/console.js');
			$path_env = new link('.env');
			$env = file($path_env->get_link(1));
			$this->html = str_replace("{{link}}", $path->get_link(0), $this->html);
			$this->init = str_replace("{{header}}", $env[3], $this->init);
			$this->res = "";
		} else {

			$this->html = "{{infos}}";
			$single = explode(" ", $comm);
			$this->res = "<pre style='margin-left: 0.5%; color: white;'>".$this->commande_exist($comm)."</pre>";
		}

		$this->replace_text($anc, $comm);
		$this->aff();
	}

	private function get_infs_modules() {

		$path = new link('.env_modules');
		$file = $path->get_link(1);
		$data = file($file);
		foreach ($data as $key => $value) {

			$tmp = unserialize($value);
			$this->commandes[] = $tmp[key($tmp)]['commandes'];
			$this->modules[] = key($tmp);
		}
	}

	private function aff() {

		echo $this->html;
	}

	private function replace_text($anc, $comm) {

		if (empty($this->res))
			$this->html = str_replace("{{infos}}", $this->init, $this->html);
		else
			$this->html = str_replace("{{infos}}", $anc.$comm.$this->res.$this->init, $this->html);
	}

	private function commande_exist($comm) {

		$single = explode(" ", $comm);
		$separe = explode(".", $single[0]);

		if (!isset($separe[1]))
			return ($this->help(0, ""));

		if (!in_array($separe[1], $this->commandes[array_search($separe[0], $this->modules)]))
			return ($this->help(1, $separe[0]));

		if (!class_exists($separe[0]))
			return ("The Class ".$separe[0]." doesn't exist ...");

		$exec = new $separe[0]($comm);

		if (!method_exists($exec, 'execute'))
			return ("The class ".$separe[0]." haven't an execute public function ...");

		return ($exec->execute());
	}

	private function help($type, $other) {

		$path = new link('template/help.txt');
		$file = $path->get_link(1);

		if ($type == 0)
			return (file_get_contents($file).$this->create_table($this->modules, ["Module(s) available", "Commande(s) available"], 0));

		return ($this->create_table($this->commandes[array_search($other, $this->modules)], "Commande(s) available for : ".$other, 1));
	}

	private function create_table($elem, $header, $type) {

		$tbl = new createtab();

		if (is_array($header))
			$tbl->setHeaders($header);
		else
			$tbl->setHeaders([$header]);

		foreach ($elem as $key => $value) {
			if ($type == 0){
				$a = implode(", ", $this->commandes[array_search($value, $this->modules)]);
				$tbl->addRow([$value, $a]);
			}
			else
				$tbl->addRow([$value]);
		}
		return ($tbl->getTable());
	}
}

?>
