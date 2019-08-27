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

	public function execute_req($tab, $req) {

		if (!in_array($this->args[0], $this->tables))
			return ("Table ".$this->args[0]." doesn't exist ");

		$exec = $this->bdd->query($req);
		$exec = $exec->fetchAll();

		$get_infs = $this->bdd->query('DESCRIBE '.$this->args[0]);
		$colum = $get_infs->fetchAll();
		
		return ($this->create_table($this->get_just_val($colum, 'Field'), $this->get_just_val($exec, ["int"]), 0));
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
			return ("Usage : bdd.insert [table to insert data] [value for each colum (ex : 'arthur, gasparotto, 19 ...') ]");

		if (!in_array($this->args[0], $this->tables))
			return ("Table ".$this->args[0]." doesn't exist ");

		$get_infs = $this->bdd->query('DESCRIBE '.$this->args[0]);
		$colum = $get_infs->fetchAll();

		$cols = $this->get_just_val($colum, 'Field');
		unset($cols[0]);
		$cols = array_values($cols);

		if (count($this->args) != (count($cols) + 1))
			return ("The number of values doesn't match with the number of colum (".(count($this->args) - 1)." value(s) for ".count($cols)." colum(s)) Colum to fill : ".implode(", ", $cols));

		$vals = "(".implode(", ", $cols).")";
		$c = count($cols);
		$values_in = "(";
		while ($c > 0) {
			$values_in .= "?, ";
			$c--;
		}

		$values_in = substr($values_in, 0, -2).')';


		$insert_data = $this->bdd->prepare("INSERT INTO ".$this->args[0].$vals." VALUES".$values_in);
		unset($this->args[0]);

		$this->args = array_values($this->args);

		$insert_data->execute($this->args);

		if ($insert_data->rowCount() > 0)
			return ("data fill success");
		return ("Error : data fill fail (maybe check the type of colum)");
	}

	private function update() {

		if (!isset($this->args[0]) || !isset($this->args[1]) || !isset($this->args[2]))
			return ("Usage : bdd.update [table to insert data] [data to change ex('nom:gasparotto, prenom:arthur')] [condition ex('id:3')]");

		if (!in_array($this->args[0], $this->tables))
			return ("Table ".$this->args[0]." doesn't exist ");

		$this->args[1] = str_replace(" ", "", $this->args[1]);
		$this->args[2] = str_replace(" ", "", $this->args[2]);

		$tab_up = explode(",", $this->args[1]);
		$tab_where = explode(",", $this->args[2]);

		if (($is_ok = $this->is_a_colum($tab_up)) != 1)
			return ($is_ok);

		if (($is_ok = $this->is_a_colum($tab_where)) != 1)
			return ($is_ok);

		return ($this->update_bdd($tab_up, $tab_where));

	}

	private function delete() {

		if (!isset($this->args[0]) || !isset($this->args[1]))
			return ("Usage : bdd.delete [table to insert data] [condition ex('id:3')]");

		if (!in_array($this->args[0], $this->tables))
			return ("Table ".$this->args[0]." doesn't exist ");

		$this->args[1] = str_replace(" ", "", $this->args[1]);
		$tab_where = explode(",", $this->args[1]);

		if (($is_ok = $this->is_a_colum($tab_where)) != 1)
			return ($is_ok);

		$values = [];
		$str = "DELETE FROM ".$this->args[0]." WHERE ";

		$i = 0;
		while (isset($tab_where[$i])) {

			$exp = explode(":", $tab_where[$i]);
			$values[] = $exp[1];
			if (isset($tab_where[$i + 1]))
				$str .= $exp[0]." = ? AND ";
			else
				$str .= $exp[0]." = ?";
			$i++;
		}

		$del = $this->bdd->prepare($str);
		$del->execute($values);

		if ($del->rowCount() > 0)
			return ("data delete success");
		return ("Error : data delete fail");

	}

	private function update_bdd($update, $cond) {

		$values = [];

		$str = "UPDATE ".$this->args[0]." SET ";

		$i = 0;
		while (isset($update[$i])) {

			$exp = explode(":", $update[$i]);
			$values[] = $exp[1];
			if (isset($update[$i + 1]))
				$str .= $exp[0]." = ?, ";
			else
				$str .= $exp[0]." = ? ";
			$i++;
		}

		$str .= "WHERE ";

		$i = 0;
		while (isset($cond[$i])) {

			$exp = explode(":", $cond[$i]);
			$values[] = $exp[1];
			if (isset($cond[$i + 1]))
				$str .= $exp[0]." = ? AND ";
			else
				$str .= $exp[0]." = ?";
			$i++;
		}

		$up = $this->bdd->prepare($str);
		$up->execute($values);

		if ($up->rowCount() > 0)
			return ("data update success");
		return ("Error : data update fail");
	}

	private function is_a_colum($tab) {

		$get_infs = $this->bdd->query('DESCRIBE '.$this->args[0]);
		$colum = $get_infs->fetchAll();
		$cols = $this->get_just_val($colum, 'Field');

		$i = 0;
		while (isset($tab[$i])) {

			$exp = explode(":", $tab[$i]);
			if (!in_array($exp[0], $cols))
				return ("The colum ".$exp[0]." doesn't exist in table ".$this->args[0]);
			$i++;
		}
		return (1);
	}

	private function get_just_val($tab, $champs) {

		$i = 0;
		$col = [];

		while (isset($tab[$i])) {

			if (is_array($champs)) {
				foreach ($tab[$i] as $key => $value) {
					if ($champs[0] == "int") {
						if (is_numeric($key))
							$col[$i][] = $value;
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

		$i = 0;
		if (!is_array($values[$i])) {

			foreach ($values as $value) {
				$tbl->addRow([$value]);
			}

			return ($tbl->getTable());
		}

		while (isset($values[$i])) {

			$tbl->addRow($values[$i]);
			$i++;
		}

		return ($tbl->getTable());
	}

	public function execute() {
		
		return ($this->{$this->comm}());
	}
}

?>