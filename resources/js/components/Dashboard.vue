<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#">
                    Library of Books
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
               
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li><router-link :to="{name: 'books'}" class="nav-link">Книги</router-link></li>
                    </ul>

                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li><router-link :to="{name: 'genres'}" class="nav-link">Жанры</router-link></li>
                    </ul>                    
                
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        
                            <li class="nav-item">
                                <router-link :to="{name: 'login'}" class="nav-link">Логин</router-link>                               
                            </li>
                            
                                <li class="nav-item">
                                    <router-link :to="{name: 'register'}" class="nav-link">Регистрация</router-link>
                                </li>
                            
                        
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    User
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#"
                                       
                                        Logout
                                    </a>                                    
                                </div>
                            </li>                       
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <router-view></router-view>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                //isLoggedIn : null,
                //name : null
            }
        },
        mounted(){  
                
            //this.isLoggedIn = localStorage.getItem('jwt')
            //this.name = localStorage.getItem('user')
            //if(!localStorage.getItem('jwt') && localStorage.getItem('user')) {
            //    this.$router.go('/');
            //}
            
        },
        /*
        beforeRouteEnter (to, from, next) { 
                if ( ! localStorage.getItem('jwt') && localStorage.getItem('user')) {
                    return next('login')
                }
                next()
            }*/
    }
</script>