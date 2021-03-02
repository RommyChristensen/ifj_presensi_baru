@extends('layout')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Absensi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Absensi</li>
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
            <button id="btn-tambah-absensi" class="btn btn-flat btn-info mb-3">Tambah Absensi Baru</button>
            <form id="form-lihat-absensi" class="form-inline" method="post">
                @csrf
                <div class="form-group mb-3 mr-3">
                    <select name="filter" class="form-control">
                        <option value="all">Lihat Semua</option>
                        <option value="0">Belum Dibuka</option>
                        <option value="1">Sedang Dibuka</option>
                        <option value="2">Sudah Ditutup</option>
                        <option value="3">Selesai</option>
                    </select>
                </div>
                <button type="submit" id="btn-generate-absensi" class="btn btn-flat btn-success mb-3">
                    &nbsp;Generate
                </button>
            </form>
            @if(session()->has('code') && session()->get('code') == 200)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Jadwal Absensi Berhasil Ditambahkan
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session()->has('code') && session()->get('code') == 1000)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Jadwal Absensi Berhasil Dihapus
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card d-none" id="card-tambah-absensi">
                <div class="card-body">
                    <form action="/rmh/tambah_absensi" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Judul Absensi</label>
                            <input type="text" name="judul_absensi" value="{{old('judul_absensi')}}" class="form-control">
                            @error('judul_absensi')
                                <span class="text-sm text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Absensi</label>
                            <input type="date" name="tanggal_absensi" value="{{old('tanggal_absensi')}}" class="form-control">
                            @error('tanggal_absensi')
                                <span class="text-sm text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-flat btn-success">Submit</button>
                    </form>
                </div>
            </div>

            <div class="card d-none" id="card-lihat-absensi">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-lihat-absensi">
                            <thead>
                                <th>Nama Absensi</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Absensi</th>
                                <th>Link Absensi</th>
                                <th>Jumlah Absen</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="body-table-lihat-absensi">

                            </tbody>
                            <tfoot>
                                <th>Nama Absensi</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Absensi</th>
                                <th>Link Absensi</th>
                                <th>Jumlah Absen</th>
                                <th>Status</th>
                                <th>Action</th>
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

@section('admin_absensi_scripts')
<script>
    $(document).ready(function(){
        // $("#input_nama").removeAttr('disabled');
        $("#table-mahasiswa").DataTable();

        $("#btn-tambah-absensi").click(function(){
            $("#card-tambah-absensi").removeClass('d-none');
        });

        $("#form-lihat-absensi").submit(function(e){
            e.preventDefault();
            if(!$("#btn-generate-absensi").hasClass('disabled')){
                $("#btn-generate-absensi").prepend(`
                    <span id="btn-generate-absensi-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                `);
                $("#btn-generate-absensi").addClass('disabled');
            }
            new ClipboardJS('.btn');

            $.ajax({
                method: "post",
                url: "/rmh/lihat_data_absensi",
                data: $(this).serialize(),
                success: function(res){
                    $("#body-table-lihat-absensi").html('');
                    console.log(res);
                    res.forEach(data => {
                        let status = '';
                        let action = '';
                        if(data.status == 0){
                            status = "Belum Dibuka";
                            action = `<a href='/rmh/absensi/buka/${data.id_header_kehadiran}' class='btn btn-flat btn-sm btn-success'>Buka</a>`;
                        }else if(data.status == 1){
                            status = "Sedang Dibuka";
                            action = `<a href='/rmh/absensi/tutup/${data.id_header_kehadiran}' class='btn btn-flat btn-sm btn-warning'>Tutup</a>`;
                        }else if(data.status == 2){
                            status = "Sudah Ditutup";
                            action = `<a href='/rmh/absensi/buka/${data.id_header_kehadiran}' class='btn btn-flat btn-sm btn-info'>Buka</a> <a href='/rmh/absensi/selesai/${data.id_header_kehadiran}' class='btn btn-flat btn-sm btn-danger'>Selesai</a>`;
                        }else if(data.status == 3){
                            status = "Selesai";
                        }

                        action += ` <a href="/rmh/absensi/detail/${data.id_header_kehadiran}" class="btn btn-flat btn-sm btn-info"><i class="fa fa-eye"></i></a> <a href="/rmh/absensi/delete/${data.id_header_kehadiran}" class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash"></i></a>`;

                        $("#body-table-lihat-absensi").append(`
                            <tr>
                                <td>${data.judul_absensi}</td>
                                <td>${data.deskripsi != null ? data.deskripsi : 'Tidak Ada Deskripsi'}</td>
                                <td>${data.tanggal_absensi}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-8">
                                            <input type="text" class="form-control" id="input-${data.id_header_kehadiran}" readonly value="${data.link_absensi}">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <button data-clipboard-action="copy" data-clipboard-target="#input-${data.id_header_kehadiran}" class="btn btn-flat btn-primary btn-clipboard"><i class="fa fa-clipboard text-white"></i></button>
                                        </div>
                                    </div>
                                </td>
                                <td>${data.detail_count}</td>
                                <td><span class="badge badge-info">${status}</span></td>
                                <td>${action}</td>
                            </tr>
                        `);
                    });
                    $("#card-lihat-absensi").removeClass('d-none');
                    $("#table-lihat-absensi").DataTable();
                    $("#btn-generate-absensi").removeClass('disabled');
                    $("#btn-generate-absensi-spinner").remove();
                }
            });
        });
    });
</script>
@endsection
