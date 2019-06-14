@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
    <section class="content-header">
      <h1>
        Form User
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">@isset($data) Ubah Data User @else Input User Baru @endif</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="@isset($data) /user/{{ $data['key'] }} @else /user @endif" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Username</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="username" placeholder="Username" value="@isset($data) {{ $data['username'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Nama Departemen</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="nama_departemen" placeholder="Nama Departemen" value="@isset($data) {{ $data['nama_departemen'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Kontak</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="No Telp" name="kontak" value="@isset($data) {{ $data['kontak'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Tingkatan Hak Akses</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="level">
                      <option value="keuangan" @isset($data) @if($data['level'] == 'keuangan') {{'selected'}} @endif @endif>Keuangan</option>
                      <option value="fakultas" @isset($data) @if($data['level'] == 'fakultas') {{'selected'}} @endif @endif>Fakultas</option>
                      <option value="prodi" @isset($data) @if($data['level'] == 'prodi') {{'selected'}} @endif @endif>Prodi</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                  </div>
                </div>
                <div class="form-group">
                  <label for="password" class="col-sm-2 control-label">Konfirmasi Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" placeholder="Konfirmasi Password" name="konfirmasi_password">
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