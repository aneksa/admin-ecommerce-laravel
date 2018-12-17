@extends('layouts.admin-app')
@section('title', 'Categories')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Categories
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Categories</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <button type="button" class="btn btn-info" onclick="openCreateForm()">Create New</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="category-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Code</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

<!-- Modals -->
<div class="modal fade" id="category-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="category-form" role="form">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="category-modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label" for="category-name">Name</label>
                        <input type="text" class="form-control" id="category-name" name="name" placeholder="Enter category name">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="category-code">Code</label>
                        <input type="text" class="form-control" id="category-code" name="code" placeholder="Enter category code">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="category-save-button">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
@section('scripts')
<script>
$('#category-table').DataTable({
    'processing': true,
    'serverSide': true,
    'ajax': {
        url: '/backend/categories/list/datatable',
        type: 'GET',
    },
    'columns': [
        {
            'data': 'name'
        },
        {
            'data': 'code'
        },
        {
            'data': null,
            'render': function(data,type,row,meta) {
                return `
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle" type="button" id="category-menu-button" data-toggle="dropdown" aria-expanded="true">
                            Action
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="category-menu-button">
                            <li>
                                <a onclick="categoryEdit('`+ data.id +`')">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a onclick="categoryDelete('`+ data.id +`')">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                `
            }
        }
    ]
})

$('#category-form').validate({
    debug: true,
    focusInvalid: true, // do not focus the last invalid input
    ignore: "",
    rules: {
        name: { required: true },
        code: { required: true }
    },
    highlight: function (element, errorClass, validClass) {
        // console.log();
        if (element.type === "radio") {
            this.findByName(element.name).addClass(errorClass).removeClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            // $(element).removeClass('form-control-success').addClass('form-control-danger');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if (element.type === "radio") {
            this.findByName(element.name).removeClass(errorClass).addClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            // $(element).removeClass('form-control-danger').addClass('form-control-success');
        }
    }
})

let openCreateForm = () => {
    $('#category-modal-title').text('Create')
    hideError()

    document.getElementById('category-form').reset()
    $('#category-save-button').attr('onclick', "javascript: categoryCreate();")
    $('#category-modal').modal('show')
}
let categoryCreate = () => {
    let formData = new FormData(document.getElementById('category-form'))
    if($('#category-form').valid()) {
        $.ajax({
            type: "post",
            url: '/backend/categories/create',
            enctype: 'multipart/form-data',
            processData: false,  // Important!
            contentType: false,
            cache: false,
            data: formData,
            success: function(res){
                $.toast({
                    heading: res.title,
                    text: res.message,
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: res.icon,
                    hideAfter: 3000, 
                    stack: 6
                });
                $('#category-modal').modal('hide');
                $("#category-table").DataTable().ajax.reload()
            }
        })
    }
}
let categoryEdit = (id) => {
    $.ajax({
        type: "get",
        url: '/backend/categories/edit',
        data: {
            "_token": "{{ csrf_token() }}",
            'id': id
        },
        success: function(res) {
            $('#category-modal-title').text('Edit');
            hideError();

            document.getElementById('category-form').reset();
            $('#category-name').val(res.data.name);
            $('#category-code').val(res.data.code);
            $('#category-save-button').attr( "onclick", "javascript: categoryUpdate('"+ res.data.id +"');" );
            $('#category-modal').modal('show');
        }
    })
}
let categoryUpdate = (id) => {
    let formData = new FormData(document.getElementById('category-form'))
    formData.append('_method', 'put')
    formData.append('id', id)
    if($('#category-form').valid()) {
        $.ajax({
            type: "post",
            url: '/backend/categories/update',
            enctype: 'multipart/form-data',
            processData: false,  // Important!
            contentType: false,
            cache: false,
            data: formData,
            success: function(res){
                $.toast({
                    heading: res.title,
                    text: res.message,
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: res.icon,
                    hideAfter: 3000, 
                    stack: 6
                });
                $('#category-modal').modal('hide');
                $("#category-table").DataTable().ajax.reload()
            }
        })
    }
}
let categoryDelete = (id) => {
    swal({
        title: "Are you sure?",
        text: "Once deleted, this item can't be showed!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((action) => {
        if(action) {
            $.ajax({
                type: "delete",
                url: '/backend/categories/soft-delete',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id
                },
                success: function(res){
                    $.toast({
                        heading: res.title,
                        text: res.message,
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: res.icon,
                        hideAfter: 3000, 
                        stack: 6
                    });
                    $("#category-table").DataTable().ajax.reload()
                }
            })
        }
    })
}
let hideError = function() {
    $("label.error").html('');
    $(".has-error").removeClass("has-error");
    $(".has-success").removeClass("has-success");
}
</script>
@endsection