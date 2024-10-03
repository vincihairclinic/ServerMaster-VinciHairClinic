function mdDateTimePickerInit(btnId, inputId, callbackOk, callbackCancel) {

    var dialog = new mdDateTimePicker.default({
        type: 'date',
        //autoClose: true,
    });
    var toggleButton = document.getElementById(btnId);
    toggleButton.addEventListener('click', function() {
        dialog.toggle();
    });

    dialog.trigger = document.getElementById(inputId);

    document.getElementById(inputId).addEventListener('onOk', function() {
        if (callbackOk && typeof (callbackOk) === 'function') {
            var _this = this;
            callbackOk(_this, dialog);
        }
    });

    document.getElementById(inputId).addEventListener('onCancel', function() {
        if (callbackCancel && typeof (callbackCancel) === 'function') {
            var _this = this;
            callbackCancel(_this, dialog);
        }
    });

    return dialog;
}
