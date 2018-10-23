@extends('layouts.system')

@section('content')
@include('system.broadcast.add_float_news')
@include('system.broadcast.edit_float_news')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Floating News</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Floating News</li>
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
                <button type="button" data-toggle="modal" data-target="#add_float_news" class="btn btn-success"><i class="fa fa-plus"></i> Add News</button>
            </div>
        </div> 
    </div>
    <div class="row" id="float-news-list">
        
    </div>
    <!-- Row -->               
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
<!-- ============================================================== -->
@endsection


@section('script_float_news')

    <script type="text/javascript"> 

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            $.get("{{URL::to('admin/readFloatNews')}}", {user_id: '{{$user_id}}'}, function(data) {
                $('#float-news-list').empty().html(data);
            });
        });

        $('#add_float_news_form').on('submit', function(e) {

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
                    $('#add_float_news_form').trigger('reset');
                    var html = '<div class="col-lg-3 col-md-6" id="'+data.id+'">'+
                                    '<div class="card">'+
                                        '<div class="card-body">'+
                                            '<h4 class="card-title">News Message</h4>'+
                                            '<p class="card-text">'+data.float_des+'</p>'+
                                            '<p class="btn btn-primary" id="float_news_edit" data-id="'+data.id+'"><i class="fa fa-edit"></i> Edit</p>'+
                                            ' <p class="btn btn-danger" id="float_news_delete" data-id="'+data.id+'"><i class="fa fa-remove"></i> Delete</p>'+
                                            ' <p class="btn btn-secondary" id="float_news_active" data-id="'+data.id+'"><i class="fa fa-check"></i></p>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                        $('#float-news-list').append(html);
                        $('#add_float_news_form .close_btn').trigger('click'); 
                }
            })
        })

        $('body').delegate('#float_news_delete', 'click', function(e) {
            var id = $(this).data('id');
            $.get('{{URL::to("admin/deleteFloatNews")}}', { id:id }, function(data){
                $('#float-news-list div#'+id).remove();
            })
        })

        $('body').delegate('#float_news_edit', 'click', function(e) {
            var id = $(this).data('id');
            $.get('{{URL::to("admin/editFloatNews")}}', { id:id }, function(data){
                $('#update_float_news_form').find('#id').val(data.id);
                $('#update_float_news_form').find('#float_des').val(data.float_des);
                $('#edit_float_news').modal('show');
            })
        })

        $('#update_float_news_form').on('submit', function(e) {
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
                    $('#update_float_news_form').trigger('reset'); 
                    var html = '<div class="col-lg-3 col-md-6" id="'+data.id+'">'+
                                    '<div class="card">'+
                                        '<div class="card-body">'+
                                            '<h4 class="card-title">News Message</h4>'+
                                            '<p class="card-text">'+data.float_des+'</p>'+
                                            '<p class="btn btn-primary" id="float_news_edit" data-id="'+data.id+'"><i class="fa fa-edit"></i> Edit</p>'+
                                            ' <p class="btn btn-danger" id="float_news_delete" data-id="'+data.id+'"><i class="fa fa-remove"></i> Delete</p>'+
                                            ' <p class="btn btn-secondary" id="float_news_active" data-id="'+data.id+'"><i class="fa fa-check"></i></p>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                        $('#float-news-list div#'+data.id).replaceWith(html);
                        $('#update_float_news_form .close_btn').trigger('click');
                }
            })
        })

        $('body').delegate('#float_news_active', 'click', function(e) {
            var p_ojb = $('p#float_news_active');
            for (var i = p_ojb.length - 1; i >= 0; i--) {  
                if (p_ojb.eq(i).data('id') != $(this).data('id')) {
                    p_ojb.eq(i).removeClass('btn-success');
                    p_ojb.eq(i).addClass('btn-secondary');
                }
            }
            var id = $(this).data('id');
            var float_active = '0';
            if ($(this).hasClass('btn-success')) {
                float_active = '0';
                $(this).removeClass('btn-success');
                $(this).addClass('btn-secondary');
            } else {
                float_active = '1';
                $(this).removeClass('btn-secondary');
                $(this).addClass('btn-success');
            }
            
            $.get('{{URL::to("admin/activeFloatNews")}}', { id:id, user_id:'{{$user_id}}', float_active: float_active }, function(data){
                alert('success');                
            })
        })

    </script>

@endsection