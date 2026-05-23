<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roster = [
            // [Model, HP, Torque, Value, Tier, Engine, TopSpeed(mph), Transmission, Weight(kg)]

            // --- Starter / Low Tier ---
            ['Fiat Punto',                       130, 148, 11000,  'D', '1.8L I4',        122, '5-speed Manual',  1150],
            ['Chevrolet Cobalt SS',              205, 200, 13000,  'D', '2.0L I4 Turbo',  148, '5-speed Manual',  1350],
            ['Volkswagen Golf GTI',              200, 207, 18500,  'D', '2.0L I4 Turbo',  146, '6-speed Manual',  1320],
            ['Lexus IS300',                      215, 218, 15000,  'D', '3.0L I6',        143, '5-speed Manual',  1580],
            ['Audi TT 3.2 quattro',              247, 236, 19000,  'D', '3.2L V6',        155, '6-speed DSG',     1430],
            ['Cadillac CTS',                     255, 252, 16000,  'D', '3.6L V6',        149, '6-speed Manual',  1680],

            // --- Mid Tier / Street Tuners ---
            ['Mazda RX-8',                       232, 159, 20000,  'C', '1.3L Rotary',    149, '6-speed Manual',  1310],
            ['Audi A3 3.2 quattro',              247, 236, 22000,  'C', '3.2L V6',        155, '6-speed DSG',     1430],
            ['Audi A4 3.2 FSI quattro',          252, 243, 24000,  'C', '3.2L V6',        155, '6-speed Manual',  1545],
            ['Mitsubishi Eclipse',               263, 260, 21000,  'C', '3.8L V6',        155, '6-speed Manual',  1470],
            ['Mazda RX-7',                       255, 217, 28000,  'C', '1.3L Twin Rotor', 159, '5-speed Manual', 1260],
            ['Renault Clio V6',                  252, 221, 26000,  'C', '3.0L V6',        150, '6-speed Manual',  1400],
            ['Toyota Supra',                     320, 315, 32000,  'C', '3.0L I6 Turbo',  161, '6-speed Manual',  1570],
            ['Mitsubishi Lancer Evolution VIII', 271, 273, 29000,  'C', '2.0L I4 Turbo',  157, '6-speed Manual',  1410],
            ['Subaru Impreza WRX STi',           300, 300, 31000,  'C', '2.5L H4 Turbo',  159, '6-speed Manual',  1495],

            // --- Muscle & Heavy Hitters ---
            ['Ford Mustang GT',                  300, 320, 35000,  'B', '4.6L V8',        155, '5-speed Manual',  1597],
            ['Pontiac GTO',                      400, 400, 34000,  'B', '6.0L V8',        175, '6-speed Manual',  1740],
            ['Vauxhall Monaro VXR',              400, 395, 36000,  'B', '6.0L V8',        170, '6-speed Manual',  1790],
            ['Mercedes-Benz CLK 500',            302, 339, 45000,  'B', '5.0L V8',        155, '7-speed Auto',    1785],
            ['Mercedes-Benz SL 500',             302, 339, 48000,  'B', '5.0L V8',        155, '7-speed Auto',    1870],

            // --- High Performance Exotics ---
            ['Lotus Elise',                      190, 138, 42000,  'B', '1.8L I4 S/C',    150, '6-speed Manual',   875],
            ['Porsche Cayman S',                 295, 251, 47000,  'B', '3.4L H6',        165, '6-speed Manual',  1350],
            ['Porsche 911 Carrera S',            355, 295, 55000,  'B', '3.8L H6',        182, '6-speed Manual',  1395],
            ['Chevrolet Corvette C6',            400, 400, 52000,  'B', '6.0L V8',        186, '6-speed Manual',  1495],
            ['Aston Martin DB9',                 450, 420, 75000,  'A', '6.0L V12',       186, '6-speed Auto',    1760],
            ['Porsche 911 Turbo S',              444, 457, 82000,  'A', '3.6L H6 Turbo',  193, '6-speed Manual',  1585],
            ['Porsche 911 GT2',                  476, 472, 88000,  'A', '3.6L H6 Turbo',  204, '6-speed Manual',  1440],
            ['Mercedes-Benz SL 65 AMG',          604, 738, 95000,  'A', '6.0L V12 Biturbo', 199, '5-speed Auto', 1990],
            ['Dodge Viper SRT10',                500, 525, 70000,  'A', '8.3L V10',       190, '6-speed Manual',  1550],

            // --- Hyper Tier / End Game ---
            ['Lamborghini Gallardo',             493, 376, 92000,  'S', '5.0L V10',       192, '6-speed Manual',  1430],
            ['Lamborghini Murciélago',           572, 479, 118000, 'S', '6.5L V12',       205, '6-speed Manual',  1665],
            ['Ford GT',                          550, 500, 130000, 'S', '5.4L V8 S/C',    205, '6-speed Manual',  1585],
            ['Porsche Carrera GT',               605, 435, 145000, 'S', '5.7L V10',       205, '6-speed Manual',  1380],
            ['Mercedes-Benz SLR McLaren',        617, 575, 165000, 'S', '5.4L V8 S/C',    208, '5-speed Auto',    1768],

            // --- Track Specials & Iconic Legend ---
            ['Chevrolet Corvette C6.R',          590, 470, 210000, 'S', '7.0L V8',        199, '6-speed Sequential', 1250],
            ['BMW M3 GTR',                       443, 354, 250000, 'S', '4.0L V8',        200, '6-speed Sequential', 1430],
        ];

        foreach ($roster as $car) {
            Car::updateOrCreate(
                ['make_model' => $car[0]],
                [
                    'base_hp'           => $car[1],
                    'base_torque'       => $car[2],
                    'base_market_value' => $car[3],
                    'tier'              => $car[4],
                    'engine_type'       => $car[5],
                    'top_speed'         => $car[6],
                    'transmission'      => $car[7],
                    'weight_kg'         => $car[8],
                ]
            );
        }
    }
}