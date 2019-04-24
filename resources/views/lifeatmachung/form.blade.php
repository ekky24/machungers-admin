@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Form Life at Ma Chung
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Input Data Baru</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="/berita">
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Judul</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Judul">
                  </div>
                </div>
                <div class="form-group">
                  <label for="konten" class="col-sm-2 control-label">Konten</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" placeholder="Konten ..."
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" id="html-editor"></textarea>
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