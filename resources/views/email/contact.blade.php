<!DOCTYPE html>
<html>
<head>
    <title>Contact Email</title>
</head>

<body>
<h3>Name:</h3>
<p>{{$user['firstname'].' '.$user['lastname']}}</p>
<br/>
<h3>Email:</h3>
<p>{{$user['email']}}</p>
<br/>
<h3>Phone:</h3>
<p>{{$user['phone']}}</p>
<br/>
<h3>Country:</h3>
<p>{{$user['country']}}</p>
<br/>
<h3>Description:</h3>
<p>{{$user['description']}}</p>

</body>
</html>