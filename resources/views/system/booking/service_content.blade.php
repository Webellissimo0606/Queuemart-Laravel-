
@if($service != '')
<div class="card card-outline-secondary" id="{{$service->id}}">
    <div class="card-header">
        <h4 class="m-b-0 text-black service_name">{{$service->service_name}}</h4>
    </div>
    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"> <a class="nav-link active show" data-toggle="tab" href="#profile" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">PROFILE</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#scheduling" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">SCHEDULING</span></a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#package" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">PACKAGE</span></a> </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content tabcontent-border">
            <div class="tab-pane active show " id="profile" role="tabpanel" style="min-height: 300px;">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">     
                                <form class="form-horizontal" action="{{ URL::to('admin/updateService') }}" method="POST" id="edit_service_form" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-body">
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="control-label">Name Your Service :</label>
                                                        <input class="form-control" type="text" id="service_name" name="service_name" value="{{$service->service_name}}">
                                                        <input class="form-control" type="text" id="id" name="id" value="{{$service->id}}" hidden>
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label class="control-label">Description (Optional) :</label>
                                                        <textarea class="form-control my-editor" rows="5" id="service_des" name="service_des">{{$service->service_des}}</textarea>

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <img src="{{$service->service_image}}" class="img-responsive" style="margin-bottom: 10px; max-height: 400px;">
                                                        <input type="file" id="service_image" name="service_image" />
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="box-title">Participants</h3>
                                            <hr class="m-t-0 m-b-20">
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <div class="col-12 text-center m-b-10">
                                                            <label class="control-label">Max Participants / Session</label>
                                                        </div>
                                                        <div class="col-12 text-center">
                                                            <input class="form-control text-center" id="service_participants" name="service_participants" type="number" value="{{$service->service_participants}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <div class="col-12 text-center m-b-10">
                                                            <label class="control-label">Show Sequence</label>
                                                        </div>
                                                        <div class="col-12 text-center">
                                                            <input class="form-control text-center" id="show_sequence" name="show_sequence" type="number" value="{{$service->show_sequence}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <h3 class="box-title">Service Type</h3>
                                            <hr class="m-t-0 m-b-20">
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <select class="custom-select col-12" id="service_type" name="service_type">
                                                            <option value="seminar">Seminar</option>
                                                            <option value="carservice">Fixed-hour</option>
                                                            <option value="salon">Roster-hour</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> 
                                            <hr class="m-t-0 m-b-10">                             
                                        </div>                                    
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-offset-3 col-md-9">
                                                            <button onclick="editService()" type="button" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                                                            <button type="button" onclick="deleteService('{{$service->id}}')" class="btn btn-danger"><i class="fa fa-remove"></i> Delete</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form> 
                            </div>
                        </div>
                    </div>              
                </div>
            </div>
            <div class="tab-pane p-20" id="scheduling" role="tabpanel" style="min-height: 300px;">
                @if($service->service_type == 'seminar')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="box-title">Event Date</h3>
                                <hr class="m-t-0 m-b-20">
                                <div class="row">
                                    <div class="checkbox checkbox-success col-11" style="margin-left: 20px;">                                    
                                        <input id="duration_show" type="checkbox" name="duration_show" value="{{$timeslot->duration_show}}">
                                        <label for="duration_show">Show</label>
                                    </div>
                                    <div class="col-12">
                                        <label class="control-label">Service Duration:</label>
                                        <input class="form-control input-daterange-datepicker" type="text" name="service_duration" id="service_duration" value="{{$timeslot->service_duration}}" /> 
                                    </div>
                                    <div class="col-12" style="margin-top: 20px;">
                                        <label class="control-label">Service Start Time:</label>
                                        <input class="form-control" value="{{$timeslot->start_time}}" id="service_start_time" type="time">
                                    </div>
                                    <div class="col-12" style="margin-top: 20px;">
                                        <button type="button" class="btn btn-success" onclick="seminarDuration('{{$service->id}}')"><i class="fa fa-check"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>    
                // Daterange picker
                $('.input-daterange-datepicker').daterangepicker({
                    buttonClasses: ['btn', 'btn-sm'],
                    applyClass: 'btn-danger',
                    cancelClass: 'btn-inverse'
                });
                $('.input-daterange-timepicker').daterangepicker({
                    timePicker: true,
                    format: 'MM/DD/YYYY h:mm A',
                    timePickerIncrement: 30,
                    timePicker12Hour: true,
                    timePickerSeconds: false,
                    buttonClasses: ['btn', 'btn-sm'],
                    applyClass: 'btn-danger',
                    cancelClass: 'btn-inverse'
                });
                $('.input-limit-datepicker').daterangepicker({
                    format: 'MM/DD/YYYY',                    
                    buttonClasses: ['btn', 'btn-sm'],
                    applyClass: 'btn-danger',
                    cancelClass: 'btn-inverse',
                    dateLimit: {
                        days: 6
                    }
                });

                var duration_show = $('#duration_show');
                if (duration_show.val() == 1) {
                    duration_show.attr('checked', true);
                } else {
                    duration_show.attr('checked', false);
                }

                duration_show.change(function(){
                    if (duration_show.val() == 1) {
                        duration_show.val(0);
                    } else {
                        duration_show.val(1);
                    }
                })

                function seminarDuration(id) {
                    var service_id = id;
                    var duration_show = $("#duration_show").val();
                    var service_duration = $("input#service_duration").val();
                    var service_start_time = $("input#service_start_time").val();
                    $('.preloader').css('display', 'block');
                    $.get('{{URL::to("admin/updateTimeslot")}}', 
                        { 
                            service_id:service_id, 
                            duration_show:duration_show, 
                            service_duration:service_duration, 
                            service_start_time:service_start_time 
                        }, function(data){
                            alert('success');
                            $('.preloader').css('display', 'none');
                    })
                }
                </script>
                @endif
                @if($service->service_type == 'carservice')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">     
                                <form class="form-horizontal" action="{{ URL::to('admin/saveFixedHours') }}" method="POST" id="edit_timeslot_form" enctype="multipart/form-data">
                                    <div class="form-body">  
                                        <input type="text" name="id" id="id" value="{{$timeslot->id}}" hidden>
                                        <input type="text" name="service_id" id="service_id" value="{{$service->id}}" hidden>
                                        <h3 class="box-title">Booking Frequency</h3>
                                        <hr class="m-t-0 m-b-20">
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-2">
                                                <div class="form-group row">                              
                                                    <div class="col-12 text-center">
                                                        <input class="form-control text-center" id="duration" name="duration" type="text" value="{{$timeslot->duration}}">
                                                        <label>minutes</label>
                                                    </div>                                                        
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="col-5 col-form-label">Customer Can Book Up to :</label>
                                                    <div class="col-4">
                                                        <select name="calendar" id="calendar" class="custom-select">
                                                            <option value="1">1 month</option>
                                                            <option value="2">2 months</option>                    
                                                            <option value="3">3 months</option>                    
                                                            <option value="4">4 months</option>                    
                                                            <option value="5">5 months</option>                    
                                                            <option value="6">6 months</option>                    
                                                            <option value="7">7 months</option>                    
                                                            <option value="8">8 months</option>                    
                                                            <option value="9">9 months</option>                    
                                                            <option value="10">10 months</option>                    
                                                            <option value="11">11 months</option>                    
                                                            <option value="12">12 months</option>                    
                                                        </select>
                                                    </div>
                                                    <label for="example-text-input" class="col-3 col-form-label">for future</label>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="col-5 col-form-label">late if not arrived after :</label>
                                                    <div class="col-4">
                                                        <select name="arrived" id="arrived" class="custom-select">
                                                            <?php 

                                                            for ($i=1; $i <61 ; $i++) { 
                                                                if ($i == 1) {
                                                                    $option = '<option value="'.$i.'">'.$i.' minute</option>';
                                                                } else {
                                                                    $option = '<option value="'.$i.'">'.$i.' minutes</option>';
                                                                }
                                                                
                                                                echo $option;
                                                            }

                                                            ?>                    
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="col-6 col-form-label">Show Estimated Waiting Time :</label>
                                                    <div class="col-4">
                                                        <select name="show_estimated" id="show_estimated" class="custom-select">
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>                    
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="example-text-input" class="col-6 col-form-label">Allow Reschedule Online :</label>
                                                    <div class="col-4">
                                                        <select name="reschedule_allow" id="reschedule_allow" class="custom-select">
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>                    
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <h3 class="box-title">Time Wall</h3>
                                        <hr class="m-t-0 m-b-20">
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <div class="col-8 offset-4">
                                                        Please make sure time slot type : <strong>09:00 12:30 16:45 20:00</strong> ....
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 offset-1">
                                                        <div class="checkbox checkbox-success">
                                                            <input id="monday_active" type="checkbox" class="checkbox" value="{{$timeslot->monday_active}}" name="monday_active">
                                                            <label for="monday_active"> Monday </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="input-group"> 
                                                            <input type="text" name="monday_val" id="monday_val" value="{{$timeslot->monday_val}}" data-role="tagsinput" placeholder="add time">
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="form-group row">
                                                    <div class="col-3 offset-1">
                                                        <div class="checkbox checkbox-success">
                                                            <input id="tuesday_active" type="checkbox" class="checkbox" value="{{$timeslot->tuesday_active}}" name="tuesday_active">
                                                            <label for="tuesday_active"> Tuesday </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="input-group"> 
                                                            <input type="text" name="tuesday_val" id="tuesday_val" value="{{$timeslot->tuesday_val}}" data-role="tagsinput" placeholder="add time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 offset-1">
                                                        <div class="checkbox checkbox-success">
                                                            <input id="wednesday_active" type="checkbox" class="checkbox" value="{{$timeslot->wednesday_active}}" name="wednesday_active">
                                                            <label for="wednesday_active"> Wednesday </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="input-group"> 
                                                            <input type="text" name="wednesday_val" id="wednesday_val" value="{{$timeslot->wednesday_val}}" data-role="tagsinput" placeholder="add time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 offset-1">
                                                        <div class="checkbox checkbox-success">
                                                            <input id="thursday_active" type="checkbox" class="checkbox" value="{{$timeslot->thursday_active}}" name="thursday_active">
                                                            <label for="thursday_active"> Thursday </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="input-group"> 
                                                            <input type="text" name="thursday_val" id="thursday_val" value="{{$timeslot->thursday_val}}" data-role="tagsinput" placeholder="add time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 offset-1">
                                                        <div class="checkbox checkbox-success">
                                                            <input id="friday_active" type="checkbox" class="checkbox" value="{{$timeslot->friday_active}}" name="friday_active">
                                                            <label for="friday_active"> Friday </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="input-group"> 
                                                            <input type="text" name="friday_val" id="friday_val" value="{{$timeslot->friday_val}}" data-role="tagsinput" placeholder="add time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 offset-1">
                                                        <div class="checkbox checkbox-success">
                                                            <input id="saturday_active" type="checkbox" class="checkbox" value="{{$timeslot->saturday_active}}" name="saturday_active">
                                                            <label for="saturday_active"> Saturday </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="input-group"> 
                                                            <input type="text" name="saturday_val" id="saturday_val" value="{{$timeslot->saturday_val}}" data-role="tagsinput" placeholder="add time">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 offset-1">
                                                        <div class="checkbox checkbox-success">
                                                            <input id="sunday_active" type="checkbox" class="checkbox" value="{{$timeslot->sunday_active}}" name="sunday_active">
                                                            <label for="sunday_active"> Sunday </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="input-group"> 
                                                            <input type="text" name="sunday_val" id="sunday_val" value="{{$timeslot->sunday_val}}" data-role="tagsinput" placeholder="add time">
                                                        </div>
                                                    </div>
                                                </div>                                                   
                                            </div>
                                        </div> 
                                        <hr class="m-t-0 m-b-10">                              
                                    </div>                                    
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form> 
                            </div>
                        </div>
                    </div>              
                </div>
                @endif
            </div>
            <div class="tab-pane p-20" id="package" role="tabpanel" style="min-height: 300px;">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="button" data-toggle="modal" data-target="#add_package_modal" class="btn btn-success">Add New</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Package Name</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Price</th>
                                                <th>Credit</th>
                                                <th>Package participant</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="package_list">
                                            @foreach($packages as $value)
                                            <tr id="{{$value->id}}">
                                                <td>{{$value->id}}</td>
                                                <td>{{$value->package_name}}</td>
                                                <td>{{$value->package_des}}</td>
                                                <td>{{$value->package_unit}}</td>
                                                <td>{{$value->package_price}}</td>
                                                <td>{{$value->credit_amount}}</td>
                                                <td>{{$value->package_participants}}</td>
                                                <td><button onclick="getPackage('{{$value->id}}')"><i class="fa fa-edit text-primary"></i></button>
                                                    <button onclick="deletePackage('{{$value->id}}')"><i class="fa fa-remove text-danger"></i></button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>  

                    <div class="modal fade" id="add_package_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="exampleModalLabel1">Add Package</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <form action="{{URL::to('admin/savePackage')}}" method="POST" id="add_package_form" enctype="multipart/form-data"> 
                                    <div class="modal-body">   
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">                                                        
                                        <div class="form-group">
                                            <label class="control-label">Name:</label>
                                            <input type="text" class="form-control" name="package_name" id="package_name">
                                            <input type="text" class="form-control" id="service_id" name="service_id" value="{{$service->id}}" hidden>
                                        </div>  
                                        <div class="form-group">
                                            <label class="control-label">Description:</label>
                                            <input type="text" class="form-control" name="package_des" id="package_des">
                                        </div>  
                                        <div class="form-group">
                                            <label class="control-label">Unit:</label>
                                            <select class="custom-select" id="package_unit" name="package_unit">
                                                <option value="RM">RM</option>
                                                <!-- <option value="SGD">SGD</option> -->
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Price:</label>
                                            <input type="number" step="0.01" class="form-control" name="package_price" id="package_price">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Credit:</label>
                                            <input type="number" class="form-control" name="credit_amount" id="credit_amount">
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label">Package Participants:</label>
                                            <input type="number" class="form-control" name="package_participants" id="package_participants">
                                        </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default close_btn" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="update_package" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="exampleModalLabel1">Add News</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <form action="{{ URL::to('admin/updatePackage') }}" method="POST" id="update_package_form">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="control-label">Name:</label>
                                            <input type="text" class="form-control" name="package_name" id="package_name">
                                            <input type="text" class="form-control" id="service_id" name="service_id" hidden>
                                            <input type="text" class="form-control" id="id" name="id" hidden>
                                        </div>  
                                        <div class="form-group">
                                            <label class="control-label">Description:</label>
                                            <input type="text" class="form-control" name="package_des" id="package_des">
                                        </div>   
                                        <div class="form-group">
                                            <label class="control-label">Unit:</label>
                                            <select class="custom-select" id="package_unit" name="package_unit">
                                                <option value="RM">RM</option>
                                                <!-- <option value="SGD">SGD</option> -->
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Price:</label>
                                            <input type="number" step="0.01" class="form-control" name="package_price" id="package_price">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Credit:</label>
                                            <input type="number" class="form-control" name="credit_amount" id="credit_amount">
                                        </div> 
                                        <div class="form-group">
                                            <label class="control-label">Package Participants:</label>
                                            <input type="number" class="form-control" name="package_participants" id="package_participants">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary update_package_btn"><i class="fa fa-check"></i> Save</button>
                                        <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var select_option_calendar = '{{$timeslot->calendar}}';
    var options_calendar = $('#edit_timeslot_form select#calendar option');

    for (var i = options_calendar.length - 1; i >= 0; i--) {
        if(options_calendar.eq(i).val() == select_option_calendar) {
            options_calendar.eq(i).attr('selected', 'selected');
        }
    }

    var select_option_arrived = '{{$timeslot->arrived}}';
    var options_arrived = $('#edit_timeslot_form select#arrived option');

    for (var i = options_arrived.length - 1; i >= 0; i--) {
        if(options_arrived.eq(i).val() == select_option_arrived) {
            options_arrived.eq(i).attr('selected', 'selected');
        }
    }

    var select_option_show_estimated = '{{$timeslot->show_estimated}}';
    var options_show_estimated = $('#edit_timeslot_form select#show_estimated option');

    for (var i = options_show_estimated.length - 1; i >= 0; i--) {
        if(options_show_estimated.eq(i).val() == select_option_show_estimated) {
            options_show_estimated.eq(i).attr('selected', 'selected');
        }
    }

    var select_option_reschedule_allow = '{{$timeslot->reschedule_allow}}';
    var options_reschedule_allow = $('#edit_timeslot_form select#reschedule_allow option');

    for (var i = options_reschedule_allow.length - 1; i >= 0; i--) {
        if(options_reschedule_allow.eq(i).val() == select_option_reschedule_allow) {
            options_reschedule_allow.eq(i).attr('selected', 'selected');
        }
    }

    var object = $('#edit_timeslot_form').find(':checkbox');
    for (var i = object.length - 1; i >= 0; i--) {
        if(object.eq(i).val() == 1){
            object.eq(i).attr('checked', true);
        }
    }

    $('#edit_timeslot_form input.checkbox').change(function() {
      if ($(this).is(':checked')) {
        $(this).val(1);
      } else {
        $(this).val(0);
      }
    });
    
    var select_option = '{{$service->service_type}}';
    var options = $('#edit_service_form select#service_type option');

    for (var i = options.length - 1; i >= 0; i--) {
        if(options.eq(i).val() == select_option) {
            options.eq(i).attr('selected', 'selected');
        }
    }

    function editService() {
        var description=document.getElementById("service_des_ifr").contentWindow.document.getElementById("tinymce").innerHTML;
        $('textarea.my-editor').eq(0).html(description);
        $('#edit_service_form').submit();
    }

    $('#edit_service_form').on('submit', function(e) {

        e.preventDefault();
        
        var url = $(this).attr('action');
        var post = $(this).attr('method');   
        $('.preloader').css('display', 'block');     
        $.ajax({
            type : post,
            url : url,
            data: new FormData($("#edit_service_form")[0]),
            contentType: false,
            processData: false,
            success:function(data){                    
                alert('Update Success!');
                $('#edit_service_form').find('img').attr('src', data.service_image);
                $('h4.service_name').text(data.service_name);
                $('#service_list a.active_btn').text(data.service_name);
                $('.preloader').css('display', 'none');
            }
        })
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    function deleteService($id) {
        var id = $id;
        var c = confirm("Are you Sure!");
        if (c == true) {
            $.get('{{URL::to("admin/deleteService")}}', { id:id }, function(data){
                if (data == 'success') {
                    location.reload();
                }
            })
        } else {
            return false;
        }
    }

    $('#add_package_form').on('submit', function(e) {
        $('.preloader').css('display', 'block');
        e.preventDefault();
        var url = $(this).attr('action');
        var post = $(this).attr('method');
        $.ajax({
            type : post,
            url : url,
            data: new FormData($("#add_package_form")[0]),
            contentType: false,
            processData: false,
            success:function(data){
                    $('#add_package_form').trigger('reset');
                    var html = '<tr id="'+data.id+'">'+
                                    '<td>'+data.id+'</td>'+
                                    '<td>'+data.package_name+'</td>'+
                                    '<td>'+data.package_des+'</td>'+
                                    '<td>'+data.package_unit+'</td>'+
                                    '<td>'+data.package_price+'</td>'+
                                    '<td>'+data.credit_amount+'</td>'+
                                    '<td>'+data.package_participants+'</td>'+
                                    '<td><button onclick="getPackage('+data.id+')"><i class="fa fa-edit text-primary"></i></button> <button onclick="deletePackage('+data.id+')"><i class="fa fa-remove text-danger"></i></button></td>'+
                                '</tr>';
                        $('#package_list').append(html);
                        $('#add_package_form .close_btn').trigger('click');
                        $('.preloader').css('display', 'none');
                }
            })
    });

    function getPackage($id){
        var id = $id;
            $.get('{{URL::to("admin/editPackage")}}', { id:id }, function(data){
                $('#update_package_form').find('#id').val(data.id);
                $('#update_package_form').find('#service_id').val(data.service_id);
                $('#update_package_form').find('#package_name').val(data.package_name);
                $('#update_package_form').find('#package_des').val(data.package_des);
                $('#update_package_form').find('#package_price').val(data.package_price);
                $('#update_package_form').find('#credit_amount').val(data.credit_amount);
                $('#update_package_form').find('#package_participants').val(data.package_participants);
                var select_option = data.package_unit;
                var options = $('#update_package_form select#package_unit option');

                for (var i = options.length - 1; i >= 0; i--) {
                    if(options.eq(i).val() == select_option) {
                        options.eq(i).attr('selected', 'selected');
                    }
                }
                $('#update_package').modal('show');
            })
    }

    function deletePackage($id) {
        var id = $id;
        var r = confirm("Are you Sure!");
        if (r == true) {
            $.get('{{URL::to("admin/deletePackage")}}', { id:id }, function(data){
                if (data == 'success') {
                    $('#package_list tr#'+id).remove();
                }
            })
        } else {
            return false;
        } 
        
    }

    $('#update_package_form').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr('action');
        var post = $(this).attr('method');
        $.ajax({
            type : post,
            url : url,
            data: data,
            dataTy:'json',
            success:function(data){
                $('#update_package_form').trigger('reset'); 
                var html = '<tr id="'+data.id+'">'+
                                '<td>'+data.id+'</td>'+
                                '<td>'+data.package_name+'</td>'+
                                '<td>'+data.package_des+'</td>'+
                                '<td>'+data.package_unit+'</td>'+
                                '<td>'+data.package_price+'</td>'+
                                '<td>'+data.credit_amount+'</td>'+
                                '<td>'+data.package_participants+'</td>'+
                                '<td><button onclick="getPackage('+data.id+')"><i class="fa fa-edit text-primary"></i></button> <button onclick="deletePackage('+data.id+')"><i class="fa fa-remove text-danger"></i></button></td>'+
                            '</tr>';
                $('#package_list tr#'+data.id).replaceWith(html);
                $('#update_package_form .close_btn').trigger('click');
            }
        })
    });

    $('#edit_timeslot_form').on('submit', function(e) {
        $('.preloader').css('display', 'block');
        e.preventDefault();        
        var data = $(this).serialize();
        var url = $(this).attr('action');
        var post = $(this).attr('method');
        $.ajax({
            type : post,
            url : url,
            data: data,
            dataTy:'json',
            success:function(data){
                alert('Success');
                $('.preloader').css('display', 'none');
            }
        })
    });

</script>

<script src="{{asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>




@else

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1>Please Add a Service!!!</h1>
    </div>
</div>
@endif