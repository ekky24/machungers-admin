@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
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
              <h3 class="box-title">Upload CSV Data Mahasiswa</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">
                <div class="form-group">
                  <div class="col-sm-10">
                    <button type="button" class="btn btn-lg btn-primary mulai_simpan_csv">Mulai Simpan CSV</button>
                  </div>
                </div>
              </div>
          </div>
    </section>
</div>
@endsection