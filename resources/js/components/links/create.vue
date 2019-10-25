<template>
	<div>
		<h4>
			Add Link
		</h4>
		<br><hr>

		<div class="row" v-if="link">
			<div class="col-sm-12">
				<div class="row">

					<div class="col-sm-6 form-group">
						<label for="name">Name:</label>
						<input type="text" class="form-control" v-model="link.name">
					</div>

					<div class="col-sm-6 form-group">
						<label for="name">URI:</label>
						<input type="text" class="form-control" v-model="link.url">
					</div>

					<div class="col-sm-12 form-group">
						<label for="name">Description:</label>
						<textarea type="text" class="form-control" v-model="link.description" rows="3"></textarea>
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
			this.setTitleDocument('Create Link')
		},

		data(){
			return {
				link: {
					name: '',
					url: '',
					description: '',
				}
			}
		},

		methods: {
			save(){
				if (this.link.name == '') {
					this.swal('Please Provide an name');
					return;
				}

				if (this.link.url == '') {
					this.swal('Please Provide an uri');
					return;
				}

				let urlTo = `${this.apiPath}/storeLink`;

				let l = {
					name: this.link.name,
					url: this.link.url,
					description: this.link.description,
				}

				var form = new FormData;

				form.append('name', l.name)
				form.append('url', l.url)
				form.append('description', l.description)
				let me = this;

				axios.post(urlTo, form)
					.then(response => {
						me.swal(response.message, 'Success!', 'success');

						setTimeout(function(e){
							window.location.reload()
						}, 300);
					})
					.catch(err => console.log(err))
			}
		}
	}
</script>
