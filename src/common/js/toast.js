'use strict';

/**
 * Toast function with static methods
 */
function Toast() {
}

/**
 * Creates a toast
 *
 * @param {boolean} error
 * @param {string} message
 */
Toast.create = function (error, message) {

  if (message === '') return false;

  var toast = $('<div class="toast toast_active">' + message + '</div>');

  $('body').append(toast);

  if (error) {
    toast.addClass('toast_status_error');
  } else {
    toast.addClass('toast_status_success');
  }

  setTimeout(function () {
    toast.remove();
  }, 4000);
};
