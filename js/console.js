if (!window.jQuery) {

	let jq = document.createElement('script');
	jq.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js';
	document.head.appendChild(jq);
}

init();

function init() {

	let input_console = document.getElementById('value_console');
	input_console.onkeydown = function(e) {

		if (e.which == 13) {

			e.preventDefault();
			$.post(link, {commande:input_console.value}, function(data) {
				
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