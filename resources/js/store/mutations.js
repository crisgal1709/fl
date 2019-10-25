import utils from './utils'

export default {
	getTodos(state){
		axios.get(utils.apiPath)
			.then(response =>{
				state.todos = response.data;
			})
	},

	fetchHistory(state){
		axios.get(utils.apiPath + '/history')
			.then(response => {
				state.todos = response.data
			})
	},

	clearTodos(state){
		axios.post(`${utils.apiPath}/clearCompleted`)
			.then(response => {
				state.todos = []
			})
	},

	addTodo(state, {todo}){

		var form = new FormData;
		form.append('done', false);
		form.append('text', todo.text);
		form.append('user_id', todo.user_id);

		axios.post(utils.apiPath + '/store', form)
			.then(response => {
				state.todos.push(response.data)
			})
	},

	editTodo (state, { todo, text = todo.text, done = todo.done }) {
		todo.text = text
		todo.done = done
	},

	toggleTodo(state, {todo}){

		axios.post(utils.apiPath + '/toogle/' + todo.id)
				.then(response => {
					todo = response.data
				})
	},

	removeTodo(state, {todo}){
		var urlTo = `${utils.apiPath}/destroy/${todo.id}`
		axios.post(urlTo)
				.then(response => {})

		state.todos = state.todos.filter(td => td.id !== todo.id)
	},

	removeCompleted(state){
		let cont = state.todos.filter(todo => todo.done == true).length
		state.todos = state.todos.filter(todo => todo.done == false)

		if (cont > 0) {
			var urlTo = `${utils.apiPath}/removeCompleted`;
			axios.post(urlTo).then(response => console.log(response))

		}
	}
}
