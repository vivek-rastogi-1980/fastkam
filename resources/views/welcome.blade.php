<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting to payment...</title>
</head>
<body style="font-family:sans-serif;text-align:center;padding-top:60px">
    <h3>Please do not refresh this page...</h3>
    <p>Redirecting you to the payment gateway.</p>

    <form id="payForm" method="POST" action="{{ $processUrl }}">
        @foreach ($apnacscParams as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <noscript>
            <button type="submit">Continue to payment</button>
        </noscript>
    </form>

    <script>
        document.getElementById('payForm').submit();
    </script>
</body>
</html>