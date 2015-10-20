@if(config('app.debug'))
    <div class="container">
        <div class="panel panel-primary shadow1">
            <div class="panel-heading" role="button" data-toggle="collapse" href="#collapseProfile" aria-expanded="false" aria-controls="collapseProfile">
                <span class="panel-title" data-icon="area-chart">Profiler</span>
            </div>
            <div class="collapse" id="collapseProfile">
                @include('core::layout.partials.profiler')
            </div>
        </div>
    </div>
@endif