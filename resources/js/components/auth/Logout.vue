<template>
 
</template>

<script>
    export default {
        data(){
           return {
            
            }
        },
        methods : {
            },
        mounted(){
            //localStorage.setItem('jwt', null);
            //console.log(localStorage.getItem('jwt'));
            
            if (localStorage.getItem('jwt') != null){
                                
                            
                let token = localStorage.getItem('jwt');
                axios.defaults.headers.common['Content-Type'] = 'application/json';
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                axios.get('api/logout').
                    then((response) => {                        
                        localStorage.removeItem('jwt');
                        this.$emit("changelogin", 1);
                    //    this.$router.push({name: 'books'});
                        this.$router.go(-1);
                    })
                    .catch(e => {                    	
                        localStorage.removeItem('jwt');
                        this.$emit("changelogin", 1);
                    });
                    
            //this.$router.go('/board')
            }
        },        
    }
    </script>