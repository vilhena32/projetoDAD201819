<template>
  <div class="container">
    <div>
      <label>Name: <span>{{currentInvoice.name}}</span></label>
    </div>
    <div>
      <label>NIF: <span>{{currentInvoice.nif}}</span></label>
    </div>
    <div>
      <label>Table number: <span>{{currentInvoice.table_number}}</span></label>
    </div>
    <div>
      <label>Total price: <span>{{currentInvoice.total_price}}€</span></label>
    </div>
    <div>
      <label>Date: <span>{{currentInvoice.date}}</span></label>
    </div>
    <div>
      <label>Waiter: <span>{{currentInvoice.user}}</span></label>
    </div>
    <table id="my-table">
      <thead>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Sub total</th>
      </thead>
      <tbody>
        <tr v-for="(item,index) in invoiceItems" :key="item.id">
          <td>{{item.item_name}}</td>
          <td>{{item.unit_price}}€</td>
          <td>{{item.quantity}}</td>
          <td>{{item.total_price}}</td>
        </tr>
      </tbody>
    </table>
    <button v-on:click="back()" class="btn btn-xs btn-warning" title="Back">Back</button>
    <button v-on:click="download()" class="btn btn-xs btn-primary" title="Download"><i class="fas fa-pencil-alt"></i></button>
  </div>
</template>

<script>
module.exports = {
  props: ["currentInvoice", "invoiceItems"],
  data: function()
  {
  
    
  },
  methods: {
    download()
    {
      let doc = this.$jsPDF();
      doc.autoTable({html: '#my-table'});
      doc.save(this.currentInvoice.id+'.pdf');
      //console.log("download");
  
    },
    back: function(){
      this.$emit('back');
    }
  }
}
</script>

<style lang="css">
</style>
