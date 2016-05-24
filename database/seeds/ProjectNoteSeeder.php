<?php

use Illuminate\Database\Seeder;

class ProjectNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        \CodeProject\Entities\Client::truncate();
        factory(\CodeProject\Entities\ProjectNote::class, 10)->create();
    }
}
