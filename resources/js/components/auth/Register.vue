<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Регистрация</div>

                    <div class="card-body">
                        <form @submit.prevent="goRegister">

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Имя пользователя</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="name" required autofocus>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required>


                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" v-model="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Регистрация
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                name: "",
                email : "",
                password : "",
                password_confirmation : ""
            }
        },
        methods : {
            goRegister() {
                let uri = `/api/register`;
                this.axios.post(uri, {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                })
                .then((response) => {
                    if(response.data.success) {
                        localStorage.setItem('user',response.data.success.name)
                        localStorage.setItem('jwt',response.data.success.token)
                        if (localStorage.getItem('jwt') != null){
                            this.$emit("changelogin", 1);
                            this.$router.go(-1);
                        }
                    }
                    else {
                        swal("Ошибка", "Нет ответа от сервера при попытке войти в систему", "error");
                        this.$emit("changelogin", 1);
                            this.$router.go(-1);
                    }
                })
                .catch(error => {
                    if(error.response) {
                        if(error.response.data.message) {
                            swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                            this.$emit("changelogin", 1);
                            this.$router.go(-1);
                        }
                        if(error.response.data.error) {
                            swal('Ошибка - ' + error.response.status, this.errMessageToStr(error.response.data.error), "error");
                            this.$emit("changelogin", 1);
                            this.$router.go(-1);
                      }
                    }
                    else {
                        swal('Ошибка', "Внутренняя ошибка сервера", "error");
                        this.$emit("changelogin", 1);
                        this.$router.go(-1);
                    }
                });
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
    }
</script>
