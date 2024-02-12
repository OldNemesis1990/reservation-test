<!DOCTYPE html>
<html>
<head>
    <title>Reservation Created</title>
</head>
<body>
    <p>Hi, {{ $data['user'][0]->name }}</p>
    <br/>
    <p>A reservation for {{ $data['accommodation'][0]->title }} has been booked for {{ $data['booking']->start_date }} at Skedonk!</p>
    <p>Please make payment of R{{ $data['booking']->total }} to complete reservation. Banking detail are as below:</p>
    <p>(Banking Details should go here essentially)</p>
</body>
</html>