@extends('layout')

@section('title')
    Редактирование комментария
@stop

@section('content')

    <h1>Редактирование комментария</h1>

    <i class="fa fa-pencil"></i> <b>{{ $comment->user->login }}</b> <small>({{ dateFixed($comment->created_at) }})</small><br><br>

    <div class="form">
        <form method="post">
            <input type="hidden" name="token" value="{{ $_SESSION['token'] }}">
            <textarea id="markItUp" cols="25" rows="5" name="msg" id="msg">{{ $comment['text'] }}</textarea><br>
            <button class="btn btn-success">Редактировать</button>
        </form>
    </div><br>

    <i class="fa fa-arrow-circle-left"></i> <a href="/news/{{ $comment['relate_id'] }}/comments?page={{ $page }}">Вернуться</a><br>
@stop
