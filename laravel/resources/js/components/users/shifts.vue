<template>
    <div>
         <div class="jumbotron">
        <h1 align="center">{{title}}</h1>
    </div>
        <div class=" alert" v-bind:class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
        <button type="button" @click = "showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
        <strong>{{successMessage}}</strong>
        <strong>{{failMessage}}</strong>
      </div>

        <div class="container" align="center">
            <strong>{{this.user.shift_active}}</strong>
            <button @click.prevent="activeShift(user)" :class="{'btn-success':user.shift_active, 'btn-warning':!user.shift_active}" class="btn btn.xs " >Iniciar/Terminar Turno</button>
        </div>
    </div>
</template>
<script>
    module.exports = {
        data:function () {
            return{
                title: 'Shifts',
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',

                user:{},
            };
        },
        methods: {
            activeShift: function (user) {

                const loggedInUser = this.$store.state.user;

                axios.put('api/users/shift/'+loggedInUser.id, loggedInUser).then(response=>{
                    this.showSuccess = true;
                    this.successMessage = "Turno iniciado/terminado com sucesso!";
                     if (loggedInUser.id == user.id) {
                         Object.assign(user, response.data.data);
                     }
            }).catch(error=>{
                this.showFailure = true;
                console.log(error);
                this.failMessage = "Erro a iniciar/terminar turno!";
            });
            }
        },
        created()
        {
            this.user = this.$store.state.user;
            console.log(this.user);
        }
    };
</script>
