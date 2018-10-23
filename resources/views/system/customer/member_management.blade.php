@extends('layouts.system')

@section('content')
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Member Management</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Member Management</li>
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
        <div class="col-lg-12">
            <div class="card"> 
                <div class="card-body">
                    <h3 class="box-title">Employee</h3>
                    <hr class="m-t-0">                    
                    <div class="table-responsive m-t-20">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users_all as $value)
                                <tr>              
                                    <td>{{$value->id}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->email}}</td>
                                    <td><?php
                                        if ($value->role_id == '5') {
                                            echo "Branch Manager";
                                        } elseif ($value->role_id == '6') {
                                            echo "Staff";
                                        }
                                     ?></td>
                                    <td>
                                        <button onclick="userEdit('{{$value->id}}')"><i class="fa fa-edit text-primary"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>                    
    </div>
    
    <div class="modal fade" id="edit_employee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1">Edit Employee</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form  id="edit_member_form">
                    <div class="modal-body">
                        <div id="errorMsg2"></div>
                        <div class="form-group">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <label for="recipient-name" class="control-label">Full Name:</label>
                            <input type="text" class="form-control" id="name" name="name" disabled>
                            <input type="text" class="form-control" id="id" name="id" hidden>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Role:</label>
                            <select class="form-control custom-select" id="role_id" name="role_id" disabled>                                
                                <option value="5">Branch Manager</option>
                                <option value="6">Staff</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Branch :</label>
                            <select class="form-control custom-select" id="branch_id" name="branch_id">                                
                                @foreach($branches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->branch_name}}</option>
                                @endforeach
                            </select>
                        </div>  
                                              
                        <div class="form-group" id="service_check_list" hidden>
                            <label class="col-form-label">Service :</label>
                            <div class="row offset-1" id="service_list">
                                @foreach($services as $service)
                                <div class="col-10 checkbox checkbox-success">
                                    <input id="service_{{$service->id}}" value="{{$service->id}}" type="checkbox">
                                    <label for="service_{{$service->id}}">{{$service->service_name}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    

    <script type="text/javascript">

        function userEdit(id) {
            $('#service_check_list').attr('hidden', '');
            $.get("{{URL::to('admin/getMember')}}", {id:id}, function(data) {
                $('#edit_member_form input#id').val(data.employee.id);
                $('#edit_member_form input#name').val(data.employee.name);

                var select_option_user = data.employee.role_id;
                var options_employee = $('#edit_member_form select#role_id option');

                for (var i = options_employee.length - 1; i >= 0; i--) {
                    options_employee.eq(i).attr('selected', false);
                    if(options_employee.eq(i).val() == select_option_user) {
                        options_employee.eq(i).attr('selected', true);
                    }
                }

                var options_branch = $('#edit_member_form select#branch_id option');

                for (var i = options_branch.length - 1; i >= 0; i--) {
                    options_branch.eq(i).attr('selected', false);
                    if(options_branch.eq(i).val() == data.employee_info.branch_id) {
                        options_branch.eq(i).attr('selected', true);
                    }
                }

                if (select_option_user == 6) {
                    $('#service_check_list').removeAttr('hidden');                
                    var real_branch_id = data.employee_info.branch_id;
                    var real_services = data.services;
                    var real_service_ids = data.service_ids;
                    console.log(real_service_ids);
                    if (real_branch_id != null) {

                        var html = '';
                        for (var i = real_services.length - 1; i >= 0; i--) {
                            for (var j = real_service_ids.length - 1; j >= 0; j--) {
                                if (real_service_ids[j] == real_services[i].id) {
                                html += '<div class="col-10 checkbox checkbox-success">'+
                                            '<input id="service_'+real_services[i].id+'" value="'+real_services[i].id+'" type="checkbox" checked>'+
                                            '<label for="service_'+real_services[i].id+'">'+real_services[i].service_name+'</label>'+
                                        '</div>';
                                        break;
                                } else if(j == 0){
                                html += '<div class="col-10 checkbox checkbox-success">'+
                                            '<input id="service_'+real_services[i].id+'" value="'+real_services[i].id+'" type="checkbox">'+
                                            '<label for="service_'+real_services[i].id+'">'+real_services[i].service_name+'</label>'+
                                        '</div>';
                                }
                            }
                        }
                        $('#service_list').empty().html(html);
                    }
                    $('select#branch_id').change(function(){
                        var branch_id_get = $(this).val();
                        $.get("{{URL::to('admin/getService')}}", {id:branch_id_get}, function(data) {
                            if (branch_id_get == real_branch_id) {
                                var html = '';
                                for (var i = data.services_get.length - 1; i >= 0; i--) {
                                    for (var j = real_service_ids.length - 1; j >= 0; j--) {
                                        if (real_service_ids[j] == data.services_get[i].id) {
                                        html += '<div class="col-10 checkbox checkbox-success">'+
                                                    '<input id="service_'+data.services_get[i].id+'" value="'+data.services_get[i].id+'" type="checkbox" checked>'+
                                                    '<label for="service_'+data.services_get[i].id+'">'+data.services_get[i].service_name+'</label>'+
                                                '</div>';
                                                break;
                                        } else if(j == 0){
                                        html += '<div class="col-10 checkbox checkbox-success">'+
                                                    '<input id="service_'+data.services_get[i].id+'" value="'+data.services_get[i].id+'" type="checkbox">'+
                                                    '<label for="service_'+data.services_get[i].id+'">'+data.services_get[i].service_name+'</label>'+
                                                '</div>';
                                        }
                                    }
                                }
                                $('#service_list').empty().html(html);
                            } else {
                                var html = '';
                                for (var i = data.services_get.length - 1; i >= 0; i--) {
                                        
                                    html += '<div class="col-10 checkbox checkbox-success">'+
                                                '<input id="service_'+data.services_get[i].id+'" value="'+data.services_get[i].id+'" type="checkbox">'+
                                                '<label for="service_'+data.services_get[i].id+'">'+data.services_get[i].service_name+'</label>'+
                                            '</div>';
                                }
                                $('#service_list').empty().html(html);
                            }
                        })
                    })

                }   
                
                $('#edit_employee').modal('show');                
            });
        }

        $('#edit_member_form').on('submit', function(e) {
            e.preventDefault();   
            var employee_branch_id = $('#edit_member_form select#branch_id').val();
            var employee_service_ids = [];
            if ($('select#role_id').val() == 6) {
                var obj = $('#service_list').find(':checkbox:checked');

                for (var i = obj.length - 1; i >= 0; i--) {
                    employee_service_ids.push(obj.eq(i).val())
                }
            }
            $.get('{{URL::to("admin/company_member")}}',
            {
                support_id: $('#edit_member_form input#id').val(),
                employee_branch_id: employee_branch_id,
                employee_service_ids: employee_service_ids,
            }, function(data) {
                location.reload();
            })
                    
        })

    </script>

    <!-- Row -->               
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
<!-- ============================================================== -->
@endsection 
