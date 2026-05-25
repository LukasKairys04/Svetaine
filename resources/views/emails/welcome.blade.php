<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sveiki atvykę</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: white; margin: 0;">FitShop</h1>
        </div>
        
        <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
            <h2 style="color: #667eea;">Sveiki, {{ $user->name }}!</h2>
            <p>Džiaugiamės, kad prisijungėte prie FitShop šeimos!</p>
            
            <h3 style="color: #333;">Ką galite daryti:</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 10px 0; border-bottom: 1px solid #ddd;">🏋️ Peržiūrėti sporto papildų asortimentą</li>
                <li style="padding: 10px 0; border-bottom: 1px solid #ddd;">🥗 Sukurti mitybos planą</li>
                <li style="padding: 10px 0; border-bottom: 1px solid #ddd;">💪 Sudaryti sporto programą</li>
                <li style="padding: 10px 0;">📊 Naudoti skaičiuokles (BMI, kalorijos)</li>
            </ul>
            
            <p style="color: #666;">Mūsų komanda visada pasiruošusi padėti. Jei turite klausimų, susisiekite su mumis!</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/') }}" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Pradėti apsipirkinėti</a>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
            <p>© {{ date('Y') }} FitShop. Visos teisės saugomos.</p>
        </div>
    </div>
</body>
</html>
