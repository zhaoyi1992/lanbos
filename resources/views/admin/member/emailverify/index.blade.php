@extends('admin.layout.layout')

@section('content')
<div class="col-sm-12 search-collapse">
    <form id="role-form">
        <div class="select-list">
            <ul>
                <li>账号：<input type="text" name="like[login_name]" value=""></li>
                <li>登陆状态：
                    <select name="where[status]">
                        <option value="">全部</option>
                        <option value="1">修改</option>
                        <option value="0">注册</option>
                    </select>
                <li>

                <li>
                    <a class="btn btn-primary btn-rounded btn-sm" onclick="$.table.search()"><i class="fa fa-search"></i> 搜索</a>
                    <a class="btn btn-warning btn-rounded btn-sm" onclick="$.form.reset()"><i class="fa fa-refresh"></i> 重置</a>
                </li>
            </ul>
        </div>
    </form>
</div>

<div class="col-sm-12 select-table table-striped">
    <table id="bootstrap-table"></table>
</div>
@stop

@section('script')
    <script>
        $(function () {
            var options = {
                modalName: "登录日志",
                sortName:'id',
                sortOrder: "desc",
                columns: [
                    {checkbox: true},
                    {field: 'id', title: '日志ID', visible:false},
                    {
                        field: '',
                        title:'序号',
                        formatter:function (value, row, index){
                            return $.table.serialNumber(index);
                        }
                    },
                    {field: 'email', title: '用户账号'},
                    {field: 'type', title: '类型'},
                    {field: 'code', title: '验证码'},
                    {field: 'created_at', title: '时间', align: 'center', sortable: true}
                ]
            };
            $.table.init(options);
        });
    </script>
@stop

