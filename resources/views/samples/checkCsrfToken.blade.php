<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head></head>
<body>
    <form method="post" action="{{ route('checkCsrfToken') }}">
        <div>
            csrf : {{ csrf_token() }}
            @csrf
        </div>
        <div>
            csrf result : {{ $csrfResult }}
        </div>
        <input type="submit">
    </form>
</body>
</html>
