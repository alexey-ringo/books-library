import VueRouter from 'vue-router';

import Login from "./components/auth/Login";
import Logout from "./components/auth/Logout";
import Register from "./components/auth/Register";
import Dashboard from "./components/Dashboard"; 
import Books from './components/books/Books';
import BookCreate from './components/books/BookCreate';
import BookEdit from './components/books/BookEdit';
import Genres from './components/genres/Genres';
import GenreCreate from './components/genres/GenreCreate';
import GenreEdit from './components/genres/GenreEdit';


export default new VueRouter({
    
    routes : [
        {
            path: '/',
            name: 'books',
            component: Books,
        },
        { 
            path: '/books/create',  
            name: 'book-create', 
            component: BookCreate 
        },
        { 
            path: '/books/:id',  
            name: 'book-edit', 
            component: BookEdit 
        },
        { 
            path: '/genres',  
            name: 'genres', 
            component: Genres 
        },      
        { 
            path: '/genres/create',  
            name: 'genre-create', 
            component: GenreCreate 
        },
        { 
            path: '/genres/:id',  
            name: 'genre-edit', 
            component: GenreEdit 
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/logout',
            name: 'logout',
            component: Logout,
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
        },
        

        //{
        //    path: '/',
        //    name: 'dashboard',
        //    component : Dashboard,
            
            
        //    children: [            
            //    { path: '/books',  name: 'books', component: Books,    
            //        children: [
            //            { path: 'create',  name: 'book-create', component: BookCreate },
            //            { path: ':id',  name: 'book-edit', component: BookEdit },
            //        ]            
            //    },               
            //    
            //    { path: '/genres',  name: 'genres', component: Genres, 
            //        children: [
            //            { path: 'create',  name: 'genre-create', component: GenreCreate },
            //            { path: ':id',  name: 'genre-edit', component: GenreEdit },
            //        ]
            //    },

            //    { path: '/books',  name: 'books', component: Books },        
        //        { path: '/books/create',  name: 'book-create', component: BookCreate },
        //        { path: '/books/:id',  name: 'book-edit', component: BookEdit },

        //        { path: '/genres',  name: 'genres', component: Genres },        
        //        { path: '/genres/create',  name: 'genre-create', component: GenreCreate },
        //        { path: '/genres/:id',  name: 'genre-edit', component: GenreEdit },                
            //]
        //},
    ],
    
    mode: 'history'
    
});