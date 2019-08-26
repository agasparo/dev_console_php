<?php

require 'class/tab.class.php';
require 'class/link.class.php';
require 'class/console.class.php';
require 'class/init.class.php';

extract($_POST);

$js_path = new link('console.php');
$js_p = $js_path->get_link(0);

?>
<script type="text/javascript">
var link = "<?= $js_p; ?>";
</script>
<?php

$aff = "";

if (isset($anc))
	$aff = $anc;

$init = new init();
require 'require/to_include.php';

if (isset($commande))
	$console = new console($commande, $aff);
else
	$console = new console("init", $aff);
?>
