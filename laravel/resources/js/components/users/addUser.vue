<template>
    <div>
        <hr>
        <h2>Adicionar User</h2>
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" v-model="currentUser.name">
        </div>
        <div class="form-group">
            <label>Username</label>
            <textarea type="text" class="form-control" v-model="currentUser.username"></textarea>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" v-model="currentUser.email">
        </div>
        <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" v-model="currentUser.type">
                <option>manager</option>
                <option>waiter</option>
                <option>cook</option>
                <option>cashier</option>
            </select>
        </div>

        <!-- <div class="form-group">
          <div class="input-group">
              <span class="input-group-btn">
                  <span class="btn btn-default btn-file">
                      Imagem: <input type="file" v-model="currentItem.photo_url">
                  </span>
              </span>
          </div>
        </div> -->

        <div class="">
          <button @click="editUser(currentUser)" class="btn btn-xs btn-success">Editar</button>
          <button @click="cancelEdit"  class="btn btn-xs btn-warning">Cancelar</button>
        </div>
    </div>
</template>

<script>
  module.exports = {
    props:["currentUser"],
    data: function(){
      return{

      }
    },
    methods: {
      cancelEdit: function(){
        this.$emit('cancel-add-user');
      },
      editUser: function(user){
        this.$emit('add-user', user);
      }
    }
  }
</script>
