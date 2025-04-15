<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller

{
    /**
     * Отображает представление задачи.
     *
     * @param Task|null $task Экземпляр задачи или null, если задача не указана.
     * @return View Представление задачи.
     */
    public function index(?Task $task = null): View
    {
        return view('task', [
            'task' => $task,
        ]);
    }

    /**
     * Создает новую задачу или обновляет существующую.
     *
     * @param \Illuminate\Http\Request $request HTTP-запрос, содержащий данные для создания или обновления задачи.
     *
     * @return \Illuminate\Http\RedirectResponse Редирект на страницу со списком задач.
     *
     * @throws \Illuminate\Validation\ValidationException Если валидация данных запроса не проходит.
     *
     * Валидация данных запроса:
     * - title: обязательное поле, строка, максимум 255 символов.
     * - description: обязательное поле, строка.
     * - status: обязательное поле, одно из значений: pending, in_progress, completed.
     *
     * Если в запросе передан идентификатор задачи (task), то существующая задача обновляется.
     * Если идентификатор отсутствует, создается новая задача.
     * Пользователь, создающий или обновляющий задачу, связывается с задачей через user_id.
     */

    public function createOrUpdate(Request $request): RedirectResponse
    {
        $request -> validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task = Task::updateOrCreate(
            ['id' => $request -> route('task')],
            [
                'title' => $request -> title,
                'description' => $request -> description,
                'status' => $request -> status,
                'user_id' => Auth::id(),
            ]
        );

        return redirect()
            -> route('tasks.index', ['task' => $task]);
    }

    /**
     * Удаляет указанную задачу и перенаправляет на главную страницу.
     *
     * @param Task $task Экземпляр задачи, которую нужно удалить.
     * @return RedirectResponse Ответ с перенаправлением на маршрут 'index'.
     */
    public function delete(Task $task): RedirectResponse
    {
        $task -> delete();

        return redirect()
            -> route('index');
    }
}
// app/Http/Controllers/TaskController.php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        // Фильтрация по статусу
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Поиск по названию или описанию
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        // Сортировка
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'date') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'status') {
                $query->orderBy('status');
            }
        }

        $tasks = $query->get();

        return view('tasks.index', compact('tasks'));
    }
}

