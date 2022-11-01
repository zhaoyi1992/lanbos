@extends('admin.layout.form')
@include('widget.asset.summernote')
@section('content')
    <form class="form-horizontal m" id="form-add">

        <div class="form-group">
            <label class="col-sm-3 control-label is-required">任务名称：</label>
            <div class="col-sm-8">
                <input type="text" name="name" value="" class="form-control" required autocomplete="off"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label is-required">类型：</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="hidden" id="treeId" name="typeid" value="">
                    <input type="text" id="treeName" value="" class="form-control" placeholder="请选择类型" readonly autocomplete="off"/>
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label is-required">关键词：</label>
            <div class="col-sm-8">
                <input type="text" name="keyword" value="" class="form-control" placeholder="关键词,隔开"required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">价格：</label>
            <div class="col-sm-8">
                <input type="text" name="price" value="" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">人数：</label>
            <div class="col-sm-8">
                <input type="text" name="num" value="" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">地址：</label>
            <div class="col-sm-8">
                <input type="text" name="address" value="" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">详情：</label>
            <div class="col-sm-8">
                <textarea id="content" name="content" class="hide" required></textarea>
                <div class="summernote" data-place="请输入详情" id="contentEditor"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">号码：</label>
            <div class="col-sm-8">
                <textarea name="links" class="form-control" placeholder="请输入号码,每行一个号码"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">状态：</label>
            <div class="col-sm-8">
                <label class="radio-box">
                    <input type="radio" name="status" value="1"/> 隐藏
                </label>
                <label class="radio-box">
                    <input type="radio" name="status" value="1"/> 显示
                </label>
            </div>
        </div>

    </form>
@stop

@section('script')
    <script>
        $(function () {
            //选择组织架构
            $("#treeName").click(function () {
                var treeId=$("#treeId").val();
                var url = urlcreate("{{route('professional.everyoneparttimetype.tree')}}","id="+treeId+"&parent=0");
                var options = {
                    title: '类选择',
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
                if($("#contentEditor").summernote('isEmpty')){
                    $.modal.msgError('请填写内容')
                    return false;
                }
                var sHTML = $('#contentEditor').summernote('code');
                $("#content").val(sHTML);
                $.operate.save(oasUrl, $('#form-add').serialize());
            }
        }
    </script>

@stop
