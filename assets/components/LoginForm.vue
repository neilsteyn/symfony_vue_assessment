<template>
    <div class="container">
        <div class="content">
            <h1>Please login</h1>
            <form method="POST" ref="form">
                <div class="form-control" ref="email">
                    <label for=""></label>
                    <input type="text" v-model="email" v-on:input="onEmailInput()" placeholder="Email" name="email"/>
                    <span class="error"></span>
                </div>
                <div class="form-control" ref="password">
                    <label for=""></label>
                    <input type="password" v-model="password" v-on:input="onPasswordInput()" placeholder="Password" name="password"/>
                    <span class="error"></span>
                </div>

                <input type="hidden" name="_csrf_token" :value="csrf">

                <a class="btn submit" v-on:click="onSubmit()">Login</a>
            </form>
        </div>
    </div>
</template>

<script>
   export default {
       name: "login-form",
         data: function () {
            return {
            email: "",
            password: "",
            };
        },
        props: [
            'csrf'
        ],
        methods: {
           onSubmit: function(){
               if (!this.email){
                   this.$refs.email.classList.add('error');
               }
                if (!this.password){
                   this.$refs.password.classList.add('error');
               }

               if (this.$refs.email.classList.contains('error') ||
               this.$refs.password.classList.contains('error')){
                   return;
               }

               this.$refs.form.submit();
           },
            onEmailInput: function(){
               this.$refs.email.classList.remove('error');
           },
           onPasswordInput: function(){
               this.$refs.password.classList.remove('error');
           }
        }
   }
</script>

<style scoped>

.container{
    margin-left: auto;
    margin-right: auto;
    width: 430px;
    border: 1px solid black;
    margin-top: 150px;
}
.content{
    padding: 30px;
    text-align: center;
}
.form-control{
    margin-top: 10px;
    margin-bottom: 10px;
}
.form-control.error input{
    border: 1px solid red;
}
input{
    padding: 5px;
}
.submit{
    display: inline-block;
    margin-top: 10px;
    padding: 5px 10px 5px 10px;
    border: 1px solid gray;
}
.submit:hover{
    cursor: pointer;
}
</style>