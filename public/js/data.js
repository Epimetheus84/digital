$(function () {
    var table = $('#yajra-datatable').DataTable({
        serverSide: true,
        processing: true,
        ajax: "/",
        displayLength: 10,
        searching: false,
        columns: [
            {data: 'id', name: 'ID'},
            {data: 'author', name: 'Автор'},
            {data: 'text', name: 'Текст'},
            {data: 'likes_count', name: 'Кол-во лайков'},
            {data: 'created_at', name: 'Дата публикации'},
            {
                data: 'action',
                name: 'action',
                orderable: false
            }
        ],
        drawCallback: function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#yajra-datatable .like').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/like',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: $(this).data('id'),
                    }
                }).success(function (data) {
                   $('#yajra-datatable').DataTable().draw(false);
                }).error(function (data) {
                    data = JSON.parse(data.responseText);
                    console.log(data)
                    alert(data.error);
                })
            });

        }
    });
});
