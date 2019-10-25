<template>

	<div class="row">

		<div class="col-sm-12 form-group">
			<div class="col-sm-4">
				<router-link active-class="active" to="/todos/history" class="btn btn-success btn-xl">
					<i class="fas fa-history"></i>
					<span>History</span>
				</router-link>
			</div>
		</div>

		<div class="col-sm-12 form-group">
			<div class="row">
				<div class="col-sm-6">
					<input type="text" class="form-control" v-model="search" placeholder="Search" @key-up.enter="newTodo()">
				</div>
				<div class="col-sm-6">
					<div class="pull-right text-right">
						<a class="btn btn-danger btn-xl" @click="removeCompleted()" v-show="hasCompleted">
							Remove Completed <i class="fa fa-trash"></i>
						</a>
						<a class="btn btn-success btn-xl" @click="newTodo()">
							Add <i class="fa fa-plus"></i>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12" v-if="adding" style="margin-bottom: 1px">
			<div class="row">
				<div class="col-sm-10">
					<input type="text" @keyup.enter="saveTodo()" class="form-control" v-model="todo">
				</div>

				<div class="col-sm-1">
					<button class="btn btn-success" @click="saveTodo()">
						<i class="fa fa-check"></i>
					</button>
				</div>
				<div class="col-sm-1">
					<button class="btn btn-danger" @click="cancelAdd()">
						<i class="fa fa-ban"></i>
					</button>
				</div>
			</div>
			<br>
		</div>

		<div class="col-sm-12" id="contentTodos">

			<ul id="listTodos" class="list-group">
				<todoItem v-for="(todo, index) in filteredTodos" :key="todo.id" :todo="todo"></todoItem>
			</ul>

		</div>
	</div>
</template>

<script>
import todoItem from './item';
import { mapActions } from 'vuex'

export default {

	name: 'Todos',
	mounted(){
		this.setTitleDocument('To-Do')
		this.fetch();
	},

	components: {
		todoItem,
	},

	data(){
		return {
			editing: false,
			edit: {},
			search: '',
			adding: false,
			todo: '',
		}
	},

	computed: {
		todos () {
			return this.$store.state.todos
		},

		filteredTodos(){
			if (this.search != '') {
				return this.todos.filter(todo => {
					return todo.text.toLowerCase().includes(this.search.toLowerCase());
				})
			}
			return this.todos;
		},

		hasCompleted(){
			return this.todos.filter(todo => todo.done == true).length > 0
		}
	},

	methods: {
		...mapActions([
			'addTodo',
			'fetch',
			'removeCompleted'
		]),
		newTodo(){
			this.adding = true;
			this.todo = ''
		},

		cancelAdd(){
			this.adding = false;
			this.todo = ''
		},

		saveTodo(){

			if (this.todo.text == '') {
				alert('Please Write an To-Do');
				return;
			}

			this.addTodo({
				todo: this.todo,
				user: this.user,
			});
			this.cancelAdd()
		},
	}
}
</script>
