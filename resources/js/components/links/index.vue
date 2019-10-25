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
							<router-link to="/links/add" class="btn btn-success btn-xl">
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
				<p v-if="!links.length && !loading" style="font-size: 32px;" class="text-center">
					<i class="fa fa-ban"></i> Not Data...
				</p>
			</div>

			<div class="clearfix"></div>
			<hr>

			<div class="col-sm-6 col-md-6 col-xs-12 py-2" v-for="link in links">
				<div class="card item-link" :key="link.id">
					<div class="card-header">
						<b>
							<a target="_blank" :href="link.url" :title="link.url">
								{{ link.name }}
							</a>
							<hr>
						</b>
					</div>

					<div class="card-body">

						<p style="margin-top: 1px">
							<i class="fa fa-link"></i> {{ link.url }}
						</p>

						<p>
							<i class="fa fa-edit"></i> {{ link.description }}
						</p>

						<div class="row">
							<div class="col-sm-6 col-xs-12">
								<router-link :to="{name: 'links-edit', params: {id: link.id}}" title="Edit" class="btn btn-outline-info form-control">
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
	name: 'Links',
	mounted(){
		console.log(this)
		this.getLinks();
		this.setTitleDocument('Links')
	},

	watch: {
		search: function(val, oldval) {
			this.page = 1;
			this.getLinks();
		}
	},

	data(){
		return {
			links: [],
			page: 1,
			search: '',
			paginator: null,
			loading: true,
		}
	},

	methods: {
		getLinks(){
			this.loading = true
			var urlTo = `${this.apiPath}/links?page=${this.page}`;

			if (this.search.trim() != "") {
				urlTo += `&search=${this.search.trim()}`;
			}

			let me = this;
			axios.get(urlTo)
				.then(response => {
					me.loading = false;
					me.links = response.data.items;
					delete response.data.items;
          			me.paginator = response.data;
          			me.loading = false;
				})
				.catch(err => {
					me.loading = false;
				})
		},

		deleteLink(link){
			if (!confirm('Are you sure?')) {
				return;
			}

			let urlTo = `${this.apiPath}/deleteLink/${link.id}`;
			var me = this;

			axios.post(urlTo)
				.then(response => {
					me.swal('Link deleted.', 'Deleted', 'success');
					this.getLinks();
				})
				.catch(err => console.log(err))
		},

		paginateHandler(pagenum) {
			this.page = pagenum
			this.getLinks()
			this.animateScroll()
		},
	}
}
</script>
