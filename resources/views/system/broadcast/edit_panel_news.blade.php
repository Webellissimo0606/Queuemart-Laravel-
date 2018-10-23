<div class="modal fade" id="edit_panel_news" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Edit News</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ URL::to('admin/updatePanelNews') }}" method="post" id="update_panel_news_form" enctype="multipart/form-data">
	            <div class="modal-body"> 
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="text" hidden name="user_id" id="user_id" value="{{$user_id}}">
                    <div class="form-group">
                        <label class="control-label">Message:</label>
                        <textarea class="form-control" rows="5" name="news_des" id="news_des"></textarea>
                        <input class="form-control" type="text" name="id" hidden id="id">
                    </div>   
                    <div class="form-group">
                        <label class="control-label">Image:</label>
                        <input class="form-control" type="file" name="news_image" id="news_image">
                    </div>
                    <div class="form-group">
                        <label class="control-label">News Date:</label>
                        <input class="form-control input-daterange-datepicker" type="text" name="news_duration" id="news_duration" value="01/01/2015 - 01/31/2015" /> 
                    </div>
	            </div>
	            <div class="modal-footer">
	                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
	                <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Cancel</button>
	            </div>
            </form>
        </div>
    </div>
</div>
