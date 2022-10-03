<!DOCTYPE html>
<html>
<head>
    <title>Transaction Email</title>
</head>

<body>

<h3>Receipt No:</h3>
<p>{{ $transaction['transaction_token'] }}</p>
<br/>
<h3>Name:</h3>
<p>{{ $user['name'] }}</p>
<br/>
<h3>Email:</h3>
<p>{{ $user['email'] }}</p>
<br/>
<h3>Contest:</h3>
<p>{{ $contest['title'] }}</p>
<br/>
<h3>Participant Name:</h3>
<p>{{ $participant['name'] }}</p>

</body>
</html>