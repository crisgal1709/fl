<template>

	<div class="row">
		<div class="col-sm-12">
			<h4 class="text-center mb-10" >
						History
			</h4>

			<div class="row">
				<div class="col-sm-4">
					<button class="btn btn-success btn-xl form-control" @click="clearTodos()" v-show="filteredTodos.length > 0">
						<i class="fa fa-trash"></i> Clear
					</button>

					<h4 v-show="filteredTodos.length == 0" class="text-center">
						No Data
					</h4>
				</div>
			</div>
			<br>
		</div>

		<hr>

		<div class="col-sm-12">
			<ul class="list-group">
				<li class="list-group-item" v-for="todo in filteredTodos" :key="todo.id">
					<div class="row">
						<div class="col-sm-9">
							{{ todo.text }}
						</div>

						<div class="col-sm-3">
							{{ formatDate(todo) }}
						</div>
					</div>
				</li>
			</ul>
		</div>

	</div>

</template>

<script>
	import {mapActions} from 'vuex'
	import moment from 'moment';
	export default {
		mounted(){
			this.setTitleDocument('History - To-do')
			this.fetchHistory()
		},

		data(){
			return {
			}
		},

		methods: {
			...mapActions([
				'fetchHistory',
				'clearTodos',
			]),

			formatDate(todo){
				return moment(todo.updated_at).format('LLL')
			}
		},

		computed: {
			filteredTodos(){
				return this.$store.state.todos
			}
		}
	}
</script>
