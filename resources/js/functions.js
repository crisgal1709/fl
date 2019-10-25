export default function axiosResponseInterceptor(axios){
	axios.interceptors.response.use(function (response) {
		return response;
	}, function (error) {
		if (401 === error.response.status) {
			Swal.fire({
				title: "Session Expired",
				text: "Your session has expired.",
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
			}, function(){
				window.location = '/login';
			}, function(){

			});

			setTimeout(() => {
				window.location = '/login';
			}, 1000)
		} else {
			return Promise.reject(error);
		}
	});
}
