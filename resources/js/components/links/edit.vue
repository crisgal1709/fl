<template>
	<div>
		<h4 v-if="original">
			Edit {{ original.name }}
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
	name: 'Links-Edit',
	mounted(){
		this.setTitleDocument('Edit Link')
	},

	watch: {
		'$route.params.id': {
			handler: function(id){
				this.getLink(id)
			},
			deep: true,
        	immediate: true
		}
	},

	data(){
		return {
			link: null,
			original: null,
			edit: null,
		}
	},

	methods: {
		getLink(id){
			var urlTo = `${this.apiPath}/getLink/${id}`;
			let me = this;

			axios.get(urlTo)
			.then(response => {
				me.link = response.data.link
				const copy = JSON.parse(JSON.stringify(response.data.link))
				me.original = copy;
			})
			.catch(err => console.log(err))
		},

		save(){
			if (this.link.name == '') {
				this.swal('Please Provide an name');
				return;
			}

			if (this.link.url == '') {
				this.swal('Please Provide an uri');
				return;
			}

			let urlTo = `${this.apiPath}/updateLink/${this.link.id}`;

			let l = {
				id: this.link.id,
				name: this.link.name,
				url: this.link.url,
				description: this.link.description,
			}

			var form = new FormData;

			form.append('id', l.id)
			form.append('name', l.name)
			form.append('url', l.url)
			form.append('description', l.description)
			let me = this;

			axios.post(urlTo, form)
				.then(response => {
					let st = response.data.status;

					if (st == 0) {
						var e = 'error';
						var d = 'Error!'
					} else {
						var e = 'success';
						var d = 'Success!'
					}

					me.swal(response.data.message, d, e);

					me.link = response.data.link
					const copy = JSON.parse(JSON.stringify(response.data.link))
					me.original = copy;
				})
				.catch(err => console.log(err))
		}
	}
}
</script>
