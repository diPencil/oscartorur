<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OTAUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $country = DB::table('countries')->insertGetId([
            'name' => 'Egypt',
            'code' => 'EG',
            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        $location = DB::table('locations')->where('name', 'Cairo')->first();
        if ($location) {
            DB::table('locations')->where('id', $location->id)->update(['country_id' => $country]);
            
            $area = DB::table('areas')->insertGetId([
                'location_id' => $location->id,
                'name' => 'Giza',
                'status' => 1,
                'created_at' => Carbon::now()
            ]);
            
            DB::table('hotels')->update(['country_id' => $country, 'area_id' => $area]);
        }
    }
}
