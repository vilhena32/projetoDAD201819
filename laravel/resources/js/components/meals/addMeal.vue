<template>
  <div>
    <div class=" alert" v-bind:class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
        <button type="button" @click = "showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
        <strong>{{successMessage}}</strong>
        <strong>{{failMessage}}</strong>
    </div>
      <hr>
      <h2>Add Meal</h2>

      <div>
        <label>Table:</label>
        <select v-model="currentMeal.table_number">
          <option v-for="table in tables" v-bind:value="table.table_number"> {{table.table_number}}</option>
        </select>
      </div>
      <div class="">
        <button @click.prevent="addMeal(currentMeal)" class="btn btn-success">Adicionar</i></button>
        <button @click="cancelAdd" class="btn btn-warning">Cancelar</i></button>
      </div>
  </div>
</template>

<script>
module.exports ={
  props:["currentMeal"],
  data: function(){
    return{
      tables: [],
      showFailure: false,
      showSuccess: false,
      failMessage: '',
      successMessage: ''
    }
  },
  methods: {
    cancelAdd: function(){
      this.$emit('cancel-add');
    },
    addMeal: function(meal){
      meal.userid = this.$store.state.user.id;
      this.$emit('add-meal', meal);
    }
  },
  mounted() {
    axios.get('/api/tables').then(response=>{
      this.tables = response.data.data;
    }).catch(error=>{
      this.showFailure = true;
      this.failMessage = 'Error while fetching tables!';
    });
  }

}
</script>
