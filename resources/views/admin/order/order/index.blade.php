@extends('admin.layout.layout')

@section('content')
    <div class="col-sm-12 search-collapse">
        <form id="role-form">
            <div class="select-list">
                <ul>
                    <li>编号：<input type="text" name="where[id]" value=""></li>
                    <li>类型名称：<input type="text" name="like[name]"></li>
                    <li>类型状态：
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
{{--        <a class="btn btn-success" onclick="$.operate.add()"><i class="fa fa-plus"></i> 新增</a>--}}
        <a class="btn btn-primary single disabled" onclick="$.operate.edit('',this)"><i class="fa fa-edit"></i> 修改</a>
        <a class="btn btn-danger multiple disabled" onclick="$.operate.removeAll(this)"><i class="fa fa-trash"></i> 批量删除</a>
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
                sortName:'id',
                sortOrder: "asc",
                columns: [
                    {checkbox: true},
                    {field: 'id', title: '编号', align: 'center', sortable: true},
                    {field: 'appidvalue', title: '平台', align: 'center', sortable: true},
                    {field: 'orderid', title: '订单号', align: 'center', sortable: true},
                    {field: 'userid', title: '用户', align: 'center', sortable: true},
                    {field: 'goodsid', title: '兼职编号', align: 'center', sortable: true},
                    {field: 'price', title: '工资', align: 'center'},
                    {field: 'note', title: '备注', align: 'center'},
                    // {
                    //     field: 'photo',
                    //     title: '单图片',
                    //     formatter: function (value, row, index) {
                    //         return $.table.imageView(row,'photo');
                    //     }
                    // },


                    {
                        field: 'status',
                        title: '状态',
                        sortable: true,
                        formatter: function(value, row, index) {
                            return $.view.statusShow(row,false,['报名中','报名成功']);

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

