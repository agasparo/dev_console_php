init();

function init() {

	let input_console = document.getElementById('value_console');
	input_console.onkeydown = function(e) {

		if (e.which == 13) {

			e.preventDefault();
			$.post(link, {commande:input_console.value}, function(data) { // chnager url ici
				
				let to_search = "clearing the console";
				clear = data.match(to_search);

				let search = "Css update success";
				reload = data.match(search);

				if (clear || reload)
					location.reload();

				input_console.remove();
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