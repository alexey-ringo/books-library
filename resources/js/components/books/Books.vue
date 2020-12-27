<template>    
    <div class="col-md-12">                
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">                        
                <nav v-if="authorized" class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <router-link :to="{name: 'book-create' }" class="btn btn-primary float-right">
                        Добавить новую книгу
                    </router-link>                    
                </nav>
                </div>
                
                <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control" v-model="keywordsSearch" placeholder="Поиск">
                    <span class="input-group-append">
                        <!--<button type="button" class="btn btn-info btn-flat" @click="refreshResults">Обновить</button>-->
                    </span>
                </div>
                </div>
                </div>                    
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Обложка</th>
                            <th>Книга</th>
                            <th>Жанры</th>
                            <th v-if="authorized">Действия</th>
                        </tr>
                    </thead>
                    <tbody>                            
                        <tr v-for="book in books" :key="book.id">
                            <td class="product-col"><img :src="'/storage/' + book.picture" class="img-fluid"></td>
                            <td>
                                <h5>{{ book.title }}</h5>
                                <p>{{ book.author }}</p>
                                </td>
                            <td>
                                <div v-for="genre in book.genres" :key="genre.id">
                                    <p>{{ genre.title }}</p>                                    
                                </div>                                
                            </td>
                            <td v-if="authorized">
                                <router-link :to="{name: 'book-edit', params: {id: book.id}}" class="btn btn-secondary">
                                    Редактировать
                                </router-link>                                                                      
                                <button class="btn btn-danger" @click.prevent = "deleteBook(book.id)">Удалить</button> 
                            </td>
                        </tr>
                          
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="row justify-content-center">
                    <nav aria-label="...">
                        <ul class="pagination">                       
                            <li class="page-item" v-if="paginateVisiblePrev">
                                <button class="page-link" @click="getPrevPage">Пред</button>                                
                            </li>
                            <li class="page-item" v-if="paginateVisiblePrev"><button class="page-link" @click="getPrevPage">1</button></li>
                            <li class="page-item active">
                                <button class="page-link">{{ meta.current_page }} <span class="sr-only">(current)</span></button>
                            </li>
                            <li class="page-item" v-if="paginateVisibleNext"><button class="page-link" @click="getNextPage">3</button></li>
                            <li class="page-item" v-if="paginateVisibleNext">
                                <button class="page-link" @click="getNextPage">След</button>                                
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>        
</template>

<script>
    export default {
        props: [
            'authorized'
            ],
        data: function () {
            return {
                books: [],
                message: '',
                keywordsSearch: '',
                links: {
                    first: null,
                    last: null,
                    next: null,
                    prev: null
                },
                meta: {
                    current_page: null,
                    from: null,
                    last_page: null,
                    path: null,
                    per_page: null,
                    to: null,
                    total: null
                }
            }
        },
        mounted() {            
            this.getBooks();
        },
        watch: {      
            keywordsSearch(after, before) {
                if(this.keywordsSearch.length > 2) {
                    this.fetchSearch();
                }
            }
        },
        methods: {
            getBooks() {
            let uri = '/api/books';
            this.axios.get(uri)
                .then((response) => {
                    if(response.data.links) {
                        this.links = response.data.links;
                    }
                    if(response.data.meta) {
                        this.meta = response.data.meta;
                    }
                    if(response.data.data) {
               	        this.books = response.data.data;
                    }
                    else if (response.data.message) {
                        this.message = response.data.message;
                        swal("Ошибка", this.message, "error");                   
                    }
                    else {
                        swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку отделов", "error");                    
                    }        
                })
                .catch(error => {
                    if(error.response) {
                        if(error.response.data.message) {
                            swal('Ошибка - ' + error.response.status, error.response.data.message, "error");                                                     
                        }                    }                    
                        else {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");                       
                        }
                });
            },
            getNextPage() {
                let uri = this.links.next;
                console.log(uri);
                this.axios.get(uri)
            	    .then((response) => {
            	        if(response.data.links) {
            	            this.links = response.data.links;
            	        }
            	        if(response.data.meta) {
            	            this.meta = response.data.meta;
            	        }
            	        if(response.data.data) {
                	        this.books = response.data.data;
            	        }
            	        else if (response.data.message) {
                            this.message = response.data.message;
                            swal("Ошибка", this.message, "error");                            
                        }
                        else {
                            swal("Ошибка", "Нет ответа от сервера", "error");                            
                        }        
                    })
                    .catch(error => {
                        if(error.response) {
                            if(error.response.data.message) {
                                swal('Ошибка - ' + error.response.status, error.response.data.message, "error");                                                     
                            }
                        }                    
                        else {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");                       
                        }
                    });
            },
            getPrevPage() {
                let uri = this.links.prev;
                this.axios.get(uri)
            	    .then((response) => {
            	        if(response.data.links) {
            	            this.links = response.data.links;
            	        }
            	        if(response.data.meta) {
            	            this.meta = response.data.meta;
            	        }
            	        if(response.data.data) {
                	        this.books = response.data.data;
            	        }
            	        else if (response.data.message) {
                            this.message = response.data.message;
                            swal("Ошибка", this.message, "error");                            
                        }
                        else {
                            swal("Ошибка", "Нет ответа от сервера", "error");                            
                        }        
                    })
                    .catch(error => {
                        if(error.response) {
                            if(error.response.data.message) {
                                swal('Ошибка - ' + error.response.status, error.response.data.message, "error");                                                     
                            }
                        }                    
                        else {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");                       
                        }
                    });
                
            },
            deleteBook(id) {
                let uri = `/api/books/${id}`;
                if (confirm("Вы действительно хотите удалить информацию об этой книге?")) {
                    this.axios.delete(uri)
                        .then((response) => {
                            if(response.data.data) {
                                //this.books.splice(this.sections.indexOf(id), 1);
                                this.books = response.data.data;
                            }
                            else if (response.data.message) {
                                this.message = response.data.message;
                                swal("Ошибка", this.message, "error");
                            }
                            else {
                                swal("Ошибка", "Нет ответа от сервера при попытке удаления выбранного отдела", "error");
                            }        
                        })
                        .catch(error => {
                            if(error.response) {
                                if(error.response.data.message) {
                                    swal('Ошибка - ' + error.response.status, error.response.data.message, "error");                                                     
                                }        
                            }                    
                            else {
                                swal('Ошибка', "Внутренняя ошибка сервера", "error");                       
                            }
                        });
                }
            },
            fetchSearch() {                
                let uri = '/api/search';
                this.axios.get(uri, { params: { keywords: this.keywordsSearch }})
          	        .then((response) => {
                        if(response.data.data) {
                            this.books = response.data.data;
                        }                        
                    })
                    .catch(e => {
            	        //console.log(e);
            	        swal('Ошибка', "Внутренняя ошибка сервера", "error");
                    });
            },
        },
        computed: {
            paginateVisibleNext() {                
                if(this.meta.current_page === this.meta.last_page) {
                    return false;
                }
                else {
                    return true;
                }
                
            },
            paginateVisiblePrev() {
                if(this.meta.current_page === this.meta.from) {
                    return false;
                }
                else {
                    return true;
                }
            },
            nextPageNum() {
            },
            prevPageNum() {
            }
        },
    }
</script>