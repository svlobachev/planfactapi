jQuery(document).ready(function ($) {
	function regform() {


		var add_form = $('#add_regform');

		// Сброс значений полей
		$('#add_regform input, #add_regform textarea, #add_regform checkbox').on('blur', function () {
			$('#add_regform input, #add_regform textarea, #add_regform checkbox').removeClass('error');
			$('.error-name,.error-email,.error-phone,.error-checkbox, .message-success').remove();
			$('#submit-regform').val('Отправить');
		});

		// Отправка значений полей
		var options = {
			url: regform_object.url,
			data: {
				action: 'regform_action',
				nonce: regform_object.nonce
			},
			type: 'POST',
			dataType: 'json',
			beforeSubmit: function (xhr) {
				// При отправке формы меняем надпись на кнопке
				$('#submit-regform').val('Отправляем...');
			},
			success: function (request, xhr, status, error) {

				if (request.success === true) {
					// Если все поля заполнены, отправляем данные и меняем надпись на кнопке
					add_form.after('<div class="message-success">' + request.data + '</div>').slideDown();
					$('#submit-regform').val('Отправить');
					$('#add_regform')[0].reset();
				} else {
					// Если поля не заполнены, выводим сообщения и меняем надпись на кнопке
					$.each(request.data, function (key, val) {
						$('.art_' + key).addClass('error');
						$('.art_' + key).before('<span class="error-' + key + '">' + val + '</span>');
					});
					// $('#submit-regform').val('Что-то пошло не так...');
					$('#submit-regform').val('Отправить')

				}
				// При успешной отправке сбрасываем значения полей
				// $('#add_regform')[0].reset();
			},
			error: function (request, status, error) {
				$('#submit-regform').val('Что-то пошло не так...');
			}
		};
		// Отправка формы
		add_form.ajaxForm(options);
	}
	regform();
});