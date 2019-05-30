@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Form Mahasiswa
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
    <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">@isset($data) Ubah Data Mahasiswa @else Input Mahasiswa Baru @endif</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="@isset($data) /mahasiswa/{{ $data['key'] }} @else /mahasiswa @endif" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="nim" class="col-sm-2 control-label">NIM</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="NIM Mahasiswa" name="nim" value="@isset($data) {{ str_replace(' ', '', $data['nim']) }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="nama" class="col-sm-2 control-label">Nama</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Nama Mahasiswa" name="nama" value="@isset($data) {{ $data['nama'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="tempat_lahir" class="col-sm-2 control-label">Tempat Lahir</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Tempat Lahir Mahasiswa" name="tempat_lahir" value="@isset($data) {{ $data['tempat_lahir'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="tgl_lahir" class="col-sm-2 control-label">Tanggal Lahir</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="tgl-mulai" placeholder="Tanggal Lahir Mahasiswa" name="tgl_lahir" value="@isset($data) {{ $data['tgl_lahir'] }} @endif">
                  </div>
                </div>
                <div class="form-group">
                  <label for="fakultas" class="col-sm-2 control-label">Pilih Fakultas</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="fakultas">
                      @isset($data)
                        @foreach($all_fakultas as $row)
                        @if($row['key'] == $data['fakultas'])
                        <option value="{{ $row['key'] }}" selected>{{ $row['nama'] }}</option>
                        @else
                        <option value="{{ $row['key'] }}">{{ $row['nama'] }}</option>
                        @endif
                        @endforeach
                      @else
                        @foreach($all_fakultas as $row)
                        <option value="{{ $row['key'] }}">{{ $row['nama'] }}</option>
                        @endforeach
                      @endif
                  </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="prodi" class="col-sm-2 control-label">Pilih Prodi</label>
                  <div class="col-sm-10">
                    <select class="form-control" name="prodi">
                      @isset($data)
                        @foreach($all_prodi as $row)
                        @if($row['key'] == $data['prodi'])
                        <option value="{{ $row['key'] }}" selected>{{ $row['nama'] }}</option>
                        @else
                        <option value="{{ $row['key'] }}">{{ $row['nama'] }}</option>
                        @endif
                        @endforeach
                      @else
                        @foreach($all_prodi as $row)
                        <option value="{{ $row['key'] }}">{{ $row['nama'] }}</option>
                        @endforeach
                      @endif
                  </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="foto" class="col-sm-2 control-label">Foto</label>
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