<div class="box box-warning">
    <form method="POST" action="{{ route('users.store') }}" id="frmCreateUser">
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <div class="row form-group">
                <label for="Email">Họ và tên</label>
                <input id="name" class="form-control" type="text" name="name" value="" placeholder="ex : Nguyễn Văn A">
            </div>

            <div class="row form-group">
                <label for="Email">Email</label>
                <input id="email" class="form-control" type="email" name="email" value="" placeholder="abc@gmail.com">
            </div>

            <div class="row form-group">
                <label for="Email">Số điện thoại</label>
                <input id="mobile" class="form-control" type="text" name="mobile" value="" placeholder="">
            </div>

            <div class="row form-group">
                <label for="gender">Giới tính</label>
                <select id="gender" class="form-control" name="gender">
                    <option value="0">Nam</option>
                    <option value="1">Nữ</option>
                </select>
            </div>

            @if(Gate::allows('admin'))

                <div class="row form-group" id="slect_agent">

                    <label for="team_id">Agent</label>
                    {!! Form::select('agent_id', $teams, '', ['id' => 'team_id', 'class' => 'form-control']) !!}
                </div>

                <div class="row form-group">
                    <label for="Role">Quyền</label>
                    {!! Form::select('role_id', $roles, '', ['class' => 'form-control', 'id' => 'roles']) !!}
                </div>

            @endif
            <div class="row text-center">
                <button class="btn btn-primary" id="addUser" type="submit"><i class="fa fa-check" aria-hidden="true"></i>Tạo
                    mới
                </button>
            </div>
    </form>
</div>

