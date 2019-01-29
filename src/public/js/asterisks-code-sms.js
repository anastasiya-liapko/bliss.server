'use strict';

$(function () {
  var smsCodeInput = $('#js-formCodeSmsInputCode'),
    smsCodeAsterisks = $('#js-formCodeSmsInputAsterisks');

  smsCodeInput.on('keyup change', function () {
    smsCodeAsterisks.text(smsCodeInput.val());
    var smsCodeAsterisksValue = smsCodeAsterisks.text();
    smsCodeAsterisks.text(smsCodeAsterisksValue.replace(/[0-9]/g, '*'));
  });
  
});