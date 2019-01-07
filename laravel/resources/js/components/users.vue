<template>
  <div>
    <div class="jumbotron">
        <h1 align="center">{{title}}</h1>
    </div>
    <div class="container" v-if="authUser.type == 'manager'">
      <button class="btn btn-primary" @click.prevent="showUsers" v-if="!showingUsers" >Mostrar utilizadores</button>
      <button class="btn btn-primary" @click.prevent="showUsers" v-if="showingUsers">Fechar utilizadores</button>
      <button class="btn btn-primary" @click.prevent="showBlockedUsers" v-if="showingUsers">Mostrar Utilizadores Bloqueados</button>
      <button class="btn btn-primary" @click.prevent="showUnblockedUsers" v-if="showingUsers">Mostrar Utilizadores Desbloqueados</button>
      <button class="btn btn-success" @click.prevent="showAddUser">Adicionar Utilizador</button>
      <!-- <button class="btn btn-warning" @click.prevent="activeUserShift" >Iniciar/Terminar Turno</button> -->

      <div class=" alert" v-bind:class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
        <button type="button" @click = "showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
        <strong>{{successMessage}}</strong>
        <strong>{{failMessage}}</strong>
      </div>

      <user-list v-bind:users="users" v-if="showingUsers" v-on:show-edit-user="showEditingUser" v-on:delete-user="deleteUser" v-on:block-user="blockUser"></user-list>
      <user-edit v-bind:currentUser="currentUser" v-if="editingUser" v-on:edit-user="editUser" v-on:cancel-edit-user="cancelEditUser"></user-edit>
      <user-add v-bind:currentUser="currentUser" v-if="addingUser" v-on:cancel-add-user="cancelAddUser"></user-add>

      <div>
        <div>
  <div>





  </div>


  </div>
      </div>
    </div>

    <div class="container" v-if="authUser.type!='manager'">
      <button class="btn btn-primary" @click.prevent="showManagers" v-if="!showingManagers" >Mostrar Managers</button>
      <button class="btn btn-primary" @click.prevent="showUsers" v-if="showingManagers">Fechar Managers</button>
      <button class="btn btn-primary" @click.prevent="sendMsgToManagers">Notificar Managers</button>
      <!-- <button class="btn btn-warning" @click.prevent="activeUserShift" >Iniciar/Terminar Turno</button> -->

      <div class=" alert" v-bind:class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
        <button type="button" @click = "showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
        <strong>{{successMessage}}</strong>
        <strong>{{failMessage}}</strong>
      </div>

      <manager-list v-bind:managers="managers" v-if="showingManagers"></manager-list>

    </div>

  </div>

</template>

<script>


    module.exports = {
        data: function() {
        return{
        title: 'Users',
        users: [],
        managers: [],
        authUser: {},
        editingUser: false,
        showingUsers: true,
        showingManagers: true,
        addingUser: false,
        showSuccess: false,
        showFailure: false,
        successMessage: '',
        failMessage: '',

        page:1,
        last:1,
        total:1,






    }
    },
    methods: {


      showBlockedUsers()
      {
        	axios.get('api/blockedUsers')
				  .then(response => {

            this.users = response.data.data;
          console.log(response);

				  });
      },

       showUnblockedUsers()
      {
        	axios.get('api/unblockedUsers')
				  .then(response => {

            this.users = response.data.data;
          console.log(response);

				  });
      },

      getResults() {
          axios.get('api/users')
				  .then(response => {

            this.users = response.data.data;


				  });
		    },

        showUsers: function(){
            if(this.showingUsers == false){
              this.showingUsers = true;
            } else{
              this.showingUsers = false;
            }
        },

        showEditingUser: function(user){
            this.editingUser = true;
            this.showUsers();
            this.addingUser = false;
            this.currentUser = Object.assign({},user);
        },

        showAddUser: function(){
            this.addingUser = true;
            this.showUsers();
            this.editingUser = false;
            this.currentUser = {};
        },

        blockUser: function(user){

            //fazer verificação de quem está online, e retirar o acesso a bloquear-se
            axios.put('api/users/blocked/'+user.id, user).then(response=>{
                this.showSuccess = true;
                this.successMessage = "Utilizador bloqueado com sucesso!";
                this.getResults();

           /*     this.users.forEach(u =>{
              if(u.id == this.currentUser.id){
                Object.assign(u,response.data.data);
              }
            });*/
          }).catch(error=>{
            this.showFailure = true;
            console.log(error);
            // this.showSuccess = false;
            this.failMessage = "Erro ao bloquear o utilizador!"
            });
        },


        // activeUserShift: function(){
        //     const shiftUser = this.currentUser;
        //     const loggedInUser = this.$store.state.user;

        //         axios.put('api/users/shift/'+loggedInUser.id, loggedInUser).then(response=>{
        //             this.showSuccess = true;
        //             this.successMessage = "Turno iniciado/terminado com sucesso!";
        //             if (loggedInUser.id == shiftUser.id) {
        //                 Object.assign(shiftUser, response.data.data);
        //             }
        //     }).catch(error=>{
        //         this.showFailure = true;
        //         this.failMessage = "Erro a iniciar/terminar turno!";
        //     });



        // },

        editUser: function(user) {
        const userAlterado = this.currentUser;
          axios.put('api/users/'+user.id, user).then(response=>{
            this.showSuccess = true;
            // this.showFailure = false;
            this.successMessage = "Utilizador atualizado com sucesso!";


            this.users.forEach(u =>{
              if(u.id == userAlterado.id){
                Object.assign(u,response.data.data);
              }
            });
            this.cancelEditUser();
          }).catch(error=>{
            this.showFailure = true;
            // this.showSuccess = false;
            this.failMessage = "Erro ao atualizar o utilizador!"
          });
      },

        deleteUser: function(user, index){
          axios.delete('/api/users/' + user.id)
          .then(response=>{
              this.successMessage = 'Utilizador apagado com sucesso';
              this.showSuccess=true;
              this.users.splice(index,1);
          }).catch(error =>{
            this.failMessage = "Erro ao apagar utilizador";
            this.showFailure=true;

          });
        },

        cancelEditUser: function(){
          this.editingUser=false;
          this.showingUsers = true;
        },

        cancelAddUser: function(){
          this.addingUser = false;
          this.showingUsers = true;
        },

        getUsers()
        {
          axios.get('api/users?page='+this.page).then(response=> {
            this.users = response.data.data;
            this.page = response.data.meta.current_page;
            this.last = response.data.meta.last_page;
            console.log(response.data.meta);
          }).catch(error=>{
          this.failMessage = 'Could not get Users!'
          this.showUsers();


        });
      },
      getManagers(){
        axios.get('api/managers').then(response => {
          this.managers = response.data.data;
        }).catch(error => {
          this.failMessage = 'Could not get Managers!';
          this.showManagers();
        });
      },
      showManagers: function(){
          if(this.showingManagers == false){
            this.showingManagers = true;
          } else{
            this.showingManagers = false;
          }
      },
      getManagerResults() {
          axios.get('api/managers')
				  .then(response => {

            this.managers = response.data.data;


				  });
		    },
        sendMsgToManagers: function(){
          // this.$socket.emit('user_enter_manager', this.authUser);
          let msg = window.prompt('What do you want to notify to the managers?');
          console.log('Sending to the server (only managers) this message: "' + msg + '"');
        if (this.$store.state.user === null) {
            this.$toasted.error('User is not logged in. Department is unknown!');
        } else {
            this.$socket.emit('message_managers', msg+" from "+ this.$store.state.user.name);
        }
        // msg = "";
        // this.$socket.emit('user_exit_manager', this.authUser);

          // this.$socket.emit('user_exit_manager', this.authUser);
      },
    },
    created()
    {
        if(this.$store.state.user!=null)
        {
            console.log(this.$store.state.user);
            this.authUser=this.$store.state.user;
        }


    },
    beforeMount() {
      this.getResults();
      this.getManagerResults();
    }
  }

</script>
