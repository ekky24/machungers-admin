<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">
  <!-- summernote wysihtml5 - text editor -->
  <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
  <!-- custom css -->
  <link rel="stylesheet" type="text/css" href="/css/style.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('layout.navbar-header')
    @include('layout.sidebar')
    @yield('content')
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Theme by </b><a href="https://adminlte.io">Almsaeed Studio</a>
        </div>
        <strong>Copyright &copy; 2019 Universitas Ma Chung.</strong> All rights
        reserved.
      </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- FastClick -->
<script src="/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="/bower_components/chart.js/Chart.js"></script>
<!-- date-range-picker -->
<script src="/bower_components/moment/min/moment.min.js"></script>
<script src="/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>
<!-- Bootstrap Summernote -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
<!-- sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
  (function($) {
      //bootstrap WYSIHTML5 - text editor
      $('#html-editor').summernote()
      //Date picker
      $('#tgl-mulai, #tgl-selesai').datepicker({
        autoclose: true,
        format: "dd/mm/yyyy",
        todayHighlight: true,
      })

      $('#data-table').DataTable()

      $('.btn-delete').click(function(e) {
        e.preventDefault()
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Data yang telah dihapus tidak bisa dikembalikan lagi.",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya'
        }).then((result) => {
          if (result.value) {
            window.location = $(this).attr("href")
          }
        })
      })

      setTimeout(function() {
        $('.alert').slideDown(1000)
        setTimeout(function() {
          $('.alert').slideUp(1000)
        }, 3000)
      }, 1000)

      if($('#barChartFakultas').length) {
        var dataCountFakultas = []
        var dataLabelFakultas = []
        var dataCountProdi = []
        var dataLabelProdi = []

        $.ajax({
          url: '/ajax_mahasiswa_fakultas',
          type:"GET",
          success:function(msg){
            msg = JSON.parse(msg)
            $.each(msg.fakultas, function(i, item) {              
              dataLabelFakultas.push(item[1])
              dataCountFakultas.push(item[2])
            })
            $.each(msg.prodi, function(i, item) {              
              dataLabelProdi.push(item[1])
              dataCountProdi.push(item[2])
            })
            $('.lds-ring').hide()
            $('#barChartFakultas').show()
            $('#barChartProdi').show()
            showChart('#barChartFakultas', dataLabelFakultas, dataCountFakultas)
            showChart('#barChartProdi', dataLabelProdi, dataCountProdi)
          }
        })

        function showChart(ctx_name, dataLabel, dataCount) {
          var chartData = {
            labels  : dataLabel,
            datasets: [
              {
                label               : 'Jumlah',
                fillColor           : 'rgba(210, 214, 222, 1)',
                strokeColor         : 'rgba(210, 214, 222, 1)',
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data                : dataCount
              }
            ]
          }
          var barChartCanvas                   = $(ctx_name).get(0).getContext('2d')
          var barChart                         = new Chart(barChartCanvas)
          var barChartData                     = chartData
          barChartData.datasets[0].fillColor   = '#36a2eb'
          barChartData.datasets[0].strokeColor = '#36a2eb'
          barChartData.datasets[0].pointColor  = '#36a2eb'
          var barChartOptions                  = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero        : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : true,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - If there is a stroke on each bar
            barShowStroke           : true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth          : 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing         : 40,
            //Number - Spacing between data sets within X values
            barDatasetSpacing       : 1,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to make the chart responsive
            responsive              : true,
            maintainAspectRatio     : true,
            multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>",
            legend: {
              display: true
            },
            scales: {
              xAxes: [{
                  maxBarThickness: 10
              }]
            }
          }

          barChartOptions.datasetFill = false
          barChart.Bar(barChartData, barChartOptions)
        }
      }
  })(jQuery);
</script>
</body>
</html>
