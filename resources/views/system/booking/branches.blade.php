 @extends('layouts.system')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Branches</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Branches</li>
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
        <div class="col-4">
            <div class="card card-outline-success">
                <div class="card-header" style="display: flex; justify-content: space-between;">
                    <span class="m-b-0 text-white" style="font-size: 20px; font-weight: 700;">Branches (<span class="branches_count">{{count($branches)}}</span>)</span>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_branch_modal"><i class="fa fa-plus"></i></button>
                </div>
                <div class="card-body">
                    <div class="form-body">                                        
                        <div class="row">
                            <div class="col-md-12" id="branch_list">
                                @foreach($branches as $branch)
                                    <a type="button" class="branch_list_btn btn btn-block btn-secondary" data-id="{{$branch->id}}">{{$branch->branch_name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-8" id="branch_content">
            
        </div> 
                          
    </div>

    <div class="modal fade" id="add_branch_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1">Add Branch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ URL::to('admin/saveBranch') }}" method="POST" id="add_branch_form" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-body">                                        
                                            <input type="file" id="input-file-now-custom-1" class="dropify" name="branch_image" data-default-file="{{asset('assets/plugins/dropify/src/images/test-image-1.jpg')}}" />
                                            <div class="form-group m-t-10">
                                                <label class="col-form-label">Name :</label>
                                                <input class="form-control" type="text" name="branch_name" id="branch_name">
                                                <input class="form-control" type="text" name="user_id" id="user_id" value="{{$user_id}}" hidden>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Branch Label :</label>
                                                <input class="form-control" type="text" name="branch_label" id="branch_label">
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Tel. No. :</label>
                                                <input class="form-control" type="text" name="branch_tel_num" id="branch_tel_num">
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Location :</label>
                                                <div class="row">
                                                    <div class="col-6 row">
                                                        <label for="example-text-input" class="col-3 col-form-label text-right">Latitude:</label>
                                                        <div class="col-9">
                                                            <input class="form-control" value="00.000000" type="text" name="latitude" id="latitude">
                                                        </div>
                                                    </div>
                                                    <div class="col-6 row">
                                                        <label for="example-text-input" class="col-3 col-form-label text-right">Longitude:</label>
                                                        <div class="col-9">
                                                            <input class="form-control" value="00.000000" type="text" name="longitude" id="longitude">
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Description :</label>
                                                <textarea class="form-control" rows="5" name="branch_des" id="branch_des"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Address :</label>
                                                <textarea class="form-control" rows="5" name="branch_address" id="branch_address"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">Services :</label>
                                                <div class="row offset-1" id="service_check_list">
                                                    @foreach($services as $service)
                                                    <div class="checkbox checkbox-success col-3">
                                                        <input id="m-{{$service->id}}" type="checkbox" value="{{$service->id}}">
                                                        <label for="m-{{$service->id}}"> {{$service->service_name}} </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
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

    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
@endsection 

@section('script_branch')

    
    <script type="text/javascript"> 

        $(document).ready(function() {

            $.get("{{URL::to('admin/readBranches')}}", {user_id: '{{$user_id}}'}, function(data) {
                $('#branch_content').empty().html(data);
                $('#branch_list a').eq(0).addClass('active_btn');
            });
        });

        $('#add_branch_form').on('submit', function(e) {

            e.preventDefault();
            
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $count = parseInt($('.branches_count').text());
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#add_branch_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    var service_ids = [];
                    var obj = $('.modal-content').find(':checkbox:checked')

                    for (var i = obj.length - 1; i >= 0; i--) {
                        service_ids.push(obj.eq(i).val())
                    }
                    
                    $.get('{{URL::to("admin/insertRelation")}}',
                    {
                        branch_id: data.id,
                        service_ids: service_ids
                    }, function(data) {
                        alert(data.message);
                    })
                    
                    $('#add_branch_form').trigger('reset');
                    $('#branch_list a').removeClass('active_btn');
                    var html = '<a type="button" class="branch_list_btn btn btn-block btn-secondary active_btn" data-id="'+data.id+'">'+data.branch_name+'</a>';
                    $('#branch_list').append(html);
                    $('.branches_count').text($count+1);                        
                    $('#add_branch_modal .close_btn').trigger('click'); 
                    $.get('{{URL::to("admin/readBranch")}}', { id:data.id }, function(data){
                        $('#branch_content').empty().html(data);
                    })
                    
                }
            })
        });

        $('body').delegate('.branch_list_btn', 'click', function(e) {
            $('#branch_list a').removeClass('active_btn');
            var id = $(this).data('id');
            $(this).addClass('active_btn');
            $.get('{{URL::to("admin/readBranch")}}', { id:id }, function(data){
                $('#branch_content').empty().html(data);
            })
        });
    

    </script>

@endsection  

