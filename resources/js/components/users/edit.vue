<template>
		<div>
		<h4>
			Edit {{ original.name }}
		</h4>
		<br><hr>

		<div class="row" v-if="editUser">
			<div class="col-sm-12">
				<div class="row">

					<div class="col-sm-12 form-group">
						<label for="name">Name:</label>
						<input type="text" class="form-control" v-model="editUser.name">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">Email:</label>
						<input type="email" class="form-control" v-model="editUser.email">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">
							Password:
						</label>
						<small>
								(Leave blank if not change)
							</small>
						<input type="password" v-model="editUser.password" class="form-control">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">
							Rol:
						</label>
						<select v-model="editUser.rol" class="form-control">
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
		mounted(){
			//console.log('Mounted Edit');
		},

		watch: {
			'$route.params.id': {
				handler: function(id){
					this.getUser(id)
				},
				deep: true,
				immediate: true
			}
		},

		data(){
			return {
				editUser: {},
				original: {},
				editUserPath: '/user/update'
			}
		},

		methods: {
			getUser(id){
				var urlTo = `/user/get/${id}`;
				let me = this;

				axios.get(urlTo)
					.then(response => {
						this.editUser = response.data;
						this.editUser.password = '';
						const copy = JSON.parse(JSON.stringify(response.data))
						me.original = copy;
					})
					.catch(err => {
						console.log(err)
					})
			},

			save(){
				var p = {
					name: this.editUser.name,
					email: this.editUser.email,
					password: this.editUser.password,
					rol: this.editUser.rol,
				}

				if (p.name == '') {
					this.swal('Please Provide an Name');
					return;
				}

				if (p.email == '') {
					this.swal('Please Provide an Email');
					return;
				}

				var form = new FormData;
				form.append('name', p.name)
				form.append('email', p.email)
				form.append('password', p.password)
				form.append('rol', p.rol)

				let urlTo = `${this.editUserPath}/${this.editUser.id}`;
				let me = this;
				let id = this.editUser.id;

				axios.post(urlTo, form)
					.then(response => {
						if (response.data.status == 1) {
							this.swal(response.data.message, 'Success!', 'success');
						} else {
							this.swal(response.data.message, 'Error!', 'error');
						}
						this.getUser(id)
					})
					.catch(err => {
						console.log(err)
					})
			}
		}

	}
</script>
