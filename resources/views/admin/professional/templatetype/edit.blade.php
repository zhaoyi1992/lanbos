@extends('admin.layout.form')

@section('content')
    <form class="form-horizontal m" id="form-edit">
        <input type="hidden" name="id" value="{{$info['id']}}">
        <input type="hidden" name="parent_id" id="treeId" value="{{$info['parent_id']}}">

        <div class="form-group">
            <label class="col-sm-3 control-label is-required">上级类：</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" id="treeName" value="{{$info['parent_name']}}" class="form-control" placeholder="请选择上级组织" readonly autocomplete="off" />
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">类名称：</label>
            <div class="col-sm-8">
                <input type="text" name="name" value="{{$info['name']}}" class="form-control" placeholder="请输入组织名称" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">显示顺序：</label>
            <div class="col-sm-8">
                <input type="number" name="listsort" value="{{$info['listsort']}}" class="form-control" placeholder="请输入显示顺序" required autocomplete="off"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label is-required">状态：</label>
            <div class="col-sm-8">
                <label class="radio-box">
                    <input type="radio" name="status" value="0" @if($info['status'] == '0') checked @endif/> 无效
                </label>
                <label class="radio-box">
                    <input type="radio" name="status" value="1" @if($info['status'] == '1') checked @endif/> 有效
                </label>
            </div>
        </div>

    </form>
@stop

@section('script')
    <script>
        $(function () {
            //选择组织树
            $("#treeName").click(function () {
                var treeId = $("#treeId").val();
                var url=urlcreate("{{route('professional.templatetype.tree')}}","id=" + treeId);
                var options = {
                    title: '组织选择',
                    width: "380",
                    url: url,
                    callBack: doSubmit
                };
                $.modal.openOptions(options);
            });
        });
        function doSubmit(index, layero){
            var body = layer.getChildFrame('body', index);
            $("#treeId").val(body.find('#treeId').val());
            $("#treeName").val(body.find('#treeName').val());
            layer.close(index);
        }
        function submitHandler() {
            if ($.validate.form()) {
                $.operate.save(oesUrl, $('#form-edit').serialize());
            }
        }
    </script>
@stop
