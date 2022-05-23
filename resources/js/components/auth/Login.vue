<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Вход в систему</div>

                    <div class="card-body">
                        <form @submit.prevent="goLogin">
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
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
                email : "",
                password : ""
            }
        },
        methods : {
            goLogin() {
                let uri = `/api/login`;
                this.axios.post(uri, {
                    email: this.email,
                    password: this.password
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
                        this.$router.push({name: 'books'});
                    }
                })
                .catch(error => {
                    if(error.response) {
                      if(error.response.status === 422) {
                        if (error.response.data.errors) {
                          let validationError = '';
                          if (error.response.data.errors.name && error.response.data.errors.name.length
                              && error.response.data.errors.name.length > 0) {
                            validationError = error.response.data.errors.name[0] + '; ';
                          }
                          if (error.response.data.errors.email && error.response.data.errors.email.length
                              && error.response.data.errors.email.length > 0) {
                            validationError = error.response.data.errors.email[0] + '; ';
                          }
                          if (error.response.data.errors.password && error.response.data.errors.password.length
                              && error.response.data.errors.password > 0) {
                            validationError = error.response.data.errors.name[0] + '; ';
                          }
                          swal('Ошибка вылидации', validationError, "error");
                        }
                      } else {
                        if(error.response.data.message) {
                          swal('Ошибка - ' + error.response.status, error.response.data.message, "error");
                          this.$emit("changelogin", 1);
                          this.$router.push({name: 'books'});
                        }
                      }
                    }
                    else {
                        swal('Ошибка', "Внутренняя ошибка сервера", "error");
                        this.$emit("changelogin", 1);
                        this.$router.push({name: 'books'});
                    }
                });
            }
        }
    }
</script>
