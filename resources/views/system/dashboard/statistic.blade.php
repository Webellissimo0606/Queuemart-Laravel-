@extends('layouts.system')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Statistic</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Statistic</li>
            </ol>
        </div>                    
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="box-title">Filter</h3>
                    <hr class="m-t-0 m-b-40">
                    <!--/row-->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-primary active">
                                    <input name="options" id="option1" autocomplete="off" checked="" type="radio" value="day"> Month
                                </label>
                                <label class="btn btn-primary">
                                    <input name="options" id="option2" autocomplete="off" type="radio" value="month"> Year
                                </label>                                
                            </div>
                        </div>                        
                        <div class="col-md-8" id="day_select">
                            <div class="form-group">
                                <input type="text" class="form-control input-sm from" >
                            </div>
                        </div>
                        <div class="col-md-8" id="month_select" hidden>
                            <div class="form-group">
                                <select class="custom-select">
                                </select>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>          

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="{{asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
        <div class="col-md-12" style="margin-bottom: 30px;">
            <div id="chart_branch" style="width: 100%; height: 300px; margin: 0 auto;"></div>
        </div>
        <div class="col-md-12" style="margin-bottom: 30px;">
            <div id="chart_first" style="width: 100%; height: 300px; margin: 0 auto;"></div>
        </div>
        <div class="col-md-12" style="margin-bottom: 30px;">
            <div id="chart_branch_revenue" style="width: 100%; height: 300px; margin: 0 auto;"></div>
        </div>
        <div class="col-md-12" style="margin-bottom: 30px;">
            <div id="chart_service_revenue" style="width: 100%; height: 300px; margin: 0 auto;"></div>
        </div>
        <div class="col-md-6" style="margin-bottom: 30px;">
            <div id="chart_status" style="width: 100%; height: 400px; margin: 0 auto;"></div>
        </div>
        <div class="col-md-6" style="margin-bottom: 30px;">
            <div id="chart_signIn" style="width: 100%; height: 400px; margin: 0 auto;"></div>
        </div>
        <div class="col-md-12" style="margin-bottom: 30px;">
            <div id="chart_payment" style="width: 100%; height: 400px; margin: 0 auto;"></div>
        </div>

        <div class="col-md-12" style="margin-bottom: 30px;">
          <div class="card">
            <div class="card-body row">
              <div class="col-md-6">
                <div id="chart_rating" style="width: 100%; height: 400px; margin: 0 auto;"></div>
              </div>
              <div class="col-md-6">
                @if (count($ratings) > 0)
                    <section class="articles" style="height: 100%;">
                        @include('system.dashboard.rating_pagination')
                    </section>
                @endif
              </div>
            </div>
          </div>
        </div>
        
    </div>

    <script type="text/javascript">
        var col_array = [];
        var total_data = [];        
        var branch_data = [];    
        var branch_revenue_data = [];    
        var service_revenue_data = [];    

        var selectedDate = new Date();
        $('.from').datepicker({
            autoclose: true,
            minViewMode: 1,
            format: 'mm/yyyy'
        }).on('changeDate', function(selected){
                selectedDate = new Date(selected.date.valueOf());
                var pic_month = selectedDate.getMonth()+1;
                if (pic_month < 10) {
                    pic_month = '0'+pic_month;
                }
                var pic_year = selectedDate.getFullYear();
                $.get("{{URL::to('admin/getChart')}}",
                {
                    user_id: '{{$user_id}}',
                    chart_type: 'month',
                    current_year: pic_year,
                    current_month: pic_month
                }, function(data) {
                    col_array = data.col;
                    total_data = data.row;
                    branch_data = data.branch;
                    branch_revenue_data = data.branch_revenue;
                    service_revenue_data = data.service_revenue;
                    openChart(col_array, total_data);
                    openChartBranch(col_array, branch_data);
                    openChartBranchRevenue(col_array, branch_revenue_data);
                    openChartServiceRevenue(col_array, service_revenue_data);
                });
            }); 

        var now_year = new Date().getFullYear();
        var now_month = new Date().getMonth()+1;
        if (now_month < 10) {
            now_month = '0'+now_month;
        }
        $('#day_select input').val(now_month+'/'+now_year);

        $.get("{{URL::to('admin/getChart')}}",
        {
            user_id: '{{$user_id}}',
            chart_type: 'month',
            current_year: now_year,
            current_month: now_month
        }, function(data) {
            col_array = data.col;
            total_data = data.row;
            branch_data = data.branch;
            branch_revenue_data = data.branch_revenue;
            service_revenue_data = data.service_revenue;
            openChart(col_array, total_data);
            openChartBranch(col_array, branch_data);
            openChartBranchRevenue(col_array, branch_revenue_data);
            openChartServiceRevenue(col_array, service_revenue_data);
        });

        
        var status = 'day';
        $('input:radio[name="options"]').change(function(){
            var status = $('input:radio[name="options"]:checked').val();
            if (status == 'day') {
                $('#day_select').removeAttr('hidden');
                $('#month_select').attr('hidden', '');
                $('#day_select input').val(now_month+'/'+now_year);
                $.get("{{URL::to('admin/getChart')}}",
                {
                    user_id: '{{$user_id}}',
                    chart_type: 'month',
                    current_year: now_year,
                    current_month: now_month
                }, function(data) {
                    col_array = data.col;
                    total_data = data.row;
                    branch_data = data.branch;
                    branch_revenue_data = data.branch_revenue;
                    service_revenue_data = data.service_revenue;
                    openChart(col_array, total_data);
                    openChartBranch(col_array, branch_data);
                    openChartBranchRevenue(col_array, branch_revenue_data);
                    openChartServiceRevenue(col_array, service_revenue_data);
                });
            } else if (status == 'month') {
                $('#month_select').removeAttr('hidden');
                $('#day_select').attr('hidden', '');
                var html_options = '';
                for (var i = 0; i < 10; i++) {
                    html_options += '<option value="'+(now_year-i)+'">'+(now_year-i)+'</option>';
                }
                $('#month_select select').empty().html(html_options);
                $.get("{{URL::to('admin/getChart')}}",
                {
                    user_id: '{{$user_id}}',
                    chart_type: 'year',
                    current_year: now_year
                }, function(data) {
                    col_array = data.col;
                    total_data = data.row;
                    branch_data = data.branch;
                    branch_revenue_data = data.branch_revenue;
                    service_revenue_data = data.service_revenue;
                    openChart(col_array, total_data);
                    openChartBranch(col_array, branch_data);
                    openChartBranchRevenue(col_array, branch_revenue_data);
                    openChartServiceRevenue(col_array, service_revenue_data);
                });
            }
        })

        $('#month_select select').change(function(){

            $.get("{{URL::to('admin/getChart')}}",
            {
                user_id: '{{$user_id}}',
                chart_type: 'year',
                current_year: $(this).val()
            }, function(data) {
                col_array = data.col;
                total_data = data.row;
                branch_data = data.branch;
                branch_revenue_data = data.branch_revenue;
                service_revenue_data = data.service_revenue;
                openChart(col_array, total_data);
                openChartBranch(col_array, branch_data);
                openChartBranchRevenue(col_array, branch_revenue_data);
                openChartServiceRevenue(col_array, service_revenue_data);       
            });
        })

        function openChart(col_array, total_data) {
            Highcharts.chart('chart_first', {
                  chart: {
                    type: 'line'
                  },
                  title: {
                    text: 'Total bookings by Services'
                  },
                  subtitle: {
                    text: 'Source: queuemart.me'
                  },
                  xAxis: {
                    categories: col_array
                  },
                  yAxis: {
                    title: {
                      text: 'Visit(s)'
                    }
                  },
                  plotOptions: {
                    line: {
                      dataLabels: {
                        enabled: true
                      },
                      enableMouseTracking: true
                    }
                  },
                  series: total_data
                });
        }

        function openChartBranch(col_array, branch_data) {
            Highcharts.chart('chart_branch', {
                  chart: {
                    type: 'line'
                  },
                  title: {
                    text: 'Total bookings by Branches'
                  },
                  subtitle: {
                    text: 'Source: queuemart.me'
                  },
                  xAxis: {
                    categories: col_array
                  },
                  yAxis: {
                    title: {
                      text: 'Visit(s)'
                    }
                  },
                  plotOptions: {
                    line: {
                      dataLabels: {
                        enabled: true
                      },
                      enableMouseTracking: true
                    }
                  },
                  series: branch_data
                });
        }

        function openChartBranchRevenue(col_array, branch_revenue_data) {
            Highcharts.chart('chart_branch_revenue', {
                  chart: {
                    type: 'line'
                  },
                  title: {
                    text: 'Revenue by Branches'
                  },
                  subtitle: {
                    text: 'Source: queuemart.me'
                  },
                  xAxis: {
                    categories: col_array
                  },
                  yAxis: {
                    title: {
                      text: 'Revenue(RM)'
                    }
                  },
                  plotOptions: {
                    line: {
                      dataLabels: {
                        enabled: true
                      },
                      enableMouseTracking: true
                    }
                  },
                  series: branch_revenue_data
                });
        }

        function openChartServiceRevenue(col_array, service_revenue_data) {
            Highcharts.chart('chart_service_revenue', {
                  chart: {
                    type: 'line'
                  },
                  title: {
                    text: 'Revenue by Services'
                  },
                  subtitle: {
                    text: 'Source: queuemart.me'
                  },
                  xAxis: {
                    categories: col_array
                  },
                  yAxis: {
                    title: {
                      text: 'Revenue(RM)'
                    }
                  },
                  plotOptions: {
                    line: {
                      dataLabels: {
                        enabled: true
                      },
                      enableMouseTracking: true
                    }
                  },
                  series: service_revenue_data
                });
        }

        var statusChart = [];
        statusChart = <?php echo json_encode($stautsChart); ?>;
        Highcharts.chart('chart_status', {
                  chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
          },
          title: {
            text: 'Total booking by Statuses'
          },
          tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
          },
          plotOptions: {
            pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                  color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
              }
            }
          },
          series: [{
            name: 'Status',
            data: statusChart
          }]
        });


        var signChart = [];
        signChart = <?php echo json_encode($signChart); ?>;
        Highcharts.chart('chart_signIn', {
          chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
          },
          title: {
            text: 'Client Sign-In'
          },
          tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
          },
          plotOptions: {
            pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                enabled: false
              },
              showInLegend: true
            }
          },
          series: [{
            name: 'Sign In',
            colorByPoint: true,
            data: signChart
          }]
        });


        var payment_data = [];
        payment_data = <?php echo json_encode($payment_data); ?>;
        Highcharts.chart('chart_payment', {
          chart: {
            type: 'bar'
          },
          title: {
            text: 'Activities by user'
          },
          xAxis: {
            categories: ['Make Booking', 'Make Payment Online', 'Reschedule Booking', 'Cancel Booking']
          },
          yAxis: {
            min: 0            
          },
          legend: {
            reversed: true
          },
          plotOptions: {
            series: {
              stacking: 'normal'
            }
          },
          series: payment_data
        });

        var ratingChart = [];
        ratingChart = <?php echo json_encode($ratingChart); ?>;
        Highcharts.chart('chart_rating', {
          chart: {
            type: 'bar'
          },
          title: {
            text: 'Rating by Branches'
          },
          xAxis: {
            categories: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star']
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Numbers'
            }
          },
          legend: {
            reversed: true
          },
          plotOptions: {
            series: {
              stacking: 'normal'
            }
          },
          series: ratingChart
        });
        
    </script>


    <!-- Row -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
@endsection           