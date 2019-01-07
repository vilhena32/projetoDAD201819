<template>
<div>
  <div class="jumbotron">
      <h1 align="center">{{title}}</h1>
  </div>

  <div class="container">
  <button class="btn btn-primary" v-if="!showingPreparedOrders && this.$store.state.user.type=='waiter'" @click="showPreparedOrders">Show prepared orders</button>
    <button class="btn btn-primary" v-if="showingPreparedOrders" @click="cancelShowPreparedOrders">Show orders</button>
    <button class="btn btn-success" v-if="this.$store.state.user.type=='waiter'" @click="showAddOrder">Add order</button>
    <div class=" alert" v-bind:class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
        <button type="button" @click = "showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
        <strong>{{successMessage}}</strong>
        <strong>{{failMessage}}</strong>
    </div>
    <order-list :showingPreparedOrders="showingPreparedOrders" :preparedOrders="preparedOrders" :user="this.$store.state.user" :orders="orders" @deliver-order="changeOrderState"
    v-if="showingOrders"
    @delete-order="deleteOrder" @take-order="takeOrder" @prepare-order="changeOrderState"></order-list>
    <add-order :items="items" :currentOrder="currentOrder" :meals="meals" v-if="showingAddOrders" @cancel-add="cancelAddOrder" @add-order="addOrder"></add-order>
  </div>
  <!-- @getOrdersParent="getOrdersP" -->
  <!-- @getPreparedOrdersParent="getPreparedOrdersP" -->
<!-- <div v-if=" this.$store.state.user.type == 'manager'">

    <b-pagination   align="center"  size="md"  v-model="page" :limit="10" :total-rows="this.total" :per-page="10" @input="getOrders(page)"></b-pagination>
    <br>
</div>
<div v-if="this.$store.state.user.type=='waiter' && showingPreparedOrders == false">

    <b-pagination  align="center"  size="md"  v-model="page" :limit="10" :total-rows="this.total" :per-page="10" @input="getOrders(page)"></b-pagination>
    <br>
</div>
<div v-if="this.$store.state.user.type=='waiter' && showingPreparedOrders == true" >

    <b-pagination  align="center" size="md"  v-model="page" :limit="10" :total-rows="this.total" :per-page="10" @input="getPreparedOrders(page)"></b-pagination>
    <br>
</div>
<div  v-if="this.$store.state.user.type=='cook' ">

    <b-pagination  size="md"  v-model="page"  align="center" :limit="10" :total-rows="this.total" :per-page="10" @input="getOrders(page)"></b-pagination>
    <br>
</div> -->


</div>
</template>

<script>
  module.exports = {
    data: function(){
      return {
        title:'Orders',
        orders: [],
        preparedOrders: [],
        meals: [],
        items: [],
        currentOrder: {},
        showingOrders: true,
        showingAddOrders: false,
        showingPreparedOrders: false,
        showSuccess: false,
        showFailure: false,
        successMessage: '',
        failMessage: '',
        // page:0,
        // last:0,
        // total:0,
      }
  },
  methods: {
        // getOrdersP(pageC)
        // {
        //   this.page= pageC;
        //   this.getOrders(this.page);
        // },
        // getPreparedOrdersP(pageC)
        // {
        //   this.page= pageC;
        //   console.log(pageC);
        //   this.getPreparedOrders(this.page);
        // },
         getOrders() { //getOrders(page)
         //   http://projeto.dad/api/userorders/6?page=2"

              axios.get('api/userorders/'+this.$store.state.user.id)
              .then(response=>{

              this.orders = response.data.data;
              // this.page = response.data.meta.current_page;
              // this.last = response.data.meta.last_page;
              // this.total = response.data.meta.total;
            // console.log(response);
            //   console.log(this.total);
            //   console.log(this.last);
            //   console.log(this.page);
                }).catch(error=>{

              this.showFailure = true;
              this.failMessage = 'Error while fetching the existing orders!';
                        // this.showingPreparedOrders=false;

          });

          // this.showingPreparedOrders=false;


			   /* axios.get('api/users?page='+page)
				  .then(response => {
            console.log(response.data.meta.total);
            this.orders = response.data.data;
            this.page = response.data.meta.current_page;
            this.last = response.data.meta.last_page;

            this.total = response.data.meta.total;
            console.log("current " + this.page);
            console.log(this.last);
            console.log(this.total); */


        },

          getPreparedOrders()
          {

            axios.get('/api/getpreparedorders/'+this.$store.state.user.id)
            .then(response=>{
              this.preparedOrders = response.data.data;
              // this.page = response.data.meta.current_page;
              // this.last = response.data.meta.last_page;
              // this.total = response.data.meta.total;
              // console.log(response);
              // console.log(this.total);
              // console.log(this.last);
              // console.log(this.page);
            }).catch(error=>{
              // console.log(error);
              this.showFailure = true;
              this.failMessage = 'Error while fetching the existing prepared orders!'
            });
            // this.showingPreparedOrders=true;

          },

        getMeals()  {
          axios.get('/api/usermeals/'+this.$store.state.user.id).then(response=>{
            meals = response.data.data;
            if(this.$store.state.user.type == 'waiter'){
              for (var i = 0; i < meals.length; i++){
                if(meals[i].responsible_waiter_id == this.$store.state.user.id && meals[i].state == 'active')
                  this.meals.push(meals[i]);
              }
            }else{
              this.meals = meals;
            }
          }).catch(error=>{
            console.log(error);
            this.showFailure = true;
            this.failMessage = 'Error while fetching the existing meals!'
          });
        },

        getItems(){
          axios.get('/api/allItems').then(response=>{
            this.items = response.data.data;
          }).catch(error=>{
            this.showFailure = true;
            this.failMessage = 'Error while fetching the existing items!'
          });
        },


    showAddOrder: function(){
      this.showingOrders = false,
      this.currentOrder = {};
      this.showingAddOrders = true;
    },
    cancelAddOrder: function(){
      this.showingAddOrders = false;
      this.showingOrders = true;
    },
    showPreparedOrders: function(){
      this.showingPreparedOrders = true;
      this.showingOrders = false;
      if(this.preparedOrders.length == 0){
        this.getPreparedOrders();
      }
      this.showingOrders = true;
    },
    cancelShowPreparedOrders: function(){
      this.showingOrders = false;
      this.showingPreparedOrders = false;

      this.getOrders();

      this.showingOrders = true;

    },
    addOrder: function(order){
      axios.post('api/orders', order).then(response=>{
        this.showSuccess = true;
        this.successMessage = "Order added with success!";
        this.cancelAddOrder();
        axios.get('/api/userorders/'+this.$store.state.user.id).then(response=>{
          this.orders = response.data.data;

        }).catch(error=>{
          this.showFailure = true;
          this.failMessage = 'Error while fetching the existing orders!'
        });
        setTimeout(()=>{
                axios.put('/api/orderstate/'+response.data.id).then(response=>{
                  axios.get('/api/userorders/'+this.$store.state.user.id).then(response=>{
                    this.orders = response.data.data;
                  }).catch(error=>{
                    this.showFailure = true;
                    this.failMessage = 'Error while fetching the existing orders!'
                  });
                  this.$socket.emit('order_changed', response.data.data);
                }).catch(error=>{
                  this.showSuccess = false;
                  this.successMessage = '';
                  this.failMessage = 'Error while fetching existing orders'
                  this.showFailure = true;
                });
            }, 5000);
      }).catch(error=>{
        this.showFailure = true;
        this.failMessage = "Error while creating a new order";
      });
    },
    deleteOrder: function(order, index){
      if(order.state=='pending'){
        axios.delete('/api/orders/' + order.id)
        .then(response=>{
            this.successMessage = 'Order deleted with success!';
            this.showSuccess=true;
            this.orders.splice(index,1);
            this.getOrders();
        }).catch(error =>{
          this.failMessage = "Error while deliting the order!";
          this.showFailure=true;

        });
      }else{
        this.failMessage = "Cannot remove orders that are not 'pending'!";
        this.showFailure=true;
      }
    },
    takeOrder: function(order, userid){
        axios.put('/api/takeorder/'+userid, order).then(response=>{
          this.showSuccess = true;
          this.successMessage = 'This order belongs to you now!'
          this.getOrders();
        }).catch(error=>{
          this.showFailure = true;
          this.failMessage = 'Error while associating this order to you!'
        });
    },
    changeOrderState: function(order, index){
        axios.put('/api/orderstate/'+order.id).then(response=>{
          this.showSuccess = true;
          this.successMessage = 'Order state changed successfuly!'
          this.getOrders();
          this.getPreparedOrders();
        }).catch(error=>{
          this.showFailure = true;
          this.failMessage = 'Error while changing order state!'
        });
    }
  },
  mounted() {


    this.getPreparedOrders();
    this.getMeals();
    this.getItems();
    this.getOrders();
   /* axios.get('/api/meals').then(response=>{
      meals = response.data.data;
      if(this.$store.state.user.type == 'waiter'){
        for (var i = 0; i < meals.length; i++){
            if(meals[i].responsible_waiter_id == this.$store.state.user.id && meals[i].state == 'active')
                this.meals.push(meals[i]);
        }
      }else{
        this.meals = meals;
      }
    }).catch(error=>{
      this.showFailure = true;
      this.failMessage = 'Error while fetching the existing meals!'
    });

    axios.get('/api/items').then(response=>{
      this.items = response.data.data;
    }).catch(error=>{
      this.showFailure = true;
      this.failMessage = 'Error while fetching the existing items!'
    });*/
    // console.log("prepared "+this.preparedOrders);
    // console.log("order"+this.orders);
  }
}
</script>
