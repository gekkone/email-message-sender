@extends('mainLayout')

@section('title', 'Отправка сообщения')

@section('head')
    <script src="{{ asset('js/sendForm.js') }}"></script>
    @include('includes.recaptcha')
@endsection

@section('content')

    <div class="notify"></div>

    <form action="/feedback/send" method="POST">
        <textarea name="content"></textarea>
        {{ csrf_field() }}
        <br>
        <input type="submit" value="Отправить">
    </form>
@endsection
