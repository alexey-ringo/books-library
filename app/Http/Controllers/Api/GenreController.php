<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GenreStoreRequest;
use App\Http\Requests\GenreUpdateRequest;
use App\Http\Resources\Genres\GenreCollection;
use App\Http\Resources\Genres\GenreResource;
use App\Models\Genre;


class GenreController extends Controller
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
    public function index(Request $request)
    {
        if($request->has('alldata')) {
            return new GenreCollection(Genre::all());
        }        
        
        return new GenreCollection(Genre::orderByDesc('created_at')->paginate(5));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GenreStoreRequest $request)
    {
        $genre = Genre::create($request->only(['title']));
        
        if($genre) {
            return response()->json(['message' => 'Новый жанр успешно создан']);
        }
        else {
            return response()->json(['message' => 'Внутранняя ошибка при создании нового жанра!'], 500);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $genre = Genre::find($id);

        if($genre) {
            return new GenreResource($genre);
        }
        else {
            return response()->json(['message' => 'Жанр с идентификатором id ' . $id . ' не найден!'], 404);
        }
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GenreUpdateRequest $request, $id)
    {        
        $genre = Genre::find($id);

        if(empty($genre)) {
            return response()->json(['message' => 'Жанр с идентификатором id ' . $id . ' не найден!'], 404);
        }
        
        if(empty($genre->update($request->only(['title'])))) {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений данных по жанру!'], 500);            
        }       
        
        return response()->json(['message' => 'Изменения для жанра "' . $genre->title . '" успешно применены']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);

        if(empty($genre)) {
            return response()->json(['message' => 'Жанр с идентификатором id ' . $id . ' не найден!'], 404);
        }

        if(!$this->user) {
            return response()->json(['message' => 'Для незарегистрированных пользователей Запрещено удаление жанра!'], 401);;
        }  
        
        $genre->books()->detach();

        $genre->delete();        
        
        return new GenreCollection(Genre::orderByDesc('created_at')->paginate(5));
    }
}