@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Prodi
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Data Prodi</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="data-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Nama Prodi</th>
                  <th>Fakultas</th>
                  <th>Profil</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_data as $row)
                <tr>
                  <td>{{ $row['nama'] }}</td>
                  <td>{{ $row['nama_fakultas'] }}</td>
                  <td>{{ substr($row['profil'], 0, 40) }}</td>
                  <td><center>
                    <a type="button" href="/prodi/edit/{{ $row['key'] }}" class="btn btn-table"><i class="fa fa-edit"></i></button>
                    <a type="button" href="/prodi/delete/{{ $row['key'] }}" class="btn btn-table btn-delete"><i class="fa fa-trash"></i></button>
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