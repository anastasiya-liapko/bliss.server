'use strict';

$(function () {

  var form = $('#js-formEditAdmin'),
    phoneInput = $('#js-formEditAdminInputPhone'),
    submitButton = $('#js-formEditAdminBtnSubmit');

  phoneInput.inputmask('+7(999)999-99-99');

  form.validate({
    highlight: function (element) {
      $(element).addClass('form-control_error');
    },
    unhighlight: function (element) {
      $(element).removeClass('form-control_error');
    },
    submitHandler: function (form) {
      var action = $(form).attr('action'),
        data = $(form).serialize();

      if (!submitButton.is(':disabled')) {
        Form.send(action, data, submitButton);
      }
    },
    rules: {
      name: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      email: {
        required: true,
        email: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      password: {
        required: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      phone: {
        required: false,
        phone_ru: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      active: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      }
    },
    messages: {
      name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      email: {
        required: 'Это обязательное поле',
        email: 'Введите e-mail в формате ivanov@gmail.com'
      },
      password: {
        required: 'Это обязательное поле'
      },
      phone: {
        required: 'Это обязательное поле',
        phone_ru: 'Введите номер в формате +7(xxx)xxx-xx-xx'
      },
      active: {
        plain_text: 'Поле не должно содержать HTML-теги'
      }
    },
    errorClass: 'form-control__error-text',
    errorElement: 'span'
  });

  form.on('keyup blur change', 'input, select, textarea', function () {
    Form.changeButtonStatus(form, submitButton);
  });

});
