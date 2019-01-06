<template>
<div>
    <div class="jumbotron">
      <h1 align="center">{{title}}</h1>
    </div>

	<div class = "container">
    	<div class="signup-form">
		<div class="alert" :class="typeofmsg" v-if="showMessage">
            	<button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
            	<strong>{{ message }}</strong>
        	</div>	
			
			<h2>Sign Up</h2>
			
			<p>Please fill in this form to create an account!</p>
			<hr>

			

			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" class="form-control" name="name" placeholder="Name" required="required" v-model="user.name">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" class="form-control" name="username" placeholder="Username" required="required" v-model="user.username">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-paper-plane"></i></span>
					<input type="email" class="form-control" name="email" placeholder="Email Address" required="required" v-model="user.email">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input type="password" class="form-control" name="password" placeholder="Password" required="required" v-model="user.password">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required="required">
				</div>
			</div>

			<div class="form-group">
				<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user-circle-o"></i></span>

				<select class="form-control" v-model="user.type" >
					<option value="" disabled selected>Type</option>
					<option value="manager">Manager</option>
					<option value="waiter">Waiter</option>
					<option value="cook">Cook</option>
					<option value="cashier">Cashier</option>
				</select>
				</div>
			</div>


		<form>
          <div class="form-group">
            <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-default btn-file">
                        Imagem: <input type="file" ref="fileInput" @change="onFileSelected">
                    </span>
                    <!-- <button @click.prevent="onFileSelected">Upload</button> -->
                </span>
            </div>
          </div>
        </form>
			<div class="form-group">
				<button type="submit"  @click="register" class="btn btn-primary btn-lg">Sign Up</button>
			</div>

			<div class="text-center">Already have an account? <a href="/login">Login here</a></div>
		</div>
	</div> 
</div>         
	
</template>

<script>
module.exports=
{
	data : function()
	{
		return{
			user: {
				"name":"",
				"email":"",
				"password":"",
				"username":"",
				"type": ""
			},
			title:'Add Worker',
			typeofmsg: "alert-success",
            showMessage: false,
            message: "",
		}
	},
	methods :
	{
		onFileSelected(event){
        let filex = this.$refs.fileInput.files[0];
        const myFormData = new FormData();
        myFormData.append('file', filex);
        axios.post('api/user_image', myFormData  ,
              {
              headers: {
                  'Content-Type': 'multipart/form-data'
              }
            }).then(response=>{
          this.user.photo_url=response.data;
        }).catch(error=>{
          console.log(error);
        })
      },
		register()
		{
			
			axios.post('api/register',this.user)
			.then(function (response) {
				this.showMessage = true;
				this.typeofmsg = "alert-success";
                this.message = "User registered!";

			})
			.catch(function (error) {
				console.log(error);
				this.typeofmsg = "alert-danger";
                this.message = "Ups.. Something went wrong";
                this.showMessage = true;
			});
			
		},

	}
}
</script>
