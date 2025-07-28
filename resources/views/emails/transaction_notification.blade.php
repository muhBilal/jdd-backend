<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Confirmation</title>
</head>
<body>
    <h1>Your Transaction is Confirmed!</h1>
    <p>Transaction ID: {{ $transaction->id }}</p>
    <p>Status: {{ $transaction->status }}</p>
    <p>Amount: {{ $transaction->amount }}</p>
</body>
</html>
