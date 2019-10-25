export default [
	{path: '/', redirect: '/links'},

	{
        path: '/links',
        name: 'links-index',
        component: require('./components/links/index').default,
    },
    {
        path: '/links/:id/edit',
        name: 'links-edit',
        component: require('./components/links/edit').default,
    },

    {
        path: '/links/add',
        name: 'links-add',
        component: require('./components/links/create').default,
    },

    {
        path: '/profile',
        name: 'profile',
        component: require('./components/profile/index').default,
    },

    {
        path: '/users',
        name: 'users',
        component: require('./components/users/index').default,
    },

    {
        path: '/users/add',
        name: 'users-add',
        component: require('./components/users/create').default,
    },

    {
        path: '/users/:id/edit',
        name: 'users-edit',
        component: require('./components/users/edit').default,
    },

    {
        path: '/todos',
        name: 'todos',
        component: require('./components/todos/index').default,
    },

    {
        path: '/todos/history',
        name: 'todos-history',
        component: require('./components/todos/history').default,
    },
]
