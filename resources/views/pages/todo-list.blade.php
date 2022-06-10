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
        <h3 class="page__heading">Todo Lists</h3>
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
                        <th class="text-center">Body</th>
                        <th class="text-center">Completed?</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todolists as $key => $task)
                    <tr class="text-center">
                        <td data-id="{{$task->id}}">{{$task->id}}</td>
                        <td data-name="{{$task->body}}">{{$task->body}}</td>
                        <td>
                            <div class="form-check">
                            <input class="form-check-input float-none jsSetIsComplete" type="checkbox" data-todolist-id="{{$task->id}}" value="{{$task->is_complete}}" @if ($task->is_complete == 1) checked @endif>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-primary button-dim jsFormBtn" data-todolist-id="{{$task->id}}" data-edit-form="true"><i class="fas fa-cog fa-2xs"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal" tabindex="-1" id="task-form">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Task Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Body</label>
            <input type="text" class="form-control" id="body" placeholder="Task body here">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit" data-todolist-id="0">Submit</button>
      </div>
    </div>
  </div>
</div>

<script>
    var form = new bootstrap.Modal(document.getElementById('task-form'))
    var action;
    $(document).ready(function () {
        $('#pages-table').DataTable();

        $('.jsFormBtn').on('click', function(event){
            target = $(event.currentTarget);
            if(target.data('edit-form')){
                action = 'edit';
                $.ajax({
                    url: "/todo-list/"+target.data('todolist-id'),
                    method: 'get',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data){
                        if(data.status == 'success'){
                            $('#body').val(data.task.body);
                            $('#submit').data('todolist-id',data.task.id);
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
                $('#body').val('');
                $('#submit').data('todolist-id','0');
            }
            form.toggle();
        });

        $('#submit').on('click', function(event){
            data = {
                todolist_id : $(event.currentTarget).data('todolist-id'),
                body : $("#body").val(),
            }
            if(data.todolist_id == 0){
                url = "/todo-list";
                method = 'post';
                console.log('create');
                formAjax(data);
            }
            else if (data.todolist_id != 0){
                url = "/todo-list/"+data.todolist_id;
                method = 'put';
                console.log('edit');
                formAjax(data);
            }
        })

        $('.jsSetIsComplete').on('change', function(event){
            data = {
                'is_complete' : $(event.currentTarget).val(),
                'todolist_id' : $(event.currentTarget).data('todolist-id'),
            };
            $(event.currentTarget).val($(event.currentTarget).val() == 0 ? 1 : 0 );
            setIsComplete(data);
        })

        function setIsComplete(data){
            $.ajax({
                url: "/todo-list/set-is-complete/"+data.todolist_id,
                method: 'put',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: this.data,
                success: function(data){
                    if(data.status == 'success'){
                        toaster.fire({
                            icon: 'success',
                            title: data.message,
                        })
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

        function formAjax(data, url, method){
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
                        setTimeout(()=>{location.reload();}, 3000);
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