<template>
<div>
  <div class="jumbotron">
      <h1 align="center">{{title}}</h1>
  </div>
  <div class="container">
      <button class="btn btn-success" @click="showAddTable" placeholder="Adicionar uma mesa">Adicionar mesa</button>
    <div class="alert" v-bind:class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
        <button type="button" @click = "showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
        <strong>{{successMessage}}</strong>
        <strong>{{failMessage}}</strong>
    </div>
    <table-add :currentTable="currentTable" v-if="showingAddTable" @add-table="addTable" @cancel-add-table="cancelAddTable"></table-add>
    <table-list :tables="tables" @delete-table="deleteTable" v-if="showingTables"></table-list>
  </div>
</div>
</template>

<script>
module.exports = {
  data: function(){
      return {
        title: 'Tables',
        tables: [],
        currentTable: {},
        showingTables: true,
        showingAddTable: false,
        showSuccess: false,
        showFailure: false,
        successMessage: '',
        failMessage: ''
      }
  },
  methods: {
    showAddTable: function(){
      this.showingTables = false;
      this.currentTable = {};
      this.showingAddTable = true;
    },
    cancelAddTable: function(){
      this.showingAddTable = false;
      this.showingTables = true;
    },
    deleteTable: function(table, index){
      axios.delete('/api/tables/'+table.table_number).then(response=>{
        this.showSuccess = true;
        this.successMessage = 'Mesa apagada com sucesso!';
        this.tables.splice(index,1);
      }).catch(error=>{
        this.showFailure = true;
        this.failMessage = 'Erro ao apagar a mesa';
      });
    },
    addTable: function(table){
      axios.post('/api/tables', table).then(response=>{
        this.showSuccess = true;
        this.successMessage = 'Mesa adicionada com sucesso!';
        this.showingAddTable = false;
        axios.get('/api/tables').then(response=>{
          this.tables = response.data.data;
          this.showingTables = true;
        }).catch(error=>{
          this.showFailure = true;
          this.failMessage = 'Não foi possivel ir buscar as mesas!';
        });
      }).catch(error=>{
        this.showFailure = true;
        this.failMessage = 'Erro ao adicionar mesa';
      });
    },
  },
  mounted() {
    axios.get('/api/tables').then(response=>{
      this.tables = response.data.data;
    }).catch(error=>{
      this.showFailure = true;
      this.failMessage = 'Não foi possivel ir buscar as mesas!';
    });
  }
}
</script>
