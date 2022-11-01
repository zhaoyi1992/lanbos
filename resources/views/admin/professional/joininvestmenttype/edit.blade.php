@extends('admin.layout.form')
@include('widget.asset.dragula')
@include('widget.asset.upload')
@section('content')
    <form class="form-horizontal m" id="form-edit">
        <input type="hidden" name="id" value="{{$info['id']}}">
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">名称：</label>
            <div class="col-sm-8">
                <input type="text" name="name" value="{{$info['name']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">关键词：</label>
            <div class="col-sm-8">
                <input type="text" name="poskey" value="{{$info['poskey']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label is-required">图片：</label>
            <div class="col-sm-8">
                <input type="hidden" name="img" value="" id="img" required>
                <x-upload type="img" name="img_upload" :extend="['cat'=>'demo','link'=>1,'tips'=>'格式为jpg,jpeg,png,gif；大小不能超过10M','data'=>$info['img']]"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label is-required">排序：</label>
            <div class="col-sm-8">
                <input type="text" name="listsort" value="{{$info['listsort']}}" class="form-control" required autocomplete="off"/>
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
                $.operate.save(oesUrl, $('#form-edit').serialize());
            }
        }
    </script>
@stop
