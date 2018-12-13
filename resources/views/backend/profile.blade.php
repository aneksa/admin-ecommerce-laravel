@extends('layouts.admin-app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Profile
      </h1>
      <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> Dashboard</li>
        <li class="active">User profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{ $user->avatar ? :'/themes/AdminLTE-2.4.5/dist/img/user2-160x160.jpg' }}" alt="User profile picture">

                <h3 class="profile-username text-center">{{ $user->name }}</h3>

                <div class="box-header with-border">
                    <h3 class="box-title">About Me</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                    <p class="text-muted">
                        {{ $user->email }}
                    </p>
                    <hr>
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Phone</strong>
                    <p class="text-muted">{{ $user->phone }}</p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#detail" data-toggle="tab">Detail</a></li>
              <li><a href="#change-password" data-toggle="tab">Change Password</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="detail">
                <form role="form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="profile-name">Name</label>
                            <input type="text" name="name" class="form-control" id="profile-name" value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label for="profile-email">Email</label>
                            <input type="email" name="email" class="form-control" id="profile-email" value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <label for="profile-phone">Phone</label>
                            <input type="text" name="phone" class="form-control" id="profile-phone" value="{{ $user->phone }}">
                        </div>
                        <div class="form-group">
                            <label for="profile-avatar">Avatar</label>
                            <input type="file" id="profile-avatar">
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" onclick="profileUpdate()" class="btn btn-primary">Save</button>
                    </div>
                </form>
              </div>

              <div class="tab-pane" id="change-password">
                <form role="form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="profile-name">Password</label>
                            <input type="password" name="password" class="form-control" id="profile-password">
                        </div>
                        <div class="form-group">
                            <label for="profile-email">Password Confirmation</label>
                            <input type="password" name="password_confirmation" class="form-control" id="profile-password-confirmation">
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" onclick="profileUpdate()" class="btn btn-primary">Save</button>
                    </div>
                </form>
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
@endsection