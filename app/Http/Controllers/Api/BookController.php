<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\Books\BookCollection;
use App\Http\Resources\Books\BookResource;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * current User
     *
     * @var User
     */
    protected $user;
    
    public function __construct(Request $request)
    {    
        $this->user = $request->user('api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        return new BookCollection(Book::with('genres')->orderByDesc('created_at')->paginate(5));
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookStoreRequest $request)
    {
        $book = Book::create([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'picture' => $request->input('picture'),
            'user_id' => $this->user->id,
        ]);

        if(empty($book)) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при создании новой книги!'], 500);            
        }

        //Проверка на наличие полученного от формы значения поля с name="genres"
        if($request->input('genres')) :
            $book->genres()->attach($request->input('genres'));
        endif;
        
        return response()->json(['message' => 'Новая книга ' . $book->title . ' успешно создана']);        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        if($book) {
            return new BookResource($book);
        }
        else {
            return response()->json(['message' => 'Книга с идентификатором id ' . $id . ' не найдена!'], 404);
        }
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookUpdateRequest $request, $id)
    {        
        $book = Book::find($id);
        if(empty($book)) {
            return response()->json(['message' => 'Книга с идентификатором id ' . $id . ' не найдена!'], 404);
        }
        
        if(empty($book->update($request->only(['title', 'author', 'picture'])))) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений данных по книге!'], 500);            
        }
        
        $book->genres()->detach();
       
        if($request->input('genres')) :
            $book->genres()->attach($request->input('genres'));
        endif;
        
        return response()->json(['message' => 'Изменения для книги "' . $book->title . '" успешно применены']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if(empty($book)) {
            return response()->json(['message' => 'Книга с идентификатором id ' . $id . ' не найдена!'], 404);
        }

        if($book->user_id !== $this->user->id) {
            return response()->json(['message' => 'Запрещено удаление не своей книни!'], 401);;
        }       
        
        $book->genres()->detach();

        $book->delete();

        return new BookCollection(Book::with('genres')->orderByDesc('created_at')->paginate(5));
    }
}