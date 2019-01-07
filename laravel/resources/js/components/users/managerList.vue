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
        <tr v-for="(user,index) in users" v-bind:key="user.id"> <!-- o users é o que esta no app.js -->
            <td>{{user.name}}</td>
            <td>{{user.username}}</td>
            <td>{{user.email}}</td>
            <td>{{user.type}}</td>
            <td>
              <img width="100px" heigth= "100px" v-bind:src="'storage/profiles/' + user.photo_url" />
            </td>
            <td>
              <button v-on:click.prevent="editUser(user)" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button>
              <button v-on:click="deleteUser(user, index)" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></button>
              <button  v-if="authUser.id!=user.id" v-on:click.prevent="blockUser(user)" :class="{'btn-success':user.blocked, 'btn-warning':!user.blocked}" class="btn btn.xs "><i class="fas fa-ban"></i></button>
            </td>
        </tr>
    </tbody>
  </table>
</template>
<script>
    module.exports = {
        props: ["users"],
        data:function () {
            return{
                authUser: {},
            };
        },
        methods: {
            editUser: function (user) {
                this.$emit('show-edit-user', user);
            },
            deleteUser: function (user, index) {
                this.$emit('delete-user', user, index);
            },
            blockUser: function (user) {
                this.$emit('block-user',user);
            }
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
