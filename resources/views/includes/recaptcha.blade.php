@if (config('app.validate_recaptcha', false))
    <meta name="recaptcha-key" content="{{ config('secrets.recaptcha.site_key') }}">
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('secrets.recaptcha.site_key') }}"></script>
@endif
