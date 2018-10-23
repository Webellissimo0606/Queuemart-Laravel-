@if($branch != '') 
<div class="card card-outline-primary" id="{{$branch->id}}">
    <div class="card-header">
        <h4 class="m-b-0 text-white branch_name">{{$branch->branch_name}}</h4>
    </div>
    <div class="card-body">
        <form action="{{ URL::to('admin/updateBranch') }}" method="POST" id="edit_branch_form" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-body">  
                <div class="form-group m-t-10">                                      
                    <img src="{{$branch->branch_image}}" class="img-responsive" style="margin-bottom: 10px; max-height: 500px;">
                    <input type="file" id="branch_image" name="branch_image" />
                    <input class="form-control" type="text" id="id" name="id" value="{{$branch->id}}" hidden>
                </div>
                <div class="form-group m-t-10">
                    <label class="col-form-label">Name :</label>
                    <input class="form-control" type="text" name="branch_name" id="branch_name" value="{{$branch->branch_name}}">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Branch Label :</label>
                    <input class="form-control" type="text" name="branch_label" id="branch_label" value="{{$branch->branch_label}}">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Tel. No. :</label>
                    <input class="form-control" type="text" name="branch_tel_num" id="branch_tel_num" value="{{$branch->branch_tel_num}}">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Location :</label>
                    <div class="row">
                        <div class="col-6 row">
                            <label for="example-text-input" class="col-3 col-form-label text-right">Latitude:</label>
                            <div class="col-9">
                                <input class="form-control" value="{{$branch->latitude}}" type="text" name="latitude" id="latitude">
                            </div>
                        </div>
                        <div class="col-6 row">
                            <label for="example-text-input" class="col-3 col-form-label text-right">Longitude:</label>
                            <div class="col-9">
                                <input class="form-control" value="{{$branch->longitude}}" type="text" name="longitude" id="longitude">
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Description :</label>
                    <textarea class="form-control" rows="5" name="branch_des" id="branch_des">{{$branch->branch_des}}</textarea>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Address :</label>
                    <textarea class="form-control" rows="5" name="branch_address" id="branch_address">{{$branch->branch_address}}</textarea>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Services :</label>
                    <div id="service_check_list_1">
                        @foreach($services as $service)
                        <div class="checkbox checkbox-success">
                            <input id="e-{{$service->id}}" type="checkbox" value="{{$service->id}}">
                            <label for="e-{{$service->id}}"> {{$service->service_name}} </label>
                        </div>
                        @endforeach
                    </div>
                    
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button> 
                <button type="button" onclick="deleteBranch('{{$branch->id}}')" class="btn btn-danger"><i class="fa fa-remove"></i> Delete</button>
            </div>
        </form>
    </div>
</div>

<div id="hidden_list" hidden>
    @foreach($relations as $relation)
        <input type="checkbox" value="{{$relation->service_id}}">
    @endforeach
</div>

<script type="text/javascript">

    var relations = [];
    var object = $('#edit_branch_form').find(':checkbox');
    var relations = $('#hidden_list').find(':checkbox');
    for (var i = object.length - 1; i >= 0; i--) {
        for (var j = relations.length - 1; j >= 0; j--) {
            if(relations.eq(j).val() == object.eq(i).val()){
                object.eq(i).attr('checked', true);
            }
        }        
    }


    $('#edit_branch_form').on('submit', function(e) {

        e.preventDefault();
        var url = $(this).attr('action');
        var post = $(this).attr('method');
        $.ajax({
            type : post,
            url : url,
            data: new FormData($("#edit_branch_form")[0]),
            contentType: false,
            processData: false,
            success:function(data){                    
                var service_ids = [];
                var obj = $('#edit_branch_form').find(':checkbox:checked')

                for (var i = obj.length - 1; i >= 0; i--) {
                    service_ids.push(obj.eq(i).val())
                }
                
                $.get('{{URL::to("admin/insertRelation")}}',
                {
                    branch_id: data.id,
                    service_ids: service_ids
                }, function(data) {
                    alert(data.message);
                    
                })                
                $('h4.branch_name').text(data.branch_name);
                $('#branch_list a.active_btn').text(data.branch_name);
                $('#edit_branch_form').find('img').attr('src', data.branch_image);
            }
        })
    });

    function deleteBranch($id) {
        var id = $id;
        var con = confirm("Are you Sure!");
        if (con == true) {
            $.get('{{URL::to("admin/deleteBranch")}}', { id:id }, function(data){
                if (data == 'success') {
                    location.reload();
                }
            })
        } else {
            return false;
        }
    }

</script>

@else

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h3>Please Add a Branch!!!</h3>
    </div>
</div>

    

@endif