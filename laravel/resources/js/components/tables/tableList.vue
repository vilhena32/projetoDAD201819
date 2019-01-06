<template>
  <div>
    <div class="card mb-4 shadow-sm" v-for="(table,index) in tables" :key="table.table_number">
      <div class="card-header">
        <h4 class="my-0 font-weight-normal">Mesa nº:</h4>
      </div>
      <div class="card-body">
        <h1 class="card-title pricing-card-title">{{table.table_number}}</h1>
        <button v-on:click="deleteTable(table, index)" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></button>
        <!-- <button v-on:click.prevent="editItem(table)" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button> -->
      </div>
    </div>
  </div>
<!-- <div>
  <ul class="list-group" v-for="(table,index) in tables" :key="table.table_number">
    <li class="list-group-item">
      {{table.table_number}}
      <button v-on:click="deleteItem(table, index)" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></button>
      <button v-on:click.prevent="editItem(table)" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button>
    </li>
  </ul>
</div> -->
</template>

<script>
module.exports = {
  props: ['tables'],
  data: function(){
    return {

    }
  },
  methods: {
    deleteTable: function(table, index){
      this.$emit('delete-table', table, index);
    }
  }
}
</script>

<style lang="css">
</style>
