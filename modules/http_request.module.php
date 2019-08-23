<?php
class http_request {

	private $comm;
	private $commandes = ["new"];
	private $args = [];
	private $methode;
	private $url;

	public function __Construct($comm) {

		$delete_usless = explode(" ", $commande);
		$this->args = $delete_usless;
		unset($this->args[0]);
		$this->args = array_values($this->args);
		$delete_usless = explode(".", $delete_usless[0]);
		$this->comm = $delete_usless[1];
	}

	private function send_request() {
		
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

		return (file_get_contents($this->url, false, $context));
	}

	private function new() {

		if (isset($this->args[0]) && isset($this->args[1]) && isset($this->args[2])) {

			$this->methode = $this->args[0];
			$this->args = $this->args[1];
			$this->url = $this->args[2];
		}
	}

	public function execute() {

		return ($this->{$this->comm}());
	}
}

?>