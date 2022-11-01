<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{config('l5-swagger.documentations.'.$documentation.'.api.title')}}</title>
    {{--    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}">--}}
    {{--    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}" sizes="32x32"/>--}}
    {{--    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}" sizes="16x16"/>--}}

    <link rel="stylesheet" type="text/css" href="https://www.hsbfa.xyz/index.php/docs/asset/swagger-ui.css?v=d6de32fafed0ea75ef760baa8ebe2bda">
    <link rel="icon" type="image/png" href="https://www.hsbfa.xyz/index.php/docs/asset/favicon-32x32.png?v=d6de32fafed0ea75ef760baa8ebe2bda" sizes="32x32"/>
    <link rel="icon" type="image/png" href="https://www.hsbfa.xyz/index.php/docs/asset/favicon-16x16.png?v=d6de32fafed0ea75ef760baa8ebe2bda" sizes="16x16"/>

    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }

        body {
            margin:0;
            background: #fafafa;
        }
    </style>
</head>

<body>
<div id="swagger-ui"></div>
{{--<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>--}}
{{--<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"></script>--}}
<script src="https://www.hsbfa.xyz/index.php/docs/asset/swagger-ui-bundle.js?v=d6de32fafed0ea75ef760baa8ebe2bda"></script>
<script src="https://www.hsbfa.xyz/index.php/docs/asset/swagger-ui-standalone-preset.js?v=d6de32fafed0ea75ef760baa8ebe2bda"></script>
<script>
    window.onload = function() {
        // Build a system
        const ui = SwaggerUIBundle({
            dom_id: '#swagger-ui',
            url: "https://www.hsbfa.xyz/docs/api-docs.json",
            operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
            configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
            validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
            oauth2RedirectUrl: "{{ route('l5-swagger.'.$documentation.'.oauth2_callback', [], $useAbsolutePath) }}",

            requestInterceptor: function(request) {
                request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                return request;
            },

            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],

            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],

            layout: "StandaloneLayout",
            docExpansion : "{!! config('l5-swagger.defaults.ui.display.doc_expansion', 'none') !!}",
            deepLinking: true,
            filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
            persistAuthorization: "{!! config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' !!}",

        })

        window.ui = ui
    }
</script>
</body>
</html>
