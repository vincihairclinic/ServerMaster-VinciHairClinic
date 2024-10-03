@if(!empty(\App\AppConf::$captcha_key) && !\App\W::$isAmp && \App\Application::pushLoad('captcha_js'))
    @if($errors->has('captcha')) <span class="error_captcha">Error verifying reCAPTCHA <br> please try again</span> @endif

    @push('js')
        <script src="https://www.google.com/recaptcha/api.js?render={{ \App\AppConf::$captcha_key }}"></script>
        <script>
            document.querySelectorAll('.captcha').forEach(function(e){
                e.addEventListener('submit', function (evt) {
                    evt.preventDefault();
                    var target = evt.target;
                    grecaptcha.ready(function () {
                        grecaptcha.execute('{{ \App\AppConf::$captcha_key }}', {action: 'login'}).then(function (token) {
                            var el = document.createElement("input");
                            el.type = "hidden";
                            el.name = "captcha";
                            el.value = token;
                            target.appendChild(el);
                            target.submit();
                        });
                    });
                });
            });
        </script>
    @endpush
@endif