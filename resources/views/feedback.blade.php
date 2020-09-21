@extends('mainLayout')

@section('title', 'Отправка сообщения')

@section('head')
    <script src="{{ asset('js/sendForm.js') }}"></script>
    @include('includes.recaptcha')

    <style>
        .form__input, .form__button {
            width: 100%;
        }
        .form__notify {
            width: 100%;
            margin: 20px 0;
            text-align: center;
        }
        .form__input_invalid {
            border: 1px solid #fe4444;
        }
        .form__error-message {
            display: inline-block;
            font-size: 12px;
            color: #fe4444;
            margin: 3px 0;
        }
        .form__button {
            margin: 20px 0;
        }
    </style>
@endsection

@section('content')
    <form class="form form-feedback" action="/feedback/send" method="POST">
        <div class="form__notify"></div>
        <textarea class="form__input" name="content"></textarea>
        {{ csrf_field() }}
        <br>
        <input class="form__button" type="submit" value="Отправить">
    </form>
@endsection
