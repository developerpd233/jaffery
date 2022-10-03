<!DOCTYPE html>
<html>
<head>
    <title>Winning Email</title>
</head>

<body>
<h2>Congratulations {{$user['name']}}</h2>
<br/>
You are win the 1st prize of <b>{{ $contest['title'] }}</b> contest. Your  prize money is <b>${{ $amount }}</b>
</body>

</html>