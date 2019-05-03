@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
              <h3 class="box-title">Data Fakultas</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="data-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Nama Fakultas</th>
                  <th>Profil</th>
                  <th>Tanggal Ubah</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_data as $row)
                <tr>
                  <td>{{ $row['nama'] }}</td>
                  <td>{{ $row['profil'] }}</td>
                  <td>{{ $row['last_edit'] }}</td>
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