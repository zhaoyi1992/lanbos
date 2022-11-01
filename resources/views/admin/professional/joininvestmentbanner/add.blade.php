@extends('admin.layout.form')
@include('widget.asset.dragula')
@include('widget.asset.upload')
@section('content')
    <form class="form-horizontal m" id="form-add">
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">名称：</label>
            <div class="col-sm-8">
                <input type="text" name="title" value="" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">位置：</label>
            <div class="col-sm-8">
                <select name="place" id="place" class="form-control">
                    <option value="0" >首页</option>
                    <option value="1" >分类1</option>
                    <option value="2" >分类2</option>
                    <option value="3" >分类3</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">图片：</label>
            <div class="col-sm-8">
                <input type="hidden" name="img" value="" id="img" required>
                <x-upload type="img" name="img_upload" :extend="['cat'=>'demo','link'=>1,'tips'=>'格式为jpg,jpeg,png,gif；大小不能超过10M']"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">链接：</label>
            <div class="col-sm-8">
                <input type="text" name="link" value="" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">排序：</label>
            <div class="col-sm-8">
                <input type="text" name="listsort" value="" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">推荐：</label>
            <div class="col-sm-8">
                <label class="radio-box">
                    <input type="radio" name="hot" value="0"/> 否
                </label>
                <label class="radio-box">
                    <input type="radio" name="hot" value="1" checked/> 是
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">状态：</label>
            <div class="col-sm-8">
                <label class="radio-box">
                    <input type="radio" name="status" value="0"/> 隐藏
                </label>
                <label class="radio-box">
                    <input type="radio" name="status" value="1" checked/> 显示
                </label>
            </div>
        </div>

    </form>
@stop

@section('script')
    <script>

        function submitHandler() {
            var img = '';
            if($("input[name='img_upload[]']").length>0){
                console.log(1)
                $("input[name='img_upload[]']").each(function () {
                    var imgval = $(this).val();

                    if(imgval){
                        img = imgval;
                    }
                })
            }

            $("#img").val(img);
            if ($.validate.form()) {
                $.operate.save(oasUrl, $('#form-add').serialize());
            }
        }
    </script>
@stop
