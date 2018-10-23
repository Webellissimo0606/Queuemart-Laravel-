@extends('layouts.system')

@section('content')
<div class="container-fluid"> 
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Services</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Services</li>
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
        <div class="col-3">
            <div class="card card-outline-success">
                <div class="card-header" style="display: flex; justify-content: space-between;">
                    <span class="m-b-0 text-white" style="font-size: 20px; font-weight: 700;">Services (<span class="services_count">{{count($services)}}</span>)</span>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add_service_modal"><i class="fa fa-plus"></i></button>
                </div>
                <div class="card-body">
                    <div class="form-body">                                        
                        <div class="row">
                            <div class="col-md-12" id="service_list">
                                @foreach($services as $value)
                                    <a type="button" class="service_list_btn btn btn-block btn-secondary" data-id="{{$value->id}}">{{$value->service_name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-9" id="service_content">
            
        </div>                    
    </div>

    <div class="modal fade" id="add_service_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1">Add Service</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">     
                                    <form class="form-horizontal" action="{{ URL::to('admin/saveService') }}" method="POST" id="add_service_form" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-body">
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Name Your Service :</label>
                                                        <input class="form-control" type="text" id="service_name" name="service_name">
                                                        <input class="form-control" type="text" id="user_id" name="user_id" value="{{$user_id}}" hidden>
                                                    </div>
                                                    <div class="form-group" hidden>
                                                        <label class="control-label">Description (Optional) :</label>
                                                        <textarea class="form-control" rows="5" id="service_des" name="service_des"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="file" id="input-file-now-custom-1" class="dropify" name="service_image" data-default-file="{{asset('assets/plugins/dropify/src/images/test-image-1.jpg')}}" />
                                                </div>
                                            </div>                                               
                                            <h3 class="box-title">Participants</h3>
                                            <hr class="m-t-0 m-b-20">
                                            <!--/row-->
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group row">
                                                        <div class="col-12 text-center m-b-10">
                                                            <label class="control-label">Max Participants / Session</label>
                                                        </div>
                                                        <div class="col-12 text-center">
                                                            <input class="form-control text-center" id="service_participants" name="service_participants" type="text">
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
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                                                            <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Cancel</button>
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
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
@endsection   

@section('script_service')

    
    <script type="text/javascript"> 

        $(document).ready(function() {
            $('.preloader').css('display', 'block');
            $.get("{{URL::to('admin/readServices')}}",{user_id: '{{$user_id}}'}, function(data) {
                $('#service_content').empty().html(data);
                $('#service_list a').eq(0).addClass('active_btn');                 
                test_textareaLoad('service_des'); 
                $('.preloader').css('display', 'none');
            });


            $('#add_service_form').on('submit', function(e) {

                e.preventDefault();
                
                var url = $(this).attr('action');
                var post = $(this).attr('method');
                $count = parseInt($('.services_count').text());
                $('.preloader').css('display', 'block');
                $.ajax({
                    type : post,
                    url : url,
                    data: new FormData($("#add_service_form")[0]),
                    contentType: false,
                    processData: false,
                    success:function(data){
                        
                        $('#add_service_form').trigger('reset');
                        $('#service_list a').removeClass('active_btn');
                        var html = '<a type="button" class="service_list_btn btn btn-block btn-secondary active_btn" data-id="'+data.id+'">'+data.service_name+'</a>';
                        $('#service_list').append(html);
                        $('.services_count').text($count+1);                        
                        $('#add_service_modal .close_btn').trigger('click'); 
                        $.get('{{URL::to("admin/readService")}}', { id:data.id }, function(data){
                            $('#service_content').empty().html(data);
                            var custom_id = ("service_des_" + Math.random()).replace('0.','');
                            $("textarea.my-editor").eq(0).attr("id", custom_id);
                            test_textareaLoad(custom_id); 
                            $("textarea.my-editor").eq(0).attr("id", "service_des");
                            $('iframe').eq(0).attr("id", 'service_des_ifr');
                            $('.preloader').css('display', 'none');
                        })
                    }
                })
            });

            $('body').delegate('.service_list_btn', 'click', function(e) {
                $('#service_list a').removeClass('active_btn');
                var id = $(this).data('id');
                $(this).addClass('active_btn');
                $('.preloader').css('display', 'block');
                $.get('{{URL::to("admin/readService")}}', { id:id }, function(data){
                    $('#service_content').empty().html(data);  
                        var custom_id = ("service_des_" + Math.random()).replace('0.','');
                        $("textarea.my-editor").eq(0).attr("id", custom_id);
                        test_textareaLoad(custom_id); 
                        $("textarea.my-editor").eq(0).attr("id", "service_des");
                        $('iframe').eq(0).attr("id", 'service_des_ifr');
                        $('.preloader').css('display', 'none');
                })
            });
        });
    </script>   

    <script type="text/javascript">
        
        function test_textareaLoad(id) {
            var editor_config = {
                path_absolute : "/",
                selector: "#" + id,
                plugins: [
                  "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                  "searchreplace wordcount visualblocks visualchars code fullscreen",
                  "insertdatetime media nonbreaking save table contextmenu directionality",
                  "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback : function(field_name, url, type, win) {
                  var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                  var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                  var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                  if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                  } else {
                    cmsURL = cmsURL + "&type=Files";
                  }

                  tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                  });
                }
              };

              tinymce.init(editor_config);

        }
              
    </script> 

@endsection       