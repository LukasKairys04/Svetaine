<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gavome jūsų žinutę</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: white; margin: 0;">FitShop</h1>
        </div>
        
        <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
            <h2 style="color: #667eea;">Gavome jūsų žinutę!</h2>
            <p>Ačiū, kad susisiekėte su mumis. Jūsų žinutė gauta ir greitai atsakysime.</p>
            
            <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #667eea;">
                <h3 style="color: #333; margin-top: 0;">Jūsų žinutė:</h3>
                <p><strong>Tema:</strong> {{ $message->subject }}</p>
                <p><strong>Data:</strong> {{ $message->created_at->format('Y-m-d H:i') }}</p>
                <p style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0;">{{ $message->message }}</p>
            </div>
            
            <p style="color: #666;">Jūsų žinutės ID: #{{ $message->id }}</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/support') }}" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Palaikymo puslapis</a>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
            <p>© {{ date('Y') }} FitShop. Visos teisės saugomos.</p>
        </div>
    </div>
</body>
</html>
