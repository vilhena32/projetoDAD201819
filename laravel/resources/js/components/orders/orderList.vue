<template lang="html">
<div class="">
  <table class="table table-striped">
      <thead>

          <tr>
              <th>State</th>
              <th>Item</th>
              <th>Responsible cook</th>
              <th>Actions</th>
          </tr>

      </thead>

      <tbody>

            <tr v-for="(order,index) in preparedOrders" v-if=" user.type == 'manager' && showingPreparedOrders == true" :key="order.id">
                <td>{{order.state}}</td>
                <td>{{order.item}}</td>
                <td>{{order.user}}</td>
                <td>
                    <button v-on:click="deliverOrder(order, index)" class="btn btn-xs btn-success" title="Deliver Order"><i class="fas fa-truck"></i></button>
                    <!-- <button v-on:click.prevent="editOrder(order)" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button> -->
                </td>

            </tr>




          <tr v-for="(order,index) in orders" v-if="user.type=='waiter' || user.type=='manager' && showingPreparedOrders == false":key="order.id">
              <td :class="{'text-danger initialism': order.state=='pending'}">{{order.state}}</td>
              <td :class="{'text-danger initialism': order.state=='pending'}">{{order.item}}</td>
              <td :class="{'text-danger initialism': order.state=='pending'}">{{order.user}}</td>
              <td>
                  <button v-on:click="deleteOrder(order, index)" v-if="order.state == 'pending'" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></button>
                  <!-- <button v-on:click.prevent="editOrder(order)" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button> -->
              </td>
          </tr>

          <tr v-for="(order,index) in preparedOrders" v-if="user.type=='waiter' && showingPreparedOrders == true" :key="order.id">
              <td>{{order.state}}</td>
              <td>{{order.item}}</td>
              <td>{{order.user}}</td>
              <td>
                  <button v-on:click="deliverOrder(order, index)" class="btn btn-xs btn-success" title="Deliver Order"><i class="fas fa-truck"></i></button>
                  <!-- <button v-on:click.prevent="editOrder(order)" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button> -->
              </td>
          </tr>


          <tr v-for="(order,index) in orders" v-if="user.type=='cook'":key="order.id">
              <td :class="{'text-danger initialism': order.state=='in preparation'}">{{order.state}}</td>
              <td :class="{'text-danger initialism': order.state=='in preparation'}">{{order.item}}</td>
              <td :class="{'text-danger initialism': order.state=='in preparation'}">{{order.user}}</td>
              <td>
                  <button v-if="order.state == 'in preparation'" v-on:click="declarePrepared(order)" class="btn btn-xs btn-success" title="Declare meal prepared"><i class="fas fa-check"></i></i></button>
                  <button v-if="order.state == 'confirmed'" v-on:click.prevent="takeOrder(order)" class="btn btn-xs btn-primary" title="click to prepare this order"><i class="far fa-hand-paper"></i></button>
              </td>
          </tr>
      </tbody>
  </table>

  </div>
</template>

<script>
  module.exports = {
    props:["orders", "user", "showingPreparedOrders", "preparedOrders"],
    data: function(){
      return {
        currentOrder: null,
        changeType: "",
        tempStyleOrder: null,


      }
    },
    methods: {

      deleteOrder: function(order, index){
        this.currentOrder = null;
        this.$emit('delete-order', order, index);
      },
      declarePrepared: function(order){
        this.currentOrder = order;
        this.$emit('prepare-order', order);
      },
      takeOrder: function(order){
        this.currentOrder = order;
        this.$emit('take-order', order, this.user.id);
      },
      deliverOrder: function(order, index){
        this.currentOrder = order;
        this.$emit('deliver-order', order, index);
      },
      //
      // Real Time
      //
      changeStyleTemp: function(order, type, time_ms){
				this.changeType = "";
				this.tempStyleOrder = null;
				this.changeType = type;
				this.tempStyleOrder = order;
				setTimeout( () => {
					this.changeType = "";
					this.tempStyleOrder = null;
				}, time_ms)
			},
      getChangedOrder: function(orderID){
				for (let idx in this.users) {
					if (this.orders[idx].id == orderID)
						return this.orders[idx];
				}
				return null;
			}
    },
    sockets: {
      order_changed(changedOrder){
      	let refToChangedOrder = this.getChangedOrder(changedOrder.id);
      	if (refToChangedOrder !== null) {
      		Object.assign(refToChangedOrder, changedOrder);
      		this.changeStyleTemp(refToChangedOrder, "changed", 3000);
      	}else{
          this.orders.unshift(changedOrder);
          this.changeStyleTemp(refToChangedOrder, "changed", 3000);
        }
      },

    },
    mounted()
    {
      this.pageC = this.page;

    }
  }
</script>

<style lang="css">
  tr.activerow {
    		background: #123456  !important;
    		color: #fff          !important;
  }
</style>
