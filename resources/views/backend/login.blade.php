@extends('layouts.admin-auth')
@section('title', 'Login')
@section('content')
<div class="login-box">
  <div class="login-logo">
    <b>Admin</b>Ecommerce
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to your account</p>

    <form id="login" method="post">
      @csrf
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" placeholder="Email">
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Password">
      </div>
      <hr>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4 pull-right">
          <button type="submit" onclick="login()" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@endsection
@section('scripts')
<script>
$('#login').validate({
    debug: true,
    focusInvalid: true, // do not focus the last invalid input
    ignore: "",
    rules: {
        email: { required: true },
        password: { required: true }
    },
    highlight: function (element, errorClass, validClass) {
        // console.log();
        if (element.type === "radio") {
            this.findByName(element.name).addClass(errorClass).removeClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-danger has-feedback');
            $(element).removeClass('form-control-success').addClass('form-control-danger');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if (element.type === "radio") {
            this.findByName(element.name).removeClass(errorClass).addClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-danger has-feedback').addClass('has-success has-feedback');
            $(element).removeClass('form-control-danger').addClass('form-control-success');
        }
    }
})
let login = () => {
  let form = document.getElementById('login');
  let formData = new FormData(form);
  if($('#login').valid()) {
      $.ajax({
          type: "post",
          url: '/backend/login',
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
              })
              if(res.success) {
                  window.location = '/backend/dashboard'
              }
          }
      })
  }
}
</script>
@endsection