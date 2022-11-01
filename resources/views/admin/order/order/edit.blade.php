@extends('admin.layout.form')

@section('content')
    <form class="form-horizontal m" id="form-edit">
        <input type="hidden" name="id" value="{{$info['id']}}">
        <input type="hidden" name="orderid" value="{{$info['orderid']}}">
        <input type="hidden" name="appid" value="{{$info['appid']}}">
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">用户id：</label>
            <div class="col-sm-8">
                <input type="text" name="userid" value="{{$info['userid']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">任务id：</label>
            <div class="col-sm-8">
                <input type="text" name="goodsid" value="{{$info['goodsid']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label is-required">价格：</label>
            <div class="col-sm-8">
                <input type="text" name="price" value="{{$info['price']}}" class="form-control" required autocomplete="off"/>
            </div>
        </div>


    </form>
@stop

@section('script')
    <script>
        function submitHandler() {
            if ($.validate.form()) {
                $.operate.save(oesUrl, $('#form-edit').serialize());
            }
        }
    </script>
@stop
