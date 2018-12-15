@extends('layouts.admin-app')
@section('title', 'Products')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Products
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Products</li>
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
              <table id="product-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Stock</th>
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
<div class="modal fade" id="product-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="product-form" role="form">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="product-modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="product-name">Name</label>
                                <input type="text" class="form-control" id="product-name" name="name" placeholder="Enter product name">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="product-category">Category</label>
                                <select class="form-control" id="product-category" name="category" style="width: 100%; height:36px;">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="product-stock">Stock</label>
                                <input type="number" class="form-control" id="product-stock" name="stock" min="0" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="product-cover">Cover</label>
                                <input type="file" class="form-control" id="product-cover" name="cover">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="product-description">Description</label>
                        <textarea class="form-control" id="product-description" name="description" row="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="product-save-button">Save</button>
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
$('#product-description').wysihtml5({
    "stylesheets": false,
    "image":false,
    "link":false
})
$('#product-category').select2({
    placeholder: "Select a category",
    allowClear: true,
    ajax: {
        url: "/backend/products/category/list",
        data: function (params) {
            return {
                q: params.term, // search term
                page: params.page,
                "_token": "{{ csrf_token() }}",
            };
        },
        processResults: function (data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;
            return {
                results: data.items,
                pagination: {
                    more: (params.page * 30) < data.total_count
                }
            };
        },
        cache: true
    },
    containerCssClass : "form-control",
    dropdownParent: $("#product-form"),
    
}).change(function (e) {
    if($(this).val()!='' && $('#product-action').text()=='true'){
        $(this).valid(); //jquery validation script validate on change
    }
})
$('#product-table').DataTable({
    'processing': true,
    'serverSide': true,
    'ajax': {
        url: '/backend/products/list/datatable',
        type: 'GET',
    },
    'columns': [
        {
            'data': 'name'
        },
        {
            'data': 'stock'
        },
        {
            'data': null,
            'render': function(data,type,row,meta) {
                return `
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle" type="button" id="product-menu-button" data-toggle="dropdown" aria-expanded="true">
                            Action
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="product-menu-button">
                            <li>
                                <a onclick="productEdit('`+ data.id +`')">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a onclick="productDelete('`+ data.id +`')">
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
$('#product-form').validate({
    debug: true,
    focusInvalid: true, // do not focus the last invalid input
    ignore: "",
    rules: {
        name: { required: true },
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
    $('#product-modal-title').text('Create')
    hideError()

    document.getElementById('product-form').reset()
    $('#product-save-button').attr('onclick', "javascript: productCreate();")
    $('#product-modal').modal('show')
}
let productCreate = () => {
    let formData = new FormData(document.getElementById('product-form'))
    if($('#product-form').valid()) {
        $.ajax({
            type: "post",
            url: '/backend/products/create',
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
                $('#product-modal').modal('hide');
                $("#product-table").DataTable().ajax.reload()
            }
        })
    }
}
let productEdit = (id) => {
    $.ajax({
        type: "get",
        url: '/backend/products/edit',
        data: {
            "_token": "{{ csrf_token() }}",
            'id': id
        },
        success: function(res) {
            $('#product-modal-title').text('Edit');
            hideError();

            document.getElementById('product-form').reset();
            $('#product-name').val(res.data.name);
            $('#product-stock').val(res.data.stock);
            if(res.data.category) {
                $('#product-category').append($('<option>'+ res.data.category.name +'</option>').val(res.data.category.id)).trigger('change');
            }
            $('#product-description').val(res.data.description);
            $('#product-save-button').attr( "onclick", "javascript: productUpdate('"+ res.data.id +"');" );
            $('#product-modal').modal('show');
        }
    })
}
let productUpdate = (id) => {
    let formData = new FormData(document.getElementById('product-form'))
    formData.append('_method', 'put')
    formData.append('id', id)
    if($('#product-form').valid()) {
        $.ajax({
            type: "post",
            url: '/backend/products/update',
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
                $('#product-modal').modal('hide');
                $("#product-table").DataTable().ajax.reload()
            }
        })
    }
}
let productDelete = (id) => {
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
                url: '/backend/products/soft-delete',
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
                    $("#product-table").DataTable().ajax.reload()
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