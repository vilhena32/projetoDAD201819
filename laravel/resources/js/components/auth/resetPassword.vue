<template>
<div class = "container">
	<div class="jumbtron"></div>
    <div class="signup-form">
		<div class="alert" :class="typeofmsg" v-if="showMessage">
            <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
            <strong>{{ message }}</strong>
        </div>

		<h2>Reset Password</h2>
		<p>Please fill in this form to Reset Password</p>
		<hr>
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-paper-plane"></i></span>
				<input type="email" class="form-control" name="email" placeholder="Email Address" required="required" v-model="user.email">
			</div>
        </div>
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<input type="password" class="form-control" name="password" placeholder="New Password" required="required" v-model="user.password">
			</div>
        </div>


		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<input type="password" class="form-control" name="confirm_password" placeholder="Confirm New Password" required="required" v-model="user.passwordconfirm">
			</div>
        </div>

		
       
		<div class="form-group">
            <button type="submit"   @click="reset(user)" class="btn btn-primary btn-lg">Reset</button>
        </div>
    
	<div class="text-center">Already have an account? <a href="/login">Login here</a></div>
	</div>
</div>   
</template>

<script>
module.exports=
{
	
data : function()
	{
		return{
			code : this.$route.params.code,
					
			user: {
				
				"password":"",
				"passwordconfirm":"",
				"email":""
				
			},
			userAPI:{
				"id":"",
			},
			showMessage: false,
			message: "",
			typeofmsg: "alert-success",
		}
	},

methods:
	{
		getUser()
		{
			axios.get('api/user/' +  this.code)
			.then(function (response) {
				// handle success
				
				this.userAPI= response.data.data;

				})
			.catch(function (error) {
				// handle error
				console.log(error);
			});
		},

		reset(user)	
		{
			if(userAPI.email != user.email)
			{
				this.showMessage=true;
				this.message="Error on the user email";
				this.typeofmsg="alert alert-danger";
				
				return;
			}
			
			axios.put('api/user/password/'+userAPI.id,user)
			.then(function(response)
			{
			
				this.showMessage=true;
				this.message="Password Reseted";
				this.typeofmsg="alert-success";
				
			})
			.catch(function(error)
			{
				console.log(error);
			})
	
		},
	},
beforeMount(){

		this.getUser()
		
},	
}
</script>
