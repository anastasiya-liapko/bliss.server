'use strict';

/**
 * Adds new methods for validator
 */
$(function () {
  /**
   * Validates passport number
   */
  $.validator.addMethod('passport', function (value, element) {
    return this.optional(element) || /^\d{2}\s\d{2}\s\d{6}$/.test(value);
  }, 'Введите данные в формате 01 02 343543');

  /**
   * Validates passport division code
   */
  $.validator.addMethod('division_code', function (value, element) {
    return this.optional(element) || /^\d{3}-\d{3}$/.test(value);
  }, 'Введите данные в формате 770-001');

  /**
   * Validates ru phone number
   */
  $.validator.addMethod('phone_ru', function (value, element) {
    return this.optional(element) || /^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/.test(value);
  }, 'Введите номер в формате +7(xxx)xxx-xx-xx');

  /**
   * Validates plain text
   */
  $.validator.addMethod('plain_text', function (value, element) {
    return this.optional(element) || !/(<([^>]+)>)/ig.test(value);
  }, 'Поле не должно содержать HTML-теги');

});
