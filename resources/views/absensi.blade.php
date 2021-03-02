<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IFJ PRESENSI</title>

  <link rel="icon" href="{{ asset('logo_ifj.png') }}" sizes="16x16" type="image/png">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
  <script type="text/javascript" src="{{ asset('instascan2.min.js') }}"></script>
</head>
<body class="hold-transition">

<style>
    .container-fluid:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        transition: all 0.8s;
        opacity: 0.5;
        background: black;
        background-size: 100% 100%;
    }
</style>

<div class="container-fluid" style="background-image: url('{{asset('christmas.png')}}'); background-size: cover;">
    <div class="row">
        <div class="col-sm-12 col-md-5" id="form-absensi">
            <div class="d-flex" style="height: 100vh">
                <div class="login-box mx-auto align-self-center">
                    <div class="login-logo text-white">
                        <a href="/rmh" class="text-white"><b>IFJ</b> Presensi</a> <br>
                        {{$header_kehadiran->judul_absensi}}
                    </div>
                    <div class="card">
                        <div class="card-body login-card-body">
                            <form id="form-absensi" method="post">
                                @csrf
                                <input type="hidden" value="{{$header_kehadiran->id_header_kehadiran}}" name="id_header_kehadiran">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="nrp" placeholder="NRP" id="input_nrp">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-key"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="nama" placeholder="Nama" id="input_nama" disabled>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control" name="ttl" placeholder="TTL" id="input_ttl" disabled>
                                </div>
                                <div class="input-group mb-3">
                                    <select name="jurusan" id="select-jurusan" class="form-control" name="id_jurusan" disabled>

                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-graduation-cap"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <button type="submit" class="btn btn-success btn-block">Absen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-info btn-flat btn-sm" id="btn-pindah-ke-scan">QR Code Scanner</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-5" id="scan-qr-code">
            <div class="d-flex" style="height: 100vh">
                <div class="login-box mx-auto align-self-center">
                    <div class="login-logo text-white">
                        <a href="/rmh" class="text-white"><b>IFJ</b> Presensi</a> <br>
                        {{$header_kehadiran->judul_absensi}}
                    </div>
                    <video id="preview" class="col-sm-12 col-md-12"></video>
                    <div class="text-center">
                        <button class="btn btn-info btn-flat btn-sm" id="btn-pindah-ke-absensi">Pindah Ke Form Absensi</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-7">
            <div class="d-flex" style="height: 100vh">
                <div class="mx-auto align-self-center">
                    <div class="card" style="height: 50vh; overflow-y: scroll">
                        <div class="card-body">
                            <h1>Kehadiran</h1>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-black" id="table-recent-absen">
                                    <thead>
                                        <th>NRP</th>
                                        <th>Nama</th>
                                        <th>Jam Absen</th>
                                    </thead>
                                    <tbody id="body-table-recent-absen">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
{{-- Datatables --}}
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>

<script>
    $(document).ready(function(){
        generateJurusan();
        getDetailKehadiran();
        $("#scan-qr-code").hide();

        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            scanner.addListener('scan', function (content) {
                $.ajax({
                    method: 'post',
                    url: '/absen',
                    data: {
                        '_token' : '{{csrf_token()}}',
                        'nrp' : content,
                        'id_header_kehadiran' : '{{$header_kehadiran->id_header_kehadiran}}'
                    },
                    success: function(code){
                        if(code == 200){
                            swal("Absensi Berhasil!", "God Bless You!", "success", {
                                button: "Okay",
                            });
                        }else if(code == 201){
                            swal("Anda Sudah Absen!", "God Bless You!", "success", {
                                button: "Okay",
                            });
                        }else{
                            swal("Absensi Gagal!", "Coba Lagi Nanti Ya!", "error", {
                                button: "Okay",
                            });
                        }
                        getDetailKehadiran();
                    }
                });
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                scanner.start(cameras[0]);
                } else {
                console.error('No cameras found.');
                }
            }).catch(function (e) {
            console.error(e);
        });

        $("#btn-pindah-ke-absensi").click(function(){
            $("#scan-qr-code").hide(500, function(){
                $("#form-absensi").show(500);
            });
        });

        $("#btn-pindah-ke-scan").click(function(){
            $("#form-absensi").hide(500, function(){
                $("#scan-qr-code").show(500);
            });
        });

        $("#form-absensi").submit(e => {
            e.preventDefault();
            let data;
            if(document.getElementById("input_nama").hasAttribute('disabled')){
                data = {
                    "_token" : "{{csrf_token()}}",
                    'nrp' : $("#input_nrp").val(),
                    'id_header_kehadiran' : '{{$header_kehadiran->id_header_kehadiran}}'
                }
            }else{
                data = {
                    "_token" : "{{csrf_token()}}",
                    'nrp' : $("#input_nrp").val(),
                    'nama' : $("#input_nama").val(),
                    'ttl' : $("#input_ttl").val(),
                    'jurusan' : $("#select-jurusan").val(),
                    'id_header_kehadiran' : '{{$header_kehadiran->id_header_kehadiran}}'
                }
            }
            $.ajax({
                method: 'POST',
                url: '/absen',
                data: data,
                success: function(code){
                    if(code == 200){
                        swal("Absensi Berhasil!", "God Bless You!", "success", {
                            button: "Okay",
                        });
                    }else if(code == 201){
                        swal("Anda Sudah Absen!", "God Bless You!", "success", {
                            button: "Okay",
                        });
                    }else{
                        swal("Absensi Gagal!", "Coba Lagi Nanti Ya!", "error", {
                            button: "Okay",
                        });
                    }
                    $("#select-jurusan").html('');
                    $("#input_nama").val('');
                    $("#input_ttl").val('');
                    $("#input_nrp").val('');
                    generateJurusan();
                    getDetailKehadiran();
                }
            });
        });

        // $("#input_nama").removeAttr('disabled');
        $("#input_nrp").keyup(function(){
            if($(this).val().length == 9){
                $.ajax({
                    method: "post",
                    url: "/checkNrp",
                    data: {
                        "_token" : "{{ csrf_token() }}",
                        "nrp" : $(this).val()
                    },
                    success: function(res){
                        if(res != false){
                            $("#input_nama").attr('disabled', 'true');
                            $("#input_ttl").attr('disabled', 'true');
                            $("#select-jurusan").attr('disabled', 'true');

                            $("#input_nama").val(res.nama);
                            $("#input_ttl").val(res.ttl);
                            $("#jurusan_id_"+res.id_jurusan).attr('selected', 'true');
                        }else{
                            swal("Daftar", "NRP anda belum terdaftar, harap isi form terlebih dahulu", "warning", {
                                button: "nghokey",
                            });
                            generateJurusan();
                            $("#input_nama").removeAttr('disabled');
                            $("#input_ttl").removeAttr('disabled');
                            $("#select-jurusan").removeAttr('disabled');
                        }
                    }
                });
            }else{
                $("#select-jurusan").html('');
                $("#input_nama").val('');
                $("#input_ttl").val('');
                generateJurusan();
            }
        });
    });

    const generateJurusan = () => {
        $.ajax({
            method: 'get',
            url: '/rmh/getJurusan',
            success: function(res){
                $("#select-jurusan").html('');
                res.forEach(jurusan => {
                    $("#select-jurusan").append(`
                        <option id="jurusan_id_${jurusan.id_jurusan}" value="${jurusan.id_jurusan}">${jurusan.nama_jurusan}</option>
                    `);
                });
            }
        });
    };

    const getDetailKehadiran = () => {
        let id = '{{$header_kehadiran->id_header_kehadiran}}';
        $.ajax({
            method: 'get',
            url: '/rmh/absensi/getDataDetail/'+id,
            success: function(res){
                $("#body-table-recent-absen").html('');
                res.forEach(data => {
                    let date = moment(data.created_at + "+07:00", "YYYY-MM-DD HH:mm:ssZ");
                    $("#body-table-recent-absen").append(`
                        <tr>
                            <td>${data.nrp}</td>
                            <td>${data.mahasiswa.nama}</td>
                            <td>${date.fromNow()}</td>
                        </tr>
                    `);
                });
            }
        })
    }

    function prettyDate(time) {
        var date = new Date((time || "").replace(/-/g, "/").replace(/[TZ]/g, " ")),
            diff = (((new Date()).getTime() - date.getTime()) / 1000),
            day_diff = Math.floor(diff / 86400);

        if (isNaN(day_diff) || day_diff < 0 || day_diff >= 31) return;

        return day_diff == 0 && (
        diff < 60 && "just now" || diff < 120 && "1 minute ago" || diff < 3600 && Math.floor(diff / 60) + " minutes ago" || diff < 7200 && "1 hour ago" || diff < 86400 && Math.floor(diff / 3600) + " hours ago") || day_diff == 1 && "Yesterday" || day_diff < 7 && day_diff + " days ago" || day_diff < 31 && Math.ceil(day_diff / 7) + " weeks ago";
    }
</script>

</body>
</html>
