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