<template>
	<li class="list-group-item">
		<div class="row">
			<div class="col-sm-6">
				<span :class="done" v-show="!editing" @dblclick="editingTodo(todo)">{{ todo.text }}</span>
				<input type="text"
					v-show="editing"
					v-focus="editing"
					:value="todo.text"
					@keyup.enter="doneEdit"
					@keyup.esc="cancelEdit"
					@blur="doneEdit"
					class="form-control"
				>
			</div>
			<div class="col-sm-6">
				<div class="text-right">

					<a class="btn btn-danger py-1 m-1" @click="deleteTodo(todo)">
						<i class="fa fa-trash"></i>
					</a>

					<label class="container-checks">
						<input type="checkbox" @click="toggle()" v-model="todo.done">
						<span class="checkmark"></span>
					</label>
				</div>
			</div>
		</div>
	</li>
</template>

<script>
import { mapActions } from 'vuex'
export default {
	name: 'todoItem',
	props: [
		'todo',
	],

	directives: {
		focus (el, { value }, { context }) {
			if (value) {
				context.$nextTick(() => {
					el.focus()
				})
			}
		}
	},

	mounted(){

	},

	data(){
		return {
			editing: false,
		}
	},

	methods: {
	...mapActions([
		'editTodo',
		'toggleTodo',
		'removeTodo'
	]),
		editingTodo(todo){
			this.editing = true;
		},

		doneEdit(e){
			var value = e.target.value.trim();
			const { todo } = this;

			this.editTodo({
				todo,
				value,
			})

			this.editing = false;
		},

		cancelEdit(){
			this.editing = false
		},

		toggle(){
			this.toggleTodo({todo: this.todo})
		},

		deleteTodo(todo){
			this.removeTodo({todo: todo})
		}
	},

	computed: {
		done(){
			return {
				todoDone: this.todo.done == true,
			}
		}
	},
}
</script>

<style scoped>
	.todoDone{text-decoration:line-through;}
</style>
