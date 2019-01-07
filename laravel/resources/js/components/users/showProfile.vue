<template>
    <div class="container">
        <hr>
        <h2>Editar User</h2>
        
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" v-model="user.name" >
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" v-model="user.username">
        </div>
        <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" v-model="user.type" >
                <option>manager</option>
                <option>waiter</option>
                <option>cook</option>
                <option>cashier</option>
            </select>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" v-model="user.email" disabled>
        </div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-default btn-file">
                        Imagem: <input type="file" ref="fileInput" @change="onFileSelected">
                    </span>
                    <!-- <button @click.prevent="onFileSelected">Upload</button> -->
                </span>
            </div>
          </div>

        <div class="">
          <button @click="updateUser(user)" class="btn btn-xs btn-success">Editar</button>
          <button  class="btn btn-xs btn-warning">Cancelar</button>
        </div>
    </div>
</template>

<script>
  module.exports = {
    
   data : function()
	{
		return{
			code : this.$route.params.code,
					
		user:[],
		}
	},
    methods: {
        onFileSelected(event){
        let filex = this.$refs.fileInput.files[0];
        const myFormData = new FormData();
        myFormData.append('file', filex);
        axios.post('api/user_image', myFormData  ,
              {
              headers: {
                  'Content-Type': 'multipart/form-data'
              }
            }).then(response=>{
          this.user.photo_url=response.data;
        }).catch(error=>{
          console.log(error);
        })
      },

        updateUser(user)
        {
            axios.put('api/users/'+this.$store.state.user.id, user).then(response=>{
            this.showSuccess = true;
            console.log(response);
            // this.showFailure = false;
            this.successMessage = "Utilizador atualizado com sucesso!";
				
            });
        }
     
    },
    created()
    {
         axios.get('api/users/'+this.$store.state.user.id)
             .then((response) => {
                this.user= response.data.data;
                });
                console.log(this.user);
    }
  }
</script>
