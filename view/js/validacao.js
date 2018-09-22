//campos obrigatorios
$(document).ready( function() {
	$(".formvalidate").validate({
		// Define as regras
		rules:{
			campoNome:{
				// nome será obrigatório (required) e terá tamanho mínimo (minLength)
				required: true, minlength: 2
			},
			campoEmail:{
				// email precisará ser um e-mail válido (email)
				required: false, email: true
			},
			campoEndereco:{
				// endereço terá tamanho mínimo (minLength)
				required: true, minlength: 2
			},
			campoQuant:{
				// quantidade precisará ser um numero válido
				required: false, number: true
			},
			campoUsuario:{
				// campo usuario será obrigatório
				required: true
			},
			campoSenha:{
				// campo senha será obrigatório (required) e terá tamanho mínimo (minLength)
				required: false, minlength: 3
			}
		},


		// Define as mensagens de erro para cada regra
		messages:{
			campoNome:{
				required: "Deve conter, no mínimo, 2 caracteres."
			},
			campoEmail:{
				email: "Digite um e-mail válido."
			},
			campoEndereco:{
				required: "Deve conter, no mínimo, 2 caracteres."
			},
			campoQuant:{
				number: "Digite um numero válido."
			},
			campoUsuario:{
				required: "Por favor selecione um cliente."
			},
			campoSenha:{
				required: "Deve conter, no mínimo, 3 caracteres."
			}
		}
	});
});