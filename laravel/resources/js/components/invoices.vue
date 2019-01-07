<template>
  <div>
    <div class="jumbotron">
        <h1 align="center">{{title}}</h1>
    </div>

      <div class="container">
      <button class="btn btn-primary" @click.prevent="getInvoicesPending(1)" v-if="showingInvoices" >Pending Invoices</button>
      <button class="btn btn-primary" @click.prevent="getInvoicesNotPaid(1)" v-if="showingInvoices" >Not Paid Invoices</button>
      <button class="btn btn-primary" @click.prevent="getInvoicesPaid(1)" v-if="showingInvoices" >Paid Invoices</button>

        <div class=" alert" v-bind:class="{'alert-success':showSuccess, 'alert-danger':showFailure}" v-if="showSuccess || showFailure">
            <button type="button" @click = "showSuccess = false; showFailure = false;" class="close-btn" >&times;</button>
            <strong>{{successMessage}}</strong>
            <strong>{{failMessage}}</strong>
        </div>
        <invoice-list :invoices="invoices" v-if="showingInvoices" :user="this.$store.state.user" @view-invoice="viewInvoiceDetails" @edit-invoice="showEditInvoice" @close-invoice="closeInvoice"></invoice-list>
        <invoice-details :currentInvoice="currentInvoice" :invoiceItems="invoiceItems" v-if="showingInvoiceDetails" @back="cancelViewDetails"></invoice-details>
        <invoice-edit :currentInvoice="currentInvoice" v-if="showEditingInvoice" @edit-invoice="editInvoice" @cancel-edit="cancelEditInvoice"></invoice-edit>
      </div>


    </div>
</template>

<script>
module.exports = {
  data: function(){
    return {
      title: 'Invoices',
      invoices: [],
      currentInvoice: {},
      invoiceItems:{},
      showingInvoices: true,
      showEditingInvoice: false,
      showingInvoiceDetails: false,
      showSuccess: false,
      showFailure: false,
      successMessage: '',
      failMessage: '',
      total:0,
      page:0,
      lastPage:0,
    }
  },
  methods: {
    getInvoicesPaid(page)
    {
      axios.get('/api/invoices/paid').then(response=>{
      this.invoices = response.data.data;
      this.page = response.data.meta.current_page;
      this.last = response.data.meta.last_page;
      this.total = response.data.meta.total;

    }).catch(error=>{
      this.failMessage = 'Error while fetching all pending invoices';
      this.showFailure = true;
    })
    },

    getInvoicesPending(page)
    {
      axios.get('/api/invoices/pending').then(response=>{
      this.invoices = response.data.data;
      this.page = response.data.meta.current_page;
      this.last = response.data.meta.last_page;
      this.total = response.data.meta.total;

    }).catch(error=>{
      this.failMessage = 'Error while fetching all pending invoices';
      this.showFailure = true;
    })
    },

    getInvoicesNotPaid(page)
    {
        axios.get('/api/invoices/notpaid').then(response=>{
        this.invoices = response.data.data;
        this.page = response.data.meta.current_page;
        this.last = response.data.meta.last_page;
        this.total = response.data.meta.total;

      }).catch(error=>{
        this.failMessage = 'Error while fetching all pending invoices';
        this.showFailure = true;
      })
    },

    viewInvoiceDetails: function(invoice){
      this.currentInvoice = invoice;
      axios.get("/api/invoiceItems/"+invoice.id).then(response=>{
        this.invoiceItems = response.data.data;
        this.showingInvoices = false;
        this.showingInvoiceDetails = true;
      }).catch(error=>{
        this.failMessage = 'Error while fetching invoice items for the selected invoice!';
        this.showFailure = true;
      });
    },
    cancelViewDetails: function(){
      this.showingInvoiceDetails = false;
      this.showingInvoices = true;
    },
    showEditInvoice: function(invoice){
      this.currentInvoice = invoice;
      this.currentInvoice.name = '';
      this.showingInvoices = false;
      this.showingInvoiceDetails = false;
      this.showEditingInvoice = true;
    },
    cancelEditInvoice: function(){
      this.showEditingInvoice = false;
      this.showingInvoiceDetails = false;
      this.showingInvoices = true;
    },
    editInvoice: function(invoice){
      axios.put('/api/invoices/'+invoice.id, invoice).then(response=>{
        this.failMessage = '';
        this.showFailure = false;
        this.successMessage = 'Invoice Edited with success!';
        this.showSuccess = true;
        this.cancelEditInvoice();
      }).catch(error=>{
        this.successMessage = '';
        this.failMessage = 'Error while editing invoice! (Name: must contain only letters and spaces | NIF: must contain exactly 9 digits)';
        this.showSuccess = false;
        this.showFailure = true;
      })
    },
    closeInvoice: function(invoice, index){
      if(invoice.name != 'Not assigned' && invoice.nif != 'Not assigned'){
        axios.put("/api/closeInvoice/"+invoice.id).then(response=>{
          this.failMessage = '';
          this.successMessage = 'Invoice closed with success!';
          this.showFailure = false;
          this.showSuccess = true;
          this.invoices.splice(index, 1);
          this.$socket.emit('paidMealNotification', msg);

          this.getInvoicesPending();
        }).catch(error=>{
          this.failMessage = 'Error while closing the invoice!';
          this.successMessage = '';
          this.showSuccess = false;
          this.showFailure = true;
        });
      }else{
        this.failMessage = 'You must fill the name and the nif before closing the invoice!';
        this.successMessage = '';
        this.showSuccess = false;
        this.showFailure = true;
      }

    }
  },
  mounted(){
    //http://projeto.dad/api/invoices?page=1
    this.getInvoicesPending(1);
  }
}
</script>

<style lang="css">
</style>
