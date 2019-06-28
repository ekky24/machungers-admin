@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
    <section class="content-header">
      <h1>
        List Notification
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Data Notification</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="data-table3" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Judul</th>
                  <th>Konten</th>
                  <th>Tipe</th>
                  <th>Time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_data as $row)
                <tr>
                  <td>{{ $row['judul'] }}</td>
                  <td>@if(strlen($row['konten']) > 50) {{ substr($row['konten'], 0, 50) . " ..." }} @else {{ $row['konten'] }} @endif</td>
                  <td>{{ $row['type'] }}</td>
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