<?php
class http_request {

	/**
	 * @comm String
	 */
	private $comm;

	/**
	 * @commandes Array
	 */
	private $commandes = ["request"];

	/**
	 * @args Array
	 */
	private $args = [];

	/**
	 * @methode String
	 */
	private $methode;

	/**
	 * @url String
	 */
	private $url;

	public function __Construct(String $commande, Array $arguments) {

		$this->args = $arguments;
		$this->comm = $commande;
	}

	private function send_request() : String {

		if ($this->methode != 'GET') {

			$postdata = http_build_query(
				$this->args
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

	private function request() : String {

		if (isset($this->args[0]) && isset($this->args[1])) {

			$this->methode = strtoupper($this->args[0]);
			$this->url = $this->args[1];
			$this->args = $this->create_table();

			return ($this->send_request());
		}

		return ("Usage : http_request.new [methode (POST, GET ...) ] [url] ['pass:hello, value:ok ...']");
	}

	private function create_table() : Array {

		if (!isset($this->args[2]))
			return ([]);

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

	public function execute() : String {

		return ($this->{$this->comm}());
	}
}

?>