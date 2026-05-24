<?php

namespace Database\Seeders;

use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupportMessageSeeder extends Seeder
{
    public function run(): void
    {
        SupportMessage::query()->delete();

        $users = User::where('is_admin', false)->get();
        $subjects = ['Užsakymas', 'Pristatymas', 'Grąžinimas', 'Produktas', 'Kita'];
        $messages = [
            'Sveiki, noriu pasitikslinti kada bus išsiųstas mano užsakymas.',
            'Ar galima pakeisti pristatymo adresą po apmokėjimo?',
            'Gavau ne tą skonį, kokį užsakiau. Kaip atlikti keitimą?',
            'Ar šis produktas tinka vartoti prieš treniruotę?',
            'Kiek laiko galioja nuolaidos kodas WELCOME10?',
        ];

        foreach (range(1, 18) as $i) {
            $user = $users->isNotEmpty() ? $users->random() : null;
            $status = ['new', 'in_progress', 'resolved', 'closed'][array_rand(['new', 'in_progress', 'resolved', 'closed'])];
            $createdAt = now()->subDays(rand(1, 60))->subMinutes(rand(0, 600));

            SupportMessage::create([
                'user_id' => $user?->id,
                'name' => $user?->name ?? 'Lankytojas ' . $i,
                'email' => $user?->email ?? "lankytojas{$i}@example.com",
                'subject' => $subjects[array_rand($subjects)],
                'message' => $messages[array_rand($messages)],
                'status' => $status,
                'admin_reply' => in_array($status, ['resolved', 'closed'], true) ? 'Ačiū už žinutę. Problema išspręsta, jei reikia — parašykite dar kartą.' : null,
                'replied_at' => in_array($status, ['resolved', 'closed'], true) ? $createdAt->copy()->addHours(rand(2, 24)) : null,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }
    }
}
