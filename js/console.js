window.onload = function() {

	init();

	function init() {
		let input_console = document.getElementById('value_console');
		input_console.onkeydown = function(e) {
			if (e.which == 13) {
				e.preventDefault();
				$.post('console.php', {commande:input_console.value}, function(data) {
					input_console.remove();
					let text = '>><<clear';
					match = data.match(text);
					let e = data.split(">><<");
					if (match)
						document.getElementById('conbsole_b').innerHTML = e[1].replace('clear', '');
					else
						$("#conbsole_b").append(data);
					document.getElementById('conbsole_b').scrollTop = document.getElementById('conbsole_b').scrollHeight
					$("#value_console").focus();
					init();
				});
			}
		}
		document.getElementById('conbsole_b').onclick = function() {
			$("#value_console").focus();
		}
	}
}