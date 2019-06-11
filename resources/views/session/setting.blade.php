@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
    <section class="content-header">
      <h1>
        Setting
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Ubah Data User</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="/setting/{{ $data['key'] }}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Username</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Username" name="username" value="@isset($data) {{ $data['username'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Kontak</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="No Telp" name="kontak" value="@isset($data) {{ $data['kontak'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Password Lama</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" placeholder="Password Lama" name="password_lama">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Password Baru</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" placeholder="Password Baru" name="password_baru">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Konfirmasi Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" placeholder="Konfirmasi Password Baru" name="konfirmasi_password">
                  </div>
                </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
    </section>
</div>

@endsection