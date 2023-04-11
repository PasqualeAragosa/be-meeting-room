<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Reservation;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 10; $i++) {
            $reservation = new Reservation();
            $reservation->name = $faker->name();
            $reservation->surname = $faker->lastName();
            $reservation->date = $faker->date();
            $reservation->timeFrom = $faker->time();
            $reservation->timeTo = $faker->time();
            $reservation->notes = $faker->text(100);
            $reservation->save();
        }
    }
}
