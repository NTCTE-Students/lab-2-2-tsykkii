<!DOCTYPE html>
<html>
<head>
    <title>Редактировать запись</title>
</head>
<body>
    <h1>Редактировать запись</h1>

    <form action="{{ route('posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="title">Название</label>
        <input type="text" name="title" value="{{ $post->title }}">
        <label for="content">Содержимое</label>
        <textarea name="content">{{ $post->content }}</textarea>
        <button type="submit">Обновить</button>
    </form>

    <a href="{{ route('posts.index') }}">Назад к списку</a>
</body>
</html>