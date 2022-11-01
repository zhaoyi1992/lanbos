@extends('admin.layout.layout')
@include('widget.asset.select2')
@include('widget.asset.jquery-layout')
@include('widget.asset.ztree')
@include('widget.asset.export')
@section('content')
    <div class="col-sm-12 search-collapse">
        <form id="role-form">
            <div class="select-list">
                <ul>
                    <li>编号：<input type="text" name="where[id]" value=""></li>
                    <li>任务名称：<input type="text" name="like[name]"></li>
                    <li>任务分类：
                        <select name="where[typeid]" class="select2" data-width="150px">
                            <option value="">全部</option>
                            @foreach($type as $value)
                                <option value="{{$value['id']}}">{{$value['poskey']}}</option>
                            @endforeach
                        </select>
                    <li>
                    <li>任务状态：
                        <select name="where[status]">
                            <option value="">全部</option>
                            <option value="1">显示</option>
                            <option value="0">隐藏</option>
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
    <div class="btn-group-sm" id="toolbar" role="group">
        <a class="btn btn-success" onclick="$.operate.add()"><i class="fa fa-plus"></i> 新增</a>
        <a class="btn btn-primary single disabled" onclick="$.operate.edit('',this)"><i class="fa fa-edit"></i> 修改</a>
        <a class="btn btn-danger multiple disabled" onclick="$.operate.removeAll(this)"><i class="fa fa-trash"></i> 批量删除</a>
        <a class="btn btn-success" onclick="$.operate.addlinks()"><i class="fa fa-plus"></i>号码分配</a>
        <a class="btn btn-danger multiple disabled" onclick="$.operate.removeAlllink(this)"><i class="fa fa-trash"></i> 清空号码</a>

    </div>
    <div class="col-sm-12 select-table table-striped">
        <table id="bootstrap-table"></table>
    </div>
@stop

@section('script')
    <script>
        $(function () {
            var options = {
                modalName: "模板类型",
                sortName:'listsort',
                sortOrder: "asc",
                columns: [
                    {checkbox: true},
                    {field: 'id', title: '编号', align: 'center', sortable: true},
                    {field: 'name', title: '任务名称', align: 'center'},
                    {field: 'typevalue', title: '任务分类', align: 'center'},
                    {field: 'keyword', title: '关键词', align: 'center'},
                    {field: 'price', title: '价格', align: 'center'},
                    {field: 'num', title: '人数', align: 'center'},
                    {field: 'address', title: '地址', align: 'center'},


                    {
                        field: 'status',
                        title: '状态',
                        sortable: true,
                        formatter: function(value, row, index) {
                            return $.view.statusShow(row,false,['隐藏','显示']);

                        }
                    },
                    {field: 'create_time', title: '创建时间', align: 'center', sortable: true},
                    {field: 'update_time', title: '更新时间', align: 'center', sortable: true,visible: false},
                    {
                        title: '操作',
                        align: 'center',
                        formatter: function(value, row, index) {
                            var actions = [];
                            actions.push('<a class="btn btn-success btn-xs" href="javascript:;" onclick="$.operate.edit(\'' + row.id + '\')"><i class="fa fa-edit"></i>编辑</a> ');
                            actions.push('<a class="btn btn-danger btn-xs" href="javascript:;" onclick="$.operate.remove(\'' + row.id + '\')"><i class="fa fa-remove"></i>删除</a> ');
                            return actions.join('');
                        }
                    }
                ]
            };
            $.table.init(options);
        });
    </script>
@stop
