			jQuery.fn.toggleText = function(a,b) {
				return   this.html(this.html().replace(new RegExp("("+a+"|"+b+")"),function(x){return(x==a)?b:a;}));
			};

			$(document).ready(function(){
				$('.tgl').before('<span class="esconde" >Cadastrar</span>');
				$('.tgl').css('display', 'none')
				$('span', '#container').click(function() {
					$(this).next().slideToggle('slow')
					.siblings('.tgl:visible').slideToggle('fast');

					$(this).toggleText('Cadastrar','Esconder')
					.siblings('span').next('.tgl:visible').prev()
					.toggleText('Cadastrar','Esconder')
				});
			});