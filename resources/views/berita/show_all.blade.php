@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
    <section class="content-header">
      <h1>
        List Berita
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Data Berita</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="data-table1" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Judul</th>
                  <th>Konten</th>
                  <th>Tanggal Ubah</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_berita as $row)
                <tr>
                  <td>{{ $row['judul'] }}</td>
                  <td>@if(strlen($row['konten']) > 50) {{ substr($row['konten'], 0, 50) . " ..." }} @else {{ $row['konten'] }} @endif</td>
                  <td>{{ $row['last_edit'] }}</td>
                  <td><center>
                    <a type="button" href="/berita/edit/{{ $row['key'] }}" class="btn btn-table"><i class="fa fa-edit"></i></button>
                    <a type="button" href="/berita/delete/{{ $row['key'] }}" class="btn btn-table btn-delete"><i class="fa fa-trash"></i></button>
                  </center></td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
</div>
@endsection