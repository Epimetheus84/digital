@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>  {{ $review->author }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('reviews.index') }}" title="Go back"> <i class="fas fa-backward "></i> </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Текст отзыва:</strong>
                {{ $review->text }}
            </div>
        </div>
    </div>
    @if($prev)
        <a class="btn btn-secondary" href="{{ route('reviews.show', ['review' => $prev]) }}" title="Предыдущий отзыв"> <i class="fas fa-backward "></i> </a>
    @endif
    @if($next)
        <a class="btn btn-secondary" href="{{ route('reviews.show', ['review' => $next]) }}" title="Следующий отзыв"> <i class="fas fa-forward "></i> </a>
    @endif
@endsection
