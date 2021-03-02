@extends('layout')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Laporan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/rmh/servant">Home</a></li>
              <li class="breadcrumb-item active">Laporan</li>
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
                    <form class="form-inline" id="generate_laporan">
                        <div class="row">
                            <div class="form-group mr-2">
                                <label>Tanggal Mulai</label>
                                <input type="date" id="date_from" class="form-control mx-2">
                            </div>
                            <div class="form-group mr-2">
                                <label>Tanggal Akhir</label>
                                <input type="date" id="date_to" class="form-control mx-2">
                            </div>
                            <div class="form-group mr-2">
                                <button type="submit" id="btn-show-laporan" class="btn btn-flat btn-info">
                                    Generate
                                </button>
                                {{-- <button type="submit" id="btn-export-to-excel" class="btn btn-flat btn-success mx-2">
                                    Export To Excel
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-laporan-per-mahasiswa">
                            <thead>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>Jumlah Kehadiran</th>
                            </thead>
                            <tbody id="body-table-laporan-per-mahasiswa">

                            </tbody>
                            <tfoot>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>Jumlah Kehadiran</th>
                            </tfoot>
                        </table>
                        <div id="link"></div>
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

@section('laporan_scripts')
<script>
    $(document).ready(function(){
        // $("#table-laporan-per-mahasiswa").DataTable();

        $("#generate_laporan").submit(function(e){
            e.preventDefault();
            $.ajax({
                method: "get",
                url: "/rmh/laporan/getLaporan/" + $("#date_from").val() + "/" + $("#date_to").val(),
                success: function (res) {
                    $("#body-table-laporan-per-mahasiswa").html('');
                    $("#link").html('');
                    res.forEach(data => {
                        $("#body-table-laporan-per-mahasiswa").append(`
                                <tr>
                                    <td>${data.nrp}</td>
                                    <td>${data.nama}</td>
                                    <td>${data.jumlah_kehadiran}</td>
                                </tr>
                            `);
                    });
                    $("#table-laporan-per-mahasiswa").DataTable();
                    $("#link").append(`<a href='/rmh/laporan/exportToExcel/${$("#date_from").val()}/${$("#date_to").val()}' target="_blank" class="btn btn-flat btn-success">Export To Excel</a>`);
                }
            });
            // alert($("#date_from").val())
        });
    });
</script>
@endsection
