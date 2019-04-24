@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Form FAQ
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Input FAQ Baru</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="/faq">
              <div class="box-body">
                <div class="form-group">
                  <label for="pertanyaan" class="col-sm-2 control-label">Pertanyaan</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="3" placeholder="Detail Pertanyaan ..."></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="jawaban" class="col-sm-2 control-label">Jawaban</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="3" placeholder="Detail Jawaban ..."></textarea>
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