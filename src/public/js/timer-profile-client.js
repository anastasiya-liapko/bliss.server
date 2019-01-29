'use strict';

$(function () {
  var timer = $('#js-profileClientTimer'),
    timerEnable = timer.data('timer-enable');

  if (!timerEnable) return false;

  var timerEnd = timer.data('timer-end'),
    progressBar = $('#js-profileClientProgressBar'),
    progressBarInner = progressBar.find('div'),
    progressBarEndAfter = progressBar.data('end-after'),
    modalSendRequest = $('#js-modalSendRequest'),
    modalNoResponse = $('#js-modalNoResponse');

  timer.countdown(timerEnd)
    .on('update.countdown', function (event) {
      $(this).html(event.strftime('%M:%S'));

      progressBarInner.width((progressBarEndAfter - event.offset.totalSeconds) * progressBar.width() / progressBarEndAfter);
    })
    .on('finish.countdown', function (event) {
      $(this).html(event.strftime('00:00'));

      progressBarInner.width('100%');

      modalSendRequest.modal('hide');

      modalNoResponse.modal({
        backdrop: 'static',
        keyboard: false
      });
    });
});
