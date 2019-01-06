require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import store from './stores/global-store';
import VueSocketio from 'vue-socket.io';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
Vue.prototype.$jsPDF = jsPDF;
//const VueRouter = require('vue-router');
Vue.use(VueRouter);

Vue.use(new VueSocketio({
  debug: true,
  connection: 'http://192.168.10.10:8080'
}));

import Toasted from 'vue-toasted';

 Vue.use(Toasted, {
   position: 'bottom-center',
   duration: 5000,
   type: 'info',
 });

 import bPagination from 'bootstrap-vue/es/components/pagination/pagination';

 Vue.component('b-pagination', bPagination);

//main
const navbarComponent = Vue.component('navbar', require('./components/navbar'));

//auth
const resetPasswordComponent = Vue.component('resetPassword', require('./components/auth/resetPassword'));

//items
const ementa = Vue.component('ementa', require('./components/ementa'));
const itemComponent = Vue.component('item-list', require('./components/items/ementaList'));
const addItemComponent = Vue.component('item-add', require('./components/items/addItem'));
const editItemComponent = Vue.component('item-edit', require('./components/items/editItem'));
//users

const registerUserComponent = Vue.component('registeruser', require('./components/users/RegisterUser'));
const users = Vue.component('users', require('./components/users'));
const userListComponent = Vue.component('user-list', require('./components/users/userList'));
const editUserComponent = Vue.component('user-edit', require('./components/users/editUser'));
const addUserComponent = Vue.component('user-add', require('./components/users/addUser'));
const showProfileComponent = Vue.component('user-show', require('./components/users/showProfile'));
//orders
const orders = Vue.component('orders',require('./components/orders'));
const orderListComponent = Vue.component('order-list', require('./components/orders/orderList'));
const addOrderComponent = Vue.component('add-order', require('./components/orders/addOrder'));
//tables
const tables = Vue.component('tables', require('./components/tables'));
const listTablesComponent = Vue.component('table-list', require('./components/tables/tableList'));
const addTableComponent = Vue.component('table-add', require('./components/tables/addTable'));
//auth
const login  = Vue.component('login', require('./components/auth/login'));
const logout  = Vue.component('logout', require('./components/auth/logout'));
//Meals
const meals = Vue.component('meals', require('./components/meals'));
const listMealsComponent = Vue.component('meal-list', require('./components/meals/mealList'));
const addMealsComponent = Vue.component('meal-add', require('./components/meals/addMeal'));
const mealDetailsComponent = Vue.component('meal-details', require('./components/meals/mealDetails'));
//invoices
const invoices = Vue.component('invoices', require('./components/invoices'));
const invoiceListComponent = Vue.component('invoice-list', require('./components/invoices/invoiceList'));
const invoiceDetailsComponent = Vue.component('invoice-details', require('./components/invoices/invoiceDetails'));
const invoiceEditComponent = Vue.component('invoice-edit', require('./components/invoices/editInvoice'));
//shifts
const shifts = Vue.component('shifts', require('./components/users/shifts'));

const routes = [
  {path:'/', redirect:'/ementa'},
  {path:'/ementa', component:ementa},
  {path:'/listementa', component:itemComponent},
  {path:'/addItem', component:addItemComponent},
  {path:'/editItem', component:editItemComponent},
  {path:'/users', component:users},
  {path:'/userlist', component:userListComponent},
  {path:'/edituser', component:editUserComponent,
  beforeEnter (to,from, next)
  {
    if(store.state.user)
    {
      next();
    }else
    {
      next('/login');
    }
  }

  },
  {path:'/adduser', component:addUserComponent},
  {path:'/orders', component:orders},
  {path:'/tables', component:tables},

  {path: '/register',component:registerUserComponent},

  {path: '/login', component:login},
  {path: '/logout', component:logout,
  beforeEnter (to,from, next)
    {
      if(store.state.user)
      {
        next();
      }else
      {
        next('/login');
      }
    }

  },

  {path: '/meals', component:meals},
  {path: '/invoices', component:invoices},
  {path: '/shifts', component:shifts,
  beforeEnter (to,from, next)
    {
      if(store.state.user)
      {
        next();
      }else
      {
        next('/login');
      }
    }
  },
  {path: '/resetpassword/:code', component:resetPasswordComponent,
  beforeEnter (to,from, next)
  {
    if(store.state.user)
    {
      next();
    }else
    {
      next('/login');
    }
  }
  },

  {
    path: '/myprofile',
    component:showProfileComponent},
];

const router = new VueRouter({
  routes: routes //ou apenas routes, pois a propriedade tem o mesmo nome da variavel. case a const routes fosse routes2 entao seria routes: routes2
});

router.beforeEach((to, from, next) => {
    if ((to.name == 'profile') || (to.name == 'logout')) {
        if (!store.state.user) {
            next("/login");
            return;
        }
    }
    next();
});

const app = new Vue({
    el: '#app', // qual o elemento que o vue vai substituir
    store,
    router: router,
    jsPDF,
    sockets: {
      connect(){
          console.log('socket connected (socket ID = '+this.$socket.id+')');
      },
      order_changed(dataFromServer){
          this.$toasted.show('Order with: ID= ' + dataFromServer.id + ') has changed');
      },
    },
    created(){
      this.$store.commit('loadTokenAndUserFromSession');
    },
    mounted(){
      this.$store.commit('loadTokenAndUserFromSession');
      console.log(jsPDF);
    }
   
}).$mount('#app');
