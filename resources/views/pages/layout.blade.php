<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Laravel</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js" integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY=" crossorigin="anonymous"></script>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    @stack('head')
    <link href="/css/app.css" rel="stylesheet">
    <style>
        main {
            display: flex;
            flex-wrap: nowrap;
            height: 100vh;
            height: -webkit-fill-available;
            max-height: 100vh;
            overflow-x: auto;
            overflow-y: hidden;
        }
    </style>
</head>
<body>
    <header class="p-3 bg-dark text-white" style="height: 75px;">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="#" class="nav-link px-2 text-light">Kodewave Test</a></li>
                </ul>

                <div class="text-end">
                    <a href="/logout"><button type="button" class="btn btn-outline-light me-2">Logout</button></a>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 g-0">
                <div class="d-flex flex-column flex-shrink-0 p-3 pt-0 text-white bg-dark" style="width: 100%; height: calc(100vh - 75px);">
                    
                    <hr class="mt-0">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="/dashboard" class="nav-link text-white jsSidebarMenu" aria-current="page" data-page="dashboard">
                            <i class="bi bi-house-door me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        @if(auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a href="/users" class="nav-link text-white jsSidebarMenu" aria-current="page" data-page="users">
                            <i class="bi bi-person-circle me-2"></i>
                                Users
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="/todo-list" class="nav-link text-white jsSidebarMenu" aria-current="page" data-page="todo-list">
                            <i class="bi bi-card-checklist me-2"></i>
                                Todo-list
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/clients" class="nav-link text-white jsSidebarMenu" aria-current="page" data-page="clients">
                            <i class="bi bi-person-badge me-2"></i>
                                Clients
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-10">
                <div class="container-fluid p-3">
                    @yield('content')
                </div>
            </div>
        </div>
        
    </div>
</body>
@stack('body')
<script>
	var toaster = Swal.mixin({
		toast: true,
		position: 'bottom-end',
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})
    $('.jsSidebarMenu[data-page="{{request()->path()}}"]').addClass('active');
    
    @if ($errors->any())
        toaster.fire({
            icon: 'error',
            title: '{{$errors->all()[0]}}',
        })
    @endif
</script>

</html>
