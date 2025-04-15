<!-- resources/views/tasks/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
</head>
<body>
    <h1>Список задач</h1>

    <form method="GET" action="{{ route('tasks.index') }}">
        <input type="text" name="search" placeholder="Поиск...">
        <select name="status">
            <option value="">Все статусы</option>
            <option value="В процессе">В процессе</option>
            <option value="Завершено">Завершено</option>
        </select>
        <select name="sort">
            <option value="date">Сортировать по дате</option>
            <option value="status">Сортировать по статусу</option>
        </select>
        <button type="submit">Фильтровать</button>
    </form>

    <ul>
        @foreach ($tasks as $task)
            <li>
                <strong>{{ $task->title }}</strong> - {{ $task->description }} ({{ $task->status }})
            </li>
        @endforeach
    </ul>
</body>
</html>