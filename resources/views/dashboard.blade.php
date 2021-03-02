@extends('layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
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
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Daftar <br>Mahasiswa</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <a href="/rmh/list_mhs" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Absensi <br><br></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <a href="/rmh/admin_absensi" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Laporan <br><br></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-chart-bar"></i>
                    </div>
                    <a href="/rmh/laporan" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('dashboard_scripts')
  <script>
      $(document).ready(function () {
          $.ajax({
            method: 'get',
            url: '/rmh/getEvents',
            success: function(res){
                let events = [];
                console.log(res);
                res.data_mhs.forEach(data => {
                    let date = data.ttl.split('-');
                    event = {
                        title: data.nama + " Birthday",
                        start: new Date(date[0], date[1]-1, date[2]),
                        backgroundColor: '#f56954', //red
                        borderColor    : '#f56954', //red
                        allDay         : true
                    };
                    events.push(event);
                });
                res.data_event.forEach(data => {
                    let date = data.tanggal_absensi.split('-');
                    event = {
                        title: data.judul_absensi,
                        start: new Date(date[0], date[1]-1, date[2]),
                        backgroundColor: '#5C40D5', //red
                        borderColor    : '#5C40D5', //red
                        allDay         : true
                    };
                    events.push(event);
                });
                var Calendar = FullCalendar.Calendar;
                var calendarEl = document.getElementById('calendar');
                var calendar = new Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    themeSystem: 'bootstrap',
                    events: events,
                    editable: false,
                    droppable: false, // this allows things to be dropped onto the calendar !!!
                    // drop: function (info) {
                    //     // is the "remove after drop" checkbox checked?
                    //     if (checkbox.checked) {
                    //         // if so, remove the element from the "Draggable Events" list
                    //         info.draggedEl.parentNode.removeChild(info.draggedEl);
                    //     }
                    // }
                });
                calendar.render();
            }
          });
      });
  </script>
@endsection
