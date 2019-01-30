'use strict';

$(function () {
  var form = $('#js-formProfileClient'),
    birthDateInput = $('#js-formProfileClientInputBirthDate'),
    tinInput = $('#js-formProfileClientInputTin'),
    passportNumberInput = $('#js-formProfileClientInputPassportNumber'),
    passportDivisionCodeInput = $('#js-formProfileClientInputPassportDivisionCode'),
    passportIssuedDateInput = $('#js-formProfileClientInputPassportIssuedDate'),
    regZipCodeInput = $('#js-formProfileClientInputRegZipCode'),
    regCityInput = $('#js-formProfileClientInputRegCity'),
    regBuildingInput = $('#js-formProfileClientInputRegBuilding'),
    regStreetInput = $('#js-formProfileClientInputRegStreet'),
    regApartmentInput = $('#js-formProfileClientInputRegApartment'),
    factZipCodeInput = $('#js-formProfileClientInputFactZipCode'),
    factCityInput = $('#js-formProfileClientInputFactCity'),
    factBuildingInput = $('#js-formProfileClientInputFactBuilding'),
    factStreetInput = $('#js-formProfileClientInputFactStreet'),
    factApartmentInput = $('#js-formProfileClientInputFactApartment'),
    submitButton = $('#js-formProfileClientBtnSubmit');

  birthDateInput.inputmask('99.99.9999');
  tinInput.inputmask('999999999999');
  passportNumberInput.inputmask('99 99 999999');
  passportDivisionCodeInput.inputmask('999-999');
  passportIssuedDateInput.inputmask('99.99.9999');
  regZipCodeInput.inputmask('999999');
  factZipCodeInput.inputmask('999999');

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
      birth_date: {
        required: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      birth_place: {
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
      passport_number: {
        required: true,
        passport: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      passport_division_code: {
        required: true,
        division_code: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      passport_issued_by: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      passport_issued_date: {
        required: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      workplace: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      salary: {
        required: true,
        number: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      reg_zip_code: {
        required: true,
        number: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      reg_city: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      reg_street: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      reg_building: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      reg_apartment: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      fact_zip_code: {
        required: true,
        number: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      fact_city: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      fact_street: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      fact_building: {
        required: true,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      fact_apartment: {
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
      birth_date: {
        required: 'Это обязательное поле',
      },
      birth_place: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      tin: {
        required: 'Это обязательное поле',
        number: 'Номер ИНН должен содержать только цифры'
      },
      passport_number: {
        required: 'Это обязательное поле',
        passport: 'Введите данные в формате 01 02 343543'
      },
      passport_division_code: {
        required: 'Это обязательное поле',
        division_code: 'Введите данные в формате 770-001'
      },
      passport_issued_by: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      passport_issued_date: {
        required: 'Это обязательное поле'
      },
      workplace: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      salary: {
        required: 'Это обязательное поле',
        number: 'Поле должено содержать только цифры'
      },
      reg_zip_code: {
        required: 'Это обязательное поле',
        number: 'Индекс должен содержать только цифры'
      },
      reg_city: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      reg_street: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      reg_building: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      reg_apartment: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      fact_zip_code: {
        required: 'Это обязательное поле',
        number: 'Индекс должен содержать только цифры'
      },
      fact_city: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      fact_street: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      fact_building: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      },
      fact_apartment: {
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

  $('#js-formProfileClientInputIsAddressMatched').on('click', function () {
    if ($(this).is(':checked')) {
      factZipCodeInput.val(regZipCodeInput.val());
      factCityInput.val(regCityInput.val());
      factBuildingInput.val(regBuildingInput.val());
      factStreetInput.val(regStreetInput.val());
      factApartmentInput.val(regApartmentInput.val());
    } else {
      factZipCodeInput.val('');
      factCityInput.val('');
      factBuildingInput.val('');
      factStreetInput.val('');
      factApartmentInput.val('');
    }
  });

  if (form.length > 0) {
    setTimeout(Form.changeButtonStatus, 300, form, submitButton);
  }

  form.on('keyup blur change', 'input, select, textarea', function () {
    Form.changeButtonStatus(form, submitButton);
  });

  birthDateInput.datepicker({
    dateFormat: 'dd.mm.yyyy',
    startDate: new Date(1990, 0, 1, 0, 0, 0, 0),
    maxDate: new Date()
  });

  passportIssuedDateInput.datepicker({
    dateFormat: 'dd.mm.yyyy',
    startDate: new Date(2010, 0, 1, 0, 0, 0, 0),
    minDate: new Date(1997, 9, 1, 0, 0, 0, 0),
    maxDate: new Date()
  });

});
