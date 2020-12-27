<?php

namespace App\Http\Resources\Books;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Genre;
use App\Http\Resources\Genres\GenreResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'picture' => $this->picture,            
            'genres' => GenreResource::collection($this->genres),
            'all_genres' => GenreResource::collection(Genre::all())            
        ];
    }
}