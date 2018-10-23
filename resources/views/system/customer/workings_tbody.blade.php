@if(count($workings)>0)
<h1 style="padding-left: 20px;">Working List</h1>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Service Name</th>
        </tr>
    </thead>
    <tbody>
    	@foreach($workings as $value)
		    <tr>
		        <td>{{$value->name}}</td>
		        <td>{{$value->service_name}}</td>
		    </tr>
		@endforeach
    </tbody>
</table>
@endif