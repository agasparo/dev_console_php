<?php

Class bdd {

	private $args = [];
	private $comm;
	private $bdd;
	private $tables = [];
	private $commandes = ["insert", "update", "delete", "show_table", "get"];

	public function __Construct($commande, $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;

		$path = new link('.env');
		$data = file($path->get_link(1));

		if (!isset($data[2]))
			$data[2] = "";

		$data[1] = str_replace("\n", "", $data[1]);
		$data[2] = str_replace("\n", "", $data[2]);
		$data[3] = str_replace("\n", "", $data[3]);

		$this->bdd = new PDO(str_replace("\n", "", $data[0]), str_replace("\n", "", $data[1]), $data[2]);

		$req_table = $this->bdd->query("SHOW TABLES");
		$res = $req_table->fetchAll();
		$this->tables = [];

		$i = 0;
		while (isset($res[$i])) {
			$this->tables[] = $res[$i][0];
			$i++;
		}
	}

	private function show_table() {

		return ($this->create_table("Database table(s)", $this->tables, 0));
	}

	private function get() {

		if (!isset($this->args[0]))
			return ("Usage : bdd.get [table to get data]");

		if (!in_array($this->args[0], $this->tables))
			return ("Table ".$this->args[0]." doesn't exist ");

		$get_infs = $this->bdd->query('DESCRIBE '.$this->args[0]);
		$colum = $get_infs->fetchAll();

		$get_infs = $this->bdd->query('SELECT * FROM '.$this->args[0]);
		$tab_infs = $get_infs->fetchAll();

		return ($this->create_table($this->get_just_val($colum, 'Field'), $this->get_just_val($tab_infs, ["int"]), 0));
	}

	private function insert() {

		if (!isset($this->args[0]))
			return ("Usage : bdd.get [table to insert data] [value for each colum (ex : 'arthur, gasparotto, 19 ...') ]");

		$get_infs = $this->bdd->query('DESCRIBE '.$this->args[0]);
		$colum = $get_infs->fetchAll();

		$cols = $this->get_just_val($colum, 'Field');

		if (!in_array($this->args[0], $this->tables))
			return ("Table ".$this->args[0]." doesn't exist ");

		if (count($this->args) != count($cols))
			return ("The number of values doesn't match with the number of colum (".(count($this->args) - 1)." value(s) for ".(count($cols) - 1)." colum(s))");
	}

	private function get_just_val($tab, $champs) {
		$i = 0;
		$col = [];

		while (isset($tab[$i])) {

			if (is_array($champs)) {
				foreach ($tab[$i] as $key => $value) {
					if ($champs[0] == "int") {
						if (is_numeric($key))
							$col[] = $value;
					}
				}
			} else
				$col[] = $tab[$i][$champs];
			$i++;
		}
		return ($col);
	}

	private function create_table($header, $values, $type) {
		$tbl = new createtab();

		if (is_array($header))
			$tbl->setHeaders($header);
		else
			$tbl->setHeaders([$header]);

		$tbl->addRow($values);

		return ($tbl->getTable());
	}

	public function execute() {
		
		return ($this->{$this->comm}());
	}
}

?>