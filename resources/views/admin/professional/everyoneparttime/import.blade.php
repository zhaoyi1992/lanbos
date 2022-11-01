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
            <label class="col-sm-3 control-label">类型：</label>
            <div class="col-sm-8">
                <select name="typeid" id="typeid" class="form-control">
                    @foreach($typeList as $key=>$value)
                        <option value="{{$value['id']}}" >{{$value['name']}}</option>
                    @endforeach
                </select>
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
