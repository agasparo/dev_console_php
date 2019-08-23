<?php

Class bdd {

	private $args = [];
	private $comm;
	private $bdd;
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
	}

	private function show_table() {

		$req_table = $this->bdd->query("SHOW TABLES");
		$res = $req_table->fetchAll();
		$tables = [];

		$i = 0;
		while (isset($res[$i])) {
			$tables[] = $res[$i][0];
			$i++;
		}

		return ($this->create_table("Database table(s)", $tables, 0));
	}

	private function create_table($header, $values, $type) {
		$tbl = new createtab();

		if (is_array($header))
			$tbl->setHeaders($header);
		else
			$tbl->setHeaders([$header]);

		foreach ($values as $key => $value) {

			if ($type == 1)
				$tbl->addRow([$key, $value]);
			else
				$tbl->addRow([$value]);
		}

		return ($tbl->getTable());
	}

	public function execute() {
		
		return ($this->{$this->comm}());
	}
}

?>