<div class="modal fade" id="booking_history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">History of Booking</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="max-height: 600px; overflow: scroll;">
                <div class="table-responsive">
                    <table class="table color-table info-table">
                        <thead>
                            <tr>
                                <th>Appt ID</th>
                                <th>Date Time</th>
                                <th>Branch</th>
                                <th>Service</th>
                                <th>Package</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $value)
                            <tr>
                                <td>{{$value->id}}</td>
                                <td>{{$value->appt_datetime}}</td>
                                <td>{{$value->branch_name}}</td>
                                <td>{{$value->service_name}}</td>
                                <td>{{$value->package_name}}</td>
                                <td>{{$value->booking_status}}</td>                                
                            </tr>
                            @endforeach                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>