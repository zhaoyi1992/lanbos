@extends('admin.layout.layout')

@section('content')
<div class="col-sm-12 search-collapse">
    <form id="role-form">
        <div class="select-list">
            <ul>
                <li>路由名称：<input type="text" name="like[urlname]" value=""></li>


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
                    {field: 'uid', title: '用户id'},
                    {field: 'appid', title: '平台'},
                    {field: 'ip', title: 'ip地址'},
                    {field: 'address', title: '地址'},
                    {field: 'source', title: '来源'},
                    {field: 'urlname', title: '操作信息'},
                    {field: 'add_time', title: '时间', align: 'center', sortable: true}
                ]
            };
            $.table.init(options);
        });
    </script>
@stop

