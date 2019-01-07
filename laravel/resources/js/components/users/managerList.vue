<template>
  <table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Type</th>
            <th>Photo</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <tr v-for="(manager,index) in managers" v-bind:key="manager.id"> <!-- o managers é o que esta no app.js -->
            <td>{{manager.name}}</td>
            <td>{{manager.username}}</td>
            <td>{{manager.email}}</td>
            <td>{{manager.type}}</td>
            <td>
              <img width="100px" heigth= "100px" v-bind:src="'storage/profiles/' + manager.photo_url" />
            </td>
            <td>
              <button v-on:click.prevent="sendMessageTo(manager)" class="btn btn-xs btn-primary"><i class="fas fa-comment-alt"></i></i></button>

            </td>
        </tr>
    </tbody>
  </table>
</template>
<script>
    module.exports = {
        props: ["managers"],
        data:function () {
            return{
                authUser: {},
            };
        },
        methods: {
          


          sendMessageTo: function(manager){
      let msg = window.prompt('What do you want to say to "' + manager.name + '"');
      console.log('Sending Message "' + msg + '" to "' + manager.name + '"');
      this.$socket.emit('privateMessage', msg, this.authUser, manager);
    },
        },
        created()
        {
            if(this.$store.state.user!=null)
            {
                console.log(this.$store.state.user);
                this.authUser=this.$store.state.user;
            }


        }
    };
</script>
