<template>    
    <div class="col-md-12">                
        <div class="card">
            <div class="card-header">
                <h3>Создание новой книги</h3>
            </div>
            <form @submit.prevent="storeBook">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="maindata" role="tabpanel">
                            <div class="form-group">
                                <label for="title">Книга</label>
                                <input name="title"
                                       id="title"                                           
                                       type="text"
                                       class="form-control"
                                       v-model="book.title"
                                >
                            </div>

                            <div class="form-group">
                                <label for="author">Автор</label>
                                <input name="author"
                                       id="author"                                           
                                       type="text"
                                       class="form-control"
                                       v-model="book.author"
                                >
                            </div>                                                   

                            <div class="form-group">
                                <label for="picture">Фото</label>
                                <input name="picture"                                                                                 
                                       type="file"
                                       id="file"                                       
                                       accept="image/*"
                                       class="form-control"
                                       @change="onFileChange"                                       
                                >
                            </div>            
                            
                            <img :src="'/storage/' + imageSrc" class="img-thumbnail" v-if="image">

                            <div class="form-group">
                                <h5>Жанры</h5>
                                <div class="form-check" v-for="genre in genres" :key="genre.id">
                                    <input name="genresCheckBox"
                                           type="checkbox" 
                                           class="form-check-input"
                                           v-bind:value="genre.id"
                                           v-model="genresChecked"
                                    >
                                    <label class="form-check-label" for="genresCheckBox">{{ genre.title }}</label>
                                </div>
                            </div>  
                                     
                        </div>
                    </div>                        
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary">Применить</button>
                    <router-link :to="{name: 'books'}" class="btn btn-secondary float-right">Отмена</router-link>
                </div>
            </form>    
        </div>
    </div>        
</template>

<script>
export default {    
    data: function () {
        return {
            book: {},
            message: '',
            genresChecked: [],
            genres: [],
            image: null,
            imageSrc: '',            
        }
    },
    mounted() {           
        this.getGenres();        
    },
    methods: {
        getGenres() {
            let uri = '/api/genres?alldata=1';            
            this.axios.get(uri)                
                .then((response) => {                    
                    if(response.data.data) {
                        this.genres = response.data.data;                          
                    }
                    else if (response.data.message) {
                        this.message = response.data.message;
                        swal("Ошибка", this.message, "error");                   
                    }
                    else {
                        swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к списку жанров", "error");                    
                    }        
                })
                .catch(error => {
                    if(error.response) {
                        if(error.response.data.message) {
                            swal('Ошибка - ' + error.response.status, error.response.data.message, "error");                                                     
                        }                    }                    
                        else {
                            swal('Ошибка', "Внутренняя ошибка сервера", "error");
                            this.$router.push({name: 'books'});                       
                        }
                });
        },
        storeBook() {
            this.book.genres = this.genresChecked;            
            this.book.picture = this.imageSrc;
            let uri = `/api/books`;
            this.axios.post(uri, this.book/*{}*/)
                .then((response) => {
                    if(response.data.message) {
                        this.message = response.data.message;
                        swal("Сохранение изменений", this.message, "success");
                        this.$router.push({name: 'books'});           
                    }
                    else {
                        swal("Ошибка", "Нет ответа от сервера при сохранении изменений информации о книге", "error");
                        this.$router.push({name: 'books'});
                    }
                })
                .catch(error => {
                    if(error.response) {
                        if(error.response.data.message) {               
                           swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                           this.$router.push({name: 'books'});                
                        }//Ошибки валидации
                        else {
                            swal('Ошибка - ' + error.response.status, this.errMessageToStr(error.response.data), "error");
                        }
                    }            
                    else {
                        swal('Ошибка', "Внутренняя ошибка сервера", "error");              
                        this.$router.push({name: 'books'});
                    }
                });
        },        
        errMessageToStr(errors) {
            let result = '';
            for(let key in errors) {
                errors[key].forEach(function(item){
                result += item + '; ';
                });
            }
        return result;
        },          
        onFileChange(event) {
            this.image = event.target.files[0];
            let formData = new FormData();
            formData.append('picture', this.image);
            
            let uri = `/api/upload`;
            this.axios.post(uri,
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }
            )
            .then((response) => {                    
                if(response.data.data) {
                    this.imageSrc = response.data.data;                          
                }
                else if (response.data.message) {
                    this.message = response.data.message;
                    swal("Ошибка", this.message, "error");                   
                }
                else {
                    swal("Ошибка", "Нет ответа от сервера при загрузке изображения", "error");                    
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
                    this.$router.push({name: 'books'});                      
                }
            });
        }       
    }
}
</script>