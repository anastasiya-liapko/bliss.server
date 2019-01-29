'use strict';

$(function () {

  var form = $('#js-formProfileShop'),
    typeInput = form.find('[name="type"]'),
    tinInput = $('#js-formProfileShopInputTin'),
    submitButton = $('#js-formProfileShopBtnSubmit');

  tinInput.inputmask('999999999999');

  form.validate({
    highlight: function (element) {
      $(element).addClass('input_error');
    },
    unhighlight: function (element) {
      $(element).removeClass('input_error');
    },
    submitHandler: function (form) {
      var action = $(form).attr('action'),
        data = $(form).serialize();

      if (!submitButton.is(':disabled')) {
        Form.send(action, data, submitButton);
      }
    },
    rules: {
      type: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      last_name: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      first_name: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      middle_name: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      tin: {
        required: true,
        number: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      company_name: {
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
      }
    },
    messages: {
      type: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      last_name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      first_name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      middle_name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      tin: {
        required: 'Это обязательное поле',
        number: 'Номер ИНН должен содержать только цифры'
      },
      company_name: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      email: {
        required: 'Это обязательное поле',
        email: 'Введите e-mail в формате ivanov@gmail.com'
      }
    },
    errorClass: 'error-text',
    errorElement: 'span'
  });

  typeInput.on('change', function () {
    if ($(this).val() === 'entrepreneur') {
      tinInput.inputmask('999999999999');
    } else {
      tinInput.inputmask('9999999999');
    }
  });

  form.on('keyup blur change', 'input, select, textarea', function () {
    Form.changeButtonStatus(form, submitButton);
  });

});
