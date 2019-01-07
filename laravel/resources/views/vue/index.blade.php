@extends('master')

@section('title', 'Restaurante')

@section('content')

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
       <router-link class="nav-link" to="/users" v-if="this.$store.state.user != null && this.$store.state.user.type != 'manager'">Managers</router-link>
     </li>
     <li class="nav-item">
       <router-link class="nav-link" to="/users" v-if="this.$store.state.user != null && this.$store.state.user.type == 'manager'">Employess</router-link>
     </li>
     <li class="nav-item">
       <router-link class="nav-link" to="/tables" v-if="this.$store.state.user != null && this.$store.state.user.type == 'manager'">Tables</router-link>
     </li>
     <li class="nav-item">
       <router-link class="nav-link" to="/orders" v-if="this.$store.state.user != null && (this.$store.state.user.type == 'manager' || (this.$store.state.user.type == 'cook' || this.$store.state.user.type == 'waiter'))">Orders</router-link>
     </li>
     <li class="nav-item">
       <router-link class="nav-link" to="/meals" v-if="this.$store.state.user != null && (this.$store.state.user.type == 'manager' || this.$store.state.user.type == 'waiter')">Meals</router-link>
     </li>
     <li class="nav-item">
       <router-link class="nav-link" to="/invoices" v-if="this.$store.state.user != null && (this.$store.state.user.type == 'manager' || this.$store.state.user.type == 'cashier')">Invoices</router-link>
     </li>
     <li>
     <button v-if="this.$store.state.user!= null && this.$store.state.user.shift_active" :v-bind="this.$store.state.user" class="btn btn-secondary" type="button" aria-haspopup="true" aria-expanded="false">
      Shift Started
     </button>
     <button v-if="this.$store.state.user!= null && !this.$store.state.user.shift_active" :v-bind="this.$store.state.user" class="btn btn-secondary" type="button" aria-haspopup="true" aria-expanded="false">
      Shiff Ended
     </button>
     </li>


   </ul>


 </div>
 <span class="navbar-text white-text">
 <ul class="navbar-nav">

 <li class="nav-item">
         <router-link class="nav-link" to="/login" v-show="!this.$store.state.user">Login</router-link>
         {{-- <router-link class="nav-link" to="/login">Login</router-link> --}}
         {{-- <router-link class="nav-link" to="/logout" v-show="this.$store.state.user">Logout <span>User: @{{this.$store.state.user != null ? this.$store.state.user.name : "" }}</span> </router-link> --}}

        </li>
       <li class="nav-item">
       <!-- <router-link class="nav-link" to="/register">Sign Up</router-link> -->
  <div class="dropdown" v-if="this.$store.state.user">
  <button class="btn btn-secondary dropdown-toggle dropdown-toggle-split" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  @{{this.$store.state.user != null ? this.$store.state.user.name : "" }}
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
  <router-link class="dropdown-item" to="/myprofile">Profile</router-link>
    <router-link class="dropdown-item" :to="`/resetpassword/${ this.$store.state.user.remember_token}`">Reset Password</router-link>
    <router-link class="dropdown-item" to="/shifts">Shifts</router-link>
    <div class="dropdown-divider"></div>
    <router-link class="dropdown-item" to="/logout">Logout</router-linka>
  </div>
</div>
     </li>






     </ul>
 </span>
</nav>

<router-view></router-view>

@endsection
@section('pagescript')
  <script src="js/app.js"></script>
@stop
