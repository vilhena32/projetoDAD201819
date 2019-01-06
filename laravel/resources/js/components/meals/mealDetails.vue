<template>
  <div class="container">
    <hr>
    <div>
      <label>Table number: <span>{{currentMeal.table_number}}</span></label>
    </div>
    <div>
      <label>Total price: <span>{{currentMeal.total_price_preview}}€</span></label>
    </div>
    <table>
      <thead>
        <th>Name</th>
        <th>Price</th>
      </thead>
      <tbody>
        <tr v-for="(item,index) in mealItems" :key="item.id">
          <td>{{item.name}}</td>
          <td>{{item.price}}€</td>
        </tr>
      </tbody>
    </table>
    <button v-on:click="back()" class="btn btn-xs btn-warning" title="Back">Back</button>
  </div>
</template>

<script>
module.exports = {
  props:["currentMeal", "mealItems"],
  methods: {
    back: function(){
      this.$emit('back');
    }
  }
}
</script>

<style lang="css">
</style>
