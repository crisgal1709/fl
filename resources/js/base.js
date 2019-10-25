export default {
	data(){
		return {
			apiPath: '/api',
			profilePath: '/profile',
			user: FL.user || {},
		}
	},

	methods: {
		swal(text = '', title = 'Error!', type="error", btn = 'OK'){

			Swal.fire({
				title: title,
				text: text,
				type: type,
				confirmButtonText: btn,
			})

		},

		animateScroll(){
			var pos = $(this.$el).offset().top;
			$('html, body').animate({
				scrollTop: pos - 50
			}, 500)
		},

		setTitleDocument(title = ''){
			let tl = FL.title || 'Favorite Links';
			let tls = `${title} - ${tl}`;
			document.title = tls;
		},
	}
}
