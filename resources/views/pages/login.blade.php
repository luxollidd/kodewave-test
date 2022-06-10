@extends('pages.layout-empty')

@section('content')
<style>
    .gradient-custom {
      background: rgb(69,185,255);
      background: linear-gradient(233deg, rgba(69,185,255,1) 0%, rgba(86,115,201,1) 45%, rgba(115,68,235,1) 100%);
    }
</style>

<section class="vh-100 gradient-custom">
	<div class="container py-5 h-100">
		<div class="row d-flex justify-content-center align-items-center h-100">
			<div class="col-12 col-md-8 col-lg-6 col-xl-5">
				<div class="card bg-dark text-white" style="border-radius: 1rem;">
					<div class="card-body p-5 text-center">
						<div class="mb-md-5 mt-md-4 pb-5">
							<h2 class="fw-bold mb-2 text-uppercase">Login</h2>
							<p class="text-white-50 mb-5">Please enter your login and password!</p>
						<div class="form-outline form-white mb-4">
							<input type="email" id="email" class="form-control form-control-lg" />
							<label class="form-label mt-3" for="typeEmailX">Email</label>
						</div>
						<div class="form-outline form-white mb-4">
							<input type="password" id="password" class="form-control form-control-lg" />
							<label class="form-label mt-3" for="typePasswordX">Password</label>
						</div>
							<button class="btn btn-outline-light btn-lg px-5" id="loginBtn">Login</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection

@push('body')

<script>
	$(document).ready(function(){
		$('#loginBtn').on('click',function(){
			if($('#email').val() == '' || $('#email').val() == null){
				toaster.fire({
					icon: 'error',
					title: 'Email must not be empty!',
				})
			}
			else if($('#password').val() == '' || $('#password').val() == null){
				toaster.fire({
					icon: 'error',
					title: 'password must not be empty!',
				})
			}
			else{
				$.ajax({
					url: "/authenticate",
					method: 'post',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data:{
						'email': $('#email').val(),
						'password': $('#password').val(),
					},
					success: function(data){
						if(data.status == 'success'){
							toaster.fire({
								icon: 'success',
								title: 'You will be redirected in 3 seconds',
							})
							setTimeout(function(){
								window.location.replace("/dashboard");
							}, 3000);
						}
						else if(data.status == 'error'){
							toaster.fire({
								icon: 'error',
								title: data.message,
							})
						}

						else toaster.fire({
							icon: 'error',
							title: 'fatal error. please contact the tech support for further assistance',
						})
					},
				});
			}
		})
	})
</script>
@endpush