<?php

class console {

	/**
	 * @commandes Array
	 */
	private $commandes = [];

	/**
	 * @modules Array
	 */
	private $modules = [];

	/**
	 * @html String
	 */
	private $html;

	/**
	 * @res String
	 */
	private $res;

	/**
	 * @var String
	 */
	private $var;

	public function __Construct(String $comm, String $anc) {

		$path_html = new link('template/console.html');
		$html_tem = $path_html->get_link(1);

		$path_html = new link('template/console_init.html');
		$html_init = $path_html->get_link(1);

		$this->get_infs_modules();
		$this->init = file_get_contents($html_init);

		$path_env = new link('.env');
		$env = file($path_env->get_link(1));

		if ($comm == "init") {

			$this->html = file_get_contents($html_tem);
			
			$path = new link('js/console.js');
			$path_css = new link('css/style.css');

			$this->html = str_replace("{{link}}", $path->get_link(0), $this->html);
			$this->html = str_replace("{{link_css}}", $path_css->get_link(0), $this->html);
			$this->init = str_replace("{{header}}", $env[3], $this->init);
			$this->res = "";
		} else {

			$this->html = "{{infos}}";
			$single = explode(" ", $comm);
			$this->init = str_replace("{{header}}", $env[3], $this->init);
			$this->res = "<pre id='txt_console'>".$this->commande_exist($comm)."</pre>";
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

	private function replace_text(String $anc, String $comm) {

		if (empty($this->res))
			$this->html = str_replace("{{infos}}", $this->init, $this->html);
		else
			$this->html = str_replace("{{infos}}", $anc.$comm.$this->res.$this->init, $this->html);
	}

	private function commande_exist(String $comm) : String {

		$single = explode(" ", $comm);
		$separe = explode(".", $single[0]);

		if (!isset($separe[1]))
			return ($this->help(0, ""));

		if (!in_array($separe[0], $this->modules))
			return ($this->help(0, ""));

		if (!in_array($separe[1], $this->commandes[array_search($separe[0], $this->modules)]))
			return ($this->help(1, $separe[0]));

		if (!class_exists($separe[0]))
			return ("The Class ".$separe[0]." doesn't exist ...");

		$args = explode(" ", $comm);
		$commande = explode(".", $args[0]);
		unset($args[0]);

		$exec = new $separe[0]($commande[1], $this->return_arg(array_values($args)));

		if (!method_exists($exec, 'execute'))
			return ("The class ".$separe[0]." haven't an execute public function ...");

		return ($exec->execute());
	}

	private function return_arg(Array $tab) : Array {

		$i = 0;
		$argum = [];
		$in = 0;
		$c = "";
		while (isset($tab[$i])) {

			if ($in == 0) {

				if ($tab[$i][0] == "'" || $tab[$i][0] == '"') {

					$c = $tab[$i][0];
					if ($tab[$i][strlen($tab[$i]) - 1] == $c)
						$argum[] = str_replace($c, '', $tab[$i]);
					else {

						$str = $tab[$i];
						$in = 1;
					}
				}  else
					$argum[] = $tab[$i];
			} else {

				$str .= " ".$tab[$i];
				if ($tab[$i][strlen($tab[$i]) - 1] == $c) {
					$in = 0;
					$argum[] = str_replace($c, '', $str);
				}
			}
			$i++;
		}
		return ($argum);
	}

	private function help(Int $type, String $other) : String {

		$path = new link('template/help.txt');
		$file = $path->get_link(1);

		if ($type == 0)
			return (file_get_contents($file).$this->create_table($this->modules, ["Module(s) available", "Commande(s) available"], 0));

		return ($this->create_table($this->commandes[array_search($other, $this->modules)], "Commande(s) available for : ".$other, 1));
	}

	private function create_table(Array $elem, $header, Int $type) : String {

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
