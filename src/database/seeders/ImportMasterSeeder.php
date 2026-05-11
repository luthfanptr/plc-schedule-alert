<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportMasterSeeder extends Seeder
{
    /**
     * ! Fungsinya buat auto import data PLANT, LINE, LINE_NAME. jadi gaperlu proses eksekusi query lagi
     */
    public function run(): void
    {
        // nyari query atribut PLANT di tabel MS_LINE pada db master 
        $masterPlant = DB::connection('sqlsrv_master')
            ->table('MS_LINE')
            ->distinct()
            ->pluck('PLANT');

        foreach ($masterPlant as $plantName){
            // simpen ke table local plants
            $plant =\App\Models\Plant::updateOrCreate( // cari kolom bernama Name di MS_LINE
                ['Name' => trim($plantName)],
                ['Name' => trim($plantName)]
            );
        }

        // ambil & simpan data line (join MS_MC)
        $masterLines = DB::connection('sqlsrv_master')
            ->table('MS_LINE as L')
            ->leftJoin('MS_MC as M', 'L.LINE_ID', '=', 'M.LINE_ID')
            ->select('L.LINE_ID', 'L.LINE_NM', 'L.PLANT', 'M.LINE')
            ->distinct()
            ->get();

        foreach ($masterLines as $ml){
            // cari ID local dari plants yang sesuai
            $localPlant = \App\Models\Plant::where('name', trim($ml->PLANT))->first();

            if($localPlant) {
                // Simpan ke tabel local Lines
                \App\Models\Line::updateOrCreate(
                    ['master_line_id' => $ml->LINE_ID], // jembatan ref unik dari db master ke local. krn klo pke id, si local blm punya id bnyk e.g id line 100 di master tp di local blm sampai 100 idnya
                    [
                        'Line' => trim($ml->LINE), // Dari MS_MC.LINE
                        'Name' => trim($ml->LINE_NM), // Dari MS_LINE.LINE_NM
                        'plant_id' => $localPlant->id, // Relasi eloquent ke ID Plant lokal (HasMany)
                    ]
                );
            }
        }
    }
}
