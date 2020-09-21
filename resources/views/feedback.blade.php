@extends('mainLayout')

@section('title', 'Отправка сообщения')

@section('head')
    <script src="{{ asset('js/sendForm.js') }}"></script>
    @include('includes.recaptcha')
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
