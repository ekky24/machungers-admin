@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
    <section class="content-header">
      <h1>
        List Newsletter
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Data Newsletter</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="data-table2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Nama File</th>
                  <th>Tanggal Upload</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_data as $row)
                <tr>
                  <td>{{ $row['nama'] }}</td>
                  <td>{{ $row['last_edit'] }}</td>
                  <td><center>
                    <a type="button" href="/newsletter/delete/{{ $row['key'] }}" class="btn btn-table btn-delete"><i class="fa fa-trash"></i></button>
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