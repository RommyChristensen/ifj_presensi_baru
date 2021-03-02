@extends('layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List Mahasiswa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">List Mahasiswa</li>
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
                    <a href="/rmh/qr_code/generate" class="btn btn-info btn-sm btn-flat mb-3"><i class="fa fa-qrcode"></i>&nbsp; Generate QR Code for All</a>
                    <div class="table-responsive">
                        <table id="table-mahasiswa" class="table table-striped table-bordered">
                            <thead>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>TTL</th>
                                <th>Jurusan</th>
                                <th>Qr Code</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                {{-- {{dd($mahasiswa)}} --}}
                                @foreach ($mahasiswa as $mhs)
                                    <tr>
                                        <td>{{$mhs->nrp}}</td>
                                        <td>{{$mhs->nama}}</td>
                                        <td>{{$mhs->ttl}}</td>
                                        {{-- {{dd($mhs->jurusan)}} --}}
                                        <td>
                                            @if ($mhs->qr_code == null)
                                                NO QR CODE GENERATED
                                            @else
                                                <img src='{{asset("$mhs->qr_code")}}' width="50" alt="">
                                            @endif
                                        </td>
                                        <td>{{$mhs->jurusan->nama_jurusan}}</td>
                                        <td>
                                            {{-- <a href="/rmh/mhs/delete/{{$mhs->nrp}}" class="btn btn-sm btn-flat btn-danger"><i class="fa fa-trash"></i></a> --}}
                                            {{-- <a href="/edit_mhs" class="btn btn-sm btn-flat btn-warning"><i class="fa fa-edit text-white"></i></a> --}}
                                            <button type="button" class="btn btn-sm btn-flat btn-warning btn-toggle-modal" nrp="{{$mhs->nrp}}" data-toggle="modal" data-target="#exampleModal">
                                                <i class="fa fa-edit text-white"></i>
                                            </button>
                                            @if ($mhs->qr_code == null)
                                                <a href="/rmh/qr_code/generate/{{$mhs->nrp}}" target="_blank" class="btn btn-info btn-flat btn-sm"><i class="fa fa-qrcode"></i></a>
                                            @else
                                                <button disabled class="btn btn-info btn-flat btn-sm"><i class="fa fa-qrcode"></i></button>
                                            @endif

                                            @if ($mhs->konfirmasi == 1)
                                                <form action="/rmh/mhs/delete" class="form-delete-mhs" method="POST" style="float: left">
                                                    @csrf
                                                    <input type="hidden" name="nrp" value="{{ $mhs->nrp }}">
                                                    <button type="submit" disabled class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash"></i></button>&nbsp;
                                                </form>
                                                <a href="/rmh/mhs/konfirmasi/{{$mhs->nrp}}/0" class="btn btn-sm btn-secondary btn-flat"><i class="fa fa-times"></i></a>
                                            @else
                                                <form action="/rmh/mhs/delete" class="form-delete-mhs" method="POST" style="float: left">
                                                    @csrf
                                                    <input type="hidden" name="nrp" value="{{ $mhs->nrp }}">
                                                    <button type="submit" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash"></i></button>&nbsp;
                                                </form>
                                                <a href="/rmh/mhs/konfirmasi/{{$mhs->nrp}}/1" class="btn btn-sm btn-success btn-flat"><i class="fa fa-check"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>TTL</th>
                                <th>Jurusan</th>
                                <th>Qr Code</th>
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
  <!-- /.content-wrapper -->
  <form action="/rmh/mhs/edit" method="POST">
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      @csrf
                      <div class="form-group">
                          <label for="">NRP</label>
                          <input type="text" name="nrp" readonly class="form-control" id="nrp_edit">
                      </div>
                      <div class="form-group">
                          <label for="">Nama</label>
                          <input type="text" name="nama" class="form-control" id="nama_edit">
                      </div>
                      <div class="form-group">
                        <label for="">TTL</label>
                        <input type="date" name="ttl" class="form-control" id="ttl_edit">
                    </div>
                      <div class="form-group">
                          <label for="">Jurusan</label>
                          <select name="jurusan" name="id_jurusan" id="jurusan_edit" class="form-control">

                          </select>
                      </div>

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
              </div>
          </div>
      </div>
  </form>

@endsection

@section('mhs_scripts')
    <script>
        $(document).ready(function(){
            // $("#table-mahasiswa").DataTable();

            $(".form-delete-mhs").submit(function(e){
                e.preventDefault();
                if(confirm("Yakin Ingin Hapus?")){
                    e.target.submit();
                }
            });

            $(".btn-toggle-modal").click(function(){
                let nrp = $(this).attr('nrp');
                $.ajax({
                    method: "get",
                    url: '/rmh/mhs/getDataMhs/'+nrp,
                    success: function(res){
                        $("#nrp_edit").val(res.nrp);
                        $("#nama_edit").val(res.nama);
                        $("#ttl_edit").val(res.ttl);
                        generateJurusan();
                        $("#option_jurusan_"+res.id_jurusan).attr('selected', 'selected');
                    }
                })
            });
        });

        function generateJurusan(){
            $("#jurusan_edit").html('');
            $("#jurusan_edit").append(`
                        <option value="1" id="option_jurusan_1">S1 Informatika</option>
                        <option value="2" id="option_jurusan_2">S1 Desain Komunikasi Visual</option>
                        <option value="3" id="option_jurusan_3">S1 Elektro</option>
                        <option value="4" id="option_jurusan_4">S1 Sistem Informasi Bisnis</option>
                        <option value="5" id="option_jurusan_5">S1 Industri</option>
                        <option value="6" id="option_jurusan_6">S1 Desain Produk</option>
                        <option value="7" id="option_jurusan_7">D3 Informatika</option>
                        <option value="8" id="option_jurusan_8">Bachelor of Informatics Technology</option>
            `);
        }
    </script>
@endsection
