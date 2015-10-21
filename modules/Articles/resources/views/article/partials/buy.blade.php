@if(auth()->check())
<div class="alert alert-info m-b-none">@lang('articles::article.message.need_to_buy', ['amount' => $article->getFormatedCost()])</div>
<div class="well well-sm">
    {!! Form::open(['route' => ['front.article.buy', $article->id]]) !!}
    {!! Form::button(trans('articles::article.button.buy'), [
        'type' => 'submit', 'class' => 'btn btn-success', 'data-icon' => 'check'
    ]) !!}
    {!! Form::close() !!}
</div>
@else
    <div class="alert alert-warning m-b-none">
        @lang('articles::article.message.need_to_register', ['amount' => $article->getFormatedCost()])
        @if($couponsCount > 0)
            <br />
            <small>
                @lang('core::comingsoon.registerBonus', ['count' => $couponsCount, 'amount' => 10])
            </small>
        @endif
    </div>
@endif