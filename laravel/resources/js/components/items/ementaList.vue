<template lang="html">
    <div>
      <table class="table table-striped">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Photo</th>
                  <th v-if="user.type == 'manager'">Actions</th>
              </tr>
          </thead>
          <tbody>
              <tr v-for="(item,index) in items" :key="item.id">
                  <td>{{item.name}}</td>
                  <td>{{item.type}}</td>
                  <td>{{item.description}}</td>
                  <td>{{item.price}}€</td>
                  <td><img width="100px" heigth= "100px" v-bind:src="'storage/items/' + item.photo_url" /> </td>

                  <td v-if="user.type == 'manager'">
                      <button v-on:click="deleteItem(item, index)" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></button>
                      <button v-on:click.prevent="editItem(item)" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button>
                  </td>
              </tr>
          </tbody>
      </table>
    </div>
</template>
<script>
    module.exports = {
      props:["items"],
      data: function(){
        return{
         user:[],
        }
      },
      methods:{
        deleteItem: function(item, index) {
          this.$emit('delete-item', item, index);
        },
        editItem: function(item){
          this.$emit('show-edit-item', item);
        }
      },
    created()
    {
        if(this.user !=null)
        {

            axios.get('api/users/'+this.$store.state.user.id)
             .then((response) => {
                this.user= response.data.data;
                });

        }else{
            this.user.type = [];
        }



                console.log(this.user);
    }

    }
</script>
