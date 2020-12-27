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
                        <li class="nav-item">
                            <router-link :to="{name: 'books'}" class="nav-link">Книги</router-link>
                        </li>
                    </ul>

                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <router-link :to="{name: 'genres'}" class="nav-link">Жанры</router-link>
                        </li>
                    </ul>                    
                
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        
                        <li v-if="!authorized" class="nav-item">
                            <router-link :to="{name: 'login'}" class="nav-link">Логин</router-link>                               
                        </li>
                            
                        <li v-if="!authorized" class="nav-item">
                            <router-link :to="{name: 'register'}" class="nav-link">Регистрация</router-link>
                        </li>

                        <li v-if="!authorized" class="nav-item">
                            <p class="nav-link">{{ loggedUserName }}</p>
                        </li>    
                        
                        <li v-if="authorized" class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                {{ loggedUserName }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">                                    
                                <router-link :to="{name: 'logout'}" class="dropdown-item">Выход</router-link>                                                                  
                            </div>
                        </li>                       
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <router-view :authorized="authorized" @changelogin="changelogin"></router-view>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                isLoggedIn : null,          
                loggedUser : {},                          
            }
        },
        mounted(){
            this.update();            
            
        },
        methods: {
            update() {
                this.isLoggedIn = localStorage.getItem('jwt');
                this.name = localStorage.getItem('user');
                this.axios.defaults.headers.common['Content-Type'] = 'application/json';
                this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.isLoggedIn;
                this.getLoggedUser();
            },
            changelogin: function(id) {
                this.isLoggedIn = null; 
                this.loggedUser = {};               
                setTimeout(() => {this.update()}, 500)
            },
            getLoggedUser() {
            let uri = '/api/logged-user';
            this.axios.get(uri)
            	.then((response) => {
            	    if(response.data.data) {
                        this.loggedUser = response.data.data;                                    	
            	    }
            	    if (response.data.message) {
                        this.message = response.data.message;
                        swal("Ошибка", this.message, "error");
                    }                
              })
              .catch(error => {
                if(error.response) {
                  if(error.response.data.message) {
                    if(error.response.status == 401) {
                      if (localStorage.getItem('jwt')) {
                        localStorage.removeItem('jwt');
                        this.$router.push({name: 'login'});
                      }
                    }
                    else {
                      swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                    }
                  }         
                }
                else if(error.request) {
                }
                else {
                  swal('Ошибка', "Внутренняя ошибка сервера", "error");
                }
              });
          },

        },
        computed: {
            loggedUserName() {
                if(this.loggedUser.name) {
                    return this.loggedUser.name;
                }               
                return 'Guest';    
            },
            authorized() {
                if(this.loggedUser.name) {
                    return true;
                }               
                else {
                    return false;
                } 
            }
        }        
    }
</script>