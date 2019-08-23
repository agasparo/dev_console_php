<?php
class http_request {

	private $comm;
	private $commandes = ["request"];
	private $args = [];
	private $methode;
	private $url;

	public function __Construct($commande) {

		$delete_usless = explode(" ", $commande);
		$this->args = $delete_usless;
		unset($this->args[0]);
		$this->args = $this->return_arg(array_values($this->args));
		$delete_usless = explode(".", $delete_usless[0]);
		$this->comm = $delete_usless[1];
	}

	public function return_arg($tab) {

		$i = 0;
		$argum = [];
		$in = 0;
		$c = "";
		while (isset($tab[$i])) {

			if ($in == 0) {

				if ($tab[$i][0] == "'" || $tab[$i][0] == '"') {

					if ($tab[$i][strlen($tab[$i]) - 1] == $c)
						$argum[] = str_replace($c, '', $tab[$i]);
					else {

						$str = $tab[$i];
						$c = $tab[$i][0];
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

	private function send_request() {

		if ($this->methode != 'GET') {

			$postdata = http_build_query(
				$this->arg
			);

			$opts = array('http' =>
			    array(
			        'method'  => $this->methode,
			        'header'  => 'Content-Type: application/x-www-form-urlencoded',
			        'content' => $postdata
			    )
			);

			$context  = stream_context_create($opts);
			$result = file_get_contents($this->url, false, $context);

		} else {

			$parametres = "";
			foreach ($this->args as $key => $value) {

				$parametres .= $key.'='.urlencode($value)."&";	
			}

			$parametres = substr($parametres, 0,  -1);
			$result = file_get_contents($this->url.'?'.$parametres);
		}

		return ($result);
	}

	private function request() {

		if (isset($this->args[0]) && isset($this->args[1])) {

			$this->methode = strtoupper($this->args[0]);
			$this->arg = $this->create_table();
			$this->url = $this->args[1];

			return ($this->send_request());
		}

		return ("Usage : http_request.new [methode (POST, GET ...) ] [url] ['pass:hello, value:ok ...']");
	}

	private function create_table() {

		$this->args[2] = str_replace(" ", "", $this->args[2]);
		$this->args[2] = str_replace("'", "", $this->args[2]);
		$this->args[2] = str_replace('"', '', $this->args[2]);

		$tab = explode(",", $this->args[2]);
		$final = [];

		$i = 0;
		while (isset($tab[$i])) {

			$ex = explode(":", $tab[$i]);
			if (isset($ex[1]))
				$final[$ex[0]] = $ex[1];
			$i++;
		}

		return ($final);
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>