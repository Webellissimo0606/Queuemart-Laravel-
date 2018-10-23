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
            <h3 class="text-themecolor m-b-0 m-t-0">Admin Role</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Admin Role</li>
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
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-success" data-toggle="modal" data-target="#add_employee"> Add Employee </button>
                        </div>
                    </div>
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
                                        if ($value->role_id == '2') {
                                            echo 'Super Admin';
                                        } elseif ($value->role_id == '3') {
                                            echo "Support";
                                        } elseif ($value->role_id == '4') {
                                            echo "Merchant Owner";
                                        } elseif ($value->role_id == '5') {
                                            echo "Branch Manager";
                                        } elseif ($value->role_id == '6') {
                                            echo "Staff";
                                        }
                                     ?></td>
                                    <td>
                                        <button onclick="userEdit('{{$value->id}}')"><i class="fa fa-edit text-primary"></i></button>
                                        <button id="delete_employee" data-id="{{$value->id}}"><i class="fa fa-remove text-danger"></i></button>
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
    <div class="modal fade" id="add_employee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1">Add Employee</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ URL::to('admin/addEmployee') }}" method="post" enctype="multipart/form-data" id="add_employee_form">
                    <div class="modal-body">
                        <div id="errorMsg1"></div>
                        <div class="form-group">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <label for="recipient-name" class="control-label">Full Name:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Email:</label>
                            <input type="text" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Role:</label>
                            <select class="form-control custom-select" id="role_id" name="role_id">
                                <option value="2">Super Admin</option>
                                <option value="3">Support</option>
                                <option value="4">Merchant Owner</option>
                                <option value="5">Branch Manager</option>
                                <option value="6">Staff</option>
                            </select>
                        </div>  
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Add</button>
                    </div>
                </form>
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
                <form action="{{ URL::to('admin/editEmployee') }}" method="post" enctype="multipart/form-data" id="edit_employee_form">
                    <div class="modal-body">
                        <div id="errorMsg2"></div>
                        <div class="form-group">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <label for="recipient-name" class="control-label">Full Name:</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <input type="text" class="form-control" id="id" name="id" hidden>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Email:</label>
                            <input type="text" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Role:</label>
                            <select class="form-control custom-select" id="role_id" name="role_id">
                                <option value="2">Super Admin</option>
                                <option value="3">Support</option>
                                <option value="4">Merchant Owner</option>
                                <option value="5">Branch Manager</option>
                                <option value="6">Staff</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">New Password:</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        <div class="form-group" id="company_check_list" hidden>
                            <label class="col-form-label">Company :</label>
                            <div class="row offset-1">
                                @foreach($companies as $company)
                                <div class="col-3 checkbox checkbox-success">
                                    <input id="{{$company->company_url}}" value="{{$company->id}}" type="checkbox">
                                    <label for="{{$company->company_url}}"> {{$company->company_url}} </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group" id="company_check_list_2" hidden>
                            <label class="col-form-label">Company :</label>
                            <div class="row offset-1">
                                @foreach($companies as $company)
                                <div class="col-3 radio radio-success">
                                    <input name="radio" id="{{$company->company_url}}_radio" value="{{$company->id}}" type="radio">
                                    <label for="{{$company->company_url}}_radio"> {{$company->company_url}} </label>
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

        $('#add_employee_form').on('submit', function(e) {
            e.preventDefault();            
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#add_employee_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    if (data == 'success') {
                        location.reload();
                    } else {
                        $('#errorMsg1').text(data);
                    }
                }
            })
        })

        function userEdit(id) {
            $('#company_check_list').attr('hidden', '');
            $('#company_check_list_2').attr('hidden', '');
            $.get("{{URL::to('admin/getEmployee')}}", {id:id}, function(data) {
                $('#edit_employee_form input#id').val(data.employee.id);
                $('#edit_employee_form input#name').val(data.employee.name);
                $('#edit_employee_form input#email').val(data.employee.email);
                var select_option_user = data.employee.role_id;
                var options_employee = $('#edit_employee_form option');

                for (var i = options_employee.length - 1; i >= 0; i--) {
                    if(options_employee.eq(i).val() == select_option_user) {
                        options_employee.eq(i).attr('selected', 'selected');
                    }
                }

                if (select_option_user == 3) {
                    $('#company_check_list').removeAttr('hidden');
                }

                if (select_option_user == 5 || select_option_user == 6) {
                    $('#company_check_list_2').removeAttr('hidden');
                }

                var object = $('#edit_employee_form').find(':checkbox');
                for (var i = object.length - 1; i >= 0; i--) {
                    object.eq(i).attr('checked', false);
                    for (var j = data.companies.length - 1; j >= 0; j--) {
                        if(data.companies[j].id == object.eq(i).val()){
                            object.eq(i).attr('checked', true);
                        }
                    }        
                }

                var object_radio = $('#edit_employee_form').find(':radio');
                for (var i = object_radio.length - 1; i >= 0; i--) {
                    object_radio.eq(i).attr('checked', false);
                    if(data.company_id == object_radio.eq(i).val()){
                        object_radio.eq(i).attr('checked', true);
                    }
                }


                $('#edit_employee').modal('show');                
            });
        }

        $('#edit_employee_form').on('submit', function(e) {
            e.preventDefault();            
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#edit_employee_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    if (data == 'success') {
                        var company_ids = [];
                        var obj = $('#edit_employee_form').find(':checkbox:checked');

                        for (var i = obj.length - 1; i >= 0; i--) {
                            company_ids.push(obj.eq(i).val())
                        }

                        var company_employee_id = $('#edit_employee_form').find(':radio:checked').val();
                        $.get('{{URL::to("admin/company_support")}}',
                        {
                            support_id: $('#edit_employee_form input#id').val(),
                            company_ids: company_ids,
                            company_employee_id: company_employee_id
                        }, function(data) {
                            location.reload();
                        })
                    } else {
                        $('#errorMsg2').text(data);
                    }
                }
            })
        })
        
        $('body').delegate('#delete_employee', 'click', function(e) {
            var id = $(this).data('id');
            var con = confirm("Are you Sure!");
            if (con == true) {
                $.get("{{URL::to('admin/deleteEmployee')}}", {id:id}, function(data) {
                    location.reload();
                });
            } else {
                return false;
            }
        })

    </script>

    <!-- Row -->               
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
<!-- ============================================================== -->
@endsection 
