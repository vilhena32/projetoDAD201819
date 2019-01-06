<template>
<div>
  <div class="jumbotron">
      <h1 align="center">{{title}}</h1>
  </div>

    <div class="container">
      <button class="btn btn-success" @click="showAddMeal">Add meal</button>
      <div class=" alert" v-bind:class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
          <button type="button" @click = "showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
          <strong>{{successMessage}}</strong>
          <strong>{{failMessage}}</strong>
      </div>
      <meal-list :meals="meals" v-if="showingMeals" @view-meal="viewMeal" @terminate-meal="terminateMeal"></meal-list>
      <meal-add :currentMeal="currentMeal" v-if="showingAddMeals" @cancel-add="cancelAddMeal" @add-meal="addMeal"></meal-add>
      <meal-details  :mealItems="mealItems" :currentMeal="currentMeal" v-if="showingMealDetails" @back="showingMealDetails = false; showingMeals = true;"></meal-details>
    </div>



  </div>
</template>

<script>
module.exports = {
  data: function(){
    return {
      title:'Meals',
      currentMeal: {},
      meals: [],
      mealItems: [],
      showingMeals: true,
      showingAddMeals: false,
      showingMealDetails: false,
      showSuccess: false,
      showFailure: false,
      successMessage: '',
      failMessage: '',
      page:1,
      last:1,
      total:1,
    }
  },
  methods: {
    getResults()
    {
      axios.get('/api/usermeals/'+this.$store.state.user.id).then(response=>{
      this.meals = response.data.data;
   
    }).catch(error=>{
      this.showFailure = true;
      this.failMessage = 'Error while fetching the existing meals!';
    });
    },
    showAddMeal: function(){
      this.showingMeals = false;
      this.currentMeal = {};
      this.showingAddMeals = true;
    },
    cancelAddMeal: function(){
      this.showingMeals = true;
      this.showingAddMeals = false;
    },
    addMeal: function(meal){
      axios.post('api/meals', meal).then(response=>{
        if(response.data == "Table already in use"){
          this.showFailure = true;
          this.failMessage = response.data;
        }else{
          this.showSuccess = true;
          this.successMessage = "Meal added with success!";
          axios.get('/api/usermeals/'+this.$store.state.user.id).then(response=>{
            this.meals = response.data.data;
            this.cancelAddMeal();
          }).catch(error=>{
            this.showFailure = true;
            this.failMessage = 'Error while fetching the existing meals!';
          });
        }
      }).catch(error=>{
        this.showFailure = true;
        this.failMessage = "Error while creating a new meal";
      });
    },
    viewMeal: function(meal){
      this.showingMeals = false;
      this.showingAddMeals = false;
      axios.get('/api/getmeal/'+meal.id).then(response=>{
        this.currentMeal = response.data;
      }).catch(error=>{
        this.showFailure = true;
        this.failMessage = 'Error while calculating the meal price!'
      });
      axios.get('/api/getmealitems/'+meal.id).then(response=>{
        this.mealItems = response.data;
        this.showingMealDetails = true;
      }).catch(error=>{
        this.showFailure = true;
        this.failMessage = 'Error while fetching items for this meal!'
      });
    },
    terminateMeal: function(meal, index){
      axios.put('/api/terminatemeal/'+meal.id).then(response=>{
        this.showSuccess = true;
        this.successMessage = "Meal terminated with success!";
        this.meals.splice(index,1);
        axios.post('/api/invoices/'+meal.id).then(response=>{
          this.showSuccess = false;
          this.successMessage = '';
          this.showSuccess = true;
          this.successMessage = 'Pending invoice created with success';
          invoice = response.data;
          axios.post('/api/invoiceItems/'+invoice.id, meal).then(response=>{
            this.successMessage = 'Invoice items created with success!';
          }).catch(error=>{
            this.showSuccess = false;
            this.successMessage = '';
            this.showFailure = true;
            this.failMessage = 'Error while creating invocie items!';
          })
        }).catch(error=>{
          this.showSuccess = false;
          this.successMessage = '';
          this.showFailure = true;
          this.failMessage = 'Error while creating a pending invocie!';
        })
      }).catch(error=>{
        this.showFailure = true;
        this.showSuccess = false;
        this.failMessage = "Error while terminating meal!"
      });
    }
  },
  mounted() {
    this.getResults();
    // axios.get('/api/usermeals/'+this.$store.state.user.id).then(response=>{
    //   this.meals = response.data.data;
    //   this.page = response.data.meta.current_page;
    //   this.last = response.data.meta.last_page;
    //   this.total = response.data.meta.total;
    // }).catch(error=>{
    //   this.showFailure = true;
    //   this.showSuccess = false;
    //   this.failMessage = "Error while fetching user meals!"
    // })
  }
}
</script>
