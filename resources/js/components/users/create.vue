<template>
		<div>
		<h4>
			New User
		</h4>
		<br><hr>

		<div class="row" v-if="newUser">
			<div class="col-sm-12">
				<div class="row">

					<div class="col-sm-12 form-group">
						<label for="name">Name:</label>
						<input type="text" class="form-control" v-model="newUser.name">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">Email:</label>
						<input type="email" class="form-control" v-model="newUser.email">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">
							Password:
						</label>
						<input type="password" v-model="newUser.password" class="form-control">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">
							Rol:
						</label>
						<select v-model="newUser.rol" class="form-control">
							<option value="admin">Admin</option>
							<option value="user">User</option>
						</select>
					</div>

					<div class="col-sm-12 form-group">
						<center>
							<button class="btn btn-success btn-lg" @click="save()">
								Save
							</button>
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'newUser',
		mounted(){
			this.setTitleDocument('Add User');
			this.setNew();
		},

		data(){
			return {
				newUser: [],
				newUserPath: '/user/store',
			}
		},

		methods: {
			save(){
				var p = {
					name: this.newUser.name,
					email: this.newUser.email,
					password: this.newUser.password,
					rol: this.newUser.rol,
				}

				if (p.name == '') {
					this.swal('Please Provide an Name');
					return;
				}

				if (p.email == '') {
					this.swal('Please Provide an Email');
					return;
				}

				if (p.password == '') {
					this.swal('Please Provide an Password');
					return;
				}

				var form = new FormData;
				form.append('name', p.name)
				form.append('email', p.email)
				form.append('password', p.password)
				form.append('rol', p.rol)

				let urlTo = this.newUserPath;
				let me = this;

				axios.post(urlTo, form)
					.then(response => {
						var res = response.data;
						if (res.status == 1) {
							this.setNew();
							this.swal(res.message, 'Success!', 'success');
						} else {
							this.swal(res.message, 'Error!', 'error');
						}
					})
					.catch(err => {
						console.log(err)
					})
			},

			setNew(){
				this.newUser = {
					name: '',
					email: '',
					password: '',
					rol: 'user',
				}
			}
		}
	}
</script>
