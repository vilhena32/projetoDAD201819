<template>
  <table class="table table-striped">
      <thead>
          <tr>
              <th>State</th>
              <th>Responsible waiter</th>
              <th>Table number</th>
              <th>Total price</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
          <tr v-for="(invoice,index) in invoices" v-if=" user.type == 'cashier' || user.type == 'manager' " :key="invoice.id" >
              <td>{{invoice.state}}</td>
              <td>{{invoice.user}}</td>
              <td>{{invoice.table_number}}</td>
              <td>{{invoice.total_price}}</td>
              <td>
                  <button v-on:click="closeInvoice(invoice, index)" class="btn btn-xs btn-success" title="Terminate Invocie"><i class="fas fa-check"></i></button>
                  <button v-on:click="editInvoice(invoice)" class="btn btn-xs btn-primary" title="edit invoice"><i class="fas fa-pencil-alt"></i></button>
                  <button  v-on:click.prevent="viewInvocie(invoice)" class="btn btn-xs btn-primary" title="View invoice details"><i class="far fa-eye"></i></button>
              </td>
          </tr>
      </tbody>
  </table>
</template>

<script>

module.exports = {

  props:["invoices", "user"],
  methods: {

    viewInvocie: function(invoice){
      this.$emit('view-invoice', invoice);
    },
    editInvoice: function(invoice){
      this.$emit('edit-invoice', invoice);
    },
    closeInvoice: function(invoice, index){
      this.$emit('close-invoice', invoice, index);
    }
  }
}
</script>

<style lang="css">
</style>
