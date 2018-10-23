<div class="modal fade" id="add_appt_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">New Appointment</h4>
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
                                            <th colspan="2">Booking Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Appointment Time :</td>
                                            <td>
                                                <input type="text" id="booking_time" class="form-control" placeholder="08:00 or 20:00">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Service Name :</td>
                                            <td>{{$service_appt->service_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Package name :</td>
                                            <td>
                                                <select class="custom-select" id="package_appt_list">
                                                    @foreach($packages_appt as $value)
                                                    <option value="{{$value->id}}">{{$value->package_name}}</option>
                                                    @endforeach               
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Client's Note :</td>
                                            <td>
                                                <input type="text" class="form-control" id="client_note">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Admin's Note :</td>
                                            <td>
                                                <input type="text" class="form-control" id="admin_note">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table color-bordered-table primary-bordered-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Customer Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="client_tbody">
                                        <tr id="client_tr">
                                            <td>Select Client :</td>
                                            <td>
                                                <input type="text" id="client_id" class="form-control" list="address_list">
                                                <datalist id="address_list">
                                                    @foreach($users_appt as $value)
                                                        <option value="{{$value->id}}. {{$value->name}} (ic:{{$value->ic}}, phone:{{$value->phone_number}})">
                                                    @endforeach
                                                </datalist>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button id="addNewCustomer" onclick="addNewCustomer()" type="button" class="btn btn-success active" data-toggle="button" aria-pressed="true">
                                                    <span class="text">Select Client</span>
                                                    <span class="text-active">New Customer</span>
                                                </button>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr id="error_MSG" hidden>
                                            <td>Error :</td>
                                            <td id="error_msg"></td>
                                        </tr>
                                        <tr id="new_customer_phone_tr" hidden>
                                            <td>Client PhoneNumber :</td>
                                            <td>
                                                <input type="text" class="form-control" id="client_phone" placeholder="60127778888">
                                            </td>
                                        </tr>
                                        <tr id="new_customer_name_tr" hidden>
                                            <td>Client's Full Name :</td>
                                            <td>
                                                <input type="text" class="form-control" id="client_name">
                                            </td>
                                        </tr>
                                        <tr id="new_customer_national_tr" hidden>
                                            <td>Client's Nationality :</td>
                                            <td>
                                                <input type="text" class="form-control" id="client_national">
                                            </td>
                                        </tr>
                                        <tr id="new_customer_ic_tr" hidden>
                                            <td>Client's IC :</td>
                                            <td>
                                                <input type="text" class="form-control" id="client_ic">
                                            </td>
                                        </tr>
                                        <tr id="new_customer_email_tr" hidden>
                                            <td>Client's Email :</td>
                                            <td>
                                                <input type="text" class="form-control" id="client_email">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>
                        </div>
                    </div>              
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add_appt_button"><i class="fa fa-check"></i> Save</button>
                <button type="button" class="btn btn-danger close_btn" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function addNewCustomer() {
        $('#client_tbody input').val('');
        if ($('#addNewCustomer').hasClass('active') == true) {
            $('tr#client_tr').attr('hidden', '');
            $('tr#new_customer_phone_tr').removeAttr('hidden');
            $('tr#new_customer_name_tr').removeAttr('hidden');
            $('tr#new_customer_national_tr').removeAttr('hidden');
            $('tr#new_customer_ic_tr').removeAttr('hidden');
            $('tr#new_customer_email_tr').removeAttr('hidden');
        } else {
            $('tr#new_customer_phone_tr').attr('hidden', '');
            $('tr#new_customer_name_tr').attr('hidden', '');
            $('tr#new_customer_national_tr').attr('hidden', '');
            $('tr#new_customer_ic_tr').attr('hidden', '');
            $('tr#new_customer_email_tr').attr('hidden', '');
            $('tr#client_tr').removeAttr('hidden');
        }
    }

    $('.add_appt_button').click(function(){
        var company_id = $('h3.company_id').attr('id'); 
        var branch_id = $('select#branch_list option:checked').val();
        var service_id = '{{$service_appt->id}}';
        var package_id = $('select#package_appt_list option:checked').val();
        var user_text = $('input#client_id').val();
        var user_id = user_text.split(".")[0];
        var booking_day = $('#date_display_view').text();
        var booking_time = $('input#booking_time').val();
        var client_note = $('input#client_note').val();
        var admin_note = $('input#admin_note').val();
        var client_phone = $('input#client_phone').val();
        var client_name = $('input#client_name').val();
        var client_national = $('input#client_national').val();
        var client_ic = $('input#client_ic').val();
        var client_email = $('input#client_email').val();
        if (booking_time != '' && user_text != '') {
            $.get('{{URL::to("admin/addAppointmentClass")}}', 
                { 
                    company_user_id:company_id,
                    branch_id:branch_id,
                    service_id:service_id,
                    package_id:package_id,
                    user_id:user_id,
                    booking_day:booking_day,
                    booking_time:booking_time,
                    client_notice:client_note,
                    admin_notice:admin_note
                }, function(data){
                var html = '<div class="client_div row" id="booking_div_'+data.id+'" style="z-index:100;" onclick="editOrder('+data.id+')">'+
                                '<div class="col-12 order_info_one" id="back_color_'+data.booking_status+'"><p><span>Client Name :</span><span>'+data.name+'</span></p><p><span>Package Name :</span><span>'+data.package_name+'</span></p><p><span>Client Note :</span><span>'+data.client_notice+'</span></p><p><span>Appt Time :</span><span>'+parseDateTime(data.appt_datetime)+'</span></p></div>'+
                                '<div class="col-12 order_check" id="order_check_'+data.id+'">'+
                                '<button onclick="arriveCheck('+data.id+')" id="arrive_check_btn_'+data.id+'" type="button" class="btn btn-primary arrived_check_btn" data-toggle="button">'+
                                        '<i class="ti-location-arrow text" aria-hidden="true"></i>'+
                                        '<i class="ti-location-arrow text-active" aria-hidden="true"></i>'+
                                    '</button>'+
                                '<button onclick="arrivedCheck('+data.id+')" id="arrived_check_btn_'+data.id+'" type="button" class="btn btn-info arrived_check_btn" data-toggle="button" data-more="#sh" aria-pressed="false" hidden>'+
                                        '<i class="ti-control-stop text" aria-hidden="true"></i>'+
                                        '<i class="ti-control-play text-active" aria-hidden="true"></i>'+
                                    '</button>'+
                                    '<span id="order_duration'+data.id+'">Booked</span>'+
                                    '<button onclick="completedCheck('+data.id+')" id="complete_check_btn_'+data.id+'" type="button" class="btn btn-success complete_check_btn" data-toggle="button" data-more="#sh" aria-pressed="false" disabled>'+
                                        '<i class="ti-na text" aria-hidden="true"></i>'+
                                        '<i class="ti-check text-active" aria-hidden="true"></i>'+
                                    '</button>'+
                                '</div>'+
                            '</div>';
                $('#timeslots_td_'+service_id).append(html);
                $('#add_appt_model').modal('hide');
            }); 
        } else if (booking_time != '' && client_phone != '' && client_name != '' && client_national != '' && client_ic != '' && client_email != '' ) {
            $.get('{{URL::to("admin/addUserAppointmentClass")}}', 
                { 
                    company_user_id:company_id,
                    branch_id:branch_id,
                    service_id:service_id,
                    package_id:package_id,
                    client_phone:client_phone,
                    client_name:client_name,
                    client_national:client_national,
                    client_ic:client_ic,
                    client_email:client_email,
                    booking_day:booking_day,
                    booking_time:booking_time,
                    client_notice:client_note,
                    admin_notice:admin_note
                }, function(data){
                    if (data.same_user) {
                        $('#error_msg').text(data.same_user);
                        $('#error_MSG').removeAttr('hidden');
                    } else if (data.same_phone) {
                        $('#error_msg').text(data.same_phone);
                        $('#error_MSG').removeAttr('hidden');
                    } else if (data.same_email) {
                        $('#error_msg').text(data.same_email);
                        $('#error_MSG').removeAttr('hidden');
                    } else {
                        var html = '<div class="client_div row" id="booking_div_'+data.id+'" style="z-index:100;" onclick="editOrder('+data.id+')">'+
                                        '<div class="col-12 order_info_one" id="back_color_'+data.booking_status+'"><p><span>Client Name :</span><span>'+data.name+'</span></p><p><span>Package Name :</span><span>'+data.package_name+'</span></p><p><span>Client Note :</span><span>'+data.client_notice+'</span></p><p><span>Appt Time :</span><span>'+parseDateTime(data.appt_datetime)+'</span></p></div>'+
                                        '<div class="col-12 order_check" id="order_check_'+data.id+'">'+
                                        '<button onclick="arriveCheck('+data.id+')" id="arrive_check_btn_'+data.id+'" type="button" class="btn btn-primary arrived_check_btn" data-toggle="button">'+
                                                '<i class="ti-location-arrow text" aria-hidden="true"></i>'+
                                                '<i class="ti-location-arrow text-active" aria-hidden="true"></i>'+
                                            '</button>'+
                                        '<button onclick="arrivedCheck('+data.id+')" id="arrived_check_btn_'+data.id+'" type="button" class="btn btn-info arrived_check_btn" data-toggle="button" data-more="#sh" aria-pressed="false" hidden>'+
                                                '<i class="ti-control-stop text" aria-hidden="true"></i>'+
                                                '<i class="ti-control-play text-active" aria-hidden="true"></i>'+
                                            '</button>'+
                                            '<span id="order_duration'+data.id+'">Booked</span>'+
                                            '<button onclick="completedCheck('+data.id+')" id="complete_check_btn_'+data.id+'" type="button" class="btn btn-success complete_check_btn" data-toggle="button" data-more="#sh" aria-pressed="false" disabled>'+
                                                '<i class="ti-na text" aria-hidden="true"></i>'+
                                                '<i class="ti-check text-active" aria-hidden="true"></i>'+
                                            '</button>'+
                                        '</div>'+
                                    '</div>';
                        $('#timeslots_td_'+service_id).append(html);
                        $('#add_appt_model').modal('hide');
                    }
            });
        }              
    })

    function parseDateTime(dt) {
        var date = false;
        if (dt) {
            var c_date = new Date(dt);
            var hrs = c_date.getHours();
            var min = c_date.getMinutes();
            if (isNaN(hrs) || isNaN(min) || c_date === "Invalid Date") {
                return null;
            }
            var type = (hrs <= 12) ? " AM" : " PM";
            date = ((+hrs % 12) || hrs) + ":" + min + type;
        }
        return date;
    }
</script>


