<div class="modal fade" id="customer_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Customer Edit</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{URL::to('admin/updateCustomer')}}" method="POST" id="update_customer_form">
                <div class="modal-body">
                    <div class="form-group" id="error_MSG" hidden>
                        <label class="control-label">Error:</label>
                        <span id="error_msg"></span>
                    </div>
                    <input type="text" hidden name="id" id="id" value="{{$user->id}}">
                    <div class="form-group">
                        <label class="control-label">Full Name:</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{$user->name}}">
                    </div> 
                    <div class="form-group">
                        <label class="control-label">Phone Number:</label>
                        <input class="form-control" type="text" name="phone_number" id="phone_number" value="{{$user->phone_number}}">
                    </div> 
                    <div class="form-group">
                        <label class="control-label">Email:</label>
                        <input class="form-control" type="text" name="email" id="email" value="{{$user->email}}">
                    </div> 
                    <div class="form-group">
                        <label class="control-label">IC:</label>
                        <input class="form-control" type="text" name="ic" id="ic" value="{{$user->ic}}">
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Edit</button>
                    <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#update_customer_form').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var post = $(this).attr('method');
        $.ajax({
            type : post,
            url : url,
            data: new FormData($("#update_customer_form")[0]),
            contentType: false,
            processData: false,
            success:function(data){
                if (data.same_user) {
                    $('#error_msg').text(data.same_user);
                    $('#error_MSG').removeAttr('hidden');
                } else {
                    $('#update_customer_form').trigger('reset'); 
                    $("#myTable tbody tr#"+data.id).find('td').eq(1).text(data.name);
                    $("#myTable tbody tr#"+data.id).find('td').eq(3).text(data.ic);
                    $("#myTable tbody tr#"+data.id).find('td').eq(4).text(data.email);
                    $("#myTable tbody tr#"+data.id).find('td').eq(5).text(data.phone_number);
                    $('#update_customer_form .close_btn').trigger('click');
                }                
            }
        })
    })
</script>