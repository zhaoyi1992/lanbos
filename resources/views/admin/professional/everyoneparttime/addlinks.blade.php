@extends('admin.layout.form')
@include('widget.asset.summernote')
@section('content')
    <form class="form-horizontal m" id="form-addlinks">
        <div class="layui-tab">
            <ul class="layui-tab-title">
                <li>未分配号码数量：{{$count}}</li>
            </ul>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">号码：</label>
            <div class="col-sm-8">
                <textarea name="links" rows="20" class="form-control" placeholder="请输入号码,每行一个号码"></textarea>
            </div>
        </div>

    </form>
@stop

@section('script')
    <script>


        function submitHandler() {
            if ($.validate.form()) {
                $.operate.save(olsUrl, $('#form-addlinks').serialize());
            }
        }
    </script>

@stop
