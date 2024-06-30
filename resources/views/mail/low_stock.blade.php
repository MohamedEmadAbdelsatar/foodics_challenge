<!DOCTYPE html>
<html>
<head>
</head>
<body>

<h1>Dear {{ $merchant }},</h1>
<p>We have a low stock of {{ $ingredientName }}, current stock is {{ $currentStock }} {{$unit}} and the full stock should be {{ $fullStock }} {{ $unit }}</p>
<p>We need to buy {{ $fullStock - $currentStock }} {{$unit}} of {{ $ingredientName }}</p>
<p>Regards,</p>
</body>
</html>
