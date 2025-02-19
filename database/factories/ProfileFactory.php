<?php

// database/factories/ProfileFactory.php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        $images = [
            'https://loremfaces.net/96/id/1.jpg',
            'https://i.pravatar.cc/200',
        ];

        return [
            'nom' => $this->faker->lastName, // Last name
            'prenom' => $this->faker->firstName, // First name
            'image' => $images[array_rand($images)], // Randomly select an image
            'statut' => 'actif', // Set status or generate randomly if needed
            // Add other fields as necessary
        ];
    }
}
