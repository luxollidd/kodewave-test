@extends('pages.layout')

@section('content')
<style>
    .table-img{
        width: 50px;
        height: 50px;
    }
    .button-dim{
        padding: 0px 8px;
    }
</style>

<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Users</h3>
    </div>
    <div class="section-body">
        <div class="w-100 text-end">
            <button class="btn btn-success action-btn float-right mb-3 button-dim jsFormBtn"><i class="fas fa-plus fa-2xs"></i></button>
        </div>
        <div class="table-responsive">
            <table class="table" id="pages-table">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr class="text-center">
                        <td data-id="{{$user->id}}">{{$user->id}}</td>
                        <td data-name="{{$user->name}}">{{$user->name}}</td>
                        <td data-email="{{$user->email}}">{{$user->email}}</td>
                        <td data-role="{{$user->role}}">{{$user->role}}</td>
                        <td>
                            <button class="btn btn-primary button-dim jsFormBtn" data-user-id="{{$user->id}}" data-edit-form="true"><i class="fas fa-cog fa-2xs"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal" tabindex="-1" id="user-form">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Name here">
        </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Email here">
        </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" aria-label="" id="role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Leave blank if unchanged">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit" data-user-id="0">Submit</button>
      </div>
    </div>
  </div>
</div>

<script>
    var form = new bootstrap.Modal(document.getElementById('user-form'))
    var action;
    $(document).ready(function () {
        $('#pages-table').DataTable();

        $('.jsFormBtn').on('click', function(event){
            target = $(event.currentTarget);
            if(target.data('edit-form')){
                action = 'edit';
                $.ajax({
                    url: "/users/"+target.data('user-id'),
                    method: 'get',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data){
                        if(data.status == 'success'){
                            $('#email').val(data.user.email);
                            $('#name').val(data.user.name);
                            $("#role option[value='"+data.user.role+"']").attr("selected","selected");
                            $('#submit').data('user-id',data.user.id);
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
            else {
                action = 'create';
                $('#email').val('');
                $('#name').val('');
                $('#password').val('');
                $("#role option[value='admin']").attr("selected","selected");
                $('#submit').data('user-id','0');
            }
            form.toggle();
        });

        $('#submit').on('click', function(event){
            data = {
                user_id : $(event.currentTarget).data('user-id'),
                email : $("#email").val(),
                name : $("#name").val(),
                password : $("#password").val(),
                role : $("#role").val(),
            }
            if(data.user_id == 0 && (data.password != null && data.password != '')){
                url = "/users";
                method = 'post';
                console.log('create');
                userAjax(data);
            }
            else if (data.user_id == 0 && (data.password == null || data.password == '')){
                toaster.fire({
                    icon: 'error',
                    title: "Password cannot be blank when creating new user",
                })
            }
            else if (data.user_id != 0){
                url = "/users/"+data.user_id;
                method = 'put';
                console.log('edit');
                userAjax(data);
            }
        })

        function userAjax(data, url, method){
            
            $.ajax({
                url: this.url,
                method: this.method,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: this.data,
                success: function(data){
                    if(data.status == 'success'){
                        toaster.fire({
                            icon: 'success',
                            title: data.message,
                        })
                        form.toggle();
                        setTimeout(()=>{location.reload();}, 3000); //do proper real-time insertion
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
                error: function(data){
                    console.log(data);
                }
            });
        }
    });
</script>
@endsection