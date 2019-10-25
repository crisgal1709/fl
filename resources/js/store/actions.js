export default {
	fetch({commit}){
		commit('getTodos')
	},

	fetchHistory({commit}){
		commit('fetchHistory')
	},

	clearTodos({commit}){
		commit('clearTodos')
	},

	addTodo ({ commit }, { todo, user }){
		let t = {
			text: todo,
			done: false,
			user_id: user.id,
		}

		commit('addTodo', { todo: t })
	},

	toggleTodo({commit}, {todo}){
		commit('toggleTodo', {todo: todo})
	},

	editTodo ({ commit }, { todo, value }) {
		commit('editTodo', { todo, text: value })
	},

	removeTodo({commit}, {todo}){
		commit('removeTodo', {todo: todo})
	},

	removeCompleted({commit}){
		commit('removeCompleted');
	}
}
