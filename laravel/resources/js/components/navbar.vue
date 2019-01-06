<template>
  <nav class="navbar navbar-expand-lg navbar-dark default-color">
  <router-link  class="navbar-brand"  to="/ementa">Restaurant Management</router-link>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
     aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
   </button>
   <div class="collapse navbar-collapse" id="navbarNav">
     <ul class="navbar-nav">
       <li class="nav-item active">
         <router-link class="nav-link" to="/ementa">Menu</router-link>
       </li>
       <li class="nav-item">
         <router-link class="nav-link" to="/users">Employess</router-link>
       </li>
       <li class="nav-item" v-if="user.type == 'manager' && user != null">
         <router-link class="nav-link" to="/tables" >Tables</router-link>
       </li>
       <li class="nav-item">
         <router-link class="nav-link" to="/orders">Orders</router-link>
       </li>
       <li class="nav-item">
         <router-link class="nav-link" to="/meals">Meals</router-link>
       </li>
       <li class="nav-item">
         <router-link class="nav-link" to="/invoices">Invoices</router-link>
       </li>


     </ul>


   </div>
   <span class="navbar-text white-text">
       <ul class="navbar-nav">
         <li class="nav-item">
           <router-link class="nav-link" to="/login" v-show="!user">Login</router-link>
           {{-- <router-link class="nav-link" to="/login">Login</router-link> --}}
           <router-link class="nav-link" to="/logout" v-show="user">Logout <span>User: @{{user != null ? user.name : "" }}</span> </router-link>
         </li>
         <li class="nav-item">
         <!-- <router-link class="nav-link" to="/register">Sign Up</router-link> -->
       </li>
       </ul>
   </span>
  </nav>
</template>

<script>
module.exports={
  props: ["user"]
}
</script>

<style lang="css">
</style>
