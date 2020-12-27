<template>    
    <div class="col-md-12">                
        <div class="card">
            <div class="card-header">
                <h3>Редактирование информации о книге</h3>
            </div>
            <form @submit.prevent="updateBook">
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
                            
                            <img :src="'/storage/' + imageSrc" class="img-thumbnail" v-if="imageSrc">                                             
                                    
                            <div class="form-group">
                                <h5>Жанры</h5>
                                <div class="form-check" v-for="genre in book.all_genres" :key="genre.id">
                                    <input name="genresCheckBox"
                                           type="checkbox" 
                                           class="form-check-input"
                                           v-bind:value="genre.id"
                                           v-model="genresChecked"
                                    >
                                    <label class="form-check-label" for="rolesCheckBox">{{ genre.title }}</label>
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
            image: null,
            imageSrc: '',
        }
    },
    mounted() {           
        this.getBook();        
    },
    methods: {
        getBook() {
            let uri = `/api/books/${this.$route.params.id}`;
            this.axios.get(uri).then((response) => {
                if(response.data.data) {
                this.book = response.data.data;
                this.setGenresChecked();
                this.renderImage();               
                }
                else if (response.data.message) {
                    this.message = response.data.message;
                    swal("Ошибка", this.message, "error");
                    this.triggerGoSectionsPage();
                }
                else {
                    swal("Ошибка", "Нет ответа от сервера при первоначальном доступе к модифицируему отделу", "error");
                    this.triggerGoSectionsPage();
                }
            })
            .catch(error => {
                if(error.response.data.message) {             
                    swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                    this.triggerGoSectionsPage();             
                }          
                else {
                    swal('Ошибка', "Внутренняя ошибка сервера", "error");                
                    this.triggerGoSectionsPage();
                }
            });
        },
        updateBook(id) {
            this.book.genres = this.genresChecked;
            this.book.picture = this.imageSrc;
            let uri = `/api/books/${this.$route.params.id}`;
            this.axios.patch(uri, this.book/*{}*/)
                .then((response) => {
                    if(response.data.message) {
                        this.message = response.data.message;
                        swal("Сохранение изменений", this.message, "success");
                        this.$router.push({name: 'books'});
                    }
                    else {
                        swal("Ошибка", "Нет ответа от сервера при сохранении изменений данных отдела", "error");
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
        setGenresChecked() {
            for(let i = 0; i < this.book.genres.length; i++) {
                Vue.set(this.genresChecked, i, this.book.genres[i].id);
    		}
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
        },
        renderImage() {
            this.imageSrc = this.book.picture;
        }        
    }
}
</script>