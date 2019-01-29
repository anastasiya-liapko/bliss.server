'use strict';

$(function () {

  var timer = $('#js-codeSmsTimer'),
    timerEnd = timer.data('timer-end'),
    repeatLink = $('#js-codeSmsRepeatSend'),
    repeatLinkShow = $('#js-codeSmsRepeatShow'),  // find #js-codeSmsRepeatShow
    timerShow = $('#js-codeSmsTimerShow'); // find #js-codeSmsTimerShow

  timer.countdown(timerEnd * 1000)
    .on('update.countdown', function (event) {
      $(this).html(event.strftime('%M:%S'));
    })
    .on('finish.countdown', function (event) {
      $(this).html(event.strftime('00:00'));
      repeatLink.removeClass('link_disabled');
      timerShow.fadeOut(); // hide #js-codeSmsTimerShow
      repeatLinkShow.fadeIn(); // show #js-codeSmsRepeatShow
    });

  repeatLink.on('click', function () {

    if ($(this).hasClass('link_disabled')) return false;

    $.ajax({
      cache: false,
      dataType: 'json',
      method: 'POST',
      url: $(this).data('link-action'),
      success: function (data) {
        repeatLinkShow.fadeOut(); // hide #js-codeSmsRepeatShow
        timerShow.fadeIn(); // show #js-codeSmsTimerShow

        repeatLink.addClass('link_disabled');
        Toast.create(data.error, data.message[0]);

        var timer_end = Date.now() / 1000 + 180;

        Cookies.set('code_sms_timer', timer_end);

        timer.countdown(timer_end * 1000)
          .on('update.countdown', function (event) {
            $(this).html(event.strftime('%M:%S'));
          })
          .on('finish.countdown', function (event) {
            $(this).html(event.strftime('00:00'));
            repeatLink.removeClass('link_disabled');
          });
      },
      error: function () {
        Toast.create(true, 'Произошла ошибка, попробуйте ещё раз.');
      }
    });

  });

});
