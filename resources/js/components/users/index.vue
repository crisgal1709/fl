<template>
	<div>
		<div class="row">

			<div class="col-sm-12 form-group">
				<div class="row">
					<div class="col-sm-6">
						<input type="text" class="form-control" v-model="search" placeholder="Search">
					</div>
					<div class="col-sm-6">
						<div class="pull-right text-right">
							<router-link to="/users/add" class="btn btn-success btn-xl">
								Add <i class="fa fa-plus"></i>
							</router-link>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-12 form-group">
				<p class="text-center" v-if="loading">
					<i class="fa fa-cog fa-spin fa-3x fa-fw"></i> Loading...
				</p>
				<p v-if="!users.length && !loading" style="font-size: 32px;" class="text-center">
					<i class="fa fa-ban"></i> Not Data...
				</p>
			</div>

			<div class="clearfix"></div>
			<hr>

			<div class="col-sm-6 col-md-6 col-xs-12 py-2" v-for="user in users">
				<div class="card item-link" :key="user.id">
					<div class="card-header">
						<b>
							<a target="_blank" :href="user.url" :title="user.url">
								{{ user.name }}
							</a>
							<hr>
						</b>
					</div>

					<div class="card-body">
						<p class="card-text">
							{{ user.email }}
						</p>

						<p class="card-text">
							<i class="fa fa-calendar"></i>
							{{ createdAt(user) }}
						</p>

						<p class="card-text">
							<i class="fa fa-link"></i>
							<b>Total Links: </b> {{ formatTotalLinks(user) }}
						</p>

						<div class="row">
							<div class="col-sm-6 col-xs-12">
								<router-link :to="{name: 'users-edit', params: {id: user.id}}" title="Edit" class="btn btn-outline-info form-control">
									<i class="fa fa-edit"></i>
								</router-link>
							</div>

							<div class="col-sm-6">
								<button title="Delete" class="btn btn-outline-danger form-control" @click="deleteLink(link)">
									<i class="fa fa-trash"></i>
								</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="col-sm-12" v-if="paginator">
      <br />
      <br />
      <nav aria-label="Page navigation example">
        <paginate
          :page-count="paginator.lastPage"
          :click-handler="paginateHandler"
          :prev-text="'Anterior'"
          :next-text="'Siguiente'"
          :container-class="'pagination justify-content-center'"
          :page-class="'page-item'"
          :page-link-class="'page-link'"
          :prev-class="'page-item'"
          :prev-link-class="'page-link'"
          :next-class="'page-item'"
          :next-link-class="'page-link'"
        ></paginate>
      </nav>
    </div>
	</div>
</template>

<script>
	export default {
		name: 'Users',
		mounted(){
			this.getUsers();
			this.setTitleDocument('Users')
		},

		watch: {
			search: function(val, oldval) {
				this.page = 1;
				this.getLinks();
			}
		},

		data(){
			return {
				users: [],
				page: 1,
				search: '',
				paginator: null,
				loading: true,
			}
		},

		methods: {
			getUsers(){
				this.loading = true
				var urlTo = `/user?page=${this.page}`;

				if (this.search.trim() != "") {
					urlTo += `&search=${this.search.trim()}`;
				}

				let me = this;
				axios.get(urlTo)
				.then(response => {
					me.loading = false;
					me.users = response.data.items;
					delete response.data.items;
					me.paginator = response.data;
					me.loading = false;
				})
				.catch(err => {
					me.loading = false;
				})
			},

			deleteUser(link){
				if (!confirm('Are you sure?')) {
					return;
				}

				let urlTo = `/user/delete/${link.id}`;
				var me = this;

				axios.post(urlTo)
				.then(response => {
					me.swal('User deleted.', 'Deleted', 'success');
					this.getUsers();
				})
				.catch(err => console.log(err))
			},

			createdAt(user){
				return moment(user.created_at).format('LLL')
			},

			formatTotalLinks(user){
				if (user.total_links) {
					return parseInt(user.total_links)
				}

				return 0;
			},

			paginateHandler(pagenum) {
				this.page = pagenum
				this.getUsers()
				this.animateScroll()
			},
		}
	}
</script>
