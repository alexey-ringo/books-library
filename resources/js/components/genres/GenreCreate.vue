<template>    
    <div class="col-md-12">                
        <div class="card">
            <div class="card-header">
                <h3>Создание нового жанра</h3>
            </div>
            <form @submit.prevent="storeGenre">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="maindata" role="tabpanel">
                            <div class="form-group">
                                <label for="name">Название жанра</label>
                                <input name="name"
                                       id="name"                                           
                                       type="text"
                                       class="form-control"
                                       v-model="genre.title"
                                >
                            </div>                                           
                        </div>
                    </div>                        
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary">Применить</button>                    
                    <router-link :to="{name: 'genres'}" class="btn btn-secondary float-right">Отмена</router-link>
                </div>
            </form>    
        </div>
    </div>        
</template>

<script>
export default {    
    data: function () {
        return {
            genre: {},
            message: ''
        }
    },
    mounted() {     
        
    },
    methods: {        
        storeGenre(id) {
            let uri = `/api/genres`;
            this.axios.post(uri, this.genre/*{}*/)
                .then((response) => {
                    if(response.data.message) {
                        this.message = response.data.message;
                        swal("Создание нового жанра", this.message, "success");
                        this.$router.push({name: 'genres'});
                    }
                    else {
                        swal("Ошибка", "Нет ответа от сервера при создании нового пользователя пользователя", "error");
                        this.$router.push({name: 'genres'});
                    }
                })
                .catch(error => {
                    if(error.response) {
                        if(error.response.data.message) {               
                           swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                            this.$router.push({name: 'genres'});                
                        }//Ошибки валидации
                        else {
                            swal('Ошибка - ' + error.response.status, this.errMessageToStr(error.response.data), "error");
                        }
                    }            
                    else {
                        swal('Ошибка', "Внутренняя ошибка сервера", "error");              
                        this.$router.push({name: 'genres'});
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
    }
}
</script>