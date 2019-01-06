<template>
    <div>
        <hr>
        <h2>Adicionar Item</h2>

        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" v-model="currentItem.name">
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <textarea type="text" class="form-control" v-model="currentItem.description"></textarea>
        </div>

        <div class="form-group">
            <label>Preço</label>
            <input type="number" class="form-control" v-model="currentItem.price">
        </div>

        <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" v-model="currentItem.type">
                <option>dish</option>
                <option>drink</option>
            </select>
        </div>
        <form >
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
        </form>

        <div class="">
          <button @click.prevent="addItem(currentItem)" class="btn btn-success">Adicionar</i></button>
          <button @click="cancelAdd" class="btn btn-warning">Cancelar</i></button>
        </div>
    </div>
</template>

<script>
  module.exports = {
    props:["currentItem"],
    data: function(){
      return{

      }
    },
    methods:{
      cancelAdd: function(){
        this.$emit('cancel-add-item');
      },
      addItem: function(item){
        this.$emit('add-item', item);
      },
      onFileSelected: function(event){
        let filex = this.$refs.fileInput.files[0];
        const myFormData = new FormData();
        myFormData.append('file', filex);
        axios.post('api/item_image', myFormData  ,
              {
              headers: {
                  'Content-Type': 'multipart/form-data'
              }
            }).then(response=>{
          this.currentItem.photo_url=response.data;
        }).catch(error=>{
          console.log(error);
        })
      }
    }
  }

</script>
