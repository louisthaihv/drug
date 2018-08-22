@extends('layouts.master')


@section('css')

    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">

@stop


@section('content')

    @if(Gate::allows('admin'))
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control" id="role_id">
                        <option value="">Chọn quyền</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border margin-bottom-10">
                    <h3 class="box-title">Danh sách thành viên</h3>
                    @if(Gate::allows('admin'))
                        <button type="button" class="btn btn-primary pull-right" id="createUserBtn">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới
                        </button>
                        <button class="btn btn-danger" type="button" onclick="deleteManyRow()">
                            <i class="fa fa-trash" aria-hidden="true"></i> Xóa
                        </button>
                    @endif
                </div>


                <div class="table-responsive">

                    <table class="table table-bordered " id="user_table">

                    </table>

                </div>

            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="registerUserModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="box-title">Tạo mới thành viên</h3>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="editUserModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="box-title">Sửa thông tin người dùng</h3>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>


@stop

@section('js')

<script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>

    <script type="text/javascript">
    var userTable;
    $(function() {
        $('#role_id').select2();
        $('#m_role_id').select2({
            theme: 'bootstrap',
            with: '100%'
        });

        $('#role_id').on('change', function(){
            userTable.ajax.reload();
        });

        userTable = $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '{!! route('users.datatable') !!}',
                "data": function ( d ) {
                    d.role_id = $('#role_id').val();
                }
            },
            columns: [
                { data: 'id', name: 'id', searchable: false, title: 'ID' },
                { data: 'name', name: 'name', title: 'Tên' },
                { data: 'mobile', name: 'mobile', title: 'Số điện thoại' },
                { data: 'email', name: 'email', title: 'Email' },
                { data: 'rName', name: 'role', title: 'Quyền', searchable: false, sortable:false },
                { data: 'status_name', name: 'status_name', title: 'Trạng thái', searchable: false,
                    render: function(data, type, row, meta) {
                        $active = '';
                        if (row['status'] == "1") {
                            $active = 'active';
                        }
                        return '<button onclick="activeUser('+ row['id'] +')" type="button" class="btn btn-lg btn-toggle ' + $active + '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
                    }
                },
                { data: 'created_at', name: 'created_at', title: 'Ngày tạo'},
                { data: 'updated_at', name: 'updated_at', title: 'Ngày cập nhật'},
                { data: 'id', name: 'id', title: 'Thao Tác', searchable: false,className: 'text-center', "orderable": false,
                    visible : visible,
                    render: function(data, type, row, meta){
                        var userId = "'" + row['id'] + "'";
                        let urlEdit = window.location.origin + '/admin/users/' + data + '/edit';
                        let switchUrl = window.location.origin + '/admin/users/switch/' + data;
                        let actionLink = '<a href="javascript:;" data-toggle="tooltip" title="Xoá '+ row['name'] +'!" onclick="deleteUserById('+ userId +')"><i class=" fa-2x fa fa-trash" aria-hidden="true"></i></a>';
                        actionLink += '&nbsp;&nbsp;&nbsp;<a href="javascript:;" onclick="editUser('+ userId +')" data-toggle="tooltip" title="Sửa '+ row['name'] +'!" ><i class="fa fa-2x fa-pencil-square-o" aria-hidden="true"></i></a>';
                        return actionLink;
                    }
                }
            ],
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0,
                'render': function (data, type, full, meta){
                   return '<input class="chkUser" type="checkbox" name="id[]" value="'
                      + data + '">';
               }
            } ],
        });

    });
    function deleteUserById(id) {
        swal({
            title: "Bạn có muốn xóa người dùng này?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: 'Bỏ qua',
            confirmButtonText: "Đồng ý",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: window.location.origin + '/admin/users/' + id,
                    method: 'DELETE',
                    success: function(data) {
                        console.log(data);
                        if(data.code == 200)
                        {
                            swal(
                              'Đã Xoá!',
                              'Bạn đã xoá thành công người dùng!',
                              'success'
                            ).then(function(){
                                userTable.ajax.reload();
                            })
                        }
                        else {
                            swal(
                                'Thất bại',
                                'Thao tác thất bại',
                                'error'
                            ).then(function(){
                                userTable.ajax.reload();
                            })
                        }
                    },
                    error: function(data) {
                        swal(
                            'Thất bại',
                            'Thao tác thất bại',
                            'error'
                        ).then(function(){
                            userTable.ajax.reload();
                        })
                    }
                });
            // result.dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
            } else if (result.dismiss === 'cancel') {
              swal(
                'Bỏ Qua',
                'Bạn đã không xoá người dùng nữa',
                'error'
              )
            }
          });
    }


    var createRouter = "{{ route('users.create') }}";

    $('#createUserBtn').on('click', function () {
        $.get(createRouter).done(function(view){
            $('#registerUserModal').find('.modal-body').html(view).promise().done(function(){
                $('#registerUserModal').modal('show');
                $('#roles').select2({
                    placeholder: "Chọn quyền thành viên",
                });
                $('#team_id').select2({
                    placeholder: "Chọn Agent",
                });

                $('.select2-container--default').css({width: '100%'});

            });
            validateSetup('frmCreateUser');

        }).fail(function(error){
            console.log(error);
        });

    });

    function validateSetup(formId) {
        $('#' + formId).bootstrapValidator({
            message: 'Dữ liệu nhập không đúng',
            fields: {
                name: {
                    message: 'Tên không đúng định dạng',
                    validators: {
                        notEmpty: {
                            message: 'Tên không được trống'
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: 'Tên dài từ 6 tới 30 ký tự'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Email không được rỗng'
                        },
                        emailAddress: {
                            message: 'Không phải địa chỉ email'
                        }
                    }
                },
                password : {
                    validators:{
                        notEmpty: {
                            message: 'Mật khẩu không được rỗng'
                        },
                        stringLength: {
                            min: 6, max: 30,
                            message: 'Mật khẩu phải dài từ 6 đến 30 ký tự'
                        }
                    }
                },
                confirmed_password : {
                    validators:{
                        notEmpty: {
                            message: 'Xác nhận mật khẩu không được rỗng'
                        },
                        identical : {
                            field: 'password',
                            message: 'Xác nhận mật khẩu không đúng'
                        }
                    }
                },
                dob: {
                    validators: {
                        date: {
                            format: 'YYYY-MM-DD',
                            message: 'Sai định dạng'
                        }
                    }
                }
            }
        });
    }

    function editUser(uId) {
        $.ajax({
                url: '{!! route("users.index") !!}' +'/'+ uId + '/edit',
                method: 'GET',
                success : function(data){
                    $('#editUserModal .modal-body').html(data).promise().done(function(){

                        validateSetup('frmEditUser');

                        $('#roles').select2({
                            placeholder: "Chọn quyền",
                        });
                        $('.datepicker').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true
                        });
                        $('#team_id').select2({
                            placeholder: "Chọn Agent",
                        });
                        $('.select2-container--default').css({width: '100%'});
                    });
                }
            });
        $("#editUserModal").modal();
    }
    function activeUser(userId) {
        toastr.options.closeButton = true;
        $.ajax({
            url: "{{ route('user.togleStatus') }}",
            data: {id: userId},
            method: 'POST',
            success: function(data) {
                userTable.ajax.reload();
                toastr.clear();
                if (data.code == 200) {
                    // Override global options
                    toastr.success('Update Thành công trạng thái người dùng', 'Thành Công', {timeOut: 3000})
                }
                else {
                    // Override global options
                    toastr.error('Update Không Thành công trạng thái người dùng', 'Thất Bại', {timeOut: 3000})
                }
            }
        });
    }
        // delete bus in checkbox
    function deleteManyRow() {
        var listUserId = [];
        $('.chkUser').each(function(){
            if ($(this).prop('checked')) {
                listUserId.push($(this).val());
            }
        });
        if (listUserId.length < 1) {
            swal("Xảy Ra Lỗi", "Bạn chưa check chọn user nào!", "error");
            return false;
        }

        swal({
            title: "Bạn có muốn xóa những user này?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: 'Bỏ qua',
            confirmButtonText: "Đồng ý",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{!! route('user.multiple.delete') !!}',
                    method: 'POST',
                    data: {data:listUserId},
                    success: function(data){
                        console.log(data);
                        if(data.code == 200)
                        {
                            swal(
                                'Đã Xoá!',
                                'Bạn đã xoá thành công ' + data.count + ' người dùng!',
                                'success'
                            ).then(function(){
                                userTable.ajax.reload();
                            })
                        }
                        else {
                            swal(
                                'Thất bại',
                                'Thao tác thất bại',
                                'error'
                            ).then(function(){
                                userTable.ajax.reload();
                            })
                        }
                    },
                    error: function(data){
                        swal(
                            'Thất bại',
                            'Thao tác thất bại',
                            'error'
                        ).then(function(){
                            userTable.ajax.reload();
                        })
                    }
                });
                // result.dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
            } else if (result.dismiss === 'cancel') {
                swal(
                    'Bỏ Qua',
                    'Bạn đã không xoá người dùng nữa',
                    'error'
                )
            }
        });
    }
    </script>

@stop
