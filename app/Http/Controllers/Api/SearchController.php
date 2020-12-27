<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\Books\BookCollection;


class SearchController extends Controller
{  
    
    /**
     * search
     *
     * @param  mixed $request
     *
     * @return BookCollection
     */
    public function search(Request $request): BookCollection
    {       
        $search = $request->keywords;

        $books = Book::where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhereHas('genres', function($q) use($search) {
                        $q->where('title', 'like', '%' . $search . '%');
                    })
                    ->with('genres')->orderByDesc('created_at')->paginate(5);

        return new BookCollection($books);
        
    }
}