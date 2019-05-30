@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Form Agenda
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">@isset($data) Ubah Data Notification @else Input Notification Fakultas @endif</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="@isset($data) /push_fakultas/{{ $data['key'] }} @else /push_fakultas @endif" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Pilih Fakultas</label>
                  <div class="col-sm-10">
                    @foreach($all_fakultas as $row)
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" value="{{ $row['key'] }}" name="fakultas[]">{{ $row['nama'] }}
                      </label>
                    </div>
                    @endforeach
                  </div>
                </div>
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Judul</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Judul Notification" name="judul" value="@isset($data) {{ $data['judul'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="konten" class="col-sm-2 control-label">Konten</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="3" placeholder="Konten Notification ..." name="konten">@isset($data) {{ $data['konten'] }} @endif</textarea>
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