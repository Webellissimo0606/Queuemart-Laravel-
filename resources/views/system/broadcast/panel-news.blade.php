@extends('layouts.system')

@section('content')
@include('system.broadcast.add_panel_news')
@include('system.broadcast.edit_panel_news')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Panel News</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Panel News</li>
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
        <div class="col-lg-12" style="margin-bottom: 10px;">
            <div class="col-md-offset-3 col-md-9">
                <button type="button" data-toggle="modal" data-target="#add_panel_news" class="btn btn-success"><i class="fa fa-plus"></i> Add News</button>
            </div>
        </div> 
    </div>
    <div class="row" id="panel-news-list">
                       
    </div>
    <!-- Row -->               
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
@endsection     

@section('script_panel_news')

    <script type="text/javascript"> 

        $(document).ready(function() {

            $.get("{{URL::to('admin/readPanelNews')}}", {user_id: '{{$user_id}}'}, function(data) {
                $('#panel-news-list').empty().html(data);
            });
        });

        $('#add_panel_news_form').on('submit', function(e) {

            e.preventDefault();
            
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#add_panel_news_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    $('#add_panel_news_form').trigger('reset');
                    var html = '<div class="col-lg-3 col-md-6" id="'+data.id+'">'+
                                    '<div class="card">'+
                                        '<img class="card-img-top img-responsive" src="'+data.news_image+'" alt="Card image cap">'+
                                        '<div class="card-body">'+
                                            '<h4 class="card-title">News Message</h4>'+
                                            '<p class="card-text">'+data.news_des+'</p>'+
                                            '<p class="btn btn-primary" id="panel_news_edit" data-id="'+data.id+'"><i class="fa fa-edit"></i> Edit</p>'+
                                            ' <p class="btn btn-danger" id="panel_news_delete" data-id="'+data.id+'"><i class="fa fa-remove"></i> Delete</p>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                        $('#panel-news-list').append(html);
                        $('#add_panel_news .close_btn').trigger('click'); 
                }
            })
        })

        $('body').delegate('#panel_news_delete', 'click', function(e) {
            var id = $(this).data('id');
            $.get('{{URL::to("admin/deletePanelNews")}}', { id:id }, function(data){
                $('#panel-news-list div#'+id).remove();
            })
        })

        $('body').delegate('#panel_news_edit', 'click', function(e) {
            var id = $(this).data('id');
            $.get('{{URL::to("admin/editPanelNews")}}', { id:id }, function(data){
                $('#update_panel_news_form').find('#id').val(data.id);
                $('#update_panel_news_form').find('#news_des').val(data.news_des);
                $('#update_panel_news_form').find('#news_duration').val(data.news_duration);
                $('#edit_panel_news').modal('show');
            })
        })

        $('#update_panel_news_form').on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#update_panel_news_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    $('#update_panel_news_form').trigger('reset'); 
                    var html = '<div class="col-lg-3 col-md-6" id="'+data.id+'">'+
                                    '<div class="card">'+
                                        '<img class="card-img-top img-responsive" src="'+data.news_image+'" alt="Card image cap">'+
                                        '<div class="card-body">'+
                                            '<h4 class="card-title">News Message</h4>'+
                                            '<p class="card-text">'+data.news_des+'</p>'+
                                            '<p class="btn btn-primary" id="panel_news_edit" data-id="'+data.id+'"><i class="fa fa-edit"></i> Edit</p>'+
                                            ' <p class="btn btn-danger" id="panel_news_delete" data-id="'+data.id+'"><i class="fa fa-remove"></i> Delete</p>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                        $('#panel-news-list div#'+data.id).replaceWith(html);
                        $('#update_panel_news_form .close_btn').trigger('click');
                }
            })
        })

    </script>

@endsection        