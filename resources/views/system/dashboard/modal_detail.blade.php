<div class="modal fade" id="edit_order_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table color-bordered-table primary-bordered-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Customer Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Name :</td>
                                            <td>{{$user->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number :</td>
                                            <td>{{$user->phone_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Nationality :</td>
                                            <td>{{$user->nationality}}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table color-bordered-table primary-bordered-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Booking Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Status :</td>
                                            <td>Booked:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$order->booked_time}}<br>
                                                Paid:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$order->paid_time}}<br>
                                                <?php if ($order->reschedule_check == 1) { ?>
                                                Reschedule:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$order->reschedule_time}}<br>
                                                <?php } ?>
                                                Arrived:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$order->arrived_time}}<br>
                                                Completed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$order->complete_time}}<br>
                                                <?php if ($order->booking_status == 'CancelledByAdmin' || $order->booking_status == 'CancelledByClient') { ?>
                                                Cancelled:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$order->updated_at}}<br>
                                                <?php } ?>
                                                <hr>
                                                <?php 
                                                    if ($order->booking_status == 'Booked') {
                                                        print '<div class="col-sm-12">
                                                                <div class="m-b-10">
                                                                    <label class="custom-control custom-radio">
                                                                        <input id="radio1" name="booking_status_check" class="custom-control-input" type="radio" value="4">
                                                                        <span class="custom-control-label">Paid</span>
                                                                    </label>
                                                                </div>
                                                                <div class="m-b-10">
                                                                    <label class="custom-control custom-radio">
                                                                        <input id="radio2" name="booking_status_check" class="custom-control-input" type="radio" value="6">
                                                                        <span class="custom-control-label">CancelByAdmin</span>
                                                                    </label>
                                                                </div>
                                                            </div>';
                                                    } else if ($order->booking_status == 'Completed' || $order->booking_status == 'Arrived') {
                                                        if ($order->paid_time == null) {
                                                            print '<div class="col-sm-12">
                                                                    <div class="m-b-10">
                                                                        <label class="custom-control custom-radio">
                                                                            <input id="radio1" name="booking_status_check" class="custom-control-input" type="radio" value="4">
                                                                            <span class="custom-control-label">Paid</span>
                                                                        </label>
                                                                    </div>
                                                                </div>';
                                                        } else {
                                                            print '';
                                                        }
                                                        
                                                    } else if ($order->booking_status == 'CancelledByClient' || $order->booking_status == 'CancelledByAdmin') {
                                                        print '';
                                                    } else {
                                                        print '<div class="col-sm-12">                                                                
                                                                <div class="m-b-10">
                                                                    <label class="custom-control custom-radio">
                                                                        <input id="radio2" name="booking_status_check" class="custom-control-input" type="radio" value="6">
                                                                        <span class="custom-control-label">CancelByAdmin</span>
                                                                    </label>
                                                                </div>
                                                            </div>';
                                                    }
                                                ?>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Service :</td>
                                            <td>{{$order->service_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Package :</td>
                                            <td>{{$order->package_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Price :</td>
                                            <td>{{$order->package_unit}}&nbsp;{{$order->package_price}}</td>
                                        </tr>
                                        @if($order->paid_time == null && $order->booking_status != 'CancelledByClient' && $order->booking_status != 'CancelledByAdmin')
                                        <tr>
                                            <td>Amount to pay :</td>
                                            <td>{{$order->package_unit}}&nbsp;{{$price}}</td>
                                        </tr>
                                        @endif
                                        @if($order->paid_time != null)
                                        <tr>
                                            <td>Paid Price :</td>
                                            <td>{{$order->package_unit}}&nbsp;{{$order->paid_price}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>Client's Note :</td>                                            
                                            <td style="display: flex;justify-content: space-between;align-items: center;"><input type="text" class="form-control" id="client_notice" value="{{$order->client_notice}}"><button onclick="addClientNotice({{$order->id}})" class="btn btn-success">save</button></td>
                                        </tr>
                                        <tr>
                                            <td>Admin's Note :</td>
                                            <td style="display: flex;justify-content: space-between;align-items: center;"><input type="text" class="form-control" id="admin_notice" value="{{$order->admin_notice}}"><button onclick="addAdminNotice({{$order->id}})" class="btn btn-success">save</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>              
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('input:radio[name="booking_status_check"]').click(function(){
        var booking_status_update = $('input:radio[name="booking_status_check"]:checked').val();
        var alert_text = '';
        if (booking_status_update == 4) {
            alert_text = "This client has made the payment and change the client status to 'Paid'!";
        } else if (booking_status_update == 6) {
            alert_text = "The appointment for this client has cancelled and change the client status to 'CancelledByAdmin'!";
        }
        var update_check = confirm(alert_text);
        if (update_check == true) {
            $.get('{{URL::to("admin/updateOrder")}}', { id:'{{$order->id}}', booking_status: booking_status_update, pay_price: '{{$price}}' }, function(data){
                if (data.status == 'success') {
                    var arrived_check = '';
                    var arrive_check = '';
                    if (data.orderData.booking_status == '4') {

                        if (data.orderData.arrived_check == 0) {
                            arrived_check = 'hidden';
                        } else {
                            arrive_check = 'hidden';
                        }
                        var html = '<button onclick="arriveCheck('+data.orderData.id+')" id="arrive_check_btn_'+data.orderData.id+'" type="button" class="btn btn-primary arrived_check_btn" data-toggle="button" '+arrive_check+'>'+
                                        '<i class="ti-location-arrow text" aria-hidden="true"></i>'+
                                        '<i class="ti-location-arrow text-active" aria-hidden="true"></i>'+
                                    '</button>'+
                                    '<button onclick="arrivedCheck('+data.orderData.id+')" id="arrived_check_btn_'+data.orderData.id+'" type="button" class="btn btn-info arrived_check_btn" data-toggle="button" data-more="#sh" aria-pressed="false" '+arrived_check+'>'+
                                        '<i class="ti-control-stop text" aria-hidden="true"></i>'+
                                        '<i class="ti-control-play text-active" aria-hidden="true"></i>'+
                                    '</button>'+
                                    '<span id="order_duration'+data.orderData.id+'">Paid</span>'+
                                    '<button onclick="completedCheck('+data.orderData.id+')" id="complete_check_btn_'+data.orderData.id+'" type="button" class="btn btn-success complete_check_btn" data-toggle="button" data-more="#sh" aria-pressed="false" disabled>'+
                                        '<i class="ti-na text" aria-hidden="true"></i>'+
                                        '<i class="ti-check text-active" aria-hidden="true"></i>'+
                                    '</button>';
                        $('#order_check_'+data.orderData.id).empty().html(html);
                        $('#booking_div_'+data.orderData.id+' .order_info_one').attr('id', 'back_color_Paid');
                        $('#edit_order_model').modal('hide');
                    } else if(data.orderData.booking_status == '6') {
                        $('#booking_div_'+data.orderData.id+' .order_info_one').attr('id', 'back_color_CancelledByAdmin');
                        var cancel_html = '<span style="margin:auto;">CancelledByAdmin</span>';
                        $('#order_check_'+data.orderData.id).empty().html(cancel_html);
                        $('#edit_order_model').modal('hide');
                    } else {
                        $('#edit_order_model').modal('hide');
                    }
                }
            });
        } else {
            $('#edit_order_model').modal('hide');
        }
    });

    function addAdminNotice(id) {
        var admin_notice = $('input#admin_notice').val();
        $.get('{{URL::to("admin/updateAdminNotice")}}', { id:id, admin_notice: admin_notice }, function(data){
            if (data == 'success') {
                $('#edit_order_model').modal('hide');
            }
        })
    }

    function addClientNotice(id) {
        var client_notice = $('input#client_notice').val();
        $.get('{{URL::to("admin/updateClientNotice")}}', { id:id, client_notice: client_notice }, function(data){
            if (data == 'success') {
                $('#edit_order_model').modal('hide');
            }
        })
    }

</script>