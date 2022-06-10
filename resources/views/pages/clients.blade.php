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
        <h3 class="page__heading">Clients</h3>
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
                        <th class="text-center">Name</th>
                        <th class="text-center">Callback</th>
                        <th class="text-center">Secret</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $key => $client)
                    <tr class="text-center">
                        <td data-id="{{$client->id}}">{{$client->id}}</td>
                        <td data-name="{{$client->name}}">{{$client->name}}</td>
                        <td data-email="{{$client->email}}">{{$client->redirect}}</td>
                        <td data-role="{{$client->role}}">{{$client->secret}}</td>
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
        <h5 class="modal-title">Client Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Name here">
        </div>
          <div class="mb-3">
            <label for="email" class="form-label">Callback URL</label>
            <input type="email" class="form-control" id="redirect" placeholder="Callback here">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit" data-client-id="0">Submit</button>
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
            action = 'create';
            $('#name').val('');
            $('#redirect').val('');
            $('#submit').data('client-id','0');
            form.toggle();
        });

        $('#submit').on('click', function(event){
            client_data = {
                name : $("#name").val(),
                redirect : $("#redirect").val(),
            }
            $.ajax({
                url: '/oauth/clients',
                method: 'post',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: client_data,
                statusCode: {
                    201: function(data){
                        toaster.fire({
                            icon: 'success',
                            title: 'Client created',
                        })
                        form.toggle();
                        setTimeout(()=>{location.reload();}, 3000); //do proper real-time insertion
                    }
                },
                error: function(data){
                    toaster.fire({
                        icon: 'error',
                        title: data.responseJSON.message,
                    })
                }
            })
            .fail(function(){
                toaster.fire({
                    icon: 'error',
                    title: 'fatal error. please contact the tech support for further assistance',
                })
            });
        })
    });
</script>
@endsection