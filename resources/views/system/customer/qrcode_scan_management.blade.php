@extends('layouts.system')

@section('content')
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
  
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->

    <div class="row">
        <div class="col-md-9 offset-md-1">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Queuescreen Management</h4>
                </div>
                <div class="card-body">
                    <form action="{{URL::to('admin/queuescreenManagement')}}" method="post" enctype="multipart/form-data" id="qr_screen_form">
                        
                        <div class="form-body"> 
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group m-t-10">
                                <label class="control-label">Video:</label>
                                <div style="width: 100%; text-align: center;">
                                    <video src="{{$queuescreen->qr_screen_video}}" autoplay muted loop style="width: 50%;"></video>
                                </div>
                                <input type="file" name="qr_screen_video" id="qr_screen_video" class="qr_screen_video"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">TV Floating News:</label>
                                <textarea type="text" class="form-control" name="qr_screen_news" id="qr_screen_news">{{$queuescreen->qr_screen_news}}</textarea>
                                <input type="text" class="form-control" name="user_id" id="user_id" hidden value="{{$user_id}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Refer to the QueueScreen URL:</label>
                                @foreach($branches as $branch)
                                    <div class="row">
                                        <div class="col-5" style="display: flex;align-items: center; justify-content: flex-end;">
                                            <h5 style="font-weight: 500;">{{$branch->branch_name}}:</h5>
                                        </div>
                                        <div class="col-7">
                                            <a href="https://queuemart.me/admin/{{$user_id}}/qrcode_scan_screen/{{$branch->id}}" target="_blank" class="form-control" style="display: block!important;">https://queuemart.me/admin/{{$user_id}}/qrcode_scan_screen/{{$branch->id}}</a>
                                        </div>
                                    </div>
                                @endforeach  
                                @foreach($branch_service_queuescreen as $branch_service)
                                    <div class="row">
                                        <div class="col-5" style="display: flex;align-items: center; justify-content: flex-end;">
                                            <h5 style="font-weight: 500;">{{$branch_service->branch_name}},{{$branch_service->service_name}}:</h5>
                                        </div>
                                        <div class="col-7">
                                            <a href="https://queuemart.me/admin/{{$user_id}}/qrcode_scan_screen_seminar/{{$branch_service->id}}" target="_blank" class="form-control" style="display: block!important;">https://queuemart.me/admin/{{$user_id}}/qrcode_scan_screen_seminar/{{$branch_service->id}}</a>
                                        </div>
                                    </div>
                                @endforeach                               
                            </div>
                            <div class="form-group">
                                <div class="checkbox checkbox-success col-12">                                    
                                    <input id="display_client" type="checkbox" name="display_client" value="{{$queuescreen->display_client}}">
                                    <label for="display_client">Display Client's Note</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var check = $('#qr_screen_form').find(':checkbox').eq(0);
        if (check.val() == 1) {
            check.attr('checked', true);
        } else {
            check.attr('checked', false);
        }

        check.change(function(){
            if (check.val() == 1) {
                check.val(1);
            } else {
                check.val(0);
            }
        })
        $('#qr_screen_form').on('submit', function(e) {
            e.preventDefault();            
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#qr_screen_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    alert(data);
                }
            })
        });
    </script>

    <!-- Row -->               
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
@endsection 
