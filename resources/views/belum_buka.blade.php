<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IFJ PRESENSI</title>

  <link rel="icon" href="{{ asset('logo_ifj.png') }}" sizes="16x16" type="image/png">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">

  {{-- Datatable --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">

  {{-- <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}"> --}}
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo">
        <h2>Maaf, Absensi Belum Dibuka. God Bless You!</h2>
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

<script>
    $(document).ready(function(){
        generateJurusan();

        $("#form-absensi").submit(e => {
            e.preventDefault();
            $.ajax({
                method: 'post',
                url: '/absen',
                data: $('#form-absensi').serialize(),
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
                res.forEach(jurusan => {
                    $("#select-jurusan").append(`
                        <option id="jurusan_id_${jurusan.id_jurusan}" value="${jurusan.id_jurusan}">${jurusan.nama_jurusan}</option>
                    `);
                });
            }
        });
    };
</script>

</body>
</html>
