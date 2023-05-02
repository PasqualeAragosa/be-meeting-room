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
        for ($i = 0; $i < 12; $i++) {
            $reservation = new Reservation();
            $reservation->name = $faker->name();
            $reservation->team_id = 2;
            $reservation->surname = $faker->lastName();
            $reservation->date = $faker->date('d-m-Y');
            $reservation->timeFrom = $faker->time('H:i');
            $reservation->timeTo = $faker->time('H:i');
            $reservation->note = $faker->text(100);
            $reservation->save();
        }
    }
}
