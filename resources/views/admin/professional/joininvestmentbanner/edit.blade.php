@extends('admin.layout.form')
@include('widget.asset.dragula')
@include('widget.asset.upload')
@section('content')
    <form class="form-horizontal m" id="form-edit">
        <input type="hidden" name="id" value="{{$info['id']}}">
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">名称：</label>
            <div class="col-sm-8">
                <input type="text" name="title" value="{{$info['title']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">位置：</label>
            <div class="col-sm-8">
                <select name="place" id="place" class="form-control">
                    <option value="0"  @if(0 == $info['place']) selected @endif>首页</option>
                    <option value="1"  @if(1 == $info['place']) selected @endif>分类1</option>
                    <option value="2"  @if(2 == $info['place']) selected @endif>分类2</option>
                    <option value="3"  @if(3 == $info['place']) selected @endif>分类3</option>
                </select>
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
            <label class="col-sm-3 control-label is-required">链接：</label>
            <div class="col-sm-8">
                <input type="text" name="link" value="{{$info['link']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">排序：</label>
            <div class="col-sm-8">
                <input type="text" name="listsort" value="{{$info['listsort']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">推荐：</label>
            <div class="col-sm-8">
                <label class="radio-box">
                    <input type="radio" name="hot" value="0" @if($info["hot"] == "0") checked @endif/> 否
                </label>
                <label class="radio-box">
                    <input type="radio" name="hot" value="1" @if($info["hot"] == "1") checked @endif/> 是
                </label>
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
