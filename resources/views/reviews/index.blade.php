@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Список отзывов</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('reviews.create') }}" title="Создать запись"> <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered" id="yajra-datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Отзыв</th>
                <th>Количество лайков</th>
                <th>Дата публикации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@endsection
