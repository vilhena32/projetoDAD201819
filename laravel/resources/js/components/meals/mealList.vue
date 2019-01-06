<template>
  <table class="table table-striped">
      <thead>
          <tr>
              <th>State</th>
              <th>Responsible</th>
              <th>Table</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
          <tr v-for="(meal,index) in meals" :key="meal.id">
              <td>{{meal.state}}</td>
              <td>{{meal.user}}</td>
              <td>{{meal.table_number}}</td>
              <td>
                  <button v-on:click="terminateMeal(meal, index)" class="btn btn-xs btn-success" title="Terminate meal"><i class="fas fa-check"></i></button>
                  <button v-on:click.prevent="viewMeal(meal)" class="btn btn-xs btn-primary" title="View meal details"><i class="far fa-eye"></i></button>
              </td>
          </tr>
      </tbody>
  </table>
</template>

<script>
module.exports = {
  props:["meals"],
  data: function(){
    return {

    }
  },
  methods: {
    viewMeal: function(meal){
      this.$emit('view-meal', meal);
    },
    terminateMeal: function(meal, index){
      axios.get('/api/getmealorders/'+meal.id).then(response=>{
        orders = response.data;
        for(var order in orders){
          if(order.state != 'delivered'){
            if (window.confirm("This measl has orders that have not been delivered. Do you want to terminate this meal anyway?")){
                this.$emit('terminate-meal', meal, index); //Ok pressed
            }
          }
        }
      })
      //criar invoice
    }
  },
}
</script>

<style lang="css">
</style>
