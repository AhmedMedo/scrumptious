<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #9C27B0;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #9C27B0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📢 {{ $title }}</h1>
    </div>
    <div class="content">
        <h2>Hello {{ $userName }}!</h2>
        <p>{{ $body }}</p>
        
        <a href="{{ config('app.url') }}" class="button">Visit Scrumptious</a>
        
        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            This is an automated notification from Scrumptious. Content will be updated later.
        </p>
    </div>
</body>
</html>
