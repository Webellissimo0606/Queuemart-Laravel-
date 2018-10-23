@extends('layouts.system')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Company Profile</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Company Profile</li>
            </ol>
        </div>                    
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    
    <div class="row">
        <div class="col-md-5">            
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Company Info</h4>
                </div>
                <div class="card-body">
                    <form action="{{ URL::to('admin/saveCompany') }}" method="post" enctype="multipart/form-data" id="company_form">
                        
                        <div class="form-body"> 
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">                                       
                            <input type="file" name="company_image" id="input-file-now-custom-1" class="dropify company_image" data-default-file="{{$company->company_image}}" />
                            <div class="form-group m-t-10">
                                <label class="control-label">Name:</label>
                                <input type="text" class="form-control" name="company_name" id="company_name" value="{{$company->company_name}}">
                                <input type="text" class="form-control" name="user_id" id="user_id" hidden value="{{$user_id}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Company URL:</label>
                                <div class="row">
                                    <div class="col-5">
                                        <h4 class="text-right" style="margin-top: 5px;">http://queuemart.me/</h4>
                                    </div>
                                    <div class="col-7">
                                        <input type="text" class="form-control" name="company_url" id="company_url" value="{{$company->company_url}}">
                                    </div> 
                                </div>                               
                            </div>
                            <div class="form-group">
                                <label class="control-label">Contact Number:</label>
                                <input type="text" class="form-control" name="company_tel_num" id="company_tel_num" value="{{$company->company_tel_num}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description:</label>
                                <textarea class="form-control" rows="5" name="company_des" id="company_des">{{$company->company_des}}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Questionnaire link:</label>
                                <input type="text" class="form-control" name="questionnaire_link" id="questionnaire_link" value="{{$company->questionnaire_link}}">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success company_save"> <i class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
        <div class="col-md-7">
            <div class="card card-outline-success">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Company Holiday</h4>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                    <!-- BEGIN MODAL -->
                    <div class="modal fade none-border" id="my-event">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"><strong>Add Event</strong></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success save-event waves-effect waves-light">Create event</button>
                                    <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <!-- END MODAL -->
                </div>
            </div>
        </div> 
        @if($user_obj->role_id == 2)
        <div class="col-md-12">            
            <div class="card card-outline-danger">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Company Mutiple Permission</h4>
                </div>
                <div class="card-body">
                    <form action="{{URL::to('admin/updateCompany')}}" method="post" enctype="multipart/form-data" id="update_company_form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">                                       
                        <input type="text" name="user_id" id="user_id" hidden value="{{$user_id}}">
                        <div class="row">
                            <div class="col-4">
                                <div class="row">  
                                    <div class="col-8 text-right">
                                        <h3>Mutiple Permission :</h3>
                                    </div>                      
                                    <div class="col-4">
                                        <select class="custom-select" name="permission_status" id="permission_status">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-4">
                                <div class="row">  
                                    <div class="col-4 text-right">
                                        <h3>Support :</h3>
                                    </div>                      
                                    <div class="col-8">
                                        <select class="custom-select" name="support_id" id="support_id">
                                            <option value="0">-----------------------------</option>
                                            @foreach($supports as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach               
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 offset-1">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                        <!-- <div class="form-body"> 
                            
                        </div>
                        <div class="form-actions">
                            
                        </div> -->
                    </form>
                </div>
            </div>
        </div>    
        @endif  
    </div> 
    
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>

@endsection  

@section('script_company')
    <script type="text/javascript">

        var select_option_support = '{{$company->support_id}}';
        var options_support = $('select#support_id option');

        for (var i = options_support.length - 1; i >= 0; i--) {
            if(options_support.eq(i).val() == select_option_support) {
                options_support.eq(i).attr('selected', 'selected');
            }
        }

        var select_option_permission = '{{$company->permission_status}}';
        var options_permission = $('select#permission_status option');

        for (var i = options_permission.length - 1; i >= 0; i--) {
            if(options_permission.eq(i).val() == select_option_permission) {
                options_permission.eq(i).attr('selected', 'selected');
            }
        }


        var ajax_url = '{{URL::to("admin/addHoliday")}}';
        var ajax_delete_url = '{{URL::to("admin/deleteHoliday")}}';
        var ajax_update_url = '{{URL::to("admin/updateHoliday")}}';
        var calendar_user_id = '{{$user_id}}';
 
        var calendar_holiday = [];

        <?php foreach ($holidays as $value): ?>
            var column = {
                            title : "<?php echo $value->holiday_for; ?>",
                            start : '<?php echo $value->holiday_date; ?>',
                            className: 'bg-info'
                        };
            calendar_holiday.push(column);
        <?php endforeach ?>

        $('#company_form').on('submit', function(e) {
            e.preventDefault();            
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#company_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    alert(data);
                }
            })
        });

        $('#update_company_form').on('submit', function(e) {
            e.preventDefault();            
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#update_company_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    alert(data);
                }
            })
        })
    </script>

@endsection