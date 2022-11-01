@extends('admin.layout.form')
@include('widget.asset.dragula')
@include('widget.asset.upload')
@section('content')
    <form class="form-horizontal m" id="form-edit">
        <input type="hidden" name="id" value="{{$info['id']}}">
        <input type="hidden" name="typeid" id="treeId" value="{{$info['typeid']}}">
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">上级类：</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <input type="text" id="treeName" value="{{$info['typename']}}" class="form-control" placeholder="请选择上级组织" readonly autocomplete="off"/>
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">元素名称：</label>
            <div class="col-sm-8">
                <input type="text" name="name" value="{{$info['name']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">预览图：</label>
            <div class="col-sm-8">
                <input type="hidden" name="img" value="{{$info['img']}}" id="img" required>
                <x-upload type="img" name="img_upload" :extend="['cat'=>'demo','link'=>1,'tips'=>'格式为jpg,jpeg,png,gif；大小不能超过10M','data'=>$info['img']]"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">html：</label>
            <div class="col-sm-8">
                <textarea name="html" class="form-control" placeholder="html">{{$info['html']}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">jsonvalue：</label>
            <div class="col-sm-8">
                <textarea name="jsonvalue" class="form-control" placeholder="jsonvalue">{{$info['jsonvalue']}}</textarea>
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
            var img = '';
            if($("input[name='img_upload[]']").length>0){
                $("input[name='img_upload[]']").each(function () {
                    var imgval = $(this).val();
                    if(imgval){
                        img = imgval;
                    }
                })
            }
            $("#img").val(img);
            //选择组织树
            $("#treeName").click(function () {
                var treeId = $("#treeId").val();
                var url=urlcreate("{{route('professional.templatetype.tree')}}","id=" + treeId);
                var options = {
                    title: '类选择',
                    width: "380",
                    url: url,
                    callBack: doSubmit
                };
                $.modal.openOptions(options);
            });
        });

        function submitHandler() {
            var img = '';
            if($("input[name='img_upload[]']").length>0){
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
