<?php

class console {

	private $html;
	private $res;
	private $commandes = ["get", "clear", "add", "update", "show", "delete", "request", "list", "set", "get_var"];
	private $get = [];
	private $add;
	private $update;
	private $delete;
	private $var;
	private $all_vars;
	private $unautorized = ["password"];

	public function __Construct($comm, $anc, $all_vars) {
		$tabs_bdd = new bdd_tab();
		$this->get = $tabs_bdd->get_tab();
		$this->add = $tabs_bdd->get_tab();
		$this->update = $tabs_bdd->get_tab();
		$this->delete = $tabs_bdd->get_tab();
		$this->all_vars = $all_vars;
		$this->init = file_get_contents('../dev_console/template/console_init.html');
		if ($comm == "init") {
			$this->html = file_get_contents('../dev_console/template/console.html');
			$this->res = "";
		} else {
			$this->html = "{{infos}}";
			$single = explode(" ", $comm);
			$this->res = "<pre style='margin-left: 0.5%; color: white;'>".$this->commande_exist($comm)."</pre>";
		}
		$this->replace_text($anc, $comm);
		$this->aff();
	}

	public function return_var() {
		if (!empty($this->var))
			return ($this->var);
		return ("none");
	}

	public function reset_var() {
		$this->var = "";
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
		if (in_array($single[0], $this->commandes))
			return ($this->prepare($comm));
		else
			return ("La commande '".$comm."' existe pas");
	}

	private function prepare($comm) {
		$single = explode(" ", $comm);
		if (!isset($this->{$single[0]}))
			return ($this->execute($single));
		$tab = $this->{$single[0]};
		if (!isset($single[1]))
			return ("usage : ".$single[0]." [ ".implode(", ", $tab)." ]");
		if (in_array($single[1], $tab))
			return($this->execute($single));
		return ("usage : ".$single[0]." [ ".implode(", ", $tab)." ]");
	}

	private function execute($tab) {
		$autre = "";
		if (isset($tab[2]) && isset($tab[3]) && isset($tab[4]))
			$autre = " WHERE ".$tab[2]." ".$tab[3]." ".$tab[4];
		if ($tab[0] == "get")
			return($this->string($this->search_infos("SELECT * FROM ", $tab[1], $autre, []), $tab[1]));
		else if ($tab[0] == "clear")
			return ($this->init.">><<clear");
		else if ($tab[0] == "add")
			return ($this->create_reqs($tab));
		else if ($tab[0] == "update")
			return ($this->up_del($tab, "UPDATE"));
		else if ($tab[0] == "delete")
			return ($this->up_del($tab, "DELETE"));
		else if ($tab[0] == "show")
			return ($this->show());
		else if ($tab[0] == "request")
			return ($this->request_init($tab));
		else if ($tab[0] == "list")
			return ('--> '.implode("<br>--> ", $this->commandes));
		else if ($tab[0] == "set")
			return ($this->set_var($tab));
		else if ($tab[0] == "get_var")
			return ($this->get_var($tab));
	}

	private function get_var($tab) {
		require_once 'Console/Table.php';
		if (isset($tab[1])) {
			$tab[1] = str_replace("$", "", $tab[1]);
			if ($tab[1] == "*") {
				$tbl = new Console_Table();
				$tbl->setHeaders(["Nom de la variable", "Valeur de la variable"]);
				foreach ($this->all_vars as $key => $value) {
					if (is_array($value))
						$tbl->addRow([$key, $this->show_var($key, $value)]);
					else
						$tbl->addRow([$key, $value]);
				}
				return ($tbl->getTable());
			}
			if (array_key_exists($tab[1], $this->all_vars)) {
				if (is_array($this->all_vars[$tab[1]])) {
					return ($this->show_var($tab[1], $this->all_vars[$tab[1]]));
				}
				return ($this->all_vars[$tab[1]]);
			}
			return ("La variable ".$tab[1]." n exist pas");
		}
		return ('GET_VAR : [variable]');
	}

	private function set_var($tab) {
		if (isset($tab[1]) && isset($tab[2]) && isset($tab[3])) {
			$this->var = array(str_replace("$", "", $tab[1]) => $tab[3]);
			return ($tab[3]);
		}
		return ('SET : [variable] = [valeure]');
	}

	private function request_init($tab) {
		if (isset($tab[1]) && isset($tab[2])) {
			$i = 3;
			$tabs = [];
			while (isset($tab[$i])) {
				$e = explode(":", str_replace("'", '', $tab[$i]));
				if (isset($e[1]))
					$tabs[$e[0]] = $e[1];
				$i++;
			}
			$url = str_replace("'", '', $tab[2]);
			$request = new http_request(strtoupper($tab[1]), $tabs, $url);
			return ($request->send_request());
		}
		return ("request [type] [url] ['name_variable:valeur_varibale']");
	}

	private function show_var($var, $tab) {
		require_once 'Console/Table.php';
		$tbl = new Console_Table();
		$tbl->setHeaders(["Valeur de la key", "Valeur de la variable : ".$var]);
		foreach ($tab as $key => $value) {
			$tbl->addRow([$key, $value]);
		}
		return ($tbl->getTable());
	}

	private function show() {
		require_once 'Console/Table.php';
		$tbl = new Console_Table();
		$tbl->setHeaders(["liste des tables de la bdd"]);
		$tabs_bdd = new bdd_tab();
		foreach ($tabs_bdd->get_tab() as $value) {
			$tbl->addRow([$value]);
		}
		return ($tbl->getTable());
	}

	private function up_del($tab, $type) {
		require '../dev_console/modules/bdd.php';
		$req = "";
		if ($type == "UPDATE") {
			$req = $type;
			if (isset($tab[2]) && isset($tab[3]) && isset($tab[4]) && isset($tab[5]) && isset($tab[6]) && isset($tab[7])) {
				$this->search_infos($type." ", $tab[1], " SET ".$tab[2]." ".$tab[3]." ?"." WHERE ".$tab[5]." ".$tab[6]." ?", [$tab[4], $tab[7]]);
				return ("L element a bien ete update");
			}
			return ("UPDATE : [tab] [colum to update] [sign(<=>)] [value to update] [value cond] [sign(<=>)] [value cond]");
		}
		else if ($type == "DELETE") {
			if (isset($tab[2]) && isset($tab[3]) && isset($tab[4])) {
				$this->search_infos($type." FROM ", $tab[1], " WHERE ".$tab[2]." ".$tab[3]." ?", [$tab[4]]);
				return ("L element a bien ete supprime");
			}
			return ("DELETE : [tab] [colum] [sign(<=>)] [value to delete]");
		}
	}

	private function create_reqs($tab) {
		$tabs = $tab;
		$colum = $this->col($tab[1], 0);
		unset($tab[0]);
		unset($tab[1]);
		unset($colum[0]);
		$colum = array_values($colum);
		$tab = array_values($tab);
		$point = "";
		$c = count($colum);
		while ($c > 0) {
			$point .= '?, ';
			$c--;
		}
		$point = substr($point, 0, -2);
		if (count($colum) != count($tab))
			return ("Erreur : tu doit completer ces champs : ".implode(", ", $colum));
		$str = '('.(implode(", ", $colum)).') VALUES('.$point.')';
		$rest = $this->search_infos("INSERT INTO ", $tabs[1], $str, $tab);
		return ("L element a bien etait ajoute");
	}

	private function search_infos($option, $table_bdd, $specific, $table_values) {
		require '../dev_console/modules/bdd.php';
		$req = $option.$table_bdd.$specific;
		$exec_req = $bdd->prepare($req);
		$exec_req->execute($table_values);
		return ($exec_req->fetchAll());
	}

	private function col($table_bdd, $t) {
		require 'modules/bdd.php';
		$e = $bdd->query('DESCRIBE '.$table_bdd);
		$colum = $e->fetchAll();
		$i = 0;
		$col = [];
		while (isset($colum[$i])) {
			if ($t == 1) {
				if (!in_array($colum[$i]['Field'], $this->unautorized))
					$col[] = $colum[$i]['Field'];
			} else if ($t == 0) {
				$col[] = $colum[$i]['Field'];
			}
			$i++;
		}
		return($col);
	}

	private function string($tab, $table_bdd) {
		require_once 'Console/Table.php';
		$i = 0;
		$strs = "";
		$tbl = new Console_Table();
		$tbl->setHeaders($this->col($table_bdd, 1));
		while (isset($tab[$i])) {
			$tab_infos = [];
			foreach ($tab[$i] as $key => $value) {
				if (!in_array($key, $this->unautorized) && !is_numeric($key))
					$tab_infos[] = $value;
			}
			$tbl->addRow($tab_infos);
			$i++;
		}
		return ($tbl->getTable());
	}
}

?>
