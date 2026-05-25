<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Užsakymo patvirtinimas</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: white; margin: 0;">FitShop</h1>
        </div>
        
        <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
            <h2 style="color: #667eea;">Ačiū už užsakymą!</h2>
            <p>Jūsų užsakymas <strong>#{{ $order->id }}</strong> gautas ir bus apdorotas.</p>
            
            <h3 style="color: #333;">Užsakymo informacija:</h3>
            <ul style="list-style: none; padding: 0;">
                <li><strong>Data:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</li>
                <li><strong>Būsena:</strong> {{ $order->status }}</li>
                <li><strong>Suma:</strong> €{{ number_format($order->total, 2) }}</li>
            </ul>
            
            <h3 style="color: #333;">Prekės:</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #667eea; color: white;">
                        <th style="padding: 10px; text-align: left;">Prekė</th>
                        <th style="padding: 10px; text-align: center;">Kiekis</th>
                        <th style="padding: 10px; text-align: right;">Kaina</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">{{ $item->product->name }}</td>
                        <td style="padding: 10px; text-align: center;">{{ $item->quantity }}</td>
                        <td style="padding: 10px; text-align: right;">€{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <p style="margin-top: 20px; color: #666;">Jei turite klausimų, susisiekite su mumis per palaikymo puslapį.</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/') }}" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Eiti į parduotuvę</a>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
            <p>© {{ date('Y') }} FitShop. Visos teisės saugomos.</p>
        </div>
    </div>
</body>
</html>
