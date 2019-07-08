@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
    <section class="content-header">
      <h1>
        Form Life at Ma Chung
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
      <ul class="nav nav-tabs">
        <li><a data-toggle="tab" href="#upload">Upload</a></li>
        <li><a data-toggle="tab" href="#choose">Pilih</a></li>
        <li class="active"><a data-toggle="tab" href="#form">Form</a></li>
      </ul>
      <div class="tab-content">
        <div id="upload" class="tab-pane fade in">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Upload Media</h3>
            </div>
            <form class="form-horizontal" method="post" action="/lifeatmachung_upload" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Upload Media</label>
                  <div class="col-sm-10">
                    <input type="file" name="gambar" placeholder="Pilih Gambar">
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
        </div>
        <div id="choose" class="tab-pane fade in">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Pilih Media</h3>
            </div>
            
            <div class="box-body">
              <table id="data-table" class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                  <th>Media</th>
                  <th>Link</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_data as $row)
                <tr>
                  <?php $url = '/storage/' . $row['img_url'] ?>
                  <td><img src="{{ $url }}" height="80px"></td>
                  <td>{{ $url }}</td>
                  <td><center>
                    <a type="button" href="/lifeatmachung_upload/delete/{{ $row['key'] }}" class="btn btn-table btn-delete"><i class="fa fa-trash"></i></a>
                  </center></td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div id="form" class="tab-pane fade in active">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">@isset($data) Ubah Data Life at Ma Chung @else Input Life at Ma Chung @endif</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="@isset($data) /lifeatmachung/{{ $data['key'] }} @else /lifeatmachung @endif">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="judul" class="col-sm-2 control-label">Judul</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Judul" name="judul" value="@isset($data) {{ $data['judul'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="konten" class="col-sm-2 control-label">Konten</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" placeholder="Konten ..." name="konten" 
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" id="html-editor">@isset($data) {{ $data['konten'] }} @endif"</textarea>
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
        </div>
      </div>
    </section>
</div>
@endsection