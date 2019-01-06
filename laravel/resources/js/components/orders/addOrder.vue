<template>
    <div>
        <hr>
        <h2>Add order</h2>

        <div class="form-group">
          <label>Table:</label>
          <select class="form-control" v-model="currentOrder.meal_id">
            <option v-for="meal in meals" v-bind:value="meal.id"> {{meal.table_number}}</option>
          </select>
        </div>


        <div class="form-group">
          <label>Dish/drink:</label>
          <select v-model="currentOrder.item_id" class=" form-control">
            <option v-for="item in items" v-bind:value="item.id"> {{item.name}}</option>
          </select>
        </div>

        <div class="">
          <button @click.prevent="addOrder(currentOrder)" class="btn btn-success">Adicionar</i></button>
          <button @click="cancelAdd" class="btn btn-warning">Cancelar</i></button>
        </div>
    </div>
</template>

<script>
  module.exports = {
    props:["currentOrder", "meals", "items"],
    data: function(){
      return{
      }
    },
    methods:{
      cancelAdd: function(){
        this.$emit('cancel-add');
      },
      addOrder: function(order){
        this.$emit('add-order', order);
    },
  }
}
</script>
