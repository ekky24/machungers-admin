@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
              <h3 class="box-title">Input User Baru</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="/berita">
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Nama</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nama Departemen">
                  </div>
                </div>
                <div class="form-group">
                  <label for="konten" class="col-sm-2 control-label">Kontak</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" placeholder="Nomor Kontak">
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