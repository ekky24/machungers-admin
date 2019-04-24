@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Form Berita
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Input Berita Baru</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="/berita">
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Judul</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Judul Berita">
                  </div>
                </div>
                <div class="form-group">
                  <label for="konten" class="col-sm-2 control-label">Konten</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="3" placeholder="Konten Berita ..."></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="konten" class="col-sm-2 control-label">Gambar</label>
                  <div class="col-sm-10">
                    <input type="file" id="gambar">
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