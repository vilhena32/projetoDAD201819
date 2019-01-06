<template>
  <div class="container">
    <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" v-model="currentInvoice.name">
    </div>
    <div class="form-group">
        <label>NIF</label>
        <input type="number" class="form-control" v-model="currentInvoice.nif">
    </div>
    <button v-on:click="submit(currentInvoice)" class="btn btn-xs btn-success" title="Edit Invoice">Edit</button>
    <button v-on:click="cancel()" class="btn btn-xs btn-warning" title="cancel">Cancel</button>
  </div>
</template>

<script>
export default {
  props: ["currentInvoice"],
  // data: function(){
  //   return {
  //     showFailure: false,
  //     showSuccess: false,
  //     failMessage: '',
  //     successMessage: ''
  //   }
  // },
  methods: {
    submit: function(currentInvoice){
      this.$emit('edit-invoice', currentInvoice);
    },
    cancel: function(){
      this.$emit('cancel-edit');
    }
  }
}
</script>

<style lang="css">
</style>
