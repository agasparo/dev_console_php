if (!window.jQuery) {

	let jq = document.createElement('script');
	jq.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js';
	document.head.appendChild(jq);
}

var nb_f = 0;
var w_i = 0;

init();

function init() {

	let input_console = document.getElementById('value_console');
	input_console.onkeydown = function(e) {

		let last = 0;
		let comms = "commande"+last;

		for (let key in localStorage) {
   			if (key == comms) {
   				last++;
   				comms = "commande"+last;
   			}
		}

		if (e.which == 39) {

			if (nb_f > last)
				nb_f = 0;

			input_console.value	= localStorage.getItem("commande"+nb_f);
			nb_f++;	
		}

		if (e.which == 13) {

			localStorage.setItem("commande"+w_i, input_console.value);
			w_i++;

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