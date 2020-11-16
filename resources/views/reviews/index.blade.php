@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Список закладок</h2>
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

    <table class="table table-bordered" id="yajra-datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Заголовок страницы</th>
                <th>URL</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="col-xs-12">
        <a href="/export" class="btn btn-success">Выгрузить в xlsx</a>
    </div>

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Подвердите удаление</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Пароль:</label>
                            <input type="password" name="password" class="form-control" required>
                            <input type="hidden" name="id" class="form-control">
                        </div>
                        <div class="form-group form-error">
                            <span class="text-danger"></span>
                        </div>
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
