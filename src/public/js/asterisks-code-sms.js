'use strict';

$(function () {
  var smsCodeInput = $('#js-formSuccessInputCode'),
    smsCodeAsterisks = $('#js-formSuccessInputAsterisks');

  smsCodeInput.on('keyup change', function () {
    smsCodeAsterisks.text(smsCodeInput.val());
    var smsCodeAsterisksValue = smsCodeAsterisks.text();
    smsCodeAsterisks.text(smsCodeAsterisksValue.replace(/[0-9]/g, '*'));
  });
  
});