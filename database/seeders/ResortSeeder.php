<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resorts = [
            ['name' => 'Amed Beach Villa','category_id' => 1,'description' => '<p>Amed Beach Villa&nbsp;</p>','price' => 850000, 'location' => 'Purwakerti, Abang, Karangasem Regency, Bali 80852'],
            ['name' => 'Purwakerti, Abang, Karangasem Regency, Bali 80852','category_id' => 1,'description' => '<p>Akasa Villa Bali - Villa in Tulamben - Diving in Tulamben</p>','price' => 560000, 'location' => 'Jl. Kubu No.88, Tulamben, Kubu, Kabupaten Karangasem, Bali 80853'],
            ['name' => 'Adiwana Monkey Forest','category_id' => 1,'description' => '<p>Adiwana Monkey Forest</p>','price' => 750000, 'location' => 'Ubud, Bali Indonesia'],
            ['name' => 'Seminyak Villa','category_id' => 1,'description' => '<p>Seminyak Villa Beach</p>','price' => 550000, 'location' => 'Seminyak, Bali Indonesia'],
            ['name' => 'Diving','category_id' => 2,'description' => '<p>Diving di Ubud, Bali Indonesia</p>','price' => 150000, 'location' => 'Ubud, Bali Indonesia'],
            ['name' => 'Surfing','category_id' => 2,'description' => '<p>Surfing di Pantai Kuta, Bali Indonesia</p>','price' => 150000, 'location' => 'Kuta, Bali Indonesia'],
        ];
        DB::table('resorts')->insert($resorts);
    }
}
