@extends('admin.layout.form')
@include('widget.asset.summernote')
@section('content')
    <form class="form-horizontal m" id="form-edit">
        <input type="hidden" name="id" value="{{$info['id']}}">
        <input type="hidden" name="typeid" id="treeId" value="{{$info['typeid']}}">
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">任务名称：</label>
            <div class="col-sm-8">
                <input type="text" name="name" value="{{$info['name']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label is-required">类型：</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" id="treeName" value="{{$typevalue['poskey']}}" class="form-control" placeholder="请选择类型" readonly autocomplete="off" />
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>



        <div class="form-group">
            <label class="col-sm-3 control-label is-required">关键词：</label>
            <div class="col-sm-8">
                <input type="text" name="keyword" value="{{$info['keyword']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">价格：</label>
            <div class="col-sm-8">
                <input type="text" name="price" value="{{$info['price']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">人数：</label>
            <div class="col-sm-8">
                <input type="text" name="num" value="{{$info['num']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">地址：</label>
            <div class="col-sm-8">
                <input type="text" name="address" value="{{$info['address']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">详情：</label>
                <div class="col-sm-8">
                    <textarea class="summernote_content hide" id="content" name="content">{{$info['content']}}</textarea>
                    <div class="summernote" data-place="请输入详情" id="contentEditor"></div>
                </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">号码：</label>
            <div class="col-sm-8">
                <textarea name="links" class="form-control" placeholder="请输入号码,每行一个号码">{{$info['links']}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">状态：</label>
            <div class="col-sm-8">
                <label class="radio-box">
                    <input type="radio" name="status" value="0" @if($info["status"] == "0") checked @endif/> 隐藏
                </label>
                <label class="radio-box">
                    <input type="radio" name="status" value="1" @if($info["status"] == "1") checked @endif/> 显示
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
        };
        function submitHandler() {
            if ($.validate.form()) {
                if($("#contentEditor").summernote('isEmpty')){
                    $.modal.msgError('请填写内容')
                    return false;
                }
                var sHTML = $('#contentEditor').summernote('code');
                $("#content").val(sHTML);
                $.operate.save(oesUrl, $('#form-edit').serialize());
            }
        }
    </script>
@stop
