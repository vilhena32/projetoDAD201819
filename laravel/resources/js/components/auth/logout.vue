<template>
<div>
    <div class="jumbotron">
      <h1 align="center">{{title}}</h1>
    </div>
  <div class="contaier">
    <hr>
      <h2 align="center" >Do you really want to leave?</h2>
    <hr>
    <div class="text-center">
      <a class="btn btn-primary btn-lg" v-on:click.prevent="logout">Exit</a>
    </div>
  </div>
</div>
</template>

<script>
  export default {
    data: function(){
        return {
            typeofmsg: "alert-success",
            showMessage: false,
            message: "",
            title:"Logout",
        }
    },
    methods: {
        logout() {
            this.showMessage = false;
            this.$socket.emit('user_exit', this.$store.state.user);
            // this.$socket.emit('user_exit_type', this.$store.state.user); 
            axios.post('api/logout')

                .then(response => {

                    this.$store.commit('clearUserAndToken');

                    this.typeofmsg = "alert-success";
                    this.message = "User has logged out correctly";
                    this.showMessage = true;

                })
                .catch(error => {
                    this.$store.commit('clearUserAndToken');
                    //this.$socket.emit('user_exit', this.$store.state.user);
                    this.typeofmsg = "alert-danger";
                    this.message = "Logout incorrect. But local credentials were discarded";
                    this.showMessage = true;
                    console.log(error);
                })
            }
    }
}
</script>

<style lang="css">
</style>
