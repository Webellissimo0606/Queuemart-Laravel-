@foreach($float_news as $value)
    <div class="col-lg-3 col-md-6" id="{{ $value-> id }}">
        <!-- Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">News Message</h4>
                <p class="card-text">{{ $value->float_des }}</p>
                <p class="btn btn-primary" id="float_news_edit" data-id="{{ $value-> id }}"><i class="fa fa-edit"></i> Edit</p>
                <p class="btn btn-danger" id="float_news_delete" data-id="{{ $value-> id }}"><i class="fa fa-remove"></i> Delete</p>
                @if($value->float_active == 1 )
                <p class="btn btn-success" id="float_news_active" data-id="{{ $value-> id }}"><i class="fa fa-check"></i></p>
                @else
                    <p class="btn btn-secondary" id="float_news_active" data-id="{{ $value-> id }}"><i class="fa fa-check"></i></p>
                @endif
            </div>
        </div>
        <!-- Card -->
    </div>     
@endforeach