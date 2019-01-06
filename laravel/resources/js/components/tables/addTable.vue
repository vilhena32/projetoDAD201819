<template>
<div>
  <h2>Adicionar table</h2>
  <div class="form-group">
      <label>Numero da mesa</label>
      <input type="text" class="form-control" v-model="currentTable.table_number">
  </div>
  <div class="">
    <button @click="addTable(currentTable)" class="btn btn-xs btn-success">Adicionar</button>
    <button @click="cancelAdd"  class="btn btn-xs btn-warning">Cancelar</button>
  </div>
</div>
</template>

<script>
module.exports = {
  props: ['currentTable'],
  data: function(){
    return {

    }
  },
  methods: {
    addTable: function(table){
      this.$emit('add-table', table);
    },
    cancelAdd: function(){
      this.$emit('cancel-add-table');
    }
  }
}
</script>

<style lang="css">
</style>
