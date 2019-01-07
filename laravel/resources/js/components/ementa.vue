<template>
<div>

  <div class="jumbotron">
      <h1 align="center">{{title}}</h1>
  </div>
  <div class="container">
    <button class="btn btn-primary" @click.prevent="showEmenta" v-if="!showingEmenta">Mostrar Ementa</button>
    <button class="btn btn-primary" @click.prevent="showEmenta" v-if="showingEmenta">Fechar Ementa</button>
    <button v-if="user.type=='manager'" class="btn btn-success" @click.prevent="showAddItem">Adicionar Item</button>
    <div class=" alert" :class="{'alert-success': showSuccess, 'alert-danger': showFailure}" v-if="showSuccess || showFailure">
        <button type="button" @click="showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
        <strong>{{successMessage}}</strong>
        <strong>{{failMessage}}</strong>
    </div>
    <item-list :items="items" v-if="showingEmenta" v-on:delete-item="deleteItem" v-on:show-edit-item="showEditingItem"></item-list>
    <item-add :currentItem="currentItem" v-if="addingItem" v-on:cancel-add-item="cancelAddItem" v-on:add-item="addItem"></item-add>
    <item-edit :currentItem="currentItem" v-if="editingItem" v-on:edit-item="editItem" v-on:cancel-edit-item="cancelEditItem"></item-edit>

   <div>

    <b-pagination  align="center" size="md-c"  v-model="page" :limit="this.last" :total-rows="this.total"  :per-page="10" @input="getResults(page)"></b-pagination>
    <br>
  </div>

  </div>


</div>
</template>

<script>
  module.exports = {
    data: function() {
      return{
        title:'Menu',
        items: [],
        user:{},
        addingItem: false,
        showingEmenta: true,
        editingItem: false,
        showFailure: false,
        showSuccess: false,
        failMessage: '',
        successMessage: '',
        currentItem: {},
        page:1,
        last:1,
        total:1,

      }
    },
    methods: {
      getResults(page)
      {
        axios.get('api/items?page='+page).then(response=>{
          this.items = response.data.data;
          this.page = response.data.meta.current_page;
          this.last = response.data.meta.last_page;
          this.total = response.data.meta.total;
        }).catch(error=>{
        this.failMessage = 'Não foi possivel ir buscar os itens!'
        this.showFailure = true;
      });
      },
      showEmenta: function(){
          if (this.showingEmenta == false){
              this.showingEmenta = true;
              this.addingItem = false;
              this.editingItem = false;
          } else{
              this.showingEmenta = false;
          }
      },
      showAddItem: function() {
        this.addingItem = true;
        this.showingEmenta = false;
        this.editingItem = false;
        this.currentItem = {};
      },
      showEditingItem: function(item){
        this.editingItem = true;
        this.showingEmenta = false;
        this.addingItem = false;
        this.currentItem = Object.assign({},item);
      },
      cancelAddItem: function(){
        this.currentItem = {};
        this.addingItem = false;
        this.showingEmenta = true;
      },
      addItem: function(item){
        axios.post('api/items', item).then(response=>{
          this.showSuccess = true;
          this.successMessage = "Item adicionado com sucesso";
          axios.get('api/items').then(response=>{
            this.items = response.data.data;
            this.showEmenta();
          }).catch(error=>{
            this.failMessage = 'Não foi possivel ir buscar os itens!'
            this.showFailure = true;
          });
        }).catch(error=>{
          this.showFailure = true;
          this.failMessage = "Erro ao criar o novo item!";
        });
      },
      editItem: function(item) {
        const itemAlterado = this.currentItem;
          axios.put('api/items/'+item.id, item).then(response=>{
            this.showSuccess = true;
            // this.showFailure = false;
            this.successMessage = "Item atualizado com sucesso!";
            this.items.forEach(i =>{
              if(i.id == itemAlterado.id){
                Object.assign(i,response.data.data);
              }
            });
            this.cancelEditItem();
          }).catch(error=>{
            this.showFailure = true;
            // this.showSuccess = false;
            this.failMessage = "Erro ao atualizar o item!"
          });
      },
      cancelEditItem: function(){
        this.editingItem = false;
        this.showingEmenta = true;
      },
      deleteItem: function(item, index){
        axios.delete('/api/items/'+item.id).then(response=>{
            this.successMessage = 'Item eliminado com sucesso da ementa!';
            this.showSuccess = true;
            this.items.splice(index,1);
        }).catch(error=>{
          this.failMessage = 'Erro ao eliminar o item da ementa!';
          this.showFailure = true;
          // console.log(error);
        });

      }
    },
    mounted() {
      this.getResults(1);
      console.log(this.last);
      //notificar
     // if(this.$store.state.user.type=='manager')
      //{
         this.$toasted.show('Welcome!',
      {
        action:
        {
          onClick : (e, toastObject) => {
            push: this.$router.push('/login');
          },

        }
      });
  
    },
    created(){
      if(this.$store.state.user == null){
        this.user={};
      }else {
        this.user = this.$store.state.user;
      }
    }
  }
</script>
