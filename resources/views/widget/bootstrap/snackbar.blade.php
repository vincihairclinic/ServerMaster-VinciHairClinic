<div id="snackbarSuccessfully" class="mdl-js-snackbar mdl-snackbar" style="background-color: #8bc34a; color: #fff; z-index: 999999;">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>

<div id="snackbarFail" class="mdl-js-snackbar mdl-snackbar" style="background-color: #f44336; color: #fff;">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>

@push('js')
    <script>
        function showSnackbarDefault(success, actionBackUrl, message) {
            success = success === undefined ? 1 : success;

            var data = {};

            if(actionBackUrl && actionBackUrl != ''){
                data.timeout = 4000;
                data.actionText = 'Back to all';
                data.actionHandler = function () {
                    location.href = actionBackUrl;
                };
            }else {
                data.timeout = 1000;
            }

            if(message !== undefined){
                data.message = message;
            }
            if(success != 0){
                if(data.message === undefined){
                    data.message = 'Successfully';
                }
                document.querySelector('#snackbarSuccessfully').MaterialSnackbar.showSnackbar(data);
            }else {
                if(data.message === undefined) {
                    data.message = 'Failed';
                }
                document.querySelector('#snackbarFail').MaterialSnackbar.showSnackbar(data);
            }
        }
    </script>
@endpush