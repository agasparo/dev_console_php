init();

function init() {
	let input_console = document.getElementById('value_console');
	input_console.onkeydown = function(e) {
		if (e.which == 13) {
			e.preventDefault();
			$.post('http://192.168.99.100.xip.io:41062/www/dev_console/console.php', {commande:input_console.value}, function(data) { // chnager url ici
				input_console.remove();
				let text = '>><<clear';
				match = data.match(text);
				let e = data.split(">><<");
				let com = input_console.value.split(" ");
				if (match)
					document.getElementById('conbsole_b').innerHTML = e[1].replace('clear', '');
				else if (com[0] == "set")
					window.location = "http://192.168.99.100.xip.io:41062/www/hypertube/"; //changer ici
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