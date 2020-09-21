@extends('mainLayout')

@section('title', 'Отправка сообщения')

@section('scripts')
    <script src="{{ asset('js/sendForm.js') }}"></script>
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
