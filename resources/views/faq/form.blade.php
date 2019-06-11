@extends('layout.master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('layout.message')
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
              <h3 class="box-title">@isset($data) Ubah Data FAQ @else Input FAQ Baru @endif</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" method="post" action="@isset($data) /faq/{{ $data['key'] }} @else /faq @endif">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="pertanyaan" class="col-sm-2 control-label">Pertanyaan</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="3" placeholder="Detail Pertanyaan ..." name="pertanyaan">@isset($data) {{ $data['pertanyaan'] }} @endif</textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="jawaban" class="col-sm-2 control-label">Jawaban</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="3" placeholder="Detail Jawaban ..." name="jawaban">@isset($data) {{ $data['jawaban'] }} @endif</textarea>
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