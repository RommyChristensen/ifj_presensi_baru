@extends('layout')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Absensi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/rmh/servant">Home</a></li>
              <li class="breadcrumb-item active"><a href="/rmh/admin_absensi">Absensi</a></li>
              <li class="breadcrumb-item active">Detail Absensi</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>Jam Absen</th>
                            </thead>
                            <tbody>
                                @forelse ($detail_kehadiran as $detail)
                                    <tr>
                                        <td>{{$detail->nrp}}</td>
                                        <td>{{$detail->mahasiswa->nama}}</td>
                                        <td>{{$detail->created_at->diffForHumans()}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">Tidak Ada Data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>Jam Absen</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
@endsection
