<div class="modal fade" id="add_float_news" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Add News</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ URL::to('admin/addFloatNews') }}" method="POST" id="add_float_news_form">
                <div class="modal-body">
                    <input type="text" hidden name="user_id" id="user_id" value="{{$user_id}}">
                    <div class="form-group">
                        <label class="control-label">Message:</label>
                        <textarea class="form-control" rows="5" name="float_des" id="float_des"></textarea>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary add_float_news_btn"><i class="fa fa-check"></i> Save</button>
                    <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>