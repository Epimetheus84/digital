$(function () {
    var table = $('#yajra-datatable').DataTable({
        serverSide: true,
        processing: true,
        ajax: "/reviews/",
        displayLength: 10,
        columns: [
            {data: 'id', name: 'ID'},
            {data: 'author', name: 'Автор'},
            {data: 'likes_count', name: 'Кол-во лайков'},
            {
                data: 'action',
                name: 'action',
                orderable: false
            }
        ],
    });
});
