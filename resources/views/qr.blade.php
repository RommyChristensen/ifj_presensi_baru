<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IFJ PRESENSI | QR CODE</title>

  <link rel="icon" href="{{ asset('logo_ifj.png') }}" sizes="16x16" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
</head>
<body class="hold-transition @auth mini-sidebar @endauth @if(Auth::check() == false) login-page @endif">
<div class="wrapper">
    <form class="text-center" id="form-check-qr" accept-charset="utf-8">
        <div class="row mt-5">
            <div class="col-md-12">
                <h2>Cek QR Code Anda</h2>
                <input type="text" id="nrp" class="form-control col-xs-8 col-sm-2 col-md-2 mx-auto my-3" placeholder="Masukkan NRP">
                <button class="btn btn-success" type="submit">Check</button>
                <div class="btn-download-wrapper my-3">
                    {{-- <a id="btn-download" class="btn btn-primary disabled" download>Download</a><br> --}}
                </div>
                <div class="img-wrapper">
                    {{-- <img class="img-thumbnail" src="{{asset('qrcode.png')}}" width="150" height="150" style="margin-top: 20px"> --}}
                </div>
            </div>
        </div>
    </form>

</div>
<!-- ./wrapper -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

<script>
    $(document).ready(function(){
        $("#form-check-qr").submit(function(e){
            e.preventDefault();
            $.ajax({
                method: 'get',
                url: '/qr_code/check/'+$("#nrp").val(),
                success: function(res){
                    console.log(res);
                    if(res != ""){
                        $(".img-wrapper").append(`
                            <img class="img-thumbnail" src="{{asset('${res}')}}" width="150" height="150" style="margin-top: 20px">
                        `);
                        $(".btn-download-wrapper").append(`
                            <a href="{{asset('${res}')}}" id="btn-download" class="btn btn-primary" download>Download</a>
                        `);
                    }else{
                        swal("QR Code Belum Dibuat!", "Hubungi Pengurus PD ya!", "error", {
                            button: "Okay",
                        });
                    }
                }
            });
        });
    });
</script>

</body>
</html>
