@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
    <section class="content-header">
      <h1>
        Form Fakultas
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">@isset($data) Ubah Data Fakultas @else Input Fakultas Baru @endif</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="@isset($data) /fakultas/{{ $data['key'] }} @else /fakultas @endif" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Nama Fakultas</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nama Fakultas" name="nama" value="@isset($data) {{ $data['nama'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="konten" class="col-sm-2 control-label">Profil</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="3" placeholder="Profil Fakultas..." name="profil">@isset($data) {{ $data['profil'] }} @endif</textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="konten" class="col-sm-2 control-label">Gambar</label>
                  <div class="col-sm-10">
                    <input type="file" name="gambar">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
    </section>
</div>
@endsection