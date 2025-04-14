<?php
public function up()
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->timestamps();
    });
}
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content'];
}
public function show($id)
{
    $post = Post::findOrFail($id); // Находим запись по ID
    return view('posts.show', compact('post')); // Возвращаем вид для отображения записи
}
public function edit($id)
{
    $post = Post::findOrFail($id);
    return view('posts.edit', compact('post'));
}

public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);
    $post->update($request->all());
    return redirect()->route('posts.index')->with('success', 'Запись обновлена успешно!');
}
public function destroy($id)
{
    $post = Post::findOrFail($id);
    $post->delete();
    return redirect()->route('posts.index')->with('success', 'Запись удалена успешно!');
}