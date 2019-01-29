'use strict';

/**
 * Form function with static methods
 */
function Form() {
}

/**
 * Sends request by ajax
 *
 * @param {string} action - The action.
 * @param {object} [data={}] - The data.
 * @param {object} [submitButton={}] - The submit button.
 */
Form.send = function (action, data, submitButton) {

  data = data || {};

  if (submitButton) {
    submitButton.prop('disabled', true);
  }

  $.ajax({
    cache: false,
    data: data,
    dataType: 'json',
    method: 'POST',
    url: action,
    success: function (data) {

      if (submitButton) {
        submitButton.prop('disabled', false);
      }

      // Opens modal
      if (data.openModal) {
        $(data.openModal.id).modal({
          backdrop: data.openModal.backdrop || 'static',
          keyboard: data.openModal.keyboard || false,
        });
      }

      // Close modal
      if (data.closeModal) {
        $(data.closeModal.id).modal('hide');
      }

      // Remove element
      if (data.removeElement) {
        $(data.removeElement.id).fadeOut(300, function () {
          $(this).remove();
        });
      }

      // Timer
      if (data.timer) {

        // Progressbar
        if (data.timer.progressBar) {
          var progressBar = $(data.timer.progressBar.id),
            progressBarInner = progressBar.find('div');
        }

        $(data.timer.id).countdown(data.timer.end)
          .on('update.countdown', function (event) {
            $(this).html(event.strftime('%M:%S'));

            if (data.timer.progressBar) {
              progressBarInner.width((data.timer.progressBar.endAfter - event.offset.totalSeconds) * progressBar.width() / data.timer.progressBar.endAfter);
            }
          })
          .on('finish.countdown', function (event) {
            $(this).html(event.strftime('00:00'));

            if (data.timer.progressBar) {
              progressBarInner.width('100%');
            }

            if (data.timer.onFinish.closeModal) {
              $(data.timer.onFinish.closeModal.id).modal('hide');
            }

            if (data.timer.onFinish.openModal) {
              $(data.timer.onFinish.openModal.id).modal({
                backdrop: data.timer.onFinish.openModal.backdrop || 'static',
                keyboard: data.timer.onFinish.openModal.keyboard || false,
              });
            }
          });
      }

      // Shows message
      if (data.message) {
        Toast.create(data.error, data.message[0]);
      }

      // Redirects
      if (data.redirect) {
        window.location.href = data.redirect;
      }
    },
    error: function (e) {
      console.log(e); //TODO remove it

      if (submitButton) {
        submitButton.prop('disabled', false);
      }

      Toast.create(true, 'Произошла ошибка, попробуйте ещё раз.');
    }
  });
};

/**
 * Change status of submit button
 *
 * @param {object} form
 * @param {object} button
 */
Form.changeButtonStatus = function changeButtonStatus(form, button) {
  if (form.validate().checkForm()) {
    button.prop('disabled', false);
  } else {
    button.prop('disabled', true);
  }
};
