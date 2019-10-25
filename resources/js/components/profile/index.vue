<template>
		<div>
		<h4>
			Profile
		</h4>
		<br><hr>

		<div class="row" v-if="profile">
			<div class="col-sm-12">
				<div class="row">

					<div class="col-sm-12 form-group">
						<label for="name">Name:</label>
						<input type="text" class="form-control" v-model="profile.name">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">Email:</label>
						<input type="email" class="form-control" v-model="profile.email">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">
							Password:
							<small>
								(Leave blank if not change)
							</small>
						</label>
						<input type="password" v-model="profile.password" class="form-control">
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
		name: 'Profile',
		mounted(){
			this.setTitleDocument('Profile'),
			this.setUser();
		},

		data(){
			return {
				// profile: JSON.parse(JSON.stringify(this.user))
				profile: [],
			}
		},

		methods: {
			setUser(){
				var me = this;

				axios.get(`${this.profilePath}/get`)
					.then(response => {
						me.user = response.data,
						me.profile = response.data;
					})
			},

			save(){
				var p = {
					name: this.profile.name,
					email: this.profile.email,
					password: this.profile.password,
				}

				if (p.name == '') {
					this.swal('Please Provide an name');
					return;
				}

				if (p.email == '') {
					this.swal('Please Provide an email');
					return;
				}

				var form = new FormData;
				form.append('name', p.name)
				form.append('email', p.email)
				form.append('password', p.password)

				let urlTo = `${this.profilePath}/update`;

				axios.post(urlTo, form)
					.then(response => {
						console.log(response)
						if (response.data.status == 1) {
							this.user = response.data.user;
							this.setUser()
							this.swal('Profile Updated', 'Success!', 'success');
						} else {
							this.swal(response.data.message, 'Error!', 'error');
						}
					})
					.catch(err => {
						console.log(err)
					})
			}
		}
	}
</script>
