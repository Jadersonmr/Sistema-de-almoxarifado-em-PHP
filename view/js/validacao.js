//campos obrigatorios
$(document).ready( function() {
	$(".formvalidate").validate({
		// Define as regras
		rules:{
			campoNome:{
				// nome ser� obrigat�rio (required) e ter� tamanho m�nimo (minLength)
				required: true, minlength: 2
			},
			campoEmail:{
				// email precisar� ser um e-mail v�lido (email)
				required: false, email: true
			},
			campoEndereco:{
				// endere�o ter� tamanho m�nimo (minLength)
				required: true, minlength: 2
			},
			campoQuant:{
				// quantidade precisar� ser um numero v�lido
				required: false, number: true
			},
			campoUsuario:{
				// campo usuario ser� obrigat�rio
				required: true
			},
			campoSenha:{
				// campo senha ser� obrigat�rio (required) e ter� tamanho m�nimo (minLength)
				required: false, minlength: 3
			}
		},


		// Define as mensagens de erro para cada regra
		messages:{
			campoNome:{
				required: "Deve conter, no m�nimo, 2 caracteres."
			},
			campoEmail:{
				email: "Digite um e-mail v�lido."
			},
			campoEndereco:{
				required: "Deve conter, no m�nimo, 2 caracteres."
			},
			campoQuant:{
				number: "Digite um numero v�lido."
			},
			campoUsuario:{
				required: "Por favor selecione um cliente."
			},
			campoSenha:{
				required: "Deve conter, no m�nimo, 3 caracteres."
			}
		}
	});
});