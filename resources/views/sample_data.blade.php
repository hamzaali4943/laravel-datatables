<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>How to Delete or Remove Data From Mysql in Laravel 6 using Ajax</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
    {{--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.62/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.62/vfs_fonts.js"></script>

    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />--}}

    {{--<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>--}}
    {{--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />--}}
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>--}}


</head>
<body>
<div class="container">
    <br />
    <h3 align="center">Datatables</h3>
    <br />
    <div align="right">
        <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Create Record</button>
        <button type="button" class="btn btn-danger btn-sm"  id="deleteAll">Delete All</button>
    </div>
    <br />
    <div class="table-responsive">


        <table id="user_table" class="table table-bordered table-striped display nowrap" style="width:100%">
            <thead>
            <tr>
                <th width="5%"> <br/> #&nbsp;&nbsp;<input type="checkbox" id="checkAll"/><br/></th>
                <th width="5%">Id</th>
                <th width="15%">First Name</th>
                <th width="15%">Last Name</th>
                <th width="20%">Created</th>
                <th width="20%">Updated</th>
                <th width="20%">Action</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        <tfoot>
        <tr>
            <td>
                <input type="text" value="" id="fnameSearch" class="form-control" placeholder=""
                       data-column="0" disabled>

            </td>
            <td>
                <input type="text" value="" id="fnameSearch" class="form-control filter-input-id" placeholder="Id"
                       data-column="1">

            </td>
            <td>
                <input type="text" value="" id="fnameSearch" class="form-control filter-input-fname" placeholder="First name"
                data-column="2">
            </td>
            <td>
                <input type="text" value="" id="fnameSearch" class="form-control filter-input-lastname" placeholder="Last name"
                       data-column="3">
            </td>

        </tr>

        </tfoot>
        </table>
    </div>
    <br />
    <br />
</div>
</body>
</html>

<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Record</h4>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-md-4" >First Name : </label>
                        <div class="col-md-8">
                            <input type="text" name="first_name" id="first_name" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Last Name : </label>
                        <div class="col-md-8">
                            <input type="text" name="last_name" id="last_name" class="form-control" />
                        </div>
                    </div>
                    <br />
                    <div class="form-group" align="center">
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Add" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Confirmation</h2>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    let pageLen = 0;
    let table;
    $(document).ready(function(){


        var user_id;


         table = $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('sample.index') }}",
            },
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    footer: true,
                    exportOptions:
                        {
                            columns: [0,1,2,3]
                        }

                },
                {
                    extend: 'csv',
                    footer: false,
                    exportOptions:
                        {
                            columns: [0,1,2,3]
                        }

                },
                {
                    extend: 'excel',
                    footer: false,
                    exportOptions:
                        {
                            columns: [0,1,2,3]
                        }
                },
                {
                    extend: 'copy',
                }
            ],
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],

            columns: [
                {

                    data: 'checkBox',
                    name: 'checkBox',
                    orderable: false

                },
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });

         $(".filter-input-id").keyup(function () {

             table.column($(this).data('column'))
                 .search($(this).val()).draw();
         });

        $(".filter-input-fname").keyup(function () {

            table.column($(this).data('column'))
                .search($(this).val()).draw();
        });

        $(".filter-input-lastname").keyup(function () {

            table.column($(this).data('column'))
                .search($(this).val()).draw();
        });

        // function colSearch(tag)
        // {
        //     $('.filter-input-'.tag).keyup(function () {
        //         alert('working');
        //         table.column($(this).data('column'))
        //             .search($(this).val()).draw();
        //     });
        // };
        // colSearch('id');
        // colSearch('fname');
        // colSearch('lastname');


        $('#create_record').click(function(){
            $('.modal-title').text('Add New Record');
            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#form_result').html('');

            $('#formModal').modal('show');
        });



        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            var action_url = '';

            if($('#action').val() == 'Add')
            {
                action_url = "{{ route('sample.store') }}";
            }

            if($('#action').val() == 'Edit')
            {
                action_url = "{{ route('sample.update') }}";
            }

            $.ajax({
                url: action_url,
                method:"POST",
                data:$(this).serialize(),
                dataType:"json",
                success:function(data)
                {
                    var html = '';
                    if(data.errors)
                    {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++)
                        {
                            html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';
                    }
                    if(data.success)
                    {
                        html = '<div class="alert alert-success">' + data.success + '</div>';
                        $('#sample_form')[0].reset();
                        $('#user_table').DataTable().ajax.reload();
                    }
                    $('#form_result').html(html);
                }
            });
        });


        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url :"/sample/"+id+"/edit",
                dataType:"json",
                success:function(data)
                {
                    $('#first_name').val(data.result.first_name);
                    $('#last_name').val(data.result.last_name);
                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Record');
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

// Delete section
        $(document).on('click', '.delete', function(){
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function(){
            $.ajax({
                url:"sample/destroy/"+user_id,
                beforeSend:function(){
                    $('#ok_button').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#user_table').DataTable().ajax.reload();
                        alert('Data Deleted');
                    }, 2000);
                }
            })
        });

        $('#checkAll').click(function () {
            $('.checkbox').prop('checked', $(this).prop("checked"));
        });
        
        $('#deleteAll').click(function () {

            const id = [];
            $(".checkbox:checked").each(function() {
                id.push($(this).val());
            });

            if(id.length <= 0)
            {
                alert("Please select row(s) first");
            }
            else
                {
                    WRN_PROFILE_DELETE = "Are you sure you want to delete selected records?";
                    var check = confirm(WRN_PROFILE_DELETE);
                    if(check == true)
                    {
                        //let ids = id.join(',');
                        // console.log(id);
                        $.ajax({
                            url: "{{ route('sample.destroy.mass') }}",
                            method:"get",
                            data: {id:id},
                            success:function (data) {
                                alert(data);
                                $('#user_table').DataTable().ajax.reload();
                            }
                        });
                    }

                }

        });
        let checkBoxCount = 0;
        $( document ).on( "change", ".checkbox", function () {

            pageLen = table.page.len();
            checkBoxCount = $('.checkbox:checked').length;
            if(pageLen == checkBoxCount)
            {
                $('#checkAll').prop('checked', true);
            }
            else
            {
                $('#checkAll').prop('checked', false);
            }

        });
    });
</script>
