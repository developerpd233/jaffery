<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body>
    <?php
        // dd($user['name']);
    ?>
    <h1>Dear <?php echo $user['name'] ?>, </h1>
    <p>your verification code is <?php echo $user['forgot_otp'] ?></p>
    
</body>
</html>