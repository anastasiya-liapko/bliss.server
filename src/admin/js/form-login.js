'use strict';

$(function () {

  var form = $('#js-formLogin'),
    submitButton = $('#js-formLoginBtnSubmit');

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
    },
    messages: {
      email: {
        required: 'Это обязательное поле',
        email: 'Введите логин в формате ivanov@gmail.com'
      },
      password: {
        required: 'Это обязательное поле'
      },
    },
    errorClass: 'form-control__error-text',
    errorElement: 'span'
  });

  form.on('keyup blur change', 'input, select, textarea', function () {
    Form.changeButtonStatus(form, submitButton);
  });

});
