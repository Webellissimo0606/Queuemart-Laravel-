@foreach($panel_news as $value)

	<div class="col-lg-3 col-md-6" id="{{ $value-> id }}">
        <!-- Card -->
        <div class="card">
            <img class="card-img-top img-responsive" src="{{ url('').$value->news_image  }}" alt="Card image cap">
            <div class="card-body">
                <h4 class="card-title">News Message</h4>
                <p class="card-text">{{ $value->news_des }}</p>
                <p class="btn btn-primary" id="panel_news_edit" data-id="{{ $value-> id }}"><i class="fa fa-edit"></i> Edit</p>
                <p class="btn btn-danger" id="panel_news_delete" data-id="{{ $value-> id }}"><i class="fa fa-remove"></i> Delete</p>	
            </div>
        </div>
        <!-- Card -->
    </div>

@endforeach