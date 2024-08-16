<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('regions')->truncate();
        
        \DB::table('regions')->insert([
            0 => [
                'id' => 1,
                'name' => 'Sant Julia de Loria',
                'code' => '06',
                'country_id' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Andorra la Vella',
                'code' => '07',
                'country_id' => 1,
            ],
            2 => [
                'id' => 3,
                'name' => 'La Massana',
                'code' => '04',
                'country_id' => 1,
            ],
            3 => 
            [
                'id' => 4,
                'name' => 'Ordino',
                'code' => '05',
                'country_id' => 1,
            ],
            4 => 
            [
                'id' => 5,
                'name' => 'Canillo',
                'code' => '02',
                'country_id' => 1,
            ],
            5 => 
            [
                'id' => 6,
                'name' => 'Encamp',
                'code' => '03',
                'country_id' => 1,
            ],
            6 => 
            [
                'id' => 7,
                'name' => 'Escaldes-Engordany',
                'code' => '08',
                'country_id' => 1,
            ],
            7 => 
            [
                'id' => 8,
                'name' => 'Fujairah',
                'code' => '04',
                'country_id' => 2,
            ],
            8 => 
            [
                'id' => 9,
                'name' => 'Abu Dhabi',
                'code' => '01',
                'country_id' => 2,
            ],
            9 => 
            [
                'id' => 10,
                'name' => 'Dubai',
                'code' => '03',
                'country_id' => 2,
            ],
            10 => 
            [
                'id' => 11,
                'name' => 'Ras Al Khaimah',
                'code' => '05',
                'country_id' => 2,
            ],
            11 => 
            [
                'id' => 12,
                'name' => 'Umm Al Quwain',
                'code' => '07',
                'country_id' => 2,
            ],
            12 => 
            [
                'id' => 13,
                'name' => 'Sharjah',
                'code' => '06',
                'country_id' => 2,
            ],
            13 => 
            [
                'id' => 14,
                'name' => 'Ajman',
                'code' => '02',
                'country_id' => 2,
            ],
            14 => 
            [
                'id' => 15,
                'name' => 'Paktika',
                'code' => '29',
                'country_id' => 3,
            ],
            15 => 
            [
                'id' => 16,
                'name' => 'Farah',
                'code' => '06',
                'country_id' => 3,
            ],
            16 => 
            [
                'id' => 17,
                'name' => 'Helmand',
                'code' => '10',
                'country_id' => 3,
            ],
            17 => 
            [
                'id' => 18,
                'name' => 'Kondoz',
                'code' => '24',
                'country_id' => 3,
            ],
            18 => 
            [
                'id' => 19,
                'name' => 'Bamian',
                'code' => '05',
                'country_id' => 3,
            ],
            19 => 
            [
                'id' => 20,
                'name' => 'Ghowr',
                'code' => '09',
                'country_id' => 3,
            ],
            20 => 
            [
                'id' => 21,
                'name' => 'Laghman',
                'code' => '35',
                'country_id' => 3,
            ],
            21 => 
            [
                'id' => 22,
                'name' => '',
                'code' => '22',
                'country_id' => 3,
            ],
            22 => 
            [
                'id' => 23,
                'name' => 'Ghazni',
                'code' => '08',
                'country_id' => 3,
            ],
            23 => 
            [
                'id' => 24,
                'name' => 'Vardak',
                'code' => '27',
                'country_id' => 3,
            ],
            24 => 
            [
                'id' => 25,
                'name' => 'Oruzgan',
                'code' => '39',
                'country_id' => 3,
            ],
            25 => 
            [
                'id' => 26,
                'name' => 'Zabol',
                'code' => '28',
                'country_id' => 3,
            ],
            26 => 
            [
                'id' => 27,
                'name' => 'Badghis',
                'code' => '02',
                'country_id' => 3,
            ],
            27 => 
            [
                'id' => 28,
                'name' => 'Badakhshan',
                'code' => '01',
                'country_id' => 3,
            ],
            28 => 
            [
                'id' => 29,
                'name' => 'Faryab',
                'code' => '07',
                'country_id' => 3,
            ],
            29 => 
            [
                'id' => 30,
                'name' => 'Takhar',
                'code' => '26',
                'country_id' => 3,
            ],
            30 => 
            [
                'id' => 31,
                'name' => 'Lowgar',
                'code' => '17',
                'country_id' => 3,
            ],
            31 => 
            [
                'id' => 32,
                'name' => 'Herat',
                'code' => '11',
                'country_id' => 3,
            ],
            32 => 
            [
                'id' => 33,
                'name' => 'Daykondi',
                'code' => '41',
                'country_id' => 3,
            ],
            33 => 
            [
                'id' => 34,
                'name' => 'Sar-e Pol',
                'code' => '33',
                'country_id' => 3,
            ],
            34 => 
            [
                'id' => 35,
                'name' => 'Balkh',
                'code' => '30',
                'country_id' => 3,
            ],
            35 => 
            [
                'id' => 36,
                'name' => 'Kabol',
                'code' => '13',
                'country_id' => 3,
            ],
            36 => 
            [
                'id' => 37,
                'name' => 'Nimruz',
                'code' => '19',
                'country_id' => 3,
            ],
            37 => 
            [
                'id' => 38,
                'name' => 'Kandahar',
                'code' => '23',
                'country_id' => 3,
            ],
            38 => 
            [
                'id' => 39,
                'name' => 'Khowst',
                'code' => '37',
                'country_id' => 3,
            ],
            39 => 
            [
                'id' => 40,
                'name' => '',
                'code' => '20',
                'country_id' => 3,
            ],
            40 => 
            [
                'id' => 41,
                'name' => 'Kapisa',
                'code' => '14',
                'country_id' => 3,
            ],
            41 => 
            [
                'id' => 42,
                'name' => 'Nangarhar',
                'code' => '18',
                'country_id' => 3,
            ],
            42 => 
            [
                'id' => 43,
                'name' => 'Samangan',
                'code' => '32',
                'country_id' => 3,
            ],
            43 => 
            [
                'id' => 44,
                'name' => 'Paktia',
                'code' => '36',
                'country_id' => 3,
            ],
            44 => 
            [
                'id' => 45,
                'name' => 'Baghlan',
                'code' => '03',
                'country_id' => 3,
            ],
            45 => 
            [
                'id' => 46,
                'name' => 'Jowzjan',
                'code' => '31',
                'country_id' => 3,
            ],
            46 => 
            [
                'id' => 47,
                'name' => 'Konar',
                'code' => '34',
                'country_id' => 3,
            ],
            47 => 
            [
                'id' => 48,
                'name' => 'Nurestan',
                'code' => '38',
                'country_id' => 3,
            ],
            48 => 
            [
                'id' => 49,
                'name' => '',
                'code' => '16',
                'country_id' => 3,
            ],
            49 => 
            [
                'id' => 50,
                'name' => '',
                'code' => '21',
                'country_id' => 3,
            ],
            50 => 
            [
                'id' => 51,
                'name' => '',
                'code' => '15',
                'country_id' => 3,
            ],
            51 => 
            [
                'id' => 52,
                'name' => 'Panjshir',
                'code' => '42',
                'country_id' => 3,
            ],
            52 => 
            [
                'id' => 53,
                'name' => 'Saint John',
                'code' => '04',
                'country_id' => 4,
            ],
            53 => 
            [
                'id' => 54,
                'name' => 'Saint Paul',
                'code' => '06',
                'country_id' => 4,
            ],
            54 => 
            [
                'id' => 55,
                'name' => 'Saint George',
                'code' => '03',
                'country_id' => 4,
            ],
            55 => 
            [
                'id' => 56,
                'name' => 'Saint Peter',
                'code' => '07',
                'country_id' => 4,
            ],
            56 => 
            [
                'id' => 57,
                'name' => 'Saint Mary',
                'code' => '05',
                'country_id' => 4,
            ],
            57 => 
            [
                'id' => 58,
                'name' => 'Barbuda',
                'code' => '01',
                'country_id' => 4,
            ],
            58 => 
            [
                'id' => 59,
                'name' => 'Saint Philip',
                'code' => '08',
                'country_id' => 4,
            ],
            59 => 
            [
                'id' => 60,
                'name' => '',
                'code' => '00',
                'country_id' => 5,
            ],
            60 => 
            [
                'id' => 61,
                'name' => 'Vlore',
                'code' => '51',
                'country_id' => 6,
            ],
            61 => 
            [
                'id' => 62,
                'name' => 'Korce',
                'code' => '46',
                'country_id' => 6,
            ],
            62 => 
            [
                'id' => 63,
                'name' => 'Shkoder',
                'code' => '49',
                'country_id' => 6,
            ],
            63 => 
            [
                'id' => 64,
                'name' => 'Durres',
                'code' => '42',
                'country_id' => 6,
            ],
            64 => 
            [
                'id' => 65,
                'name' => 'Elbasan',
                'code' => '43',
                'country_id' => 6,
            ],
            65 => 
            [
                'id' => 66,
                'name' => 'Kukes',
                'code' => '47',
                'country_id' => 6,
            ],
            66 => 
            [
                'id' => 67,
                'name' => 'Fier',
                'code' => '44',
                'country_id' => 6,
            ],
            67 => 
            [
                'id' => 68,
                'name' => 'Berat',
                'code' => '40',
                'country_id' => 6,
            ],
            68 => 
            [
                'id' => 69,
                'name' => 'Gjirokaster',
                'code' => '45',
                'country_id' => 6,
            ],
            69 => 
            [
                'id' => 70,
                'name' => 'Tirane',
                'code' => '50',
                'country_id' => 6,
            ],
            70 => 
            [
                'id' => 71,
                'name' => 'Lezhe',
                'code' => '48',
                'country_id' => 6,
            ],
            71 => 
            [
                'id' => 72,
                'name' => 'Diber',
                'code' => '41',
                'country_id' => 6,
            ],
            72 => 
            [
                'id' => 73,
                'name' => 'Aragatsotn',
                'code' => '01',
                'country_id' => 7,
            ],
            73 => 
            [
                'id' => 74,
                'name' => 'Ararat',
                'code' => '02',
                'country_id' => 7,
            ],
            74 => 
            [
                'id' => 75,
                'name' => 'Kotayk\'',
                'code' => '05',
                'country_id' => 7,
            ],
            75 => 
            [
                'id' => 76,
                'name' => 'Tavush',
                'code' => '09',
                'country_id' => 7,
            ],
            76 => 
            [
                'id' => 77,
                'name' => 'Syunik\'',
                'code' => '08',
                'country_id' => 7,
            ],
            77 => 
            [
                'id' => 78,
                'name' => 'Geghark\'unik\'',
                'code' => '04',
                'country_id' => 7,
            ],
            78 => 
            [
                'id' => 79,
                'name' => 'Vayots\' Dzor',
                'code' => '10',
                'country_id' => 7,
            ],
            79 => 
            [
                'id' => 80,
                'name' => 'Lorri',
                'code' => '06',
                'country_id' => 7,
            ],
            80 => 
            [
                'id' => 81,
                'name' => 'Armavir',
                'code' => '03',
                'country_id' => 7,
            ],
            81 => 
            [
                'id' => 82,
                'name' => 'Yerevan',
                'code' => '11',
                'country_id' => 7,
            ],
            82 => 
            [
                'id' => 83,
                'name' => 'Shirak',
                'code' => '07',
                'country_id' => 7,
            ],
            83 => 
            [
                'id' => 84,
                'name' => '',
                'code' => '00',
                'country_id' => 8,
            ],
            84 => 
            [
                'id' => 85,
                'name' => 'Benguela',
                'code' => '01',
                'country_id' => 9,
            ],
            85 => 
            [
                'id' => 86,
                'name' => 'Uige',
                'code' => '15',
                'country_id' => 9,
            ],
            86 => 
            [
                'id' => 87,
                'name' => 'Bengo',
                'code' => '19',
                'country_id' => 9,
            ],
            87 => 
            [
                'id' => 88,
                'name' => 'Cuanza Norte',
                'code' => '05',
                'country_id' => 9,
            ],
            88 => 
            [
                'id' => 89,
                'name' => 'Malanje',
                'code' => '12',
                'country_id' => 9,
            ],
            89 => 
            [
                'id' => 90,
                'name' => 'Cuanza Sul',
                'code' => '06',
                'country_id' => 9,
            ],
            90 => 
            [
                'id' => 91,
                'name' => 'Huambo',
                'code' => '08',
                'country_id' => 9,
            ],
            91 => 
            [
                'id' => 92,
                'name' => 'Moxico',
                'code' => '14',
                'country_id' => 9,
            ],
            92 => 
            [
                'id' => 93,
                'name' => 'Cuando Cubango',
                'code' => '04',
                'country_id' => 9,
            ],
            93 => 
            [
                'id' => 94,
                'name' => 'Bie',
                'code' => '02',
                'country_id' => 9,
            ],
            94 => 
            [
                'id' => 95,
                'name' => 'Huila',
                'code' => '09',
                'country_id' => 9,
            ],
            95 => 
            [
                'id' => 96,
                'name' => 'Lunda Sul',
                'code' => '18',
                'country_id' => 9,
            ],
            96 => 
            [
                'id' => 97,
                'name' => '',
                'code' => '10',
                'country_id' => 9,
            ],
            97 => 
            [
                'id' => 98,
                'name' => 'Zaire',
                'code' => '16',
                'country_id' => 9,
            ],
            98 => 
            [
                'id' => 99,
                'name' => 'Cunene',
                'code' => '07',
                'country_id' => 9,
            ],
            99 => 
            [
                'id' => 100,
                'name' => 'Lunda Norte',
                'code' => '17',
                'country_id' => 9,
            ],
            100 => 
            [
                'id' => 101,
                'name' => 'Namibe',
                'code' => '13',
                'country_id' => 9,
            ],
            101 => 
            [
                'id' => 102,
                'name' => 'Cabinda',
                'code' => '03',
                'country_id' => 9,
            ],
            102 => 
            [
                'id' => 103,
                'name' => 'Buenos Aires',
                'code' => '01',
                'country_id' => 10,
            ],
            103 => 
            [
                'id' => 104,
                'name' => 'Cordoba',
                'code' => '05',
                'country_id' => 10,
            ],
            104 => 
            [
                'id' => 105,
                'name' => 'Entre Rios',
                'code' => '08',
                'country_id' => 10,
            ],
            105 => 
            [
                'id' => 106,
                'name' => 'Salta',
                'code' => '17',
                'country_id' => 10,
            ],
            106 => 
            [
                'id' => 107,
                'name' => 'Jujuy',
                'code' => '10',
                'country_id' => 10,
            ],
            107 => 
            [
                'id' => 108,
                'name' => 'La Pampa',
                'code' => '11',
                'country_id' => 10,
            ],
            108 => 
            [
                'id' => 109,
                'name' => 'Mendoza',
                'code' => '13',
                'country_id' => 10,
            ],
            109 => 
            [
                'id' => 110,
                'name' => 'Misiones',
                'code' => '14',
                'country_id' => 10,
            ],
            110 => 
            [
                'id' => 111,
                'name' => 'Santa Cruz',
                'code' => '20',
                'country_id' => 10,
            ],
            111 => 
            [
                'id' => 112,
                'name' => 'Santa Fe',
                'code' => '21',
                'country_id' => 10,
            ],
            112 => 
            [
                'id' => 113,
                'name' => 'Tucuman',
                'code' => '24',
                'country_id' => 10,
            ],
            113 => 
            [
                'id' => 114,
                'name' => 'Corrientes',
                'code' => '06',
                'country_id' => 10,
            ],
            114 => 
            [
                'id' => 115,
                'name' => 'San Juan',
                'code' => '18',
                'country_id' => 10,
            ],
            115 => 
            [
                'id' => 116,
                'name' => 'Santiago del Estero',
                'code' => '22',
                'country_id' => 10,
            ],
            116 => 
            [
                'id' => 117,
                'name' => 'Catamarca',
                'code' => '02',
                'country_id' => 10,
            ],
            117 => 
            [
                'id' => 118,
                'name' => 'Neuquen',
                'code' => '15',
                'country_id' => 10,
            ],
            118 => 
            [
                'id' => 119,
                'name' => 'Distrito Federal',
                'code' => '07',
                'country_id' => 10,
            ],
            119 => 
            [
                'id' => 120,
                'name' => 'La Rioja',
                'code' => '12',
                'country_id' => 10,
            ],
            120 => 
            [
                'id' => 121,
                'name' => 'Rio Negro',
                'code' => '16',
                'country_id' => 10,
            ],
            121 => 
            [
                'id' => 122,
                'name' => 'Chubut',
                'code' => '04',
                'country_id' => 10,
            ],
            122 => 
            [
                'id' => 123,
                'name' => 'San Luis',
                'code' => '19',
                'country_id' => 10,
            ],
            123 => 
            [
                'id' => 124,
                'name' => 'Tierra del Fuego',
                'code' => '23',
                'country_id' => 10,
            ],
            124 => 
            [
                'id' => 125,
                'name' => 'Formosa',
                'code' => '09',
                'country_id' => 10,
            ],
            125 => 
            [
                'id' => 126,
                'name' => 'Chaco',
                'code' => '03',
                'country_id' => 10,
            ],
            126 => 
            [
                'id' => 127,
                'name' => 'Niederosterreich',
                'code' => '03',
                'country_id' => 11,
            ],
            127 => 
            [
                'id' => 128,
                'name' => 'Salzburg',
                'code' => '05',
                'country_id' => 11,
            ],
            128 => 
            [
                'id' => 129,
                'name' => 'Oberosterreich',
                'code' => '04',
                'country_id' => 11,
            ],
            129 => 
            [
                'id' => 130,
                'name' => 'Tirol',
                'code' => '07',
                'country_id' => 11,
            ],
            130 => 
            [
                'id' => 131,
                'name' => 'Karnten',
                'code' => '02',
                'country_id' => 11,
            ],
            131 => 
            [
                'id' => 132,
                'name' => 'Steiermark',
                'code' => '06',
                'country_id' => 11,
            ],
            132 => 
            [
                'id' => 133,
                'name' => 'Vorarlberg',
                'code' => '08',
                'country_id' => 11,
            ],
            133 => 
            [
                'id' => 134,
                'name' => 'Wien',
                'code' => '09',
                'country_id' => 11,
            ],
            134 => 
            [
                'id' => 135,
                'name' => 'Burgenland',
                'code' => '01',
                'country_id' => 11,
            ],
            135 => 
            [
                'id' => 136,
                'name' => 'New South Wales',
                'code' => '02',
                'country_id' => 12,
            ],
            136 => 
            [
                'id' => 137,
                'name' => 'Tasmania',
                'code' => '06',
                'country_id' => 12,
            ],
            137 => 
            [
                'id' => 138,
                'name' => 'Western Australia',
                'code' => '08',
                'country_id' => 12,
            ],
            138 => 
            [
                'id' => 139,
                'name' => 'Queensland',
                'code' => '04',
                'country_id' => 12,
            ],
            139 => 
            [
                'id' => 140,
                'name' => 'Victoria',
                'code' => '07',
                'country_id' => 12,
            ],
            140 => 
            [
                'id' => 141,
                'name' => 'South Australia',
                'code' => '05',
                'country_id' => 12,
            ],
            141 => 
            [
                'id' => 142,
                'name' => 'Northern Territory',
                'code' => '03',
                'country_id' => 12,
            ],
            142 => 
            [
                'id' => 143,
                'name' => 'Australian Capital Territory',
                'code' => '01',
                'country_id' => 12,
            ],
            143 => 
            [
                'id' => 144,
                'name' => '',
                'code' => '00',
                'country_id' => 12,
            ],
            144 => 
            [
                'id' => 145,
                'name' => '',
                'code' => '00',
                'country_id' => 13,
            ],
            145 => 
            [
                'id' => 146,
                'name' => 'Neftcala',
                'code' => '36',
                'country_id' => 14,
            ],
            146 => 
            [
                'id' => 147,
                'name' => 'Xanlar',
                'code' => '62',
                'country_id' => 14,
            ],
            147 => 
            [
                'id' => 148,
                'name' => 'Yevlax',
                'code' => '68',
                'country_id' => 14,
            ],
            148 => 
            [
                'id' => 149,
                'name' => 'Agdas',
                'code' => '04',
                'country_id' => 14,
            ],
            149 => 
            [
                'id' => 150,
                'name' => 'Sabirabad',
                'code' => '46',
                'country_id' => 14,
            ],
            150 => 
            [
                'id' => 151,
                'name' => 'Yardimli',
                'code' => '66',
                'country_id' => 14,
            ],
            151 => 
            [
                'id' => 152,
                'name' => 'Calilabad',
                'code' => '15',
                'country_id' => 14,
            ],
            152 => 
            [
                'id' => 153,
                'name' => 'Saatli',
                'code' => '45',
                'country_id' => 14,
            ],
            153 => 
            [
                'id' => 154,
                'name' => 'Saki',
                'code' => '47',
                'country_id' => 14,
            ],
            154 => 
            [
                'id' => 155,
                'name' => 'Kurdamir',
                'code' => '27',
                'country_id' => 14,
            ],
            155 => 
            [
                'id' => 156,
                'name' => 'Qazax',
                'code' => '40',
                'country_id' => 14,
            ],
            156 => 
            [
                'id' => 157,
                'name' => 'Tovuz',
                'code' => '58',
                'country_id' => 14,
            ],
            157 => 
            [
                'id' => 158,
                'name' => 'Samkir',
                'code' => '51',
                'country_id' => 14,
            ],
            158 => 
            [
                'id' => 159,
                'name' => 'Agdam',
                'code' => '03',
                'country_id' => 14,
            ],
            159 => 
            [
                'id' => 160,
                'name' => 'Qubadli',
                'code' => '43',
                'country_id' => 14,
            ],
            160 => 
            [
                'id' => 161,
                'name' => 'Oguz',
                'code' => '37',
                'country_id' => 14,
            ],
            161 => 
            [
                'id' => 162,
                'name' => 'Lacin',
                'code' => '28',
                'country_id' => 14,
            ],
            162 => 
            [
                'id' => 163,
                'name' => 'Kalbacar',
                'code' => '26',
                'country_id' => 14,
            ],
            163 => 
            [
                'id' => 164,
                'name' => 'Haciqabul',
                'code' => '23',
                'country_id' => 14,
            ],
            164 => 
            [
                'id' => 165,
                'name' => 'Bilasuvar',
                'code' => '13',
                'country_id' => 14,
            ],
            165 => 
            [
                'id' => 166,
                'name' => 'Balakan',
                'code' => '10',
                'country_id' => 14,
            ],
            166 => 
            [
                'id' => 167,
                'name' => 'Naxcivan',
                'code' => '35',
                'country_id' => 14,
            ],
            167 => 
            [
                'id' => 168,
                'name' => 'Qabala',
                'code' => '38',
                'country_id' => 14,
            ],
            168 => 
            [
                'id' => 169,
                'name' => 'Agcabadi',
                'code' => '02',
                'country_id' => 14,
            ],
            169 => 
            [
                'id' => 170,
                'name' => 'Samaxi',
                'code' => '50',
                'country_id' => 14,
            ],
            170 => 
            [
                'id' => 171,
                'name' => 'Davaci',
                'code' => '17',
                'country_id' => 14,
            ],
            171 => 
            [
                'id' => 172,
                'name' => 'Quba',
                'code' => '42',
                'country_id' => 14,
            ],
            172 => 
            [
                'id' => 173,
                'name' => 'Qusar',
                'code' => '44',
                'country_id' => 14,
            ],
            173 => 
            [
                'id' => 174,
                'name' => 'Imisli',
                'code' => '24',
                'country_id' => 14,
            ],
            174 => 
            [
                'id' => 175,
                'name' => 'Abseron',
                'code' => '01',
                'country_id' => 14,
            ],
            175 => 
            [
                'id' => 176,
                'name' => 'Xacmaz',
                'code' => '60',
                'country_id' => 14,
            ],
            176 => 
            [
                'id' => 177,
                'name' => 'Cabrayil',
                'code' => '14',
                'country_id' => 14,
            ],
            177 => 
            [
                'id' => 178,
                'name' => 'Ismayilli',
                'code' => '25',
                'country_id' => 14,
            ],
            178 => 
            [
                'id' => 179,
                'name' => 'Goranboy',
                'code' => '21',
                'country_id' => 14,
            ],
            179 => 
            [
                'id' => 180,
                'name' => 'Fuzuli',
                'code' => '18',
                'country_id' => 14,
            ],
            180 => 
            [
                'id' => 181,
                'name' => 'Baki',
                'code' => '09',
                'country_id' => 14,
            ],
            181 => 
            [
                'id' => 182,
                'name' => 'Beylaqan',
                'code' => '12',
                'country_id' => 14,
            ],
            182 => 
            [
                'id' => 183,
                'name' => 'Daskasan',
                'code' => '16',
                'country_id' => 14,
            ],
            183 => 
            [
                'id' => 184,
                'name' => 'Masalli',
                'code' => '32',
                'country_id' => 14,
            ],
            184 => 
            [
                'id' => 185,
                'name' => 'Zaqatala',
                'code' => '70',
                'country_id' => 14,
            ],
            185 => 
            [
                'id' => 186,
                'name' => 'Lankaran',
                'code' => '29',
                'country_id' => 14,
            ],
            186 => 
            [
                'id' => 187,
                'name' => 'Lerik',
                'code' => '31',
                'country_id' => 14,
            ],
            187 => 
            [
                'id' => 188,
                'name' => 'Ali Bayramli',
                'code' => '07',
                'country_id' => 14,
            ],
            188 => 
            [
                'id' => 189,
                'name' => 'Qax',
                'code' => '39',
                'country_id' => 14,
            ],
            189 => 
            [
                'id' => 190,
                'name' => 'Samux',
                'code' => '52',
                'country_id' => 14,
            ],
            190 => 
            [
                'id' => 191,
                'name' => 'Zardab',
                'code' => '71',
                'country_id' => 14,
            ],
            191 => 
            [
                'id' => 192,
                'name' => 'Gadabay',
                'code' => '19',
                'country_id' => 14,
            ],
            192 => 
            [
                'id' => 193,
                'name' => 'Ucar',
                'code' => '59',
                'country_id' => 14,
            ],
            193 => 
            [
                'id' => 194,
                'name' => 'Barda',
                'code' => '11',
                'country_id' => 14,
            ],
            194 => 
            [
                'id' => 195,
                'name' => 'Siyazan',
                'code' => '53',
                'country_id' => 14,
            ],
            195 => 
            [
                'id' => 196,
                'name' => 'Xocavand',
                'code' => '65',
                'country_id' => 14,
            ],
            196 => 
            [
                'id' => 197,
                'name' => 'Zangilan',
                'code' => '69',
                'country_id' => 14,
            ],
            197 => 
            [
                'id' => 198,
                'name' => 'Xizi',
                'code' => '63',
                'country_id' => 14,
            ],
            198 => 
            [
                'id' => 199,
                'name' => 'Yevlax',
                'code' => '67',
                'country_id' => 14,
            ],
            199 => 
            [
                'id' => 200,
                'name' => 'Agsu',
                'code' => '06',
                'country_id' => 14,
            ],
            200 => 
            [
                'id' => 201,
                'name' => 'Qobustan',
                'code' => '41',
                'country_id' => 14,
            ],
            201 => 
            [
                'id' => 202,
                'name' => 'Goycay',
                'code' => '22',
                'country_id' => 14,
            ],
            202 => 
            [
                'id' => 203,
                'name' => 'Astara',
                'code' => '08',
                'country_id' => 14,
            ],
            203 => 
            [
                'id' => 204,
                'name' => 'Xocali',
                'code' => '64',
                'country_id' => 14,
            ],
            204 => 
            [
                'id' => 205,
                'name' => 'Xankandi',
                'code' => '61',
                'country_id' => 14,
            ],
            205 => 
            [
                'id' => 206,
                'name' => 'Tartar',
                'code' => '57',
                'country_id' => 14,
            ],
            206 => 
            [
                'id' => 207,
                'name' => 'Agstafa',
                'code' => '05',
                'country_id' => 14,
            ],
            207 => 
            [
                'id' => 208,
                'name' => 'Salyan',
                'code' => '49',
                'country_id' => 14,
            ],
            208 => 
            [
                'id' => 209,
                'name' => 'Susa',
                'code' => '55',
                'country_id' => 14,
            ],
            209 => 
            [
                'id' => 210,
                'name' => 'Ganca',
                'code' => '20',
                'country_id' => 14,
            ],
            210 => 
            [
                'id' => 211,
                'name' => 'Sumqayit',
                'code' => '54',
                'country_id' => 14,
            ],
            211 => 
            [
                'id' => 212,
                'name' => 'Saki',
                'code' => '48',
                'country_id' => 14,
            ],
            212 => 
            [
                'id' => 213,
                'name' => 'Naftalan',
                'code' => '34',
                'country_id' => 14,
            ],
            213 => 
            [
                'id' => 214,
                'name' => 'Lankaran',
                'code' => '30',
                'country_id' => 14,
            ],
            214 => 
            [
                'id' => 215,
                'name' => 'Mingacevir',
                'code' => '33',
                'country_id' => 14,
            ],
            215 => 
            [
                'id' => 216,
                'name' => 'Susa',
                'code' => '56',
                'country_id' => 14,
            ],
            216 => 
            [
                'id' => 217,
                'name' => 'Republika Srpska',
                'code' => '02',
                'country_id' => 15,
            ],
            217 => 
            [
                'id' => 218,
                'name' => 'Federation of Bosnia and Herzegovina',
                'code' => '01',
                'country_id' => 15,
            ],
            218 => 
            [
                'id' => 219,
                'name' => '',
                'code' => 'BD',
                'country_id' => 15,
            ],
            219 => 
            [
                'id' => 220,
                'name' => 'Saint Joseph',
                'code' => '06',
                'country_id' => 16,
            ],
            220 => 
            [
                'id' => 221,
                'name' => 'Saint Lucy',
                'code' => '07',
                'country_id' => 16,
            ],
            221 => 
            [
                'id' => 222,
                'name' => 'Saint Thomas',
                'code' => '11',
                'country_id' => 16,
            ],
            222 => 
            [
                'id' => 223,
                'name' => 'Saint James',
                'code' => '04',
                'country_id' => 16,
            ],
            223 => 
            [
                'id' => 224,
                'name' => 'Saint John',
                'code' => '05',
                'country_id' => 16,
            ],
            224 => 
            [
                'id' => 225,
                'name' => 'Saint Peter',
                'code' => '09',
                'country_id' => 16,
            ],
            225 => 
            [
                'id' => 226,
                'name' => 'Christ Church',
                'code' => '01',
                'country_id' => 16,
            ],
            226 => 
            [
                'id' => 227,
                'name' => 'Saint George',
                'code' => '03',
                'country_id' => 16,
            ],
            227 => 
            [
                'id' => 228,
                'name' => 'Saint Michael',
                'code' => '08',
                'country_id' => 16,
            ],
            228 => 
            [
                'id' => 229,
                'name' => 'Saint Andrew',
                'code' => '02',
                'country_id' => 16,
            ],
            229 => 
            [
                'id' => 230,
                'name' => 'Saint Philip',
                'code' => '10',
                'country_id' => 16,
            ],
            230 => 
            [
                'id' => 231,
                'name' => 'Khulna',
                'code' => '82',
                'country_id' => 17,
            ],
            231 => 
            [
                'id' => 232,
                'name' => 'Rajshahi',
                'code' => '83',
                'country_id' => 17,
            ],
            232 => 
            [
                'id' => 233,
                'name' => 'Dhaka',
                'code' => '81',
                'country_id' => 17,
            ],
            233 => 
            [
                'id' => 234,
                'name' => '',
                'code' => '80',
                'country_id' => 17,
            ],
            234 => 
            [
                'id' => 235,
                'name' => 'Barisal',
                'code' => '85',
                'country_id' => 17,
            ],
            235 => 
            [
                'id' => 236,
                'name' => 'Sylhet',
                'code' => '86',
                'country_id' => 17,
            ],
            236 => 
            [
                'id' => 237,
                'name' => 'Chittagong',
                'code' => '84',
                'country_id' => 17,
            ],
            237 => 
            [
                'id' => 238,
                'name' => 'Oost-Vlaanderen',
                'code' => '08',
                'country_id' => 18,
            ],
            238 => 
            [
                'id' => 239,
                'name' => 'West-Vlaanderen',
                'code' => '09',
                'country_id' => 18,
            ],
            239 => 
            [
                'id' => 240,
                'name' => '',
                'code' => '02',
                'country_id' => 18,
            ],
            240 => 
            [
                'id' => 241,
                'name' => 'Limburg',
                'code' => '05',
                'country_id' => 18,
            ],
            241 => 
            [
                'id' => 242,
                'name' => 'Antwerpen',
                'code' => '01',
                'country_id' => 18,
            ],
            242 => 
            [
                'id' => 243,
                'name' => 'Luxembourg',
                'code' => '06',
                'country_id' => 18,
            ],
            243 => 
            [
                'id' => 244,
                'name' => 'Hainaut',
                'code' => '03',
                'country_id' => 18,
            ],
            244 => 
            [
                'id' => 245,
                'name' => 'Liege',
                'code' => '04',
                'country_id' => 18,
            ],
            245 => 
            [
                'id' => 246,
                'name' => 'Namur',
                'code' => '07',
                'country_id' => 18,
            ],
            246 => 
            [
                'id' => 247,
                'name' => 'Brussels Hoofdstedelijk Gewest',
                'code' => '11',
                'country_id' => 18,
            ],
            247 => 
            [
                'id' => 248,
                'name' => 'Vlaams-Brabant',
                'code' => '12',
                'country_id' => 18,
            ],
            248 => 
            [
                'id' => 249,
                'name' => 'Brabant Wallon',
                'code' => '10',
                'country_id' => 18,
            ],
            249 => 
            [
                'id' => 250,
                'name' => '',
                'code' => '38',
                'country_id' => 19,
            ],
            250 => 
            [
                'id' => 251,
                'name' => 'Mouhoun',
                'code' => '63',
                'country_id' => 19,
            ],
            251 => 
            [
                'id' => 252,
                'name' => 'Bam',
                'code' => '15',
                'country_id' => 19,
            ],
            252 => 
            [
                'id' => 253,
                'name' => '',
                'code' => '37',
                'country_id' => 19,
            ],
            253 => 
            [
                'id' => 254,
                'name' => '',
                'code' => '16',
                'country_id' => 19,
            ],
            254 => 
            [
                'id' => 255,
                'name' => '',
                'code' => '24',
                'country_id' => 19,
            ],
            255 => 
            [
                'id' => 256,
                'name' => '',
                'code' => '32',
                'country_id' => 19,
            ],
            256 => 
            [
                'id' => 257,
                'name' => 'Tapoa',
                'code' => '42',
                'country_id' => 19,
            ],
            257 => 
            [
                'id' => 258,
                'name' => 'Soum',
                'code' => '40',
                'country_id' => 19,
            ],
            258 => 
            [
                'id' => 259,
                'name' => 'Leraba',
                'code' => '61',
                'country_id' => 19,
            ],
            259 => 
            [
                'id' => 260,
                'name' => 'Noumbiel',
                'code' => '67',
                'country_id' => 19,
            ],
            260 => 
            [
                'id' => 261,
                'name' => '',
                'code' => '30',
                'country_id' => 19,
            ],
            261 => 
            [
                'id' => 262,
                'name' => 'Gnagna',
                'code' => '21',
                'country_id' => 19,
            ],
            262 => 
            [
                'id' => 263,
                'name' => '',
                'code' => '31',
                'country_id' => 19,
            ],
            263 => 
            [
                'id' => 264,
                'name' => '',
                'code' => '22',
                'country_id' => 19,
            ],
            264 => 
            [
                'id' => 265,
                'name' => 'Yatenga',
                'code' => '76',
                'country_id' => 19,
            ],
            265 => 
            [
                'id' => 266,
                'name' => 'Banwa',
                'code' => '46',
                'country_id' => 19,
            ],
            266 => 
            [
                'id' => 267,
                'name' => 'Poni',
                'code' => '69',
                'country_id' => 19,
            ],
            267 => 
            [
                'id' => 268,
                'name' => 'Loroum',
                'code' => '62',
                'country_id' => 19,
            ],
            268 => 
            [
                'id' => 269,
                'name' => 'Kouritenga',
                'code' => '28',
                'country_id' => 19,
            ],
            269 => 
            [
                'id' => 270,
                'name' => 'Tuy',
                'code' => '74',
                'country_id' => 19,
            ],
            270 => 
            [
                'id' => 271,
                'name' => 'Kossi',
                'code' => '58',
                'country_id' => 19,
            ],
            271 => 
            [
                'id' => 272,
                'name' => 'Passore',
                'code' => '34',
                'country_id' => 19,
            ],
            272 => 
            [
                'id' => 273,
                'name' => 'Kenedougou',
                'code' => '54',
                'country_id' => 19,
            ],
            273 => 
            [
                'id' => 274,
                'name' => 'Bale',
                'code' => '45',
                'country_id' => 19,
            ],
            274 => 
            [
                'id' => 275,
                'name' => 'Bougouriba',
                'code' => '48',
                'country_id' => 19,
            ],
            275 => 
            [
                'id' => 276,
                'name' => 'Houet',
                'code' => '51',
                'country_id' => 19,
            ],
            276 => 
            [
                'id' => 277,
                'name' => 'Gourma',
                'code' => '50',
                'country_id' => 19,
            ],
            277 => 
            [
                'id' => 278,
                'name' => 'Namentenga',
                'code' => '64',
                'country_id' => 19,
            ],
            278 => 
            [
                'id' => 279,
                'name' => 'Sanmatenga',
                'code' => '70',
                'country_id' => 19,
            ],
            279 => 
            [
                'id' => 280,
                'name' => '',
                'code' => '18',
                'country_id' => 19,
            ],
            280 => 
            [
                'id' => 281,
                'name' => 'Ioba',
                'code' => '52',
                'country_id' => 19,
            ],
            281 => 
            [
                'id' => 282,
                'name' => 'Ganzourgou',
                'code' => '20',
                'country_id' => 19,
            ],
            282 => 
            [
                'id' => 283,
                'name' => 'Naouri',
                'code' => '65',
                'country_id' => 19,
            ],
            283 => 
            [
                'id' => 284,
                'name' => 'Boulkiemde',
                'code' => '19',
                'country_id' => 19,
            ],
            284 => 
            [
                'id' => 285,
                'name' => 'Zoundweogo',
                'code' => '44',
                'country_id' => 19,
            ],
            285 => 
            [
                'id' => 286,
                'name' => 'Zondoma',
                'code' => '78',
                'country_id' => 19,
            ],
            286 => 
            [
                'id' => 287,
                'name' => '',
                'code' => '39',
                'country_id' => 19,
            ],
            287 => 
            [
                'id' => 288,
                'name' => '',
                'code' => '43',
                'country_id' => 19,
            ],
            288 => 
            [
                'id' => 289,
                'name' => 'Komoe',
                'code' => '55',
                'country_id' => 19,
            ],
            289 => 
            [
                'id' => 290,
                'name' => 'Yagha',
                'code' => '75',
                'country_id' => 19,
            ],
            290 => 
            [
                'id' => 291,
                'name' => 'Komondjari',
                'code' => '56',
                'country_id' => 19,
            ],
            291 => 
            [
                'id' => 292,
                'name' => 'Sourou',
                'code' => '73',
                'country_id' => 19,
            ],
            292 => 
            [
                'id' => 293,
                'name' => 'Nayala',
                'code' => '66',
                'country_id' => 19,
            ],
            293 => 
            [
                'id' => 294,
                'name' => 'Sissili',
                'code' => '72',
                'country_id' => 19,
            ],
            294 => 
            [
                'id' => 295,
                'name' => 'Sanguie',
                'code' => '36',
                'country_id' => 19,
            ],
            295 => 
            [
                'id' => 296,
                'name' => 'Oudalan',
                'code' => '33',
                'country_id' => 19,
            ],
            296 => 
            [
                'id' => 297,
                'name' => 'Koulpelogo',
                'code' => '59',
                'country_id' => 19,
            ],
            297 => 
            [
                'id' => 298,
                'name' => 'Ziro',
                'code' => '77',
                'country_id' => 19,
            ],
            298 => 
            [
                'id' => 299,
                'name' => 'Kourweogo',
                'code' => '60',
                'country_id' => 19,
            ],
            299 => 
            [
                'id' => 300,
                'name' => 'Oubritenga',
                'code' => '68',
                'country_id' => 19,
            ],
            300 => 
            [
                'id' => 301,
                'name' => 'Seno',
                'code' => '71',
                'country_id' => 19,
            ],
            301 => 
            [
                'id' => 302,
                'name' => 'Bazega',
                'code' => '47',
                'country_id' => 19,
            ],
            302 => 
            [
                'id' => 303,
                'name' => 'Kadiogo',
                'code' => '53',
                'country_id' => 19,
            ],
            303 => 
            [
                'id' => 304,
                'name' => 'Kompienga',
                'code' => '57',
                'country_id' => 19,
            ],
            304 => 
            [
                'id' => 305,
                'name' => 'Boulgou',
                'code' => '49',
                'country_id' => 19,
            ],
            305 => 
            [
                'id' => 306,
                'name' => 'Lovech',
                'code' => '46',
                'country_id' => 20,
            ],
            306 => 
            [
                'id' => 307,
                'name' => 'Varna',
                'code' => '61',
                'country_id' => 20,
            ],
            307 => 
            [
                'id' => 308,
                'name' => 'Burgas',
                'code' => '39',
                'country_id' => 20,
            ],
            308 => 
            [
                'id' => 309,
                'name' => 'Razgrad',
                'code' => '52',
                'country_id' => 20,
            ],
            309 => 
            [
                'id' => 310,
                'name' => 'Plovdiv',
                'code' => '51',
                'country_id' => 20,
            ],
            310 => 
            [
                'id' => 311,
                'name' => 'Khaskovo',
                'code' => '43',
                'country_id' => 20,
            ],
            311 => 
            [
                'id' => 312,
                'name' => 'Sofiya',
                'code' => '58',
                'country_id' => 20,
            ],
            312 => 
            [
                'id' => 313,
                'name' => 'Silistra',
                'code' => '55',
                'country_id' => 20,
            ],
            313 => 
            [
                'id' => 314,
                'name' => 'Vidin',
                'code' => '63',
                'country_id' => 20,
            ],
            314 => 
            [
                'id' => 315,
                'name' => 'Montana',
                'code' => '47',
                'country_id' => 20,
            ],
            315 => 
            [
                'id' => 316,
                'name' => 'Mikhaylovgrad',
                'code' => '33',
                'country_id' => 20,
            ],
            316 => 
            [
                'id' => 317,
                'name' => 'Grad Sofiya',
                'code' => '42',
                'country_id' => 20,
            ],
            317 => 
            [
                'id' => 318,
                'name' => 'Turgovishte',
                'code' => '60',
                'country_id' => 20,
            ],
            318 => 
            [
                'id' => 319,
                'name' => 'Kurdzhali',
                'code' => '44',
                'country_id' => 20,
            ],
            319 => 
            [
                'id' => 320,
                'name' => 'Dobrich',
                'code' => '40',
                'country_id' => 20,
            ],
            320 => 
            [
                'id' => 321,
                'name' => 'Shumen',
                'code' => '54',
                'country_id' => 20,
            ],
            321 => 
            [
                'id' => 322,
                'name' => 'Blagoevgrad',
                'code' => '38',
                'country_id' => 20,
            ],
            322 => 
            [
                'id' => 323,
                'name' => 'Smolyan',
                'code' => '57',
                'country_id' => 20,
            ],
            323 => 
            [
                'id' => 324,
                'name' => 'Stara Zagora',
                'code' => '59',
                'country_id' => 20,
            ],
            324 => 
            [
                'id' => 325,
                'name' => 'Pazardzhik',
                'code' => '48',
                'country_id' => 20,
            ],
            325 => 
            [
                'id' => 326,
                'name' => 'Ruse',
                'code' => '53',
                'country_id' => 20,
            ],
            326 => 
            [
                'id' => 327,
                'name' => 'Vratsa',
                'code' => '64',
                'country_id' => 20,
            ],
            327 => 
            [
                'id' => 328,
                'name' => 'Pleven',
                'code' => '50',
                'country_id' => 20,
            ],
            328 => 
            [
                'id' => 329,
                'name' => 'Pernik',
                'code' => '49',
                'country_id' => 20,
            ],
            329 => 
            [
                'id' => 330,
                'name' => 'Kyustendil',
                'code' => '45',
                'country_id' => 20,
            ],
            330 => 
            [
                'id' => 331,
                'name' => 'Yambol',
                'code' => '65',
                'country_id' => 20,
            ],
            331 => 
            [
                'id' => 332,
                'name' => 'Gabrovo',
                'code' => '41',
                'country_id' => 20,
            ],
            332 => 
            [
                'id' => 333,
                'name' => 'Sliven',
                'code' => '56',
                'country_id' => 20,
            ],
            333 => 
            [
                'id' => 334,
                'name' => 'Veliko Turnovo',
                'code' => '62',
                'country_id' => 20,
            ],
            334 => 
            [
                'id' => 335,
                'name' => 'Jidd Hafs',
                'code' => '05',
                'country_id' => 21,
            ],
            335 => 
            [
                'id' => 336,
                'name' => '',
                'code' => '03',
                'country_id' => 21,
            ],
            336 => 
            [
                'id' => 337,
                'name' => 'Al Mintaqah ash Shamaliyah',
                'code' => '10',
                'country_id' => 21,
            ],
            337 => 
            [
                'id' => 338,
                'name' => '',
                'code' => '07',
                'country_id' => 21,
            ],
            338 => 
            [
                'id' => 339,
                'name' => 'Al Manamah',
                'code' => '02',
                'country_id' => 21,
            ],
            339 => 
            [
                'id' => 340,
                'name' => 'Sitrah',
                'code' => '06',
                'country_id' => 21,
            ],
            340 => 
            [
                'id' => 341,
                'name' => 'Al Mintaqah al Gharbiyah',
                'code' => '08',
                'country_id' => 21,
            ],
            341 => 
            [
                'id' => 342,
                'name' => 'Mintaqat Juzur Hawar',
                'code' => '09',
                'country_id' => 21,
            ],
            342 => 
            [
                'id' => 343,
                'name' => 'Al Hadd',
                'code' => '01',
                'country_id' => 21,
            ],
            343 => 
            [
                'id' => 344,
                'name' => 'Al Mintaqah al Wusta',
                'code' => '11',
                'country_id' => 21,
            ],
            344 => 
            [
                'id' => 345,
                'name' => 'Ar Rifa',
                'code' => '13',
                'country_id' => 21,
            ],
            345 => 
            [
                'id' => 346,
                'name' => 'Madinat',
                'code' => '12',
                'country_id' => 21,
            ],
            346 => 
            [
                'id' => 347,
                'name' => 'Karuzi',
                'code' => '14',
                'country_id' => 22,
            ],
            347 => 
            [
                'id' => 348,
                'name' => 'Ruyigi',
                'code' => '21',
                'country_id' => 22,
            ],
            348 => 
            [
                'id' => 349,
                'name' => 'Bubanza',
                'code' => '09',
                'country_id' => 22,
            ],
            349 => 
            [
                'id' => 350,
                'name' => 'Bururi',
                'code' => '10',
                'country_id' => 22,
            ],
            350 => 
            [
                'id' => 351,
                'name' => 'Makamba',
                'code' => '17',
                'country_id' => 22,
            ],
            351 => 
            [
                'id' => 352,
                'name' => 'Kayanza',
                'code' => '15',
                'country_id' => 22,
            ],
            352 => 
            [
                'id' => 353,
                'name' => '',
                'code' => '05',
                'country_id' => 22,
            ],
            353 => 
            [
                'id' => 354,
                'name' => 'Rutana',
                'code' => '20',
                'country_id' => 22,
            ],
            354 => 
            [
                'id' => 355,
                'name' => 'Muyinga',
                'code' => '18',
                'country_id' => 22,
            ],
            355 => 
            [
                'id' => 356,
                'name' => 'Cibitoke',
                'code' => '12',
                'country_id' => 22,
            ],
            356 => 
            [
                'id' => 357,
                'name' => 'Gitega',
                'code' => '13',
                'country_id' => 22,
            ],
            357 => 
            [
                'id' => 358,
                'name' => 'Cankuzo',
                'code' => '11',
                'country_id' => 22,
            ],
            358 => 
            [
                'id' => 359,
                'name' => 'Bujumbura',
                'code' => '02',
                'country_id' => 22,
            ],
            359 => 
            [
                'id' => 360,
                'name' => 'Ngozi',
                'code' => '19',
                'country_id' => 22,
            ],
            360 => 
            [
                'id' => 361,
                'name' => 'Kirundo',
                'code' => '16',
                'country_id' => 22,
            ],
            361 => 
            [
                'id' => 362,
                'name' => 'Plateau',
                'code' => '17',
                'country_id' => 23,
            ],
            362 => 
            [
                'id' => 363,
                'name' => 'Collines',
                'code' => '11',
                'country_id' => 23,
            ],
            363 => 
            [
                'id' => 364,
                'name' => '',
                'code' => '04',
                'country_id' => 23,
            ],
            364 => 
            [
                'id' => 365,
                'name' => '',
                'code' => '01',
                'country_id' => 23,
            ],
            365 => 
            [
                'id' => 366,
                'name' => 'Oueme',
                'code' => '16',
                'country_id' => 23,
            ],
            366 => 
            [
                'id' => 367,
                'name' => 'Zou',
                'code' => '18',
                'country_id' => 23,
            ],
            367 => 
            [
                'id' => 368,
                'name' => '',
                'code' => '05',
                'country_id' => 23,
            ],
            368 => 
            [
                'id' => 369,
                'name' => '',
                'code' => '02',
                'country_id' => 23,
            ],
            369 => 
            [
                'id' => 370,
                'name' => 'Atlanyique',
                'code' => '09',
                'country_id' => 23,
            ],
            370 => 
            [
                'id' => 371,
                'name' => 'Borgou',
                'code' => '10',
                'country_id' => 23,
            ],
            371 => 
            [
                'id' => 372,
                'name' => 'Mono',
                'code' => '15',
                'country_id' => 23,
            ],
            372 => 
            [
                'id' => 373,
                'name' => '',
                'code' => '03',
                'country_id' => 23,
            ],
            373 => 
            [
                'id' => 374,
                'name' => 'Kouffo',
                'code' => '12',
                'country_id' => 23,
            ],
            374 => 
            [
                'id' => 375,
                'name' => 'Donga',
                'code' => '13',
                'country_id' => 23,
            ],
            375 => 
            [
                'id' => 376,
                'name' => 'Littoral',
                'code' => '14',
                'country_id' => 23,
            ],
            376 => 
            [
                'id' => 377,
                'name' => 'Alibori',
                'code' => '07',
                'country_id' => 23,
            ],
            377 => 
            [
                'id' => 378,
                'name' => 'Atakora',
                'code' => '08',
                'country_id' => 23,
            ],
            378 => 
            [
                'id' => 379,
                'name' => 'Devonshire',
                'code' => '01',
                'country_id' => 24,
            ],
            379 => 
            [
                'id' => 380,
                'name' => 'Paget',
                'code' => '04',
                'country_id' => 24,
            ],
            380 => 
            [
                'id' => 381,
                'name' => 'Saint George\'s',
                'code' => '07',
                'country_id' => 24,
            ],
            381 => 
            [
                'id' => 382,
                'name' => 'Smiths',
                'code' => '09',
                'country_id' => 24,
            ],
            382 => 
            [
                'id' => 383,
                'name' => 'Hamilton',
                'code' => '03',
                'country_id' => 24,
            ],
            383 => 
            [
                'id' => 384,
                'name' => 'Warwick',
                'code' => '11',
                'country_id' => 24,
            ],
            384 => 
            [
                'id' => 385,
                'name' => 'Sandys',
                'code' => '08',
                'country_id' => 24,
            ],
            385 => 
            [
                'id' => 386,
                'name' => 'Saint George',
                'code' => '06',
                'country_id' => 24,
            ],
            386 => 
            [
                'id' => 387,
                'name' => 'Hamilton',
                'code' => '02',
                'country_id' => 24,
            ],
            387 => 
            [
                'id' => 388,
                'name' => '',
                'code' => '00',
                'country_id' => 25,
            ],
            388 => 
            [
                'id' => 389,
                'name' => 'Santa Cruz',
                'code' => '08',
                'country_id' => 26,
            ],
            389 => 
            [
                'id' => 390,
                'name' => 'Pando',
                'code' => '06',
                'country_id' => 26,
            ],
            390 => 
            [
                'id' => 391,
                'name' => 'Tarija',
                'code' => '09',
                'country_id' => 26,
            ],
            391 => 
            [
                'id' => 392,
                'name' => 'La Paz',
                'code' => '04',
                'country_id' => 26,
            ],
            392 => 
            [
                'id' => 393,
                'name' => 'Oruro',
                'code' => '05',
                'country_id' => 26,
            ],
            393 => 
            [
                'id' => 394,
                'name' => 'Cochabamba',
                'code' => '02',
                'country_id' => 26,
            ],
            394 => 
            [
                'id' => 395,
                'name' => 'Potosi',
                'code' => '07',
                'country_id' => 26,
            ],
            395 => 
            [
                'id' => 396,
                'name' => 'Chuquisaca',
                'code' => '01',
                'country_id' => 26,
            ],
            396 => 
            [
                'id' => 397,
                'name' => 'El Beni',
                'code' => '03',
                'country_id' => 26,
            ],
            397 => 
            [
                'id' => 398,
                'name' => 'Santa Catarina',
                'code' => '26',
                'country_id' => 27,
            ],
            398 => 
            [
                'id' => 399,
                'name' => 'Mato Grosso do Sul',
                'code' => '11',
                'country_id' => 27,
            ],
            399 => 
            [
                'id' => 400,
                'name' => 'Rio Grande do Sul',
                'code' => '23',
                'country_id' => 27,
            ],
            400 => 
            [
                'id' => 401,
                'name' => 'Espirito Santo',
                'code' => '08',
                'country_id' => 27,
            ],
            401 => 
            [
                'id' => 402,
                'name' => 'Bahia',
                'code' => '05',
                'country_id' => 27,
            ],
            402 => 
            [
                'id' => 403,
                'name' => 'Rondonia',
                'code' => '24',
                'country_id' => 27,
            ],
            403 => 
            [
                'id' => 404,
                'name' => 'Minas Gerais',
                'code' => '15',
                'country_id' => 27,
            ],
            404 => 
            [
                'id' => 405,
                'name' => 'Paraiba',
                'code' => '17',
                'country_id' => 27,
            ],
            405 => 
            [
                'id' => 406,
                'name' => 'Amapa',
                'code' => '03',
                'country_id' => 27,
            ],
            406 => 
            [
                'id' => 407,
                'name' => 'Amazonas',
                'code' => '04',
                'country_id' => 27,
            ],
            407 => 
            [
                'id' => 408,
                'name' => 'Para',
                'code' => '16',
                'country_id' => 27,
            ],
            408 => 
            [
                'id' => 409,
                'name' => 'Ceara',
                'code' => '06',
                'country_id' => 27,
            ],
            409 => 
            [
                'id' => 410,
                'name' => 'Rio de Janeiro',
                'code' => '21',
                'country_id' => 27,
            ],
            410 => 
            [
                'id' => 411,
                'name' => 'Goias',
                'code' => '29',
                'country_id' => 27,
            ],
            411 => 
            [
                'id' => 412,
                'name' => 'Sao Paulo',
                'code' => '27',
                'country_id' => 27,
            ],
            412 => 
            [
                'id' => 413,
                'name' => 'Parana',
                'code' => '18',
                'country_id' => 27,
            ],
            413 => 
            [
                'id' => 414,
                'name' => 'Rio Grande do Norte',
                'code' => '22',
                'country_id' => 27,
            ],
            414 => 
            [
                'id' => 415,
                'name' => 'Acre',
                'code' => '01',
                'country_id' => 27,
            ],
            415 => 
            [
                'id' => 416,
                'name' => 'Piaui',
                'code' => '20',
                'country_id' => 27,
            ],
            416 => 
            [
                'id' => 417,
                'name' => 'Pernambuco',
                'code' => '30',
                'country_id' => 27,
            ],
            417 => 
            [
                'id' => 418,
                'name' => 'Mato Grosso',
                'code' => '14',
                'country_id' => 27,
            ],
            418 => 
            [
                'id' => 419,
                'name' => 'Maranhao',
                'code' => '13',
                'country_id' => 27,
            ],
            419 => 
            [
                'id' => 420,
                'name' => 'Tocantins',
                'code' => '31',
                'country_id' => 27,
            ],
            420 => 
            [
                'id' => 421,
                'name' => 'Roraima',
                'code' => '25',
                'country_id' => 27,
            ],
            421 => 
            [
                'id' => 422,
                'name' => 'Alagoas',
                'code' => '02',
                'country_id' => 27,
            ],
            422 => 
            [
                'id' => 423,
                'name' => 'Sergipe',
                'code' => '28',
                'country_id' => 27,
            ],
            423 => 
            [
                'id' => 424,
                'name' => 'Distrito Federal',
                'code' => '07',
                'country_id' => 27,
            ],
            424 => 
            [
                'id' => 425,
                'name' => 'Acklins and Crooked Islands',
                'code' => '24',
                'country_id' => 28,
            ],
            425 => 
            [
                'id' => 426,
                'name' => 'Mayaguana',
                'code' => '16',
                'country_id' => 28,
            ],
            426 => 
            [
                'id' => 427,
                'name' => 'Long Island',
                'code' => '15',
                'country_id' => 28,
            ],
            427 => 
            [
                'id' => 428,
                'name' => 'New Providence',
                'code' => '23',
                'country_id' => 28,
            ],
            428 => 
            [
                'id' => 429,
                'name' => 'Exuma',
                'code' => '10',
                'country_id' => 28,
            ],
            429 => 
            [
                'id' => 430,
                'name' => 'Bimini',
                'code' => '05',
                'country_id' => 28,
            ],
            430 => 
            [
                'id' => 431,
                'name' => 'Governor\'s Harbour',
                'code' => '27',
                'country_id' => 28,
            ],
            431 => 
            [
                'id' => 432,
                'name' => 'San Salvador and Rum Cay',
                'code' => '35',
                'country_id' => 28,
            ],
            432 => 
            [
                'id' => 433,
                'name' => 'Fresh Creek',
                'code' => '26',
                'country_id' => 28,
            ],
            433 => 
            [
                'id' => 434,
                'name' => 'Cat Island',
                'code' => '06',
                'country_id' => 28,
            ],
            434 => 
            [
                'id' => 435,
                'name' => 'Nichollstown and Berry Islands',
                'code' => '32',
                'country_id' => 28,
            ],
            435 => 
            [
                'id' => 436,
                'name' => 'Kemps Bay',
                'code' => '30',
                'country_id' => 28,
            ],
            436 => 
            [
                'id' => 437,
                'name' => 'Freeport',
                'code' => '25',
                'country_id' => 28,
            ],
            437 => 
            [
                'id' => 438,
                'name' => 'Rock Sound',
                'code' => '33',
                'country_id' => 28,
            ],
            438 => 
            [
                'id' => 439,
                'name' => 'Harbour Island',
                'code' => '22',
                'country_id' => 28,
            ],
            439 => 
            [
                'id' => 440,
                'name' => 'High Rock',
                'code' => '29',
                'country_id' => 28,
            ],
            440 => 
            [
                'id' => 441,
                'name' => 'Green Turtle Cay',
                'code' => '28',
                'country_id' => 28,
            ],
            441 => 
            [
                'id' => 442,
                'name' => 'Marsh Harbour',
                'code' => '31',
                'country_id' => 28,
            ],
            442 => 
            [
                'id' => 443,
                'name' => 'Ragged Island',
                'code' => '18',
                'country_id' => 28,
            ],
            443 => 
            [
                'id' => 444,
                'name' => 'Sandy Point',
                'code' => '34',
                'country_id' => 28,
            ],
            444 => 
            [
                'id' => 445,
                'name' => 'Inagua',
                'code' => '13',
                'country_id' => 28,
            ],
            445 => 
            [
                'id' => 446,
                'name' => 'Wangdi Phodrang',
                'code' => '22',
                'country_id' => 29,
            ],
            446 => 
            [
                'id' => 447,
                'name' => 'Paro',
                'code' => '13',
                'country_id' => 29,
            ],
            447 => 
            [
                'id' => 448,
                'name' => 'Daga',
                'code' => '08',
                'country_id' => 29,
            ],
            448 => 
            [
                'id' => 449,
                'name' => 'Mongar',
                'code' => '12',
                'country_id' => 29,
            ],
            449 => 
            [
                'id' => 450,
                'name' => 'Shemgang',
                'code' => '18',
                'country_id' => 29,
            ],
            450 => 
            [
                'id' => 451,
                'name' => 'Thimphu',
                'code' => '20',
                'country_id' => 29,
            ],
            451 => 
            [
                'id' => 452,
                'name' => 'Tashigang',
                'code' => '19',
                'country_id' => 29,
            ],
            452 => 
            [
                'id' => 453,
                'name' => 'Chirang',
                'code' => '07',
                'country_id' => 29,
            ],
            453 => 
            [
                'id' => 454,
                'name' => 'Geylegphug',
                'code' => '09',
                'country_id' => 29,
            ],
            454 => 
            [
                'id' => 455,
                'name' => 'Samdrup',
                'code' => '17',
                'country_id' => 29,
            ],
            455 => 
            [
                'id' => 456,
                'name' => 'Bumthang',
                'code' => '05',
                'country_id' => 29,
            ],
            456 => 
            [
                'id' => 457,
                'name' => 'Samchi',
                'code' => '16',
                'country_id' => 29,
            ],
            457 => 
            [
                'id' => 458,
                'name' => 'Tongsa',
                'code' => '21',
                'country_id' => 29,
            ],
            458 => 
            [
                'id' => 459,
                'name' => 'Chhukha',
                'code' => '06',
                'country_id' => 29,
            ],
            459 => 
            [
                'id' => 460,
                'name' => 'Pemagatsel',
                'code' => '14',
                'country_id' => 29,
            ],
            460 => 
            [
                'id' => 461,
                'name' => 'Ha',
                'code' => '10',
                'country_id' => 29,
            ],
            461 => 
            [
                'id' => 462,
                'name' => 'Punakha',
                'code' => '15',
                'country_id' => 29,
            ],
            462 => 
            [
                'id' => 463,
                'name' => 'Lhuntshi',
                'code' => '11',
                'country_id' => 29,
            ],
            463 => 
            [
                'id' => 464,
                'name' => 'Central',
                'code' => '01',
                'country_id' => 30,
            ],
            464 => 
            [
                'id' => 465,
                'name' => 'South-East',
                'code' => '09',
                'country_id' => 30,
            ],
            465 => 
            [
                'id' => 466,
                'name' => 'North-East',
                'code' => '08',
                'country_id' => 30,
            ],
            466 => 
            [
                'id' => 467,
                'name' => 'North-West',
                'code' => '11',
                'country_id' => 30,
            ],
            467 => 
            [
                'id' => 468,
                'name' => 'Ghanzi',
                'code' => '03',
                'country_id' => 30,
            ],
            468 => 
            [
                'id' => 469,
                'name' => 'Kweneng',
                'code' => '06',
                'country_id' => 30,
            ],
            469 => 
            [
                'id' => 470,
                'name' => 'Kgalagadi',
                'code' => '04',
                'country_id' => 30,
            ],
            470 => 
            [
                'id' => 471,
                'name' => '',
                'code' => '00',
                'country_id' => 30,
            ],
            471 => 
            [
                'id' => 472,
                'name' => 'Southern',
                'code' => '10',
                'country_id' => 30,
            ],
            472 => 
            [
                'id' => 473,
                'name' => 'Kgatleng',
                'code' => '05',
                'country_id' => 30,
            ],
            473 => 
            [
                'id' => 474,
                'name' => 'Homyel\'skaya Voblasts\'',
                'code' => '02',
                'country_id' => 31,
            ],
            474 => 
            [
                'id' => 475,
                'name' => 'Minsk',
                'code' => '04',
                'country_id' => 31,
            ],
            475 => 
            [
                'id' => 476,
                'name' => 'Brestskaya Voblasts\'',
                'code' => '01',
                'country_id' => 31,
            ],
            476 => 
            [
                'id' => 477,
                'name' => 'Hrodzyenskaya Voblasts\'',
                'code' => '03',
                'country_id' => 31,
            ],
            477 => 
            [
                'id' => 478,
                'name' => 'Mahilyowskaya Voblasts\'',
                'code' => '06',
                'country_id' => 31,
            ],
            478 => 
            [
                'id' => 479,
                'name' => 'Vitsyebskaya Voblasts\'',
                'code' => '07',
                'country_id' => 31,
            ],
            479 => 
            [
                'id' => 480,
                'name' => 'Toledo',
                'code' => '06',
                'country_id' => 32,
            ],
            480 => 
            [
                'id' => 481,
                'name' => 'Cayo',
                'code' => '02',
                'country_id' => 32,
            ],
            481 => 
            [
                'id' => 482,
                'name' => 'Stann Creek',
                'code' => '05',
                'country_id' => 32,
            ],
            482 => 
            [
                'id' => 483,
                'name' => 'Corozal',
                'code' => '03',
                'country_id' => 32,
            ],
            483 => 
            [
                'id' => 484,
                'name' => 'Orange Walk',
                'code' => '04',
                'country_id' => 32,
            ],
            484 => 
            [
                'id' => 485,
                'name' => 'Belize',
                'code' => '01',
                'country_id' => 32,
            ],
            485 => 
            [
                'id' => 486,
                'name' => '',
                'code' => '02',
                'country_id' => 33,
            ],
            486 => 
            [
                'id' => 487,
                'name' => '',
                'code' => '11',
                'country_id' => 33,
            ],
            487 => 
            [
                'id' => 488,
                'name' => '',
                'code' => '10',
                'country_id' => 33,
            ],
            488 => 
            [
                'id' => 489,
                'name' => '',
                'code' => '01',
                'country_id' => 33,
            ],
            489 => 
            [
                'id' => 490,
                'name' => '',
                'code' => '05',
                'country_id' => 33,
            ],
            490 => 
            [
                'id' => 491,
                'name' => '',
                'code' => '08',
                'country_id' => 33,
            ],
            491 => 
            [
                'id' => 492,
                'name' => '',
                'code' => '07',
                'country_id' => 33,
            ],
            492 => 
            [
                'id' => 493,
                'name' => '',
                'code' => '12',
                'country_id' => 33,
            ],
            493 => 
            [
                'id' => 494,
                'name' => '',
                'code' => '13',
                'country_id' => 33,
            ],
            494 => 
            [
                'id' => 495,
                'name' => '',
                'code' => '14',
                'country_id' => 33,
            ],
            495 => 
            [
                'id' => 496,
                'name' => '',
                'code' => '09',
                'country_id' => 33,
            ],
            496 => 
            [
                'id' => 497,
                'name' => '',
                'code' => '04',
                'country_id' => 33,
            ],
            497 => 
            [
                'id' => 498,
                'name' => '',
                'code' => '03',
                'country_id' => 33,
            ],
            498 => 
            [
                'id' => 499,
                'name' => '',
                'code' => '00',
                'country_id' => 34,
            ],
            499 => 
            [
                'id' => 500,
                'name' => 'Equateur',
                'code' => '02',
                'country_id' => 35,
            ],
        ]);
        \DB::table('regions')->insert([
            0 => 
            [
                'id' => 501,
                'name' => 'Orientale',
                'code' => '09',
                'country_id' => 35,
            ],
            1 => 
            [
                'id' => 502,
                'name' => '',
                'code' => '00',
                'country_id' => 35,
            ],
            2 => 
            [
                'id' => 503,
                'name' => 'Nord-Kivu',
                'code' => '11',
                'country_id' => 35,
            ],
            3 => 
            [
                'id' => 504,
                'name' => '',
                'code' => '07',
                'country_id' => 35,
            ],
            4 => 
            [
                'id' => 505,
                'name' => 'Maniema',
                'code' => '10',
                'country_id' => 35,
            ],
            5 => 
            [
                'id' => 506,
                'name' => 'Bandundu',
                'code' => '01',
                'country_id' => 35,
            ],
            6 => 
            [
                'id' => 507,
                'name' => '',
                'code' => '03',
                'country_id' => 35,
            ],
            7 => 
            [
                'id' => 508,
                'name' => 'Katanga',
                'code' => '05',
                'country_id' => 35,
            ],
            8 => 
            [
                'id' => 509,
                'name' => 'Sud-Kivu',
                'code' => '12',
                'country_id' => 35,
            ],
            9 => 
            [
                'id' => 510,
                'name' => 'Bas-Congo',
                'code' => '08',
                'country_id' => 35,
            ],
            10 => 
            [
                'id' => 511,
                'name' => 'Kasai-Oriental',
                'code' => '04',
                'country_id' => 35,
            ],
            11 => 
            [
                'id' => 512,
                'name' => 'Kinshasa',
                'code' => '06',
                'country_id' => 35,
            ],
            12 => 
            [
                'id' => 513,
                'name' => 'Nana-Mambere',
                'code' => '09',
                'country_id' => 36,
            ],
            13 => 
            [
                'id' => 514,
                'name' => 'Ouaka',
                'code' => '11',
                'country_id' => 36,
            ],
            14 => 
            [
                'id' => 515,
                'name' => 'Haute-Kotto',
                'code' => '03',
                'country_id' => 36,
            ],
            15 => 
            [
                'id' => 516,
                'name' => 'Sangha-Mbaere',
                'code' => '16',
                'country_id' => 36,
            ],
            16 => 
            [
                'id' => 517,
                'name' => 'Bamingui-Bangoran',
                'code' => '01',
                'country_id' => 36,
            ],
            17 => 
            [
                'id' => 518,
                'name' => 'Mbomou',
                'code' => '08',
                'country_id' => 36,
            ],
            18 => 
            [
                'id' => 519,
                'name' => 'Basse-Kotto',
                'code' => '02',
                'country_id' => 36,
            ],
            19 => 
            [
                'id' => 520,
                'name' => 'Kemo',
                'code' => '06',
                'country_id' => 36,
            ],
            20 => 
            [
                'id' => 521,
                'name' => 'Haut-Mbomou',
                'code' => '05',
                'country_id' => 36,
            ],
            21 => 
            [
                'id' => 522,
                'name' => 'Ouham-Pende',
                'code' => '13',
                'country_id' => 36,
            ],
            22 => 
            [
                'id' => 523,
                'name' => 'Ouham',
                'code' => '12',
                'country_id' => 36,
            ],
            23 => 
            [
                'id' => 524,
                'name' => 'Ombella-Mpoko',
                'code' => '17',
                'country_id' => 36,
            ],
            24 => 
            [
                'id' => 525,
                'name' => 'Cuvette-Ouest',
                'code' => '14',
                'country_id' => 36,
            ],
            25 => 
            [
                'id' => 526,
                'name' => 'Mambere-Kadei',
                'code' => '04',
                'country_id' => 36,
            ],
            26 => 
            [
                'id' => 527,
                'name' => 'Lobaye',
                'code' => '07',
                'country_id' => 36,
            ],
            27 => 
            [
                'id' => 528,
                'name' => '',
                'code' => '00',
                'country_id' => 36,
            ],
            28 => 
            [
                'id' => 529,
                'name' => 'Nana-Grebizi',
                'code' => '15',
                'country_id' => 36,
            ],
            29 => 
            [
                'id' => 530,
                'name' => 'Bangui',
                'code' => '18',
                'country_id' => 36,
            ],
            30 => 
            [
                'id' => 531,
                'name' => '',
                'code' => '03',
                'country_id' => 37,
            ],
            31 => 
            [
                'id' => 532,
                'name' => 'Plateaux',
                'code' => '08',
                'country_id' => 37,
            ],
            32 => 
            [
                'id' => 533,
                'name' => 'Pool',
                'code' => '11',
                'country_id' => 37,
            ],
            33 => 
            [
                'id' => 534,
                'name' => 'Sangha',
                'code' => '10',
                'country_id' => 37,
            ],
            34 => 
            [
                'id' => 535,
                'name' => 'Lekoumou',
                'code' => '05',
                'country_id' => 37,
            ],
            35 => 
            [
                'id' => 536,
                'name' => 'Likouala',
                'code' => '06',
                'country_id' => 37,
            ],
            36 => 
            [
                'id' => 537,
                'name' => 'Kouilou',
                'code' => '04',
                'country_id' => 37,
            ],
            37 => 
            [
                'id' => 538,
                'name' => 'Niari',
                'code' => '07',
                'country_id' => 37,
            ],
            38 => 
            [
                'id' => 539,
                'name' => 'Bouenza',
                'code' => '01',
                'country_id' => 37,
            ],
            39 => 
            [
                'id' => 540,
                'name' => 'Brazzaville',
                'code' => '12',
                'country_id' => 37,
            ],
            40 => 
            [
                'id' => 541,
                'name' => 'Cuvette-Ouest',
                'code' => '14',
                'country_id' => 37,
            ],
            41 => 
            [
                'id' => 542,
                'name' => 'Cuvette',
                'code' => '13',
                'country_id' => 37,
            ],
            42 => 
            [
                'id' => 543,
                'name' => 'Thurgau',
                'code' => '19',
                'country_id' => 38,
            ],
            43 => 
            [
                'id' => 544,
                'name' => 'Aargau',
                'code' => '01',
                'country_id' => 38,
            ],
            44 => 
            [
                'id' => 545,
                'name' => 'Bern',
                'code' => '05',
                'country_id' => 38,
            ],
            45 => 
            [
                'id' => 546,
                'name' => 'Zurich',
                'code' => '25',
                'country_id' => 38,
            ],
            46 => 
            [
                'id' => 547,
                'name' => 'Fribourg',
                'code' => '06',
                'country_id' => 38,
            ],
            47 => 
            [
                'id' => 548,
                'name' => 'Ausser-Rhoden',
                'code' => '02',
                'country_id' => 38,
            ],
            48 => 
            [
                'id' => 549,
                'name' => 'Valais',
                'code' => '22',
                'country_id' => 38,
            ],
            49 => 
            [
                'id' => 550,
                'name' => 'Uri',
                'code' => '21',
                'country_id' => 38,
            ],
            50 => 
            [
                'id' => 551,
                'name' => 'Graubunden',
                'code' => '09',
                'country_id' => 38,
            ],
            51 => 
            [
                'id' => 552,
                'name' => 'Ticino',
                'code' => '20',
                'country_id' => 38,
            ],
            52 => 
            [
                'id' => 553,
                'name' => 'Luzern',
                'code' => '11',
                'country_id' => 38,
            ],
            53 => 
            [
                'id' => 554,
                'name' => 'Obwalden',
                'code' => '14',
                'country_id' => 38,
            ],
            54 => 
            [
                'id' => 555,
                'name' => 'Solothurn',
                'code' => '18',
                'country_id' => 38,
            ],
            55 => 
            [
                'id' => 556,
                'name' => 'Basel-Stadt',
                'code' => '04',
                'country_id' => 38,
            ],
            56 => 
            [
                'id' => 557,
                'name' => 'Inner-Rhoden',
                'code' => '10',
                'country_id' => 38,
            ],
            57 => 
            [
                'id' => 558,
                'name' => 'Zug',
                'code' => '24',
                'country_id' => 38,
            ],
            58 => 
            [
                'id' => 559,
                'name' => 'Vaud',
                'code' => '23',
                'country_id' => 38,
            ],
            59 => 
            [
                'id' => 560,
                'name' => 'Jura',
                'code' => '26',
                'country_id' => 38,
            ],
            60 => 
            [
                'id' => 561,
                'name' => 'Basel-Landschaft',
                'code' => '03',
                'country_id' => 38,
            ],
            61 => 
            [
                'id' => 562,
                'name' => 'Schwyz',
                'code' => '17',
                'country_id' => 38,
            ],
            62 => 
            [
                'id' => 563,
                'name' => 'Sankt Gallen',
                'code' => '15',
                'country_id' => 38,
            ],
            63 => 
            [
                'id' => 564,
                'name' => 'Schaffhausen',
                'code' => '16',
                'country_id' => 38,
            ],
            64 => 
            [
                'id' => 565,
                'name' => 'Glarus',
                'code' => '08',
                'country_id' => 38,
            ],
            65 => 
            [
                'id' => 566,
                'name' => 'Geneve',
                'code' => '07',
                'country_id' => 38,
            ],
            66 => 
            [
                'id' => 567,
                'name' => 'Neuchatel',
                'code' => '12',
                'country_id' => 38,
            ],
            67 => 
            [
                'id' => 568,
                'name' => 'Nidwalden',
                'code' => '13',
                'country_id' => 38,
            ],
            68 => 
            [
                'id' => 569,
                'name' => '',
                'code' => '00',
                'country_id' => 38,
            ],
            69 => 
            [
                'id' => 570,
                'name' => '',
                'code' => '39',
                'country_id' => 39,
            ],
            70 => 
            [
                'id' => 571,
                'name' => '',
                'code' => '61',
                'country_id' => 39,
            ],
            71 => 
            [
                'id' => 572,
                'name' => '',
                'code' => '58',
                'country_id' => 39,
            ],
            72 => 
            [
                'id' => 573,
                'name' => '',
                'code' => '62',
                'country_id' => 39,
            ],
            73 => 
            [
                'id' => 574,
                'name' => '',
                'code' => '67',
                'country_id' => 39,
            ],
            74 => 
            [
                'id' => 575,
                'name' => '',
                'code' => '16',
                'country_id' => 39,
            ],
            75 => 
            [
                'id' => 576,
                'name' => '',
                'code' => '30',
                'country_id' => 39,
            ],
            76 => 
            [
                'id' => 577,
                'name' => '',
                'code' => '05',
                'country_id' => 39,
            ],
            77 => 
            [
                'id' => 578,
                'name' => '',
                'code' => '59',
                'country_id' => 39,
            ],
            78 => 
            [
                'id' => 579,
                'name' => 'Vallee du Bandama',
                'code' => '90',
                'country_id' => 39,
            ],
            79 => 
            [
                'id' => 580,
                'name' => '',
                'code' => '37',
                'country_id' => 39,
            ],
            80 => 
            [
                'id' => 581,
                'name' => 'N\'zi-Comoe',
                'code' => '86',
                'country_id' => 39,
            ],
            81 => 
            [
                'id' => 582,
                'name' => '',
                'code' => '06',
                'country_id' => 39,
            ],
            82 => 
            [
                'id' => 583,
                'name' => '',
                'code' => '20',
                'country_id' => 39,
            ],
            83 => 
            [
                'id' => 584,
                'name' => '',
                'code' => '42',
                'country_id' => 39,
            ],
            84 => 
            [
                'id' => 585,
                'name' => 'Moyen-Comoe',
                'code' => '85',
                'country_id' => 39,
            ],
            85 => 
            [
                'id' => 586,
                'name' => '',
                'code' => '64',
                'country_id' => 39,
            ],
            86 => 
            [
                'id' => 587,
                'name' => 'Lagunes',
                'code' => '82',
                'country_id' => 39,
            ],
            87 => 
            [
                'id' => 588,
                'name' => 'Zanzan',
                'code' => '92',
                'country_id' => 39,
            ],
            88 => 
            [
                'id' => 589,
                'name' => 'Sud-Comoe',
                'code' => '89',
                'country_id' => 39,
            ],
            89 => 
            [
                'id' => 590,
                'name' => 'Lacs',
                'code' => '81',
                'country_id' => 39,
            ],
            90 => 
            [
                'id' => 591,
                'name' => '',
                'code' => '18',
                'country_id' => 39,
            ],
            91 => 
            [
                'id' => 592,
                'name' => '',
                'code' => '63',
                'country_id' => 39,
            ],
            92 => 
            [
                'id' => 593,
                'name' => '',
                'code' => '54',
                'country_id' => 39,
            ],
            93 => 
            [
                'id' => 594,
                'name' => '',
                'code' => '68',
                'country_id' => 39,
            ],
            94 => 
            [
                'id' => 595,
                'name' => '',
                'code' => '66',
                'country_id' => 39,
            ],
            95 => 
            [
                'id' => 596,
                'name' => '',
                'code' => '70',
                'country_id' => 39,
            ],
            96 => 
            [
                'id' => 597,
                'name' => '',
                'code' => '51',
                'country_id' => 39,
            ],
            97 => 
            [
                'id' => 598,
                'name' => 'Fromager',
                'code' => '79',
                'country_id' => 39,
            ],
            98 => 
            [
                'id' => 599,
                'name' => '',
                'code' => '25',
                'country_id' => 39,
            ],
            99 => 
            [
                'id' => 600,
                'name' => 'Agneby',
                'code' => '74',
                'country_id' => 39,
            ],
            100 => 
            [
                'id' => 601,
                'name' => 'Bas-Sassandra',
                'code' => '76',
                'country_id' => 39,
            ],
            101 => 
            [
                'id' => 602,
                'name' => '',
                'code' => '03',
                'country_id' => 39,
            ],
            102 => 
            [
                'id' => 603,
                'name' => 'Marahoue',
                'code' => '83',
                'country_id' => 39,
            ],
            103 => 
            [
                'id' => 604,
                'name' => '',
                'code' => '17',
                'country_id' => 39,
            ],
            104 => 
            [
                'id' => 605,
                'name' => '',
                'code' => '11',
                'country_id' => 39,
            ],
            105 => 
            [
                'id' => 606,
                'name' => '',
                'code' => '41',
                'country_id' => 39,
            ],
            106 => 
            [
                'id' => 607,
                'name' => '',
                'code' => '26',
                'country_id' => 39,
            ],
            107 => 
            [
                'id' => 608,
                'name' => 'Bafing',
                'code' => '75',
                'country_id' => 39,
            ],
            108 => 
            [
                'id' => 609,
                'name' => '',
                'code' => '47',
                'country_id' => 39,
            ],
            109 => 
            [
                'id' => 610,
                'name' => '',
                'code' => '46',
                'country_id' => 39,
            ],
            110 => 
            [
                'id' => 611,
                'name' => '',
                'code' => '23',
                'country_id' => 39,
            ],
            111 => 
            [
                'id' => 612,
                'name' => '',
                'code' => '21',
                'country_id' => 39,
            ],
            112 => 
            [
                'id' => 613,
                'name' => '',
                'code' => '14',
                'country_id' => 39,
            ],
            113 => 
            [
                'id' => 614,
                'name' => 'Savanes',
                'code' => '87',
                'country_id' => 39,
            ],
            114 => 
            [
                'id' => 615,
                'name' => '',
                'code' => '07',
                'country_id' => 39,
            ],
            115 => 
            [
                'id' => 616,
                'name' => '',
                'code' => '69',
                'country_id' => 39,
            ],
            116 => 
            [
                'id' => 617,
                'name' => '',
                'code' => '52',
                'country_id' => 39,
            ],
            117 => 
            [
                'id' => 618,
                'name' => '',
                'code' => '12',
                'country_id' => 39,
            ],
            118 => 
            [
                'id' => 619,
                'name' => 'Sud-Bandama',
                'code' => '88',
                'country_id' => 39,
            ],
            119 => 
            [
                'id' => 620,
                'name' => 'Haut-Sassandra',
                'code' => '80',
                'country_id' => 39,
            ],
            120 => 
            [
                'id' => 621,
                'name' => 'Moyen-Cavally',
                'code' => '84',
                'country_id' => 39,
            ],
            121 => 
            [
                'id' => 622,
                'name' => 'Dix-Huit Montagnes',
                'code' => '78',
                'country_id' => 39,
            ],
            122 => 
            [
                'id' => 623,
                'name' => 'Denguele',
                'code' => '77',
                'country_id' => 39,
            ],
            123 => 
            [
                'id' => 624,
                'name' => 'Worodougou',
                'code' => '91',
                'country_id' => 39,
            ],
            124 => 
            [
                'id' => 625,
                'name' => '',
                'code' => '00',
                'country_id' => 40,
            ],
            125 => 
            [
                'id' => 626,
                'name' => 'Bio-Bio',
                'code' => '06',
                'country_id' => 41,
            ],
            126 => 
            [
                'id' => 627,
                'name' => 'Maule',
                'code' => '11',
                'country_id' => 41,
            ],
            127 => 
            [
                'id' => 628,
                'name' => 'Los Lagos',
                'code' => '09',
                'country_id' => 41,
            ],
            128 => 
            [
                'id' => 629,
                'name' => 'Tarapaca',
                'code' => '13',
                'country_id' => 41,
            ],
            129 => 
            [
                'id' => 630,
                'name' => 'Valparaiso',
                'code' => '01',
                'country_id' => 41,
            ],
            130 => 
            [
                'id' => 631,
                'name' => 'Atacama',
                'code' => '05',
                'country_id' => 41,
            ],
            131 => 
            [
                'id' => 632,
                'name' => 'Coquimbo',
                'code' => '07',
                'country_id' => 41,
            ],
            132 => 
            [
                'id' => 633,
                'name' => 'Libertador General Bernardo O\'Higgins',
                'code' => '08',
                'country_id' => 41,
            ],
            133 => 
            [
                'id' => 634,
                'name' => 'Antofagasta',
                'code' => '03',
                'country_id' => 41,
            ],
            134 => 
            [
                'id' => 635,
                'name' => 'Araucania',
                'code' => '04',
                'country_id' => 41,
            ],
            135 => 
            [
                'id' => 636,
                'name' => 'Aisen del General Carlos Ibanez del Campo',
                'code' => '02',
                'country_id' => 41,
            ],
            136 => 
            [
                'id' => 637,
                'name' => 'Region Metropolitana',
                'code' => '12',
                'country_id' => 41,
            ],
            137 => 
            [
                'id' => 638,
                'name' => 'Magallanes y de la Antartica Chilena',
                'code' => '10',
                'country_id' => 41,
            ],
            138 => 
            [
                'id' => 639,
                'name' => 'Est',
                'code' => '04',
                'country_id' => 42,
            ],
            139 => 
            [
                'id' => 640,
                'name' => 'Adamaoua',
                'code' => '10',
                'country_id' => 42,
            ],
            140 => 
            [
                'id' => 641,
                'name' => 'Centre',
                'code' => '11',
                'country_id' => 42,
            ],
            141 => 
            [
                'id' => 642,
                'name' => 'Sud',
                'code' => '14',
                'country_id' => 42,
            ],
            142 => 
            [
                'id' => 643,
                'name' => 'Nord-Ouest',
                'code' => '07',
                'country_id' => 42,
            ],
            143 => 
            [
                'id' => 644,
                'name' => 'Extreme-Nord',
                'code' => '12',
                'country_id' => 42,
            ],
            144 => 
            [
                'id' => 645,
                'name' => 'Sud-Ouest',
                'code' => '09',
                'country_id' => 42,
            ],
            145 => 
            [
                'id' => 646,
                'name' => 'Littoral',
                'code' => '05',
                'country_id' => 42,
            ],
            146 => 
            [
                'id' => 647,
                'name' => 'Nord',
                'code' => '13',
                'country_id' => 42,
            ],
            147 => 
            [
                'id' => 648,
                'name' => 'Ouest',
                'code' => '08',
                'country_id' => 42,
            ],
            148 => 
            [
                'id' => 649,
                'name' => 'Sichuan',
                'code' => '32',
                'country_id' => 43,
            ],
            149 => 
            [
                'id' => 650,
                'name' => 'Xinjiang',
                'code' => '13',
                'country_id' => 43,
            ],
            150 => 
            [
                'id' => 651,
                'name' => 'Nei Mongol',
                'code' => '20',
                'country_id' => 43,
            ],
            151 => 
            [
                'id' => 652,
                'name' => 'Yunnan',
                'code' => '29',
                'country_id' => 43,
            ],
            152 => 
            [
                'id' => 653,
                'name' => 'Guizhou',
                'code' => '18',
                'country_id' => 43,
            ],
            153 => 
            [
                'id' => 654,
                'name' => 'Heilongjiang',
                'code' => '08',
                'country_id' => 43,
            ],
            154 => 
            [
                'id' => 655,
                'name' => 'Shandong',
                'code' => '25',
                'country_id' => 43,
            ],
            155 => 
            [
                'id' => 656,
                'name' => 'Liaoning',
                'code' => '19',
                'country_id' => 43,
            ],
            156 => 
            [
                'id' => 657,
                'name' => 'Shaanxi',
                'code' => '26',
                'country_id' => 43,
            ],
            157 => 
            [
                'id' => 658,
                'name' => 'Qinghai',
                'code' => '06',
                'country_id' => 43,
            ],
            158 => 
            [
                'id' => 659,
                'name' => 'Gansu',
                'code' => '15',
                'country_id' => 43,
            ],
            159 => 
            [
                'id' => 660,
                'name' => 'Jiangsu',
                'code' => '04',
                'country_id' => 43,
            ],
            160 => 
            [
                'id' => 661,
                'name' => 'Fujian',
                'code' => '07',
                'country_id' => 43,
            ],
            161 => 
            [
                'id' => 662,
                'name' => 'Hunan',
                'code' => '11',
                'country_id' => 43,
            ],
            162 => 
            [
                'id' => 663,
                'name' => 'Jiangxi',
                'code' => '03',
                'country_id' => 43,
            ],
            163 => 
            [
                'id' => 664,
                'name' => 'Guangxi',
                'code' => '16',
                'country_id' => 43,
            ],
            164 => 
            [
                'id' => 665,
                'name' => 'Zhejiang',
                'code' => '02',
                'country_id' => 43,
            ],
            165 => 
            [
                'id' => 666,
                'name' => 'Hebei',
                'code' => '10',
                'country_id' => 43,
            ],
            166 => 
            [
                'id' => 667,
                'name' => 'Hubei',
                'code' => '12',
                'country_id' => 43,
            ],
            167 => 
            [
                'id' => 668,
                'name' => 'Anhui',
                'code' => '01',
                'country_id' => 43,
            ],
            168 => 
            [
                'id' => 669,
                'name' => 'Tianjin',
                'code' => '28',
                'country_id' => 43,
            ],
            169 => 
            [
                'id' => 670,
                'name' => 'Hainan',
                'code' => '31',
                'country_id' => 43,
            ],
            170 => 
            [
                'id' => 671,
                'name' => 'Guangdong',
                'code' => '30',
                'country_id' => 43,
            ],
            171 => 
            [
                'id' => 672,
                'name' => 'Xizang',
                'code' => '14',
                'country_id' => 43,
            ],
            172 => 
            [
                'id' => 673,
                'name' => 'Jilin',
                'code' => '05',
                'country_id' => 43,
            ],
            173 => 
            [
                'id' => 674,
                'name' => 'Chongqing',
                'code' => '33',
                'country_id' => 43,
            ],
            174 => 
            [
                'id' => 675,
                'name' => 'Beijing',
                'code' => '22',
                'country_id' => 43,
            ],
            175 => 
            [
                'id' => 676,
                'name' => 'Shanxi',
                'code' => '24',
                'country_id' => 43,
            ],
            176 => 
            [
                'id' => 677,
                'name' => 'Shanghai',
                'code' => '23',
                'country_id' => 43,
            ],
            177 => 
            [
                'id' => 678,
                'name' => 'Henan',
                'code' => '09',
                'country_id' => 43,
            ],
            178 => 
            [
                'id' => 679,
                'name' => 'Ningxia',
                'code' => '21',
                'country_id' => 43,
            ],
            179 => 
            [
                'id' => 680,
                'name' => 'Cundinamarca',
                'code' => '33',
                'country_id' => 44,
            ],
            180 => 
            [
                'id' => 681,
                'name' => 'Norte de Santander',
                'code' => '21',
                'country_id' => 44,
            ],
            181 => 
            [
                'id' => 682,
                'name' => 'Narino',
                'code' => '20',
                'country_id' => 44,
            ],
            182 => 
            [
                'id' => 683,
                'name' => '',
                'code' => '18',
                'country_id' => 44,
            ],
            183 => 
            [
                'id' => 684,
                'name' => 'Risaralda',
                'code' => '24',
                'country_id' => 44,
            ],
            184 => 
            [
                'id' => 685,
                'name' => 'Antioquia',
                'code' => '02',
                'country_id' => 44,
            ],
            185 => 
            [
                'id' => 686,
                'name' => 'Amazonas',
                'code' => '01',
                'country_id' => 44,
            ],
            186 => 
            [
                'id' => 687,
                'name' => 'La Guajira',
                'code' => '17',
                'country_id' => 44,
            ],
            187 => 
            [
                'id' => 688,
                'name' => 'Choco',
                'code' => '11',
                'country_id' => 44,
            ],
            188 => 
            [
                'id' => 689,
                'name' => 'Cauca',
                'code' => '09',
                'country_id' => 44,
            ],
            189 => 
            [
                'id' => 690,
                'name' => 'Valle del Cauca',
                'code' => '29',
                'country_id' => 44,
            ],
            190 => 
            [
                'id' => 691,
                'name' => 'Arauca',
                'code' => '03',
                'country_id' => 44,
            ],
            191 => 
            [
                'id' => 692,
                'name' => 'Meta',
                'code' => '19',
                'country_id' => 44,
            ],
            192 => 
            [
                'id' => 693,
                'name' => 'Caqueta',
                'code' => '08',
                'country_id' => 44,
            ],
            193 => 
            [
                'id' => 694,
                'name' => 'Casanare',
                'code' => '32',
                'country_id' => 44,
            ],
            194 => 
            [
                'id' => 695,
                'name' => 'Vaupes',
                'code' => '30',
                'country_id' => 44,
            ],
            195 => 
            [
                'id' => 696,
                'name' => 'Tolima',
                'code' => '28',
                'country_id' => 44,
            ],
            196 => 
            [
                'id' => 697,
                'name' => 'Huila',
                'code' => '16',
                'country_id' => 44,
            ],
            197 => 
            [
                'id' => 698,
                'name' => '',
                'code' => '05',
                'country_id' => 44,
            ],
            198 => 
            [
                'id' => 699,
                'name' => 'Atlantico',
                'code' => '04',
                'country_id' => 44,
            ],
            199 => 
            [
                'id' => 700,
                'name' => 'Cordoba',
                'code' => '12',
                'country_id' => 44,
            ],
            200 => 
            [
                'id' => 701,
                'name' => 'Santander',
                'code' => '26',
                'country_id' => 44,
            ],
            201 => 
            [
                'id' => 702,
                'name' => 'Cesar',
                'code' => '10',
                'country_id' => 44,
            ],
            202 => 
            [
                'id' => 703,
                'name' => 'Sucre',
                'code' => '27',
                'country_id' => 44,
            ],
            203 => 
            [
                'id' => 704,
                'name' => '',
                'code' => '06',
                'country_id' => 44,
            ],
            204 => 
            [
                'id' => 705,
                'name' => 'Putumayo',
                'code' => '22',
                'country_id' => 44,
            ],
            205 => 
            [
                'id' => 706,
                'name' => '',
                'code' => '07',
                'country_id' => 44,
            ],
            206 => 
            [
                'id' => 707,
                'name' => 'Guaviare',
                'code' => '14',
                'country_id' => 44,
            ],
            207 => 
            [
                'id' => 708,
                'name' => 'San Andres y Providencia',
                'code' => '25',
                'country_id' => 44,
            ],
            208 => 
            [
                'id' => 709,
                'name' => 'Vichada',
                'code' => '31',
                'country_id' => 44,
            ],
            209 => 
            [
                'id' => 710,
                'name' => 'Quindio',
                'code' => '23',
                'country_id' => 44,
            ],
            210 => 
            [
                'id' => 711,
                'name' => 'Guainia',
                'code' => '15',
                'country_id' => 44,
            ],
            211 => 
            [
                'id' => 712,
                'name' => 'Distrito Especial',
                'code' => '34',
                'country_id' => 44,
            ],
            212 => 
            [
                'id' => 713,
                'name' => 'Guanacaste',
                'code' => '03',
                'country_id' => 45,
            ],
            213 => 
            [
                'id' => 714,
                'name' => 'Limon',
                'code' => '06',
                'country_id' => 45,
            ],
            214 => 
            [
                'id' => 715,
                'name' => 'Puntarenas',
                'code' => '07',
                'country_id' => 45,
            ],
            215 => 
            [
                'id' => 716,
                'name' => 'Alajuela',
                'code' => '01',
                'country_id' => 45,
            ],
            216 => 
            [
                'id' => 717,
                'name' => 'Heredia',
                'code' => '04',
                'country_id' => 45,
            ],
            217 => 
            [
                'id' => 718,
                'name' => 'San Jose',
                'code' => '08',
                'country_id' => 45,
            ],
            218 => 
            [
                'id' => 719,
                'name' => 'Cartago',
                'code' => '02',
                'country_id' => 45,
            ],
            219 => 
            [
                'id' => 720,
                'name' => 'Cienfuegos',
                'code' => '08',
                'country_id' => 46,
            ],
            220 => 
            [
                'id' => 721,
                'name' => 'La Habana',
                'code' => '11',
                'country_id' => 46,
            ],
            221 => 
            [
                'id' => 722,
                'name' => 'Santiago de Cuba',
                'code' => '15',
                'country_id' => 46,
            ],
            222 => 
            [
                'id' => 723,
                'name' => 'Camaguey',
                'code' => '05',
                'country_id' => 46,
            ],
            223 => 
            [
                'id' => 724,
                'name' => 'Ciudad de la Habana',
                'code' => '02',
                'country_id' => 46,
            ],
            224 => 
            [
                'id' => 725,
                'name' => 'Villa Clara',
                'code' => '16',
                'country_id' => 46,
            ],
            225 => 
            [
                'id' => 726,
                'name' => 'Pinar del Rio',
                'code' => '01',
                'country_id' => 46,
            ],
            226 => 
            [
                'id' => 727,
                'name' => 'Matanzas',
                'code' => '03',
                'country_id' => 46,
            ],
            227 => 
            [
                'id' => 728,
                'name' => 'Guantanamo',
                'code' => '10',
                'country_id' => 46,
            ],
            228 => 
            [
                'id' => 729,
                'name' => 'Las Tunas',
                'code' => '13',
                'country_id' => 46,
            ],
            229 => 
            [
                'id' => 730,
                'name' => 'Ciego de Avila',
                'code' => '07',
                'country_id' => 46,
            ],
            230 => 
            [
                'id' => 731,
                'name' => 'Sancti Spiritus',
                'code' => '14',
                'country_id' => 46,
            ],
            231 => 
            [
                'id' => 732,
                'name' => 'Holguin',
                'code' => '12',
                'country_id' => 46,
            ],
            232 => 
            [
                'id' => 733,
                'name' => 'Granma',
                'code' => '09',
                'country_id' => 46,
            ],
            233 => 
            [
                'id' => 734,
                'name' => 'Isla de la Juventud',
                'code' => '04',
                'country_id' => 46,
            ],
            234 => 
            [
                'id' => 735,
                'name' => 'Sao Domingos',
                'code' => '17',
                'country_id' => 47,
            ],
            235 => 
            [
                'id' => 736,
                'name' => '',
                'code' => '00',
                'country_id' => 47,
            ],
            236 => 
            [
                'id' => 737,
                'name' => '',
                'code' => '00',
                'country_id' => 48,
            ],
            237 => 
            [
                'id' => 738,
                'name' => 'Limassol',
                'code' => '05',
                'country_id' => 49,
            ],
            238 => 
            [
                'id' => 739,
                'name' => 'Nicosia',
                'code' => '04',
                'country_id' => 49,
            ],
            239 => 
            [
                'id' => 740,
                'name' => 'Paphos',
                'code' => '06',
                'country_id' => 49,
            ],
            240 => 
            [
                'id' => 741,
                'name' => 'Famagusta',
                'code' => '01',
                'country_id' => 49,
            ],
            241 => 
            [
                'id' => 742,
                'name' => 'Larnaca',
                'code' => '03',
                'country_id' => 49,
            ],
            242 => 
            [
                'id' => 743,
                'name' => 'Kyrenia',
                'code' => '02',
                'country_id' => 49,
            ],
            243 => 
            [
                'id' => 744,
                'name' => 'Karlovarsky kraj',
                'code' => '81',
                'country_id' => 50,
            ],
            244 => 
            [
                'id' => 745,
                'name' => 'Pardubicky kraj',
                'code' => '86',
                'country_id' => 50,
            ],
            245 => 
            [
                'id' => 746,
                'name' => '',
                'code' => '30',
                'country_id' => 50,
            ],
            246 => 
            [
                'id' => 747,
                'name' => 'Jihomoravsky kraj',
                'code' => '78',
                'country_id' => 50,
            ],
            247 => 
            [
                'id' => 748,
                'name' => 'Jihocesky kraj',
                'code' => '79',
                'country_id' => 50,
            ],
            248 => 
            [
                'id' => 749,
                'name' => 'Olomoucky kraj',
                'code' => '84',
                'country_id' => 50,
            ],
            249 => 
            [
                'id' => 750,
                'name' => 'Moravskoslezsky kraj',
                'code' => '85',
                'country_id' => 50,
            ],
            250 => 
            [
                'id' => 751,
                'name' => '',
                'code' => '70',
                'country_id' => 50,
            ],
            251 => 
            [
                'id' => 752,
                'name' => 'Kralovehradecky kraj',
                'code' => '82',
                'country_id' => 50,
            ],
            252 => 
            [
                'id' => 753,
                'name' => 'Ustecky kraj',
                'code' => '89',
                'country_id' => 50,
            ],
            253 => 
            [
                'id' => 754,
                'name' => 'Stredocesky kraj',
                'code' => '88',
                'country_id' => 50,
            ],
            254 => 
            [
                'id' => 755,
                'name' => 'Vysocina',
                'code' => '80',
                'country_id' => 50,
            ],
            255 => 
            [
                'id' => 756,
                'name' => 'Plzensky kraj',
                'code' => '87',
                'country_id' => 50,
            ],
            256 => 
            [
                'id' => 757,
                'name' => '',
                'code' => '33',
                'country_id' => 50,
            ],
            257 => 
            [
                'id' => 758,
                'name' => '',
                'code' => '21',
                'country_id' => 50,
            ],
            258 => 
            [
                'id' => 759,
                'name' => '',
                'code' => '20',
                'country_id' => 50,
            ],
            259 => 
            [
                'id' => 760,
                'name' => 'Zlinsky kraj',
                'code' => '90',
                'country_id' => 50,
            ],
            260 => 
            [
                'id' => 761,
                'name' => 'Hlavni mesto Praha',
                'code' => '52',
                'country_id' => 50,
            ],
            261 => 
            [
                'id' => 762,
                'name' => '',
                'code' => '45',
                'country_id' => 50,
            ],
            262 => 
            [
                'id' => 763,
                'name' => 'Liberecky kraj',
                'code' => '83',
                'country_id' => 50,
            ],
            263 => 
            [
                'id' => 764,
                'name' => '',
                'code' => '23',
                'country_id' => 50,
            ],
            264 => 
            [
                'id' => 765,
                'name' => '',
                'code' => '41',
                'country_id' => 50,
            ],
            265 => 
            [
                'id' => 766,
                'name' => '',
                'code' => '61',
                'country_id' => 50,
            ],
            266 => 
            [
                'id' => 767,
                'name' => '',
                'code' => '36',
                'country_id' => 50,
            ],
            267 => 
            [
                'id' => 768,
                'name' => '',
                'code' => '39',
                'country_id' => 50,
            ],
            268 => 
            [
                'id' => 769,
                'name' => '',
                'code' => '37',
                'country_id' => 50,
            ],
            269 => 
            [
                'id' => 770,
                'name' => '',
                'code' => '73',
                'country_id' => 50,
            ],
            270 => 
            [
                'id' => 771,
                'name' => '',
                'code' => '00',
                'country_id' => 50,
            ],
            271 => 
            [
                'id' => 772,
                'name' => '',
                'code' => '24',
                'country_id' => 50,
            ],
            272 => 
            [
                'id' => 773,
                'name' => 'Nordrhein-Westfalen',
                'code' => '07',
                'country_id' => 51,
            ],
            273 => 
            [
                'id' => 774,
                'name' => 'Baden-Wurttemberg',
                'code' => '01',
                'country_id' => 51,
            ],
            274 => 
            [
                'id' => 775,
                'name' => 'Bayern',
                'code' => '02',
                'country_id' => 51,
            ],
            275 => 
            [
                'id' => 776,
                'name' => 'Rheinland-Pfalz',
                'code' => '08',
                'country_id' => 51,
            ],
            276 => 
            [
                'id' => 777,
                'name' => 'Niedersachsen',
                'code' => '06',
                'country_id' => 51,
            ],
            277 => 
            [
                'id' => 778,
                'name' => 'Schleswig-Holstein',
                'code' => '10',
                'country_id' => 51,
            ],
            278 => 
            [
                'id' => 779,
                'name' => 'Brandenburg',
                'code' => '11',
                'country_id' => 51,
            ],
            279 => 
            [
                'id' => 780,
                'name' => 'Sachsen-Anhalt',
                'code' => '14',
                'country_id' => 51,
            ],
            280 => 
            [
                'id' => 781,
                'name' => 'Sachsen',
                'code' => '13',
                'country_id' => 51,
            ],
            281 => 
            [
                'id' => 782,
                'name' => 'Thuringen',
                'code' => '15',
                'country_id' => 51,
            ],
            282 => 
            [
                'id' => 783,
                'name' => 'Hessen',
                'code' => '05',
                'country_id' => 51,
            ],
            283 => 
            [
                'id' => 784,
                'name' => 'Mecklenburg-Vorpommern',
                'code' => '12',
                'country_id' => 51,
            ],
            284 => 
            [
                'id' => 785,
                'name' => 'Hamburg',
                'code' => '04',
                'country_id' => 51,
            ],
            285 => 
            [
                'id' => 786,
                'name' => 'Berlin',
                'code' => '16',
                'country_id' => 51,
            ],
            286 => 
            [
                'id' => 787,
                'name' => 'Saarland',
                'code' => '09',
                'country_id' => 51,
            ],
            287 => 
            [
                'id' => 788,
                'name' => 'Bremen',
                'code' => '03',
                'country_id' => 51,
            ],
            288 => 
            [
                'id' => 789,
                'name' => 'Ali Sabieh',
                'code' => '01',
                'country_id' => 52,
            ],
            289 => 
            [
                'id' => 790,
                'name' => 'Tadjoura',
                'code' => '05',
                'country_id' => 52,
            ],
            290 => 
            [
                'id' => 791,
                'name' => '',
                'code' => '02',
                'country_id' => 52,
            ],
            291 => 
            [
                'id' => 792,
                'name' => 'Obock',
                'code' => '04',
                'country_id' => 52,
            ],
            292 => 
            [
                'id' => 793,
                'name' => '',
                'code' => '03',
                'country_id' => 52,
            ],
            293 => 
            [
                'id' => 794,
                'name' => 'Arta',
                'code' => '08',
                'country_id' => 52,
            ],
            294 => 
            [
                'id' => 795,
                'name' => 'Dikhil',
                'code' => '06',
                'country_id' => 52,
            ],
            295 => 
            [
                'id' => 796,
                'name' => 'Syddanmark',
                'code' => '21',
                'country_id' => 53,
            ],
            296 => 
            [
                'id' => 797,
                'name' => 'Midtjylland',
                'code' => '18',
                'country_id' => 53,
            ],
            297 => 
            [
                'id' => 798,
                'name' => 'Nordjylland',
                'code' => '19',
                'country_id' => 53,
            ],
            298 => 
            [
                'id' => 799,
                'name' => 'Sjelland',
                'code' => '20',
                'country_id' => 53,
            ],
            299 => 
            [
                'id' => 800,
                'name' => 'Hovedstaden',
                'code' => '17',
                'country_id' => 53,
            ],
            300 => 
            [
                'id' => 801,
                'name' => 'Saint Andrew',
                'code' => '02',
                'country_id' => 54,
            ],
            301 => 
            [
                'id' => 802,
                'name' => 'Saint David',
                'code' => '03',
                'country_id' => 54,
            ],
            302 => 
            [
                'id' => 803,
                'name' => 'Saint Joseph',
                'code' => '06',
                'country_id' => 54,
            ],
            303 => 
            [
                'id' => 804,
                'name' => 'Saint George',
                'code' => '04',
                'country_id' => 54,
            ],
            304 => 
            [
                'id' => 805,
                'name' => 'Saint Patrick',
                'code' => '09',
                'country_id' => 54,
            ],
            305 => 
            [
                'id' => 806,
                'name' => 'Saint Peter',
                'code' => '11',
                'country_id' => 54,
            ],
            306 => 
            [
                'id' => 807,
                'name' => 'Saint John',
                'code' => '05',
                'country_id' => 54,
            ],
            307 => 
            [
                'id' => 808,
                'name' => 'Saint Mark',
                'code' => '08',
                'country_id' => 54,
            ],
            308 => 
            [
                'id' => 809,
                'name' => 'Saint Paul',
                'code' => '10',
                'country_id' => 54,
            ],
            309 => 
            [
                'id' => 810,
                'name' => 'Saint Luke',
                'code' => '07',
                'country_id' => 54,
            ],
            310 => 
            [
                'id' => 811,
                'name' => 'Sanchez Ramirez',
                'code' => '21',
                'country_id' => 55,
            ],
            311 => 
            [
                'id' => 812,
                'name' => 'Espaillat',
                'code' => '08',
                'country_id' => 55,
            ],
            312 => 
            [
                'id' => 813,
                'name' => 'Duarte',
                'code' => '06',
                'country_id' => 55,
            ],
            313 => 
            [
                'id' => 814,
                'name' => 'Samana',
                'code' => '20',
                'country_id' => 55,
            ],
            314 => 
            [
                'id' => 815,
                'name' => 'Maria Trinidad Sanchez',
                'code' => '14',
                'country_id' => 55,
            ],
            315 => 
            [
                'id' => 816,
                'name' => 'La Romana',
                'code' => '12',
                'country_id' => 55,
            ],
            316 => 
            [
                'id' => 817,
                'name' => 'Azua',
                'code' => '01',
                'country_id' => 55,
            ],
            317 => 
            [
                'id' => 818,
                'name' => 'San Cristobal',
                'code' => '33',
                'country_id' => 55,
            ],
            318 => 
            [
                'id' => 819,
                'name' => 'El Seibo',
                'code' => '28',
                'country_id' => 55,
            ],
            319 => 
            [
                'id' => 820,
                'name' => 'Monte Plata',
                'code' => '32',
                'country_id' => 55,
            ],
            320 => 
            [
                'id' => 821,
                'name' => 'Distrito Nacional',
                'code' => '05',
                'country_id' => 55,
            ],
            321 => 
            [
                'id' => 822,
                'name' => 'Elias Pina',
                'code' => '11',
                'country_id' => 55,
            ],
            322 => 
            [
                'id' => 823,
                'name' => 'Santiago',
                'code' => '25',
                'country_id' => 55,
            ],
            323 => 
            [
                'id' => 824,
                'name' => 'Santiago Rodriguez',
                'code' => '26',
                'country_id' => 55,
            ],
            324 => 
            [
                'id' => 825,
                'name' => 'Peravia',
                'code' => '17',
                'country_id' => 55,
            ],
            325 => 
            [
                'id' => 826,
                'name' => 'Monte Cristi',
                'code' => '15',
                'country_id' => 55,
            ],
            326 => 
            [
                'id' => 827,
                'name' => 'Salcedo',
                'code' => '19',
                'country_id' => 55,
            ],
            327 => 
            [
                'id' => 828,
                'name' => 'Puerto Plata',
                'code' => '18',
                'country_id' => 55,
            ],
            328 => 
            [
                'id' => 829,
                'name' => 'San Pedro De Macoris',
                'code' => '24',
                'country_id' => 55,
            ],
            329 => 
            [
                'id' => 830,
                'name' => 'Pedernales',
                'code' => '16',
                'country_id' => 55,
            ],
            330 => 
            [
                'id' => 831,
                'name' => 'Independencia',
                'code' => '09',
                'country_id' => 55,
            ],
            331 => 
            [
                'id' => 832,
                'name' => 'La Vega',
                'code' => '30',
                'country_id' => 55,
            ],
            332 => 
            [
                'id' => 833,
                'name' => 'Dajabon',
                'code' => '04',
                'country_id' => 55,
            ],
            333 => 
            [
                'id' => 834,
                'name' => 'Hato Mayor',
                'code' => '29',
                'country_id' => 55,
            ],
            334 => 
            [
                'id' => 835,
                'name' => 'Barahona',
                'code' => '03',
                'country_id' => 55,
            ],
            335 => 
            [
                'id' => 836,
                'name' => 'San Juan',
                'code' => '23',
                'country_id' => 55,
            ],
            336 => 
            [
                'id' => 837,
                'name' => 'La Altagracia',
                'code' => '10',
                'country_id' => 55,
            ],
            337 => 
            [
                'id' => 838,
                'name' => 'Valverde',
                'code' => '27',
                'country_id' => 55,
            ],
            338 => 
            [
                'id' => 839,
                'name' => 'Baoruco',
                'code' => '02',
                'country_id' => 55,
            ],
            339 => 
            [
                'id' => 840,
                'name' => 'Monsenor Nouel',
                'code' => '31',
                'country_id' => 55,
            ],
            340 => 
            [
                'id' => 841,
                'name' => 'Ain Temouchent',
                'code' => '36',
                'country_id' => 56,
            ],
            341 => 
            [
                'id' => 842,
                'name' => 'Oran',
                'code' => '09',
                'country_id' => 56,
            ],
            342 => 
            [
                'id' => 843,
                'name' => 'Medea',
                'code' => '06',
                'country_id' => 56,
            ],
            343 => 
            [
                'id' => 844,
                'name' => 'Chlef',
                'code' => '41',
                'country_id' => 56,
            ],
            344 => 
            [
                'id' => 845,
                'name' => 'Bechar',
                'code' => '38',
                'country_id' => 56,
            ],
            345 => 
            [
                'id' => 846,
                'name' => 'Tamanghasset',
                'code' => '53',
                'country_id' => 56,
            ],
            346 => 
            [
                'id' => 847,
                'name' => 'Bejaia',
                'code' => '18',
                'country_id' => 56,
            ],
            347 => 
            [
                'id' => 848,
                'name' => 'Tizi Ouzou',
                'code' => '14',
                'country_id' => 56,
            ],
            348 => 
            [
                'id' => 849,
                'name' => 'Boumerdes',
                'code' => '40',
                'country_id' => 56,
            ],
            349 => 
            [
                'id' => 850,
                'name' => 'Ain Defla',
                'code' => '35',
                'country_id' => 56,
            ],
            350 => 
            [
                'id' => 851,
                'name' => 'Annaba',
                'code' => '37',
                'country_id' => 56,
            ],
            351 => 
            [
                'id' => 852,
                'name' => 'Setif',
                'code' => '12',
                'country_id' => 56,
            ],
            352 => 
            [
                'id' => 853,
                'name' => 'Relizane',
                'code' => '51',
                'country_id' => 56,
            ],
            353 => 
            [
                'id' => 854,
                'name' => 'Mascara',
                'code' => '26',
                'country_id' => 56,
            ],
            354 => 
            [
                'id' => 855,
                'name' => 'Mostaganem',
                'code' => '07',
                'country_id' => 56,
            ],
            355 => 
            [
                'id' => 856,
                'name' => 'Tiaret',
                'code' => '13',
                'country_id' => 56,
            ],
            356 => 
            [
                'id' => 857,
                'name' => 'Bordj Bou Arreridj',
                'code' => '39',
                'country_id' => 56,
            ],
            357 => 
            [
                'id' => 858,
                'name' => 'Tipaza',
                'code' => '55',
                'country_id' => 56,
            ],
            358 => 
            [
                'id' => 859,
                'name' => '',
                'code' => '00',
                'country_id' => 56,
            ],
            359 => 
            [
                'id' => 860,
                'name' => 'Bouira',
                'code' => '21',
                'country_id' => 56,
            ],
            360 => 
            [
                'id' => 861,
                'name' => 'Tissemsilt',
                'code' => '56',
                'country_id' => 56,
            ],
            361 => 
            [
                'id' => 862,
                'name' => 'Jijel',
                'code' => '24',
                'country_id' => 56,
            ],
            362 => 
            [
                'id' => 863,
                'name' => 'Saida',
                'code' => '10',
                'country_id' => 56,
            ],
            363 => 
            [
                'id' => 864,
                'name' => 'Illizi',
                'code' => '46',
                'country_id' => 56,
            ],
            364 => 
            [
                'id' => 865,
                'name' => 'Tlemcen',
                'code' => '15',
                'country_id' => 56,
            ],
            365 => 
            [
                'id' => 866,
                'name' => 'Adrar',
                'code' => '34',
                'country_id' => 56,
            ],
            366 => 
            [
                'id' => 867,
                'name' => 'Laghouat',
                'code' => '25',
                'country_id' => 56,
            ],
            367 => 
            [
                'id' => 868,
                'name' => 'Constantine',
                'code' => '04',
                'country_id' => 56,
            ],
            368 => 
            [
                'id' => 869,
                'name' => 'Khenchela',
                'code' => '47',
                'country_id' => 56,
            ],
            369 => 
            [
                'id' => 870,
                'name' => 'Batna',
                'code' => '03',
                'country_id' => 56,
            ],
            370 => 
            [
                'id' => 871,
                'name' => 'Alger',
                'code' => '01',
                'country_id' => 56,
            ],
            371 => 
            [
                'id' => 872,
                'name' => 'M\'sila',
                'code' => '27',
                'country_id' => 56,
            ],
            372 => 
            [
                'id' => 873,
                'name' => 'Skikda',
                'code' => '31',
                'country_id' => 56,
            ],
            373 => 
            [
                'id' => 874,
                'name' => 'Oum el Bouaghi',
                'code' => '29',
                'country_id' => 56,
            ],
            374 => 
            [
                'id' => 875,
                'name' => 'Naama',
                'code' => '49',
                'country_id' => 56,
            ],
            375 => 
            [
                'id' => 876,
                'name' => 'Sidi Bel Abbes',
                'code' => '30',
                'country_id' => 56,
            ],
            376 => 
            [
                'id' => 877,
                'name' => 'Mila',
                'code' => '48',
                'country_id' => 56,
            ],
            377 => 
            [
                'id' => 878,
                'name' => 'Ouargla',
                'code' => '50',
                'country_id' => 56,
            ],
            378 => 
            [
                'id' => 879,
                'name' => 'Djelfa',
                'code' => '22',
                'country_id' => 56,
            ],
            379 => 
            [
                'id' => 880,
                'name' => 'El Bayadh',
                'code' => '42',
                'country_id' => 56,
            ],
            380 => 
            [
                'id' => 881,
                'name' => 'Souk Ahras',
                'code' => '52',
                'country_id' => 56,
            ],
            381 => 
            [
                'id' => 882,
                'name' => 'El Oued',
                'code' => '43',
                'country_id' => 56,
            ],
            382 => 
            [
                'id' => 883,
                'name' => 'Blida',
                'code' => '20',
                'country_id' => 56,
            ],
            383 => 
            [
                'id' => 884,
                'name' => 'Biskra',
                'code' => '19',
                'country_id' => 56,
            ],
            384 => 
            [
                'id' => 885,
                'name' => 'Tebessa',
                'code' => '33',
                'country_id' => 56,
            ],
            385 => 
            [
                'id' => 886,
                'name' => 'Guelma',
                'code' => '23',
                'country_id' => 56,
            ],
            386 => 
            [
                'id' => 887,
                'name' => 'Tindouf',
                'code' => '54',
                'country_id' => 56,
            ],
            387 => 
            [
                'id' => 888,
                'name' => 'Ghardaia',
                'code' => '45',
                'country_id' => 56,
            ],
            388 => 
            [
                'id' => 889,
                'name' => 'Manabi',
                'code' => '14',
                'country_id' => 57,
            ],
            389 => 
            [
                'id' => 890,
                'name' => 'Zamora-Chinchipe',
                'code' => '20',
                'country_id' => 57,
            ],
            390 => 
            [
                'id' => 891,
                'name' => 'Morona-Santiago',
                'code' => '15',
                'country_id' => 57,
            ],
            391 => 
            [
                'id' => 892,
                'name' => 'El Oro',
                'code' => '08',
                'country_id' => 57,
            ],
            392 => 
            [
                'id' => 893,
                'name' => 'Azuay',
                'code' => '02',
                'country_id' => 57,
            ],
            393 => 
            [
                'id' => 894,
                'name' => 'Sucumbios',
                'code' => '22',
                'country_id' => 57,
            ],
            394 => 
            [
                'id' => 895,
                'name' => 'Guayas',
                'code' => '10',
                'country_id' => 57,
            ],
            395 => 
            [
                'id' => 896,
                'name' => 'Los Rios',
                'code' => '13',
                'country_id' => 57,
            ],
            396 => 
            [
                'id' => 897,
                'name' => 'Loja',
                'code' => '12',
                'country_id' => 57,
            ],
            397 => 
            [
                'id' => 898,
                'name' => 'Chimborazo',
                'code' => '06',
                'country_id' => 57,
            ],
            398 => 
            [
                'id' => 899,
                'name' => 'Tungurahua',
                'code' => '19',
                'country_id' => 57,
            ],
            399 => 
            [
                'id' => 900,
                'name' => 'Esmeraldas',
                'code' => '09',
                'country_id' => 57,
            ],
            400 => 
            [
                'id' => 901,
                'name' => 'Pichincha',
                'code' => '18',
                'country_id' => 57,
            ],
            401 => 
            [
                'id' => 902,
                'name' => 'Imbabura',
                'code' => '11',
                'country_id' => 57,
            ],
            402 => 
            [
                'id' => 903,
                'name' => 'Cotopaxi',
                'code' => '07',
                'country_id' => 57,
            ],
            403 => 
            [
                'id' => 904,
                'name' => 'Carchi',
                'code' => '05',
                'country_id' => 57,
            ],
            404 => 
            [
                'id' => 905,
                'name' => 'Napo',
                'code' => '23',
                'country_id' => 57,
            ],
            405 => 
            [
                'id' => 906,
                'name' => 'Canar',
                'code' => '04',
                'country_id' => 57,
            ],
            406 => 
            [
                'id' => 907,
                'name' => 'Pastaza',
                'code' => '17',
                'country_id' => 57,
            ],
            407 => 
            [
                'id' => 908,
                'name' => 'Orellana',
                'code' => '24',
                'country_id' => 57,
            ],
            408 => 
            [
                'id' => 909,
                'name' => 'Bolivar',
                'code' => '03',
                'country_id' => 57,
            ],
            409 => 
            [
                'id' => 910,
                'name' => 'Galapagos',
                'code' => '01',
                'country_id' => 57,
            ],
            410 => 
            [
                'id' => 911,
                'name' => 'Harjumaa',
                'code' => '01',
                'country_id' => 58,
            ],
            411 => 
            [
                'id' => 912,
                'name' => 'Tartumaa',
                'code' => '18',
                'country_id' => 58,
            ],
            412 => 
            [
                'id' => 913,
                'name' => 'Hiiumaa',
                'code' => '02',
                'country_id' => 58,
            ],
            413 => 
            [
                'id' => 914,
                'name' => 'Raplamaa',
                'code' => '13',
                'country_id' => 58,
            ],
            414 => 
            [
                'id' => 915,
                'name' => 'Valgamaa',
                'code' => '19',
                'country_id' => 58,
            ],
            415 => 
            [
                'id' => 916,
                'name' => 'Laanemaa',
                'code' => '07',
                'country_id' => 58,
            ],
            416 => 
            [
                'id' => 917,
                'name' => 'Polvamaa',
                'code' => '12',
                'country_id' => 58,
            ],
            417 => 
            [
                'id' => 918,
                'name' => 'Parnumaa',
                'code' => '11',
                'country_id' => 58,
            ],
            418 => 
            [
                'id' => 919,
                'name' => 'Laane-Virumaa',
                'code' => '08',
                'country_id' => 58,
            ],
            419 => 
            [
                'id' => 920,
                'name' => 'Jarvamaa',
                'code' => '04',
                'country_id' => 58,
            ],
            420 => 
            [
                'id' => 921,
                'name' => 'Viljandimaa',
                'code' => '20',
                'country_id' => 58,
            ],
            421 => 
            [
                'id' => 922,
                'name' => 'Saaremaa',
                'code' => '14',
                'country_id' => 58,
            ],
            422 => 
            [
                'id' => 923,
                'name' => 'Jogevamaa',
                'code' => '05',
                'country_id' => 58,
            ],
            423 => 
            [
                'id' => 924,
                'name' => 'Ida-Virumaa',
                'code' => '03',
                'country_id' => 58,
            ],
            424 => 
            [
                'id' => 925,
                'name' => 'Vorumaa',
                'code' => '21',
                'country_id' => 58,
            ],
            425 => 
            [
                'id' => 926,
                'name' => 'Ash Sharqiyah',
                'code' => '14',
                'country_id' => 59,
            ],
            426 => 
            [
                'id' => 927,
                'name' => 'Al Gharbiyah',
                'code' => '05',
                'country_id' => 59,
            ],
            427 => 
            [
                'id' => 928,
                'name' => 'Ad Daqahliyah',
                'code' => '01',
                'country_id' => 59,
            ],
            428 => 
            [
                'id' => 929,
                'name' => 'Al Jizah',
                'code' => '08',
                'country_id' => 59,
            ],
            429 => 
            [
                'id' => 930,
                'name' => 'Al Minya',
                'code' => '10',
                'country_id' => 59,
            ],
            430 => 
            [
                'id' => 931,
                'name' => 'Kafr ash Shaykh',
                'code' => '21',
                'country_id' => 59,
            ],
            431 => 
            [
                'id' => 932,
                'name' => 'Al Buhayrah',
                'code' => '03',
                'country_id' => 59,
            ],
            432 => 
            [
                'id' => 933,
                'name' => 'Qina',
                'code' => '23',
                'country_id' => 59,
            ],
            433 => 
            [
                'id' => 934,
                'name' => 'Al Qahirah',
                'code' => '11',
                'country_id' => 59,
            ],
            434 => 
            [
                'id' => 935,
                'name' => 'Al Iskandariyah',
                'code' => '06',
                'country_id' => 59,
            ],
            435 => 
            [
                'id' => 936,
                'name' => 'Al Fayyum',
                'code' => '04',
                'country_id' => 59,
            ],
            436 => 
            [
                'id' => 937,
                'name' => 'Asyut',
                'code' => '17',
                'country_id' => 59,
            ],
            437 => 
            [
                'id' => 938,
                'name' => 'Al Minufiyah',
                'code' => '09',
                'country_id' => 59,
            ],
            438 => 
            [
                'id' => 939,
                'name' => 'Bani Suwayf',
                'code' => '18',
                'country_id' => 59,
            ],
            439 => 
            [
                'id' => 940,
                'name' => 'Al Qalyubiyah',
                'code' => '12',
                'country_id' => 59,
            ],
            440 => 
            [
                'id' => 941,
                'name' => 'Aswan',
                'code' => '16',
                'country_id' => 59,
            ],
            441 => 
            [
                'id' => 942,
                'name' => 'Shamal Sina\'',
                'code' => '27',
                'country_id' => 59,
            ],
            442 => 
            [
                'id' => 943,
                'name' => 'Suhaj',
                'code' => '24',
                'country_id' => 59,
            ],
            443 => 
            [
                'id' => 944,
                'name' => 'Janub Sina\'',
                'code' => '26',
                'country_id' => 59,
            ],
            444 => 
            [
                'id' => 945,
                'name' => 'Al Bahr al Ahmar',
                'code' => '02',
                'country_id' => 59,
            ],
            445 => 
            [
                'id' => 946,
                'name' => 'Al Isma\'iliyah',
                'code' => '07',
                'country_id' => 59,
            ],
            446 => 
            [
                'id' => 947,
                'name' => 'Dumyat',
                'code' => '20',
                'country_id' => 59,
            ],
            447 => 
            [
                'id' => 948,
                'name' => 'Matruh',
                'code' => '22',
                'country_id' => 59,
            ],
            448 => 
            [
                'id' => 949,
                'name' => 'As Suways',
                'code' => '15',
                'country_id' => 59,
            ],
            449 => 
            [
                'id' => 950,
                'name' => 'Al Wadi al Jadid',
                'code' => '13',
                'country_id' => 59,
            ],
            450 => 
            [
                'id' => 951,
                'name' => 'Bur Sa\'id',
                'code' => '19',
                'country_id' => 59,
            ],
            451 => 
            [
                'id' => 952,
                'name' => '',
                'code' => '00',
                'country_id' => 60,
            ],
            452 => 
            [
                'id' => 953,
                'name' => '',
                'code' => '00',
                'country_id' => 61,
            ],
            453 => 
            [
                'id' => 954,
                'name' => 'Aragon',
                'code' => '52',
                'country_id' => 62,
            ],
            454 => 
            [
                'id' => 955,
                'name' => 'Galicia',
                'code' => '58',
                'country_id' => 62,
            ],
            455 => 
            [
                'id' => 956,
                'name' => 'Castilla y Leon',
                'code' => '55',
                'country_id' => 62,
            ],
            456 => 
            [
                'id' => 957,
                'name' => 'Extremadura',
                'code' => '57',
                'country_id' => 62,
            ],
            457 => 
            [
                'id' => 958,
                'name' => 'Pais Vasco',
                'code' => '59',
                'country_id' => 62,
            ],
            458 => 
            [
                'id' => 959,
                'name' => 'Cantabria',
                'code' => '39',
                'country_id' => 62,
            ],
            459 => 
            [
                'id' => 960,
                'name' => 'Navarra',
                'code' => '32',
                'country_id' => 62,
            ],
            460 => 
            [
                'id' => 961,
                'name' => 'Asturias',
                'code' => '34',
                'country_id' => 62,
            ],
            461 => 
            [
                'id' => 962,
                'name' => 'La Rioja',
                'code' => '27',
                'country_id' => 62,
            ],
            462 => 
            [
                'id' => 963,
                'name' => 'Castilla-La Mancha',
                'code' => '54',
                'country_id' => 62,
            ],
            463 => 
            [
                'id' => 964,
                'name' => 'Murcia',
                'code' => '31',
                'country_id' => 62,
            ],
            464 => 
            [
                'id' => 965,
                'name' => 'Andalucia',
                'code' => '51',
                'country_id' => 62,
            ],
            465 => 
            [
                'id' => 966,
                'name' => 'Comunidad Valenciana',
                'code' => '60',
                'country_id' => 62,
            ],
            466 => 
            [
                'id' => 967,
                'name' => 'Catalonia',
                'code' => '56',
                'country_id' => 62,
            ],
            467 => 
            [
                'id' => 968,
                'name' => 'Canarias',
                'code' => '53',
                'country_id' => 62,
            ],
            468 => 
            [
                'id' => 969,
                'name' => 'Madrid',
                'code' => '29',
                'country_id' => 62,
            ],
            469 => 
            [
                'id' => 970,
                'name' => 'Islas Baleares',
                'code' => '07',
                'country_id' => 62,
            ],
            470 => 
            [
                'id' => 971,
                'name' => '',
                'code' => '12',
                'country_id' => 63,
            ],
            471 => 
            [
                'id' => 972,
                'name' => '',
                'code' => '08',
                'country_id' => 63,
            ],
            472 => 
            [
                'id' => 973,
                'name' => '',
                'code' => '14',
                'country_id' => 63,
            ],
            473 => 
            [
                'id' => 974,
                'name' => '',
                'code' => '10',
                'country_id' => 63,
            ],
            474 => 
            [
                'id' => 975,
                'name' => '',
                'code' => '13',
                'country_id' => 63,
            ],
            475 => 
            [
                'id' => 976,
                'name' => '',
                'code' => '01',
                'country_id' => 63,
            ],
            476 => 
            [
                'id' => 977,
                'name' => '',
                'code' => '02',
                'country_id' => 63,
            ],
            477 => 
            [
                'id' => 978,
                'name' => '',
                'code' => '11',
                'country_id' => 63,
            ],
            478 => 
            [
                'id' => 979,
                'name' => '',
                'code' => '06',
                'country_id' => 63,
            ],
            479 => 
            [
                'id' => 980,
                'name' => '',
                'code' => '09',
                'country_id' => 63,
            ],
            480 => 
            [
                'id' => 981,
                'name' => '',
                'code' => '05',
                'country_id' => 63,
            ],
            481 => 
            [
                'id' => 982,
                'name' => '',
                'code' => '07',
                'country_id' => 63,
            ],
            482 => 
            [
                'id' => 983,
                'name' => '',
                'code' => '03',
                'country_id' => 63,
            ],
            483 => 
            [
                'id' => 984,
                'name' => 'Oulu',
                'code' => '08',
                'country_id' => 64,
            ],
            484 => 
            [
                'id' => 985,
                'name' => 'Western Finland',
                'code' => '15',
                'country_id' => 64,
            ],
            485 => 
            [
                'id' => 986,
                'name' => 'Lapland',
                'code' => '06',
                'country_id' => 64,
            ],
            486 => 
            [
                'id' => 987,
                'name' => 'Eastern Finland',
                'code' => '14',
                'country_id' => 64,
            ],
            487 => 
            [
                'id' => 988,
                'name' => 'Southern Finland',
                'code' => '13',
                'country_id' => 64,
            ],
            488 => 
            [
                'id' => 989,
                'name' => 'Aland',
                'code' => '01',
                'country_id' => 64,
            ],
            489 => 
            [
                'id' => 990,
                'name' => '',
                'code' => '02',
                'country_id' => 64,
            ],
            490 => 
            [
                'id' => 991,
                'name' => '',
                'code' => '00',
                'country_id' => 65,
            ],
            491 => 
            [
                'id' => 992,
                'name' => 'Northern',
                'code' => '03',
                'country_id' => 65,
            ],
            492 => 
            [
                'id' => 993,
                'name' => 'Western',
                'code' => '05',
                'country_id' => 65,
            ],
            493 => 
            [
                'id' => 994,
                'name' => 'Central',
                'code' => '01',
                'country_id' => 65,
            ],
            494 => 
            [
                'id' => 995,
                'name' => 'Eastern',
                'code' => '02',
                'country_id' => 65,
            ],
            495 => 
            [
                'id' => 996,
                'name' => '',
                'code' => '00',
                'country_id' => 66,
            ],
            496 => 
            [
                'id' => 997,
                'name' => 'Yap',
                'code' => '04',
                'country_id' => 67,
            ],
            497 => 
            [
                'id' => 998,
                'name' => 'Pohnpei',
                'code' => '02',
                'country_id' => 67,
            ],
            498 => 
            [
                'id' => 999,
                'name' => 'Chuuk',
                'code' => '03',
                'country_id' => 67,
            ],
            499 => 
            [
                'id' => 1000,
                'name' => 'Kosrae',
                'code' => '01',
                'country_id' => 67,
            ],
        ]);
        \DB::table('regions')->insert([
            0 => 
            [
                'id' => 1001,
                'name' => '',
                'code' => '00',
                'country_id' => 68,
            ],
            1 => 
            [
                'id' => 1002,
                'name' => 'Aquitaine',
                'code' => '97',
                'country_id' => 69,
            ],
            2 => 
            [
                'id' => 1003,
                'name' => 'Nord-Pas-de-Calais',
                'code' => 'B4',
                'country_id' => 69,
            ],
            3 => 
            [
                'id' => 1004,
                'name' => 'Lorraine',
                'code' => 'B2',
                'country_id' => 69,
            ],
            4 => 
            [
                'id' => 1005,
                'name' => 'Haute-Normandie',
                'code' => 'A7',
                'country_id' => 69,
            ],
            5 => 
            [
                'id' => 1006,
                'name' => 'Picardie',
                'code' => 'B6',
                'country_id' => 69,
            ],
            6 => 
            [
                'id' => 1007,
                'name' => 'Franche-Comte',
                'code' => 'A6',
                'country_id' => 69,
            ],
            7 => 
            [
                'id' => 1008,
                'name' => 'Pays de la Loire',
                'code' => 'B5',
                'country_id' => 69,
            ],
            8 => 
            [
                'id' => 1009,
                'name' => 'Champagne-Ardenne',
                'code' => 'A4',
                'country_id' => 69,
            ],
            9 => 
            [
                'id' => 1010,
                'name' => 'Centre',
                'code' => 'A3',
                'country_id' => 69,
            ],
            10 => 
            [
                'id' => 1011,
                'name' => 'Languedoc-Roussillon',
                'code' => 'A9',
                'country_id' => 69,
            ],
            11 => 
            [
                'id' => 1012,
                'name' => 'Poitou-Charentes',
                'code' => 'B7',
                'country_id' => 69,
            ],
            12 => 
            [
                'id' => 1013,
                'name' => 'Rhone-Alpes',
                'code' => 'B9',
                'country_id' => 69,
            ],
            13 => 
            [
                'id' => 1014,
                'name' => 'Basse-Normandie',
                'code' => '99',
                'country_id' => 69,
            ],
            14 => 
            [
                'id' => 1015,
                'name' => 'Ile-de-France',
                'code' => 'A8',
                'country_id' => 69,
            ],
            15 => 
            [
                'id' => 1016,
                'name' => 'Bourgogne',
                'code' => 'A1',
                'country_id' => 69,
            ],
            16 => 
            [
                'id' => 1017,
                'name' => 'Auvergne',
                'code' => '98',
                'country_id' => 69,
            ],
            17 => 
            [
                'id' => 1018,
                'name' => 'Provence-Alpes-Cote d\'Azur',
                'code' => 'B8',
                'country_id' => 69,
            ],
            18 => 
            [
                'id' => 1019,
                'name' => 'Corse',
                'code' => 'A5',
                'country_id' => 69,
            ],
            19 => 
            [
                'id' => 1020,
                'name' => 'Alsace',
                'code' => 'C1',
                'country_id' => 69,
            ],
            20 => 
            [
                'id' => 1021,
                'name' => 'Bretagne',
                'code' => 'A2',
                'country_id' => 69,
            ],
            21 => 
            [
                'id' => 1022,
                'name' => 'Midi-Pyrenees',
                'code' => 'B3',
                'country_id' => 69,
            ],
            22 => 
            [
                'id' => 1023,
                'name' => 'Limousin',
                'code' => 'B1',
                'country_id' => 69,
            ],
            23 => 
            [
                'id' => 1024,
                'name' => 'Estuaire',
                'code' => '01',
                'country_id' => 70,
            ],
            24 => 
            [
                'id' => 1025,
                'name' => 'Woleu-Ntem',
                'code' => '09',
                'country_id' => 70,
            ],
            25 => 
            [
                'id' => 1026,
                'name' => 'Moyen-Ogooue',
                'code' => '03',
                'country_id' => 70,
            ],
            26 => 
            [
                'id' => 1027,
                'name' => 'Ogooue-Maritime',
                'code' => '08',
                'country_id' => 70,
            ],
            27 => 
            [
                'id' => 1028,
                'name' => 'Ogooue-Lolo',
                'code' => '07',
                'country_id' => 70,
            ],
            28 => 
            [
                'id' => 1029,
                'name' => 'Ogooue-Ivindo',
                'code' => '06',
                'country_id' => 70,
            ],
            29 => 
            [
                'id' => 1030,
                'name' => 'Haut-Ogooue',
                'code' => '02',
                'country_id' => 70,
            ],
            30 => 
            [
                'id' => 1031,
                'name' => 'Ngounie',
                'code' => '04',
                'country_id' => 70,
            ],
            31 => 
            [
                'id' => 1032,
                'name' => 'Nyanga',
                'code' => '05',
                'country_id' => 70,
            ],
            32 => 
            [
                'id' => 1033,
                'name' => 'Worcestershire',
                'code' => 'Q4',
                'country_id' => 71,
            ],
            33 => 
            [
                'id' => 1034,
                'name' => 'Hampshire',
                'code' => 'F2',
                'country_id' => 71,
            ],
            34 => 
            [
                'id' => 1035,
                'name' => 'Herefordshire',
                'code' => 'F7',
                'country_id' => 71,
            ],
            35 => 
            [
                'id' => 1036,
                'name' => 'Essex',
                'code' => 'E4',
                'country_id' => 71,
            ],
            36 => 
            [
                'id' => 1037,
                'name' => 'Powys',
                'code' => 'Y8',
                'country_id' => 71,
            ],
            37 => 
            [
                'id' => 1038,
                'name' => 'Monmouthshire',
                'code' => 'Y4',
                'country_id' => 71,
            ],
            38 => 
            [
                'id' => 1039,
                'name' => 'Scottish Borders',
                'code' => 'T9',
                'country_id' => 71,
            ],
            39 => 
            [
                'id' => 1040,
                'name' => 'Cumbria',
                'code' => 'C9',
                'country_id' => 71,
            ],
            40 => 
            [
                'id' => 1041,
                'name' => 'Devon',
                'code' => 'D4',
                'country_id' => 71,
            ],
            41 => 
            [
                'id' => 1042,
                'name' => 'Staffordshire',
                'code' => 'M9',
                'country_id' => 71,
            ],
            42 => 
            [
                'id' => 1043,
                'name' => 'Dorset',
                'code' => 'D6',
                'country_id' => 71,
            ],
            43 => 
            [
                'id' => 1044,
                'name' => 'Hertford',
                'code' => 'F8',
                'country_id' => 71,
            ],
            44 => 
            [
                'id' => 1045,
                'name' => 'Cambridgeshire',
                'code' => 'C3',
                'country_id' => 71,
            ],
            45 => 
            [
                'id' => 1046,
                'name' => 'Lancashire',
                'code' => 'H2',
                'country_id' => 71,
            ],
            46 => 
            [
                'id' => 1047,
                'name' => 'Conwy',
                'code' => 'X8',
                'country_id' => 71,
            ],
            47 => 
            [
                'id' => 1048,
                'name' => 'Ceredigion',
                'code' => 'X6',
                'country_id' => 71,
            ],
            48 => 
            [
                'id' => 1049,
                'name' => 'Rhondda Cynon Taff',
                'code' => 'Y9',
                'country_id' => 71,
            ],
            49 => 
            [
                'id' => 1050,
                'name' => 'Highland',
                'code' => 'V3',
                'country_id' => 71,
            ],
            50 => 
            [
                'id' => 1051,
                'name' => 'Perth and Kinross',
                'code' => 'W1',
                'country_id' => 71,
            ],
            51 => 
            [
                'id' => 1052,
                'name' => 'Caerphilly',
                'code' => 'X4',
                'country_id' => 71,
            ],
            52 => 
            [
                'id' => 1053,
                'name' => 'Blaenau Gwent',
                'code' => 'X2',
                'country_id' => 71,
            ],
            53 => 
            [
                'id' => 1054,
                'name' => 'Merthyr Tydfil',
                'code' => 'Y3',
                'country_id' => 71,
            ],
            54 => 
            [
                'id' => 1055,
                'name' => 'Pembrokeshire',
                'code' => 'Y7',
                'country_id' => 71,
            ],
            55 => 
            [
                'id' => 1056,
                'name' => 'Aberdeenshire',
                'code' => 'T6',
                'country_id' => 71,
            ],
            56 => 
            [
                'id' => 1057,
                'name' => 'Gwynedd',
                'code' => 'Y2',
                'country_id' => 71,
            ],
            57 => 
            [
                'id' => 1058,
                'name' => 'Aberdeen City',
                'code' => 'T5',
                'country_id' => 71,
            ],
            58 => 
            [
                'id' => 1059,
                'name' => 'Fife',
                'code' => 'V1',
                'country_id' => 71,
            ],
            59 => 
            [
                'id' => 1060,
                'name' => 'Neath Port Talbot',
                'code' => 'Y5',
                'country_id' => 71,
            ],
            60 => 
            [
                'id' => 1061,
                'name' => 'Isle of Anglesey',
                'code' => 'X1',
                'country_id' => 71,
            ],
            61 => 
            [
                'id' => 1062,
                'name' => 'Wokingham',
                'code' => 'Q2',
                'country_id' => 71,
            ],
            62 => 
            [
                'id' => 1063,
                'name' => 'York',
                'code' => 'Q5',
                'country_id' => 71,
            ],
            63 => 
            [
                'id' => 1064,
                'name' => 'Stirling',
                'code' => 'W6',
                'country_id' => 71,
            ],
            64 => 
            [
                'id' => 1065,
                'name' => 'Carmarthenshire',
                'code' => 'X7',
                'country_id' => 71,
            ],
            65 => 
            [
                'id' => 1066,
                'name' => 'Bridgend',
                'code' => 'X3',
                'country_id' => 71,
            ],
            66 => 
            [
                'id' => 1067,
                'name' => 'East Lothian',
                'code' => 'U6',
                'country_id' => 71,
            ],
            67 => 
            [
                'id' => 1068,
                'name' => 'Angus',
                'code' => 'T7',
                'country_id' => 71,
            ],
            68 => 
            [
                'id' => 1069,
                'name' => 'Moray',
                'code' => 'V6',
                'country_id' => 71,
            ],
            69 => 
            [
                'id' => 1070,
                'name' => 'Torfaen',
                'code' => 'Z2',
                'country_id' => 71,
            ],
            70 => 
            [
                'id' => 1071,
                'name' => 'Swansea',
                'code' => 'Z1',
                'country_id' => 71,
            ],
            71 => 
            [
                'id' => 1072,
                'name' => 'Vale of Glamorgan',
                'code' => 'Z3',
                'country_id' => 71,
            ],
            72 => 
            [
                'id' => 1073,
                'name' => 'Oxfordshire',
                'code' => 'K2',
                'country_id' => 71,
            ],
            73 => 
            [
                'id' => 1074,
                'name' => 'Surrey',
                'code' => 'N7',
                'country_id' => 71,
            ],
            74 => 
            [
                'id' => 1075,
                'name' => 'South Lanarkshire',
                'code' => 'W5',
                'country_id' => 71,
            ],
            75 => 
            [
                'id' => 1076,
                'name' => 'Leicestershire',
                'code' => 'H5',
                'country_id' => 71,
            ],
            76 => 
            [
                'id' => 1077,
                'name' => 'Wigan',
                'code' => 'P7',
                'country_id' => 71,
            ],
            77 => 
            [
                'id' => 1078,
                'name' => 'Northamptonshire',
                'code' => 'J1',
                'country_id' => 71,
            ],
            78 => 
            [
                'id' => 1079,
                'name' => 'Lincolnshire',
                'code' => 'H7',
                'country_id' => 71,
            ],
            79 => 
            [
                'id' => 1080,
                'name' => 'Argyll and Bute',
                'code' => 'T8',
                'country_id' => 71,
            ],
            80 => 
            [
                'id' => 1081,
                'name' => 'Northumberland',
                'code' => 'J6',
                'country_id' => 71,
            ],
            81 => 
            [
                'id' => 1082,
                'name' => 'Norfolk',
                'code' => 'I9',
                'country_id' => 71,
            ],
            82 => 
            [
                'id' => 1083,
                'name' => 'Solihull',
                'code' => 'M2',
                'country_id' => 71,
            ],
            83 => 
            [
                'id' => 1084,
                'name' => 'Wrexham',
                'code' => 'Z4',
                'country_id' => 71,
            ],
            84 => 
            [
                'id' => 1085,
                'name' => 'Cheshire',
                'code' => 'C5',
                'country_id' => 71,
            ],
            85 => 
            [
                'id' => 1086,
                'name' => 'Shropshire',
                'code' => 'L6',
                'country_id' => 71,
            ],
            86 => 
            [
                'id' => 1087,
                'name' => 'Banbridge',
                'code' => 'R2',
                'country_id' => 71,
            ],
            87 => 
            [
                'id' => 1088,
                'name' => 'South Gloucestershire',
                'code' => 'M6',
                'country_id' => 71,
            ],
            88 => 
            [
                'id' => 1089,
                'name' => 'West Lothian',
                'code' => 'W9',
                'country_id' => 71,
            ],
            89 => 
            [
                'id' => 1090,
                'name' => '',
                'code' => '17',
                'country_id' => 71,
            ],
            90 => 
            [
                'id' => 1091,
                'name' => 'Kent',
                'code' => 'G5',
                'country_id' => 71,
            ],
            91 => 
            [
                'id' => 1092,
                'name' => 'Leeds',
                'code' => 'H3',
                'country_id' => 71,
            ],
            92 => 
            [
                'id' => 1093,
                'name' => 'Somerset',
                'code' => 'M3',
                'country_id' => 71,
            ],
            93 => 
            [
                'id' => 1094,
                'name' => 'Gloucestershire',
                'code' => 'E6',
                'country_id' => 71,
            ],
            94 => 
            [
                'id' => 1095,
                'name' => 'Buckinghamshire',
                'code' => 'B9',
                'country_id' => 71,
            ],
            95 => 
            [
                'id' => 1096,
                'name' => 'Coleraine',
                'code' => 'R6',
                'country_id' => 71,
            ],
            96 => 
            [
                'id' => 1097,
                'name' => 'Craigavon',
                'code' => 'R8',
                'country_id' => 71,
            ],
            97 => 
            [
                'id' => 1098,
                'name' => 'Antrim',
                'code' => 'Q6',
                'country_id' => 71,
            ],
            98 => 
            [
                'id' => 1099,
                'name' => 'Limavady',
                'code' => 'S4',
                'country_id' => 71,
            ],
            99 => 
            [
                'id' => 1100,
                'name' => 'Armagh',
                'code' => 'Q8',
                'country_id' => 71,
            ],
            100 => 
            [
                'id' => 1101,
                'name' => 'Ballymena',
                'code' => 'Q9',
                'country_id' => 71,
            ],
            101 => 
            [
                'id' => 1102,
                'name' => 'North Yorkshire',
                'code' => 'J7',
                'country_id' => 71,
            ],
            102 => 
            [
                'id' => 1103,
                'name' => 'Sefton',
                'code' => 'L8',
                'country_id' => 71,
            ],
            103 => 
            [
                'id' => 1104,
                'name' => 'Warwickshire',
                'code' => 'P3',
                'country_id' => 71,
            ],
            104 => 
            [
                'id' => 1105,
                'name' => 'Derry',
                'code' => 'S6',
                'country_id' => 71,
            ],
            105 => 
            [
                'id' => 1106,
                'name' => 'Eilean Siar',
                'code' => 'W8',
                'country_id' => 71,
            ],
            106 => 
            [
                'id' => 1107,
                'name' => 'North Lanarkshire',
                'code' => 'V8',
                'country_id' => 71,
            ],
            107 => 
            [
                'id' => 1108,
                'name' => 'Falkirk',
                'code' => 'U9',
                'country_id' => 71,
            ],
            108 => 
            [
                'id' => 1109,
                'name' => 'Shetland Islands',
                'code' => 'W3',
                'country_id' => 71,
            ],
            109 => 
            [
                'id' => 1110,
                'name' => 'Wiltshire',
                'code' => 'P8',
                'country_id' => 71,
            ],
            110 => 
            [
                'id' => 1111,
                'name' => 'Durham',
                'code' => 'D8',
                'country_id' => 71,
            ],
            111 => 
            [
                'id' => 1112,
                'name' => 'Darlington',
                'code' => 'D1',
                'country_id' => 71,
            ],
            112 => 
            [
                'id' => 1113,
                'name' => 'Suffolk',
                'code' => 'N5',
                'country_id' => 71,
            ],
            113 => 
            [
                'id' => 1114,
                'name' => 'Derbyshire',
                'code' => 'D3',
                'country_id' => 71,
            ],
            114 => 
            [
                'id' => 1115,
                'name' => 'Walsall',
                'code' => 'O8',
                'country_id' => 71,
            ],
            115 => 
            [
                'id' => 1116,
                'name' => 'Rotherham',
                'code' => 'L3',
                'country_id' => 71,
            ],
            116 => 
            [
                'id' => 1117,
                'name' => 'West Dunbartonshire',
                'code' => 'W7',
                'country_id' => 71,
            ],
            117 => 
            [
                'id' => 1118,
                'name' => 'East Sussex',
                'code' => 'E2',
                'country_id' => 71,
            ],
            118 => 
            [
                'id' => 1119,
                'name' => 'Coventry',
                'code' => 'C7',
                'country_id' => 71,
            ],
            119 => 
            [
                'id' => 1120,
                'name' => 'Derby',
                'code' => 'D2',
                'country_id' => 71,
            ],
            120 => 
            [
                'id' => 1121,
                'name' => 'Southend-on-Sea',
                'code' => 'M5',
                'country_id' => 71,
            ],
            121 => 
            [
                'id' => 1122,
                'name' => 'Clackmannanshire',
                'code' => 'U1',
                'country_id' => 71,
            ],
            122 => 
            [
                'id' => 1123,
                'name' => 'Kirklees',
                'code' => 'G8',
                'country_id' => 71,
            ],
            123 => 
            [
                'id' => 1124,
                'name' => 'St. Helens',
                'code' => 'N1',
                'country_id' => 71,
            ],
            124 => 
            [
                'id' => 1125,
                'name' => 'Omagh',
                'code' => 'T3',
                'country_id' => 71,
            ],
            125 => 
            [
                'id' => 1126,
                'name' => 'Cornwall',
                'code' => 'C6',
                'country_id' => 71,
            ],
            126 => 
            [
                'id' => 1127,
                'name' => 'North Lincolnshire',
                'code' => 'J3',
                'country_id' => 71,
            ],
            127 => 
            [
                'id' => 1128,
                'name' => 'Newry and Mourne',
                'code' => 'S9',
                'country_id' => 71,
            ],
            128 => 
            [
                'id' => 1129,
                'name' => 'South Ayrshire',
                'code' => 'W4',
                'country_id' => 71,
            ],
            129 => 
            [
                'id' => 1130,
                'name' => 'Isle of Wight',
                'code' => 'G2',
                'country_id' => 71,
            ],
            130 => 
            [
                'id' => 1131,
                'name' => '',
                'code' => '43',
                'country_id' => 71,
            ],
            131 => 
            [
                'id' => 1132,
                'name' => 'Dumfries and Galloway',
                'code' => 'U2',
                'country_id' => 71,
            ],
            132 => 
            [
                'id' => 1133,
                'name' => 'Bedfordshire',
                'code' => 'A5',
                'country_id' => 71,
            ],
            133 => 
            [
                'id' => 1134,
                'name' => 'Down',
                'code' => 'R9',
                'country_id' => 71,
            ],
            134 => 
            [
                'id' => 1135,
                'name' => 'Dungannon',
                'code' => 'S1',
                'country_id' => 71,
            ],
            135 => 
            [
                'id' => 1136,
                'name' => 'Renfrewshire',
                'code' => 'W2',
                'country_id' => 71,
            ],
            136 => 
            [
                'id' => 1137,
                'name' => 'Leicester',
                'code' => 'H4',
                'country_id' => 71,
            ],
            137 => 
            [
                'id' => 1138,
                'name' => 'Glasgow City',
                'code' => 'V2',
                'country_id' => 71,
            ],
            138 => 
            [
                'id' => 1139,
                'name' => 'West Sussex',
                'code' => 'P6',
                'country_id' => 71,
            ],
            139 => 
            [
                'id' => 1140,
                'name' => 'Warrington',
                'code' => 'P2',
                'country_id' => 71,
            ],
            140 => 
            [
                'id' => 1141,
                'name' => 'Cookstown',
                'code' => 'R7',
                'country_id' => 71,
            ],
            141 => 
            [
                'id' => 1142,
                'name' => 'North Ayrshire',
                'code' => 'V7',
                'country_id' => 71,
            ],
            142 => 
            [
                'id' => 1143,
                'name' => 'Barnsley',
                'code' => 'A3',
                'country_id' => 71,
            ],
            143 => 
            [
                'id' => 1144,
                'name' => 'Strabane',
                'code' => 'T4',
                'country_id' => 71,
            ],
            144 => 
            [
                'id' => 1145,
                'name' => 'Doncaster',
                'code' => 'D5',
                'country_id' => 71,
            ],
            145 => 
            [
                'id' => 1146,
                'name' => 'Ballymoney',
                'code' => 'R1',
                'country_id' => 71,
            ],
            146 => 
            [
                'id' => 1147,
                'name' => 'Fermanagh',
                'code' => 'S2',
                'country_id' => 71,
            ],
            147 => 
            [
                'id' => 1148,
                'name' => '',
                'code' => '87',
                'country_id' => 71,
            ],
            148 => 
            [
                'id' => 1149,
                'name' => 'Nottingham',
                'code' => 'J8',
                'country_id' => 71,
            ],
            149 => 
            [
                'id' => 1150,
                'name' => '',
                'code' => '03',
                'country_id' => 71,
            ],
            150 => 
            [
                'id' => 1151,
                'name' => 'Tameside',
                'code' => 'O1',
                'country_id' => 71,
            ],
            151 => 
            [
                'id' => 1152,
                'name' => 'Rutland',
                'code' => 'L4',
                'country_id' => 71,
            ],
            152 => 
            [
                'id' => 1153,
                'name' => 'Nottinghamshire',
                'code' => 'J9',
                'country_id' => 71,
            ],
            153 => 
            [
                'id' => 1154,
                'name' => 'Midlothian',
                'code' => 'V5',
                'country_id' => 71,
            ],
            154 => 
            [
                'id' => 1155,
                'name' => 'East Ayrshire',
                'code' => 'U4',
                'country_id' => 71,
            ],
            155 => 
            [
                'id' => 1156,
                'name' => 'Stoke-on-Trent',
                'code' => 'N4',
                'country_id' => 71,
            ],
            156 => 
            [
                'id' => 1157,
                'name' => 'Bristol',
                'code' => 'B7',
                'country_id' => 71,
            ],
            157 => 
            [
                'id' => 1158,
                'name' => 'Flintshire',
                'code' => 'Y1',
                'country_id' => 71,
            ],
            158 => 
            [
                'id' => 1159,
                'name' => 'Blackburn with Darwen',
                'code' => 'A8',
                'country_id' => 71,
            ],
            159 => 
            [
                'id' => 1160,
                'name' => 'Moyle',
                'code' => 'S8',
                'country_id' => 71,
            ],
            160 => 
            [
                'id' => 1161,
                'name' => 'Carrickfergus',
                'code' => 'R4',
                'country_id' => 71,
            ],
            161 => 
            [
                'id' => 1162,
                'name' => 'Castlereagh',
                'code' => 'R5',
                'country_id' => 71,
            ],
            162 => 
            [
                'id' => 1163,
                'name' => 'Larne',
                'code' => 'S3',
                'country_id' => 71,
            ],
            163 => 
            [
                'id' => 1164,
                'name' => 'Belfast',
                'code' => 'R3',
                'country_id' => 71,
            ],
            164 => 
            [
                'id' => 1165,
                'name' => 'Magherafelt',
                'code' => 'S7',
                'country_id' => 71,
            ],
            165 => 
            [
                'id' => 1166,
                'name' => 'North Down',
                'code' => 'T2',
                'country_id' => 71,
            ],
            166 => 
            [
                'id' => 1167,
                'name' => 'North Somerset',
                'code' => 'J4',
                'country_id' => 71,
            ],
            167 => 
            [
                'id' => 1168,
                'name' => 'East Renfrewshire',
                'code' => 'U7',
                'country_id' => 71,
            ],
            168 => 
            [
                'id' => 1169,
                'name' => 'Newport',
                'code' => 'Y6',
                'country_id' => 71,
            ],
            169 => 
            [
                'id' => 1170,
                'name' => 'Bath and North East Somerset',
                'code' => 'A4',
                'country_id' => 71,
            ],
            170 => 
            [
                'id' => 1171,
                'name' => '',
                'code' => '45',
                'country_id' => 71,
            ],
            171 => 
            [
                'id' => 1172,
                'name' => '',
                'code' => '37',
                'country_id' => 71,
            ],
            172 => 
            [
                'id' => 1173,
                'name' => 'Newham',
                'code' => 'I8',
                'country_id' => 71,
            ],
            173 => 
            [
                'id' => 1174,
                'name' => '',
                'code' => '90',
                'country_id' => 71,
            ],
            174 => 
            [
                'id' => 1175,
                'name' => 'Denbighshire',
                'code' => 'X9',
                'country_id' => 71,
            ],
            175 => 
            [
                'id' => 1176,
                'name' => 'East Riding of Yorkshire',
                'code' => 'E1',
                'country_id' => 71,
            ],
            176 => 
            [
                'id' => 1177,
                'name' => 'Bexley',
                'code' => 'A6',
                'country_id' => 71,
            ],
            177 => 
            [
                'id' => 1178,
                'name' => 'Bromley',
                'code' => 'B8',
                'country_id' => 71,
            ],
            178 => 
            [
                'id' => 1179,
                'name' => 'Bradford',
                'code' => 'B4',
                'country_id' => 71,
            ],
            179 => 
            [
                'id' => 1180,
                'name' => 'Bracknell Forest',
                'code' => 'B3',
                'country_id' => 71,
            ],
            180 => 
            [
                'id' => 1181,
                'name' => 'Cardiff',
                'code' => 'X5',
                'country_id' => 71,
            ],
            181 => 
            [
                'id' => 1182,
                'name' => 'Birmingham',
                'code' => 'A7',
                'country_id' => 71,
            ],
            182 => 
            [
                'id' => 1183,
                'name' => 'Orkney',
                'code' => 'V9',
                'country_id' => 71,
            ],
            183 => 
            [
                'id' => 1184,
                'name' => 'East Dunbartonshire',
                'code' => 'U5',
                'country_id' => 71,
            ],
            184 => 
            [
                'id' => 1185,
                'name' => 'Blackpool',
                'code' => 'A9',
                'country_id' => 71,
            ],
            185 => 
            [
                'id' => 1186,
                'name' => 'Southampton',
                'code' => 'M4',
                'country_id' => 71,
            ],
            186 => 
            [
                'id' => 1187,
                'name' => 'Newcastle upon Tyne',
                'code' => 'I7',
                'country_id' => 71,
            ],
            187 => 
            [
                'id' => 1188,
                'name' => 'Bolton',
                'code' => 'B1',
                'country_id' => 71,
            ],
            188 => 
            [
                'id' => 1189,
                'name' => 'Redcar and Cleveland',
                'code' => 'K9',
                'country_id' => 71,
            ],
            189 => 
            [
                'id' => 1190,
                'name' => 'Bournemouth',
                'code' => 'B2',
                'country_id' => 71,
            ],
            190 => 
            [
                'id' => 1191,
                'name' => 'Swindon',
                'code' => 'N9',
                'country_id' => 71,
            ],
            191 => 
            [
                'id' => 1192,
                'name' => 'Stockport',
                'code' => 'N2',
                'country_id' => 71,
            ],
            192 => 
            [
                'id' => 1193,
                'name' => 'Windsor and Maidenhead',
                'code' => 'P9',
                'country_id' => 71,
            ],
            193 => 
            [
                'id' => 1194,
                'name' => 'Inverclyde',
                'code' => 'V4',
                'country_id' => 71,
            ],
            194 => 
            [
                'id' => 1195,
                'name' => 'Medway',
                'code' => 'I3',
                'country_id' => 71,
            ],
            195 => 
            [
                'id' => 1196,
                'name' => 'Milton Keynes',
                'code' => 'I6',
                'country_id' => 71,
            ],
            196 => 
            [
                'id' => 1197,
                'name' => 'Dundee City',
                'code' => 'U3',
                'country_id' => 71,
            ],
            197 => 
            [
                'id' => 1198,
                'name' => 'Telford and Wrekin',
                'code' => 'O2',
                'country_id' => 71,
            ],
            198 => 
            [
                'id' => 1199,
                'name' => 'Reading',
                'code' => 'K7',
                'country_id' => 71,
            ],
            199 => 
            [
                'id' => 1200,
                'name' => 'Bury',
                'code' => 'C1',
                'country_id' => 71,
            ],
            200 => 
            [
                'id' => 1201,
                'name' => 'Wolverhampton',
                'code' => 'Q3',
                'country_id' => 71,
            ],
            201 => 
            [
                'id' => 1202,
                'name' => 'Southwark',
                'code' => 'M8',
                'country_id' => 71,
            ],
            202 => 
            [
                'id' => 1203,
                'name' => 'Camden',
                'code' => 'C4',
                'country_id' => 71,
            ],
            203 => 
            [
                'id' => 1204,
                'name' => 'Slough',
                'code' => 'M1',
                'country_id' => 71,
            ],
            204 => 
            [
                'id' => 1205,
                'name' => 'Middlesbrough',
                'code' => 'I5',
                'country_id' => 71,
            ],
            205 => 
            [
                'id' => 1206,
                'name' => 'Stockton-on-Tees',
                'code' => 'N3',
                'country_id' => 71,
            ],
            206 => 
            [
                'id' => 1207,
                'name' => 'Newtownabbey',
                'code' => 'T1',
                'country_id' => 71,
            ],
            207 => 
            [
                'id' => 1208,
                'name' => 'Lisburn',
                'code' => 'S5',
                'country_id' => 71,
            ],
            208 => 
            [
                'id' => 1209,
                'name' => '',
                'code' => '28',
                'country_id' => 71,
            ],
            209 => 
            [
                'id' => 1210,
                'name' => 'Lewisham',
                'code' => 'H6',
                'country_id' => 71,
            ],
            210 => 
            [
                'id' => 1211,
                'name' => 'West Berkshire',
                'code' => 'P4',
                'country_id' => 71,
            ],
            211 => 
            [
                'id' => 1212,
                'name' => 'Manchester',
                'code' => 'I2',
                'country_id' => 71,
            ],
            212 => 
            [
                'id' => 1213,
                'name' => 'Westminster',
                'code' => 'P5',
                'country_id' => 71,
            ],
            213 => 
            [
                'id' => 1214,
                'name' => 'Ards',
                'code' => 'Q7',
                'country_id' => 71,
            ],
            214 => 
            [
                'id' => 1215,
                'name' => 'Plymouth',
                'code' => 'K4',
                'country_id' => 71,
            ],
            215 => 
            [
                'id' => 1216,
                'name' => 'Croydon',
                'code' => 'C8',
                'country_id' => 71,
            ],
            216 => 
            [
                'id' => 1217,
                'name' => 'Barking and Dagenham',
                'code' => 'A1',
                'country_id' => 71,
            ],
            217 => 
            [
                'id' => 1218,
                'name' => 'Hartlepool',
                'code' => 'F5',
                'country_id' => 71,
            ],
            218 => 
            [
                'id' => 1219,
                'name' => 'Sheffield',
                'code' => 'L9',
                'country_id' => 71,
            ],
            219 => 
            [
                'id' => 1220,
                'name' => 'Oldham',
                'code' => 'K1',
                'country_id' => 71,
            ],
            220 => 
            [
                'id' => 1221,
                'name' => 'Knowsley',
                'code' => 'G9',
                'country_id' => 71,
            ],
            221 => 
            [
                'id' => 1222,
                'name' => 'Liverpool',
                'code' => 'H8',
                'country_id' => 71,
            ],
            222 => 
            [
                'id' => 1223,
                'name' => 'Dudley',
                'code' => 'D7',
                'country_id' => 71,
            ],
            223 => 
            [
                'id' => 1224,
                'name' => 'Gateshead',
                'code' => 'E5',
                'country_id' => 71,
            ],
            224 => 
            [
                'id' => 1225,
                'name' => 'Ealing',
                'code' => 'D9',
                'country_id' => 71,
            ],
            225 => 
            [
                'id' => 1226,
                'name' => 'Edinburgh',
                'code' => 'U8',
                'country_id' => 71,
            ],
            226 => 
            [
                'id' => 1227,
                'name' => 'Enfield',
                'code' => 'E3',
                'country_id' => 71,
            ],
            227 => 
            [
                'id' => 1228,
                'name' => 'Calderdale',
                'code' => 'C2',
                'country_id' => 71,
            ],
            228 => 
            [
                'id' => 1229,
                'name' => 'Halton',
                'code' => 'E9',
                'country_id' => 71,
            ],
            229 => 
            [
                'id' => 1230,
                'name' => 'North Tyneside',
                'code' => 'J5',
                'country_id' => 71,
            ],
            230 => 
            [
                'id' => 1231,
                'name' => 'Thurrock',
                'code' => 'O3',
                'country_id' => 71,
            ],
            231 => 
            [
                'id' => 1232,
                'name' => 'North East Lincolnshire',
                'code' => 'J2',
                'country_id' => 71,
            ],
            232 => 
            [
                'id' => 1233,
                'name' => 'Wirral',
                'code' => 'Q1',
                'country_id' => 71,
            ],
            233 => 
            [
                'id' => 1234,
                'name' => 'Hackney',
                'code' => 'E8',
                'country_id' => 71,
            ],
            234 => 
            [
                'id' => 1235,
                'name' => 'Hammersmith and Fulham',
                'code' => 'F1',
                'country_id' => 71,
            ],
            235 => 
            [
                'id' => 1236,
                'name' => 'Havering',
                'code' => 'F6',
                'country_id' => 71,
            ],
            236 => 
            [
                'id' => 1237,
                'name' => 'Harrow',
                'code' => 'F4',
                'country_id' => 71,
            ],
            237 => 
            [
                'id' => 1238,
                'name' => 'Barnet',
                'code' => 'A2',
                'country_id' => 71,
            ],
            238 => 
            [
                'id' => 1239,
                'name' => 'Hounslow',
                'code' => 'G1',
                'country_id' => 71,
            ],
            239 => 
            [
                'id' => 1240,
                'name' => 'Brighton and Hove',
                'code' => 'B6',
                'country_id' => 71,
            ],
            240 => 
            [
                'id' => 1241,
                'name' => 'Kingston upon Hull',
                'code' => 'G6',
                'country_id' => 71,
            ],
            241 => 
            [
                'id' => 1242,
                'name' => 'Redbridge',
                'code' => 'K8',
                'country_id' => 71,
            ],
            242 => 
            [
                'id' => 1243,
                'name' => 'Islington',
                'code' => 'G3',
                'country_id' => 71,
            ],
            243 => 
            [
                'id' => 1244,
                'name' => 'Kensington and Chelsea',
                'code' => 'G4',
                'country_id' => 71,
            ],
            244 => 
            [
                'id' => 1245,
                'name' => 'Kingston upon Thames',
                'code' => 'G7',
                'country_id' => 71,
            ],
            245 => 
            [
                'id' => 1246,
                'name' => 'Lambeth',
                'code' => 'H1',
                'country_id' => 71,
            ],
            246 => 
            [
                'id' => 1247,
                'name' => 'London',
                'code' => 'H9',
                'country_id' => 71,
            ],
            247 => 
            [
                'id' => 1248,
                'name' => 'Luton',
                'code' => 'I1',
                'country_id' => 71,
            ],
            248 => 
            [
                'id' => 1249,
                'name' => 'Sunderland',
                'code' => 'N6',
                'country_id' => 71,
            ],
            249 => 
            [
                'id' => 1250,
                'name' => 'Merton',
                'code' => 'I4',
                'country_id' => 71,
            ],
            250 => 
            [
                'id' => 1251,
                'name' => 'Sandwell',
                'code' => 'L7',
                'country_id' => 71,
            ],
            251 => 
            [
                'id' => 1252,
                'name' => 'Salford',
                'code' => 'L5',
                'country_id' => 71,
            ],
            252 => 
            [
                'id' => 1253,
                'name' => 'Peterborough',
                'code' => 'K3',
                'country_id' => 71,
            ],
            253 => 
            [
                'id' => 1254,
                'name' => 'Poole',
                'code' => 'K5',
                'country_id' => 71,
            ],
            254 => 
            [
                'id' => 1255,
                'name' => 'Tower Hamlets',
                'code' => 'O5',
                'country_id' => 71,
            ],
            255 => 
            [
                'id' => 1256,
                'name' => 'Portsmouth',
                'code' => 'K6',
                'country_id' => 71,
            ],
            256 => 
            [
                'id' => 1257,
                'name' => 'Rochdale',
                'code' => 'L2',
                'country_id' => 71,
            ],
            257 => 
            [
                'id' => 1258,
                'name' => 'Greenwich',
                'code' => 'E7',
                'country_id' => 71,
            ],
            258 => 
            [
                'id' => 1259,
                'name' => 'South Tyneside',
                'code' => 'M7',
                'country_id' => 71,
            ],
            259 => 
            [
                'id' => 1260,
                'name' => 'Trafford',
                'code' => 'O6',
                'country_id' => 71,
            ],
            260 => 
            [
                'id' => 1261,
                'name' => 'Sutton',
                'code' => 'N8',
                'country_id' => 71,
            ],
            261 => 
            [
                'id' => 1262,
                'name' => 'Torbay',
                'code' => 'O4',
                'country_id' => 71,
            ],
            262 => 
            [
                'id' => 1263,
                'name' => 'Richmond upon Thames',
                'code' => 'L1',
                'country_id' => 71,
            ],
            263 => 
            [
                'id' => 1264,
                'name' => 'Hillingdon',
                'code' => 'F9',
                'country_id' => 71,
            ],
            264 => 
            [
                'id' => 1265,
                'name' => 'Wakefield',
                'code' => 'O7',
                'country_id' => 71,
            ],
            265 => 
            [
                'id' => 1266,
                'name' => 'Waltham Forest',
                'code' => 'O9',
                'country_id' => 71,
            ],
            266 => 
            [
                'id' => 1267,
                'name' => 'Wandsworth',
                'code' => 'P1',
                'country_id' => 71,
            ],
            267 => 
            [
                'id' => 1268,
                'name' => 'Brent',
                'code' => 'B5',
                'country_id' => 71,
            ],
            268 => 
            [
                'id' => 1269,
                'name' => 'Haringey',
                'code' => 'F3',
                'country_id' => 71,
            ],
            269 => 
            [
                'id' => 1270,
                'name' => 'Saint Andrew',
                'code' => '01',
                'country_id' => 72,
            ],
            270 => 
            [
                'id' => 1271,
                'name' => 'Saint George',
                'code' => '03',
                'country_id' => 72,
            ],
            271 => 
            [
                'id' => 1272,
                'name' => 'Saint David',
                'code' => '02',
                'country_id' => 72,
            ],
            272 => 
            [
                'id' => 1273,
                'name' => 'Saint Patrick',
                'code' => '06',
                'country_id' => 72,
            ],
            273 => 
            [
                'id' => 1274,
                'name' => 'Saint Mark',
                'code' => '05',
                'country_id' => 72,
            ],
            274 => 
            [
                'id' => 1275,
                'name' => 'Saint John',
                'code' => '04',
                'country_id' => 72,
            ],
            275 => 
            [
                'id' => 1276,
                'name' => 'Abkhazia',
                'code' => '02',
                'country_id' => 73,
            ],
            276 => 
            [
                'id' => 1277,
                'name' => 'Ninotsmindis Raioni',
                'code' => '39',
                'country_id' => 73,
            ],
            277 => 
            [
                'id' => 1278,
                'name' => 'P\'ot\'i',
                'code' => '42',
                'country_id' => 73,
            ],
            278 => 
            [
                'id' => 1279,
                'name' => 'Ambrolauris Raioni',
                'code' => '09',
                'country_id' => 73,
            ],
            279 => 
            [
                'id' => 1280,
                'name' => 'Abashis Raioni',
                'code' => '01',
                'country_id' => 73,
            ],
            280 => 
            [
                'id' => 1281,
                'name' => 'Akhalts\'ikhis Raioni',
                'code' => '07',
                'country_id' => 73,
            ],
            281 => 
            [
                'id' => 1282,
                'name' => 'Zestap\'onis Raioni',
                'code' => '62',
                'country_id' => 73,
            ],
            282 => 
            [
                'id' => 1283,
                'name' => 'Tsalenjikhis Raioni',
                'code' => '58',
                'country_id' => 73,
            ],
            283 => 
            [
                'id' => 1284,
                'name' => 'Marneulis Raioni',
                'code' => '35',
                'country_id' => 73,
            ],
            284 => 
            [
                'id' => 1285,
                'name' => 'Goris Raioni',
                'code' => '22',
                'country_id' => 73,
            ],
            285 => 
            [
                'id' => 1286,
                'name' => 'K\'arelis Raioni',
                'code' => '25',
                'country_id' => 73,
            ],
            286 => 
            [
                'id' => 1287,
                'name' => 'Khashuris Raioni',
                'code' => '28',
                'country_id' => 73,
            ],
            287 => 
            [
                'id' => 1288,
                'name' => 'Kaspis Raioni',
                'code' => '26',
                'country_id' => 73,
            ],
            288 => 
            [
                'id' => 1289,
                'name' => 'Ajaria',
                'code' => '04',
                'country_id' => 73,
            ],
            289 => 
            [
                'id' => 1290,
                'name' => 'Mts\'khet\'is Raioni',
                'code' => '38',
                'country_id' => 73,
            ],
            290 => 
            [
                'id' => 1291,
                'name' => 'Ch\'okhatauris Raioni',
                'code' => '16',
                'country_id' => 73,
            ],
            291 => 
            [
                'id' => 1292,
                'name' => 'Akhalk\'alak\'is Raioni',
                'code' => '06',
                'country_id' => 73,
            ],
            292 => 
            [
                'id' => 1293,
                'name' => 'Samtrediis Raioni',
                'code' => '48',
                'country_id' => 73,
            ],
            293 => 
            [
                'id' => 1294,
                'name' => 'Tqibuli',
                'code' => '56',
                'country_id' => 73,
            ],
            294 => 
            [
                'id' => 1295,
                'name' => 'Dushet\'is Raioni',
                'code' => '19',
                'country_id' => 73,
            ],
            295 => 
            [
                'id' => 1296,
                'name' => 'Onis Raioni',
                'code' => '40',
                'country_id' => 73,
            ],
            296 => 
            [
                'id' => 1297,
                'name' => 'Lentekhis Raioni',
                'code' => '34',
                'country_id' => 73,
            ],
            297 => 
            [
                'id' => 1298,
                'name' => 'Martvilis Raioni',
                'code' => '36',
                'country_id' => 73,
            ],
            298 => 
            [
                'id' => 1299,
                'name' => 'K\'ut\'aisi',
                'code' => '31',
                'country_id' => 73,
            ],
            299 => 
            [
                'id' => 1300,
                'name' => 'Akhalgoris Raioni',
                'code' => '05',
                'country_id' => 73,
            ],
            300 => 
            [
                'id' => 1301,
                'name' => 'Aspindzis Raioni',
                'code' => '10',
                'country_id' => 73,
            ],
            301 => 
            [
                'id' => 1302,
                'name' => 'Akhmetis Raioni',
                'code' => '08',
                'country_id' => 73,
            ],
            302 => 
            [
                'id' => 1303,
                'name' => 'Lagodekhis Raioni',
                'code' => '32',
                'country_id' => 73,
            ],
            303 => 
            [
                'id' => 1304,
                'name' => 'Zugdidis Raioni',
                'code' => '64',
                'country_id' => 73,
            ],
            304 => 
            [
                'id' => 1305,
                'name' => 'Borjomis Raioni',
                'code' => '13',
                'country_id' => 73,
            ],
            305 => 
            [
                'id' => 1306,
                'name' => 'T\'ianet\'is Raioni',
                'code' => '55',
                'country_id' => 73,
            ],
            306 => 
            [
                'id' => 1307,
                'name' => 'Khobis Raioni',
                'code' => '29',
                'country_id' => 73,
            ],
            307 => 
            [
                'id' => 1308,
                'name' => 'Kharagaulis Raioni',
                'code' => '27',
                'country_id' => 73,
            ],
            308 => 
            [
                'id' => 1309,
                'name' => 'Vanis Raioni',
                'code' => '61',
                'country_id' => 73,
            ],
            309 => 
            [
                'id' => 1310,
                'name' => 'T\'elavis Raioni',
                'code' => '52',
                'country_id' => 73,
            ],
            310 => 
            [
                'id' => 1311,
                'name' => 'Tsalkis Raioni',
                'code' => '59',
                'country_id' => 73,
            ],
            311 => 
            [
                'id' => 1312,
                'name' => 'Qazbegis Raioni',
                'code' => '43',
                'country_id' => 73,
            ],
            312 => 
            [
                'id' => 1313,
                'name' => 'Sagarejos Raioni',
                'code' => '47',
                'country_id' => 73,
            ],
            313 => 
            [
                'id' => 1314,
                'name' => 'T\'et\'ritsqaros Raioni',
                'code' => '54',
                'country_id' => 73,
            ],
            314 => 
            [
                'id' => 1315,
                'name' => 'Dedop\'listsqaros Raioni',
                'code' => '17',
                'country_id' => 73,
            ],
            315 => 
            [
                'id' => 1316,
                'name' => 'Javis Raioni',
                'code' => '24',
                'country_id' => 73,
            ],
            316 => 
            [
                'id' => 1317,
                'name' => 'Ch\'khorotsqus Raioni',
                'code' => '15',
                'country_id' => 73,
            ],
            317 => 
            [
                'id' => 1318,
                'name' => 'Tsqaltubo',
                'code' => '60',
                'country_id' => 73,
            ],
            318 => 
            [
                'id' => 1319,
                'name' => 'Rust\'avi',
                'code' => '45',
                'country_id' => 73,
            ],
            319 => 
            [
                'id' => 1320,
                'name' => 'T\'bilisi',
                'code' => '51',
                'country_id' => 73,
            ],
            320 => 
            [
                'id' => 1321,
                'name' => 'Baghdat\'is Raioni',
                'code' => '11',
                'country_id' => 73,
            ],
            321 => 
            [
                'id' => 1322,
                'name' => 'Lanch\'khut\'is Raioni',
                'code' => '33',
                'country_id' => 73,
            ],
            322 => 
            [
                'id' => 1323,
                'name' => 'Chiat\'ura',
                'code' => '14',
                'country_id' => 73,
            ],
            323 => 
            [
                'id' => 1324,
                'name' => 'Ts\'ageris Raioni',
                'code' => '57',
                'country_id' => 73,
            ],
            324 => 
            [
                'id' => 1325,
                'name' => '',
                'code' => '00',
                'country_id' => 74,
            ],
            325 => 
            [
                'id' => 1326,
                'name' => '',
                'code' => '00',
                'country_id' => 75,
            ],
            326 => 
            [
                'id' => 1327,
                'name' => 'Central',
                'code' => '04',
                'country_id' => 76,
            ],
            327 => 
            [
                'id' => 1328,
                'name' => 'Western',
                'code' => '09',
                'country_id' => 76,
            ],
            328 => 
            [
                'id' => 1329,
                'name' => 'Ashanti',
                'code' => '02',
                'country_id' => 76,
            ],
            329 => 
            [
                'id' => 1330,
                'name' => 'Upper East',
                'code' => '10',
                'country_id' => 76,
            ],
            330 => 
            [
                'id' => 1331,
                'name' => 'Volta',
                'code' => '08',
                'country_id' => 76,
            ],
            331 => 
            [
                'id' => 1332,
                'name' => 'Brong-Ahafo',
                'code' => '03',
                'country_id' => 76,
            ],
            332 => 
            [
                'id' => 1333,
                'name' => 'Northern',
                'code' => '06',
                'country_id' => 76,
            ],
            333 => 
            [
                'id' => 1334,
                'name' => 'Greater Accra',
                'code' => '01',
                'country_id' => 76,
            ],
            334 => 
            [
                'id' => 1335,
                'name' => 'Upper West',
                'code' => '11',
                'country_id' => 76,
            ],
            335 => 
            [
                'id' => 1336,
                'name' => 'Eastern',
                'code' => '05',
                'country_id' => 76,
            ],
            336 => 
            [
                'id' => 1337,
                'name' => '',
                'code' => '00',
                'country_id' => 77,
            ],
            337 => 
            [
                'id' => 1338,
                'name' => 'Vestgronland',
                'code' => '03',
                'country_id' => 78,
            ],
            338 => 
            [
                'id' => 1339,
                'name' => 'Nordgronland',
                'code' => '01',
                'country_id' => 78,
            ],
            339 => 
            [
                'id' => 1340,
                'name' => 'Ostgronland',
                'code' => '02',
                'country_id' => 78,
            ],
            340 => 
            [
                'id' => 1341,
                'name' => 'Central River',
                'code' => '03',
                'country_id' => 79,
            ],
            341 => 
            [
                'id' => 1342,
                'name' => 'Western',
                'code' => '05',
                'country_id' => 79,
            ],
            342 => 
            [
                'id' => 1343,
                'name' => 'North Bank',
                'code' => '07',
                'country_id' => 79,
            ],
            343 => 
            [
                'id' => 1344,
                'name' => 'Upper River',
                'code' => '04',
                'country_id' => 79,
            ],
            344 => 
            [
                'id' => 1345,
                'name' => 'Lower River',
                'code' => '02',
                'country_id' => 79,
            ],
            345 => 
            [
                'id' => 1346,
                'name' => 'Banjul',
                'code' => '01',
                'country_id' => 79,
            ],
            346 => 
            [
                'id' => 1347,
                'name' => 'Kouroussa',
                'code' => '19',
                'country_id' => 80,
            ],
            347 => 
            [
                'id' => 1348,
                'name' => 'Beyla',
                'code' => '01',
                'country_id' => 80,
            ],
            348 => 
            [
                'id' => 1349,
                'name' => 'Koundara',
                'code' => '18',
                'country_id' => 80,
            ],
            349 => 
            [
                'id' => 1350,
                'name' => 'Dinguiraye',
                'code' => '07',
                'country_id' => 80,
            ],
            350 => 
            [
                'id' => 1351,
                'name' => 'Mali',
                'code' => '22',
                'country_id' => 80,
            ],
            351 => 
            [
                'id' => 1352,
                'name' => 'Macenta',
                'code' => '21',
                'country_id' => 80,
            ],
            352 => 
            [
                'id' => 1353,
                'name' => '',
                'code' => '26',
                'country_id' => 80,
            ],
            353 => 
            [
                'id' => 1354,
                'name' => '',
                'code' => '24',
                'country_id' => 80,
            ],
            354 => 
            [
                'id' => 1355,
                'name' => 'Kissidougou',
                'code' => '17',
                'country_id' => 80,
            ],
            355 => 
            [
                'id' => 1356,
                'name' => 'Forecariah',
                'code' => '10',
                'country_id' => 80,
            ],
            356 => 
            [
                'id' => 1357,
                'name' => 'Pita',
                'code' => '25',
                'country_id' => 80,
            ],
            357 => 
            [
                'id' => 1358,
                'name' => '',
                'code' => '14',
                'country_id' => 80,
            ],
            358 => 
            [
                'id' => 1359,
                'name' => '',
                'code' => '20',
                'country_id' => 80,
            ],
            359 => 
            [
                'id' => 1360,
                'name' => '',
                'code' => '08',
                'country_id' => 80,
            ],
            360 => 
            [
                'id' => 1361,
                'name' => 'Dabola',
                'code' => '05',
                'country_id' => 80,
            ],
            361 => 
            [
                'id' => 1362,
                'name' => 'Boke',
                'code' => '03',
                'country_id' => 80,
            ],
            362 => 
            [
                'id' => 1363,
                'name' => 'Mamou',
                'code' => '23',
                'country_id' => 80,
            ],
            363 => 
            [
                'id' => 1364,
                'name' => 'Faranah',
                'code' => '09',
                'country_id' => 80,
            ],
            364 => 
            [
                'id' => 1365,
                'name' => 'Telimele',
                'code' => '27',
                'country_id' => 80,
            ],
            365 => 
            [
                'id' => 1366,
                'name' => 'Boffa',
                'code' => '02',
                'country_id' => 80,
            ],
            366 => 
            [
                'id' => 1367,
                'name' => 'Gueckedou',
                'code' => '13',
                'country_id' => 80,
            ],
            367 => 
            [
                'id' => 1368,
                'name' => 'Kindia',
                'code' => '16',
                'country_id' => 80,
            ],
            368 => 
            [
                'id' => 1369,
                'name' => 'Fria',
                'code' => '11',
                'country_id' => 80,
            ],
            369 => 
            [
                'id' => 1370,
                'name' => 'Tougue',
                'code' => '28',
                'country_id' => 80,
            ],
            370 => 
            [
                'id' => 1371,
                'name' => 'Yomou',
                'code' => '29',
                'country_id' => 80,
            ],
            371 => 
            [
                'id' => 1372,
                'name' => 'Gaoual',
                'code' => '12',
                'country_id' => 80,
            ],
            372 => 
            [
                'id' => 1373,
                'name' => 'Kerouane',
                'code' => '15',
                'country_id' => 80,
            ],
            373 => 
            [
                'id' => 1374,
                'name' => 'Dalaba',
                'code' => '06',
                'country_id' => 80,
            ],
            374 => 
            [
                'id' => 1375,
                'name' => 'Conakry',
                'code' => '04',
                'country_id' => 80,
            ],
            375 => 
            [
                'id' => 1376,
                'name' => 'Coyah',
                'code' => '30',
                'country_id' => 80,
            ],
            376 => 
            [
                'id' => 1377,
                'name' => 'Dubreka',
                'code' => '31',
                'country_id' => 80,
            ],
            377 => 
            [
                'id' => 1378,
                'name' => 'Kankan',
                'code' => '32',
                'country_id' => 80,
            ],
            378 => 
            [
                'id' => 1379,
                'name' => 'Koubia',
                'code' => '33',
                'country_id' => 80,
            ],
            379 => 
            [
                'id' => 1380,
                'name' => 'Labe',
                'code' => '34',
                'country_id' => 80,
            ],
            380 => 
            [
                'id' => 1381,
                'name' => 'Lelouma',
                'code' => '35',
                'country_id' => 80,
            ],
            381 => 
            [
                'id' => 1382,
                'name' => 'Lola',
                'code' => '36',
                'country_id' => 80,
            ],
            382 => 
            [
                'id' => 1383,
                'name' => 'Mandiana',
                'code' => '37',
                'country_id' => 80,
            ],
            383 => 
            [
                'id' => 1384,
                'name' => 'Nzerekore',
                'code' => '38',
                'country_id' => 80,
            ],
            384 => 
            [
                'id' => 1385,
                'name' => 'Siguiri',
                'code' => '39',
                'country_id' => 80,
            ],
            385 => 
            [
                'id' => 1386,
                'name' => '',
                'code' => '00',
                'country_id' => 81,
            ],
            386 => 
            [
                'id' => 1387,
                'name' => 'Centro Sur',
                'code' => '06',
                'country_id' => 82,
            ],
            387 => 
            [
                'id' => 1388,
                'name' => 'Wele-Nzas',
                'code' => '09',
                'country_id' => 82,
            ],
            388 => 
            [
                'id' => 1389,
                'name' => 'Kie-Ntem',
                'code' => '07',
                'country_id' => 82,
            ],
            389 => 
            [
                'id' => 1390,
                'name' => 'Litoral',
                'code' => '08',
                'country_id' => 82,
            ],
            390 => 
            [
                'id' => 1391,
                'name' => 'Annobon',
                'code' => '03',
                'country_id' => 82,
            ],
            391 => 
            [
                'id' => 1392,
                'name' => 'Bioko Norte',
                'code' => '04',
                'country_id' => 82,
            ],
            392 => 
            [
                'id' => 1393,
                'name' => 'Bioko Sur',
                'code' => '05',
                'country_id' => 82,
            ],
            393 => 
            [
                'id' => 1394,
                'name' => '',
                'code' => '00',
                'country_id' => 82,
            ],
            394 => 
            [
                'id' => 1395,
                'name' => 'Kilkis',
                'code' => '06',
                'country_id' => 83,
            ],
            395 => 
            [
                'id' => 1396,
                'name' => 'Larisa',
                'code' => '21',
                'country_id' => 83,
            ],
            396 => 
            [
                'id' => 1397,
                'name' => 'Attiki',
                'code' => '35',
                'country_id' => 83,
            ],
            397 => 
            [
                'id' => 1398,
                'name' => 'Trikala',
                'code' => '22',
                'country_id' => 83,
            ],
            398 => 
            [
                'id' => 1399,
                'name' => 'Preveza',
                'code' => '19',
                'country_id' => 83,
            ],
            399 => 
            [
                'id' => 1400,
                'name' => 'Kerkira',
                'code' => '25',
                'country_id' => 83,
            ],
            400 => 
            [
                'id' => 1401,
                'name' => 'Ioannina',
                'code' => '17',
                'country_id' => 83,
            ],
            401 => 
            [
                'id' => 1402,
                'name' => 'Pella',
                'code' => '07',
                'country_id' => 83,
            ],
            402 => 
            [
                'id' => 1403,
                'name' => 'Thessaloniki',
                'code' => '13',
                'country_id' => 83,
            ],
            403 => 
            [
                'id' => 1404,
                'name' => 'Voiotia',
                'code' => '33',
                'country_id' => 83,
            ],
            404 => 
            [
                'id' => 1405,
                'name' => 'Kikladhes',
                'code' => '49',
                'country_id' => 83,
            ],
            405 => 
            [
                'id' => 1406,
                'name' => 'Kavala',
                'code' => '14',
                'country_id' => 83,
            ],
            406 => 
            [
                'id' => 1407,
                'name' => 'Argolis',
                'code' => '36',
                'country_id' => 83,
            ],
            407 => 
            [
                'id' => 1408,
                'name' => 'Rethimni',
                'code' => '44',
                'country_id' => 83,
            ],
            408 => 
            [
                'id' => 1409,
                'name' => 'Serrai',
                'code' => '05',
                'country_id' => 83,
            ],
            409 => 
            [
                'id' => 1410,
                'name' => 'Lakonia',
                'code' => '42',
                'country_id' => 83,
            ],
            410 => 
            [
                'id' => 1411,
                'name' => 'Iraklion',
                'code' => '45',
                'country_id' => 83,
            ],
            411 => 
            [
                'id' => 1412,
                'name' => 'Lasithi',
                'code' => '46',
                'country_id' => 83,
            ],
            412 => 
            [
                'id' => 1413,
                'name' => 'Rodhopi',
                'code' => '02',
                'country_id' => 83,
            ],
            413 => 
            [
                'id' => 1414,
                'name' => 'Drama',
                'code' => '04',
                'country_id' => 83,
            ],
            414 => 
            [
                'id' => 1415,
                'name' => 'Messinia',
                'code' => '40',
                'country_id' => 83,
            ],
            415 => 
            [
                'id' => 1416,
                'name' => 'Evvoia',
                'code' => '34',
                'country_id' => 83,
            ],
            416 => 
            [
                'id' => 1417,
                'name' => 'Akhaia',
                'code' => '38',
                'country_id' => 83,
            ],
            417 => 
            [
                'id' => 1418,
                'name' => 'Magnisia',
                'code' => '24',
                'country_id' => 83,
            ],
            418 => 
            [
                'id' => 1419,
                'name' => 'Khania',
                'code' => '43',
                'country_id' => 83,
            ],
            419 => 
            [
                'id' => 1420,
                'name' => 'Kardhitsa',
                'code' => '23',
                'country_id' => 83,
            ],
            420 => 
            [
                'id' => 1421,
                'name' => 'Evros',
                'code' => '01',
                'country_id' => 83,
            ],
            421 => 
            [
                'id' => 1422,
                'name' => 'Arkadhia',
                'code' => '41',
                'country_id' => 83,
            ],
            422 => 
            [
                'id' => 1423,
                'name' => 'Aitolia kai Akarnania',
                'code' => '31',
                'country_id' => 83,
            ],
            423 => 
            [
                'id' => 1424,
                'name' => 'Kozani',
                'code' => '11',
                'country_id' => 83,
            ],
            424 => 
            [
                'id' => 1425,
                'name' => 'Thesprotia',
                'code' => '18',
                'country_id' => 83,
            ],
            425 => 
            [
                'id' => 1426,
                'name' => 'Lesvos',
                'code' => '51',
                'country_id' => 83,
            ],
            426 => 
            [
                'id' => 1427,
                'name' => 'Dhodhekanisos',
                'code' => '47',
                'country_id' => 83,
            ],
            427 => 
            [
                'id' => 1428,
                'name' => 'Kefallinia',
                'code' => '27',
                'country_id' => 83,
            ],
            428 => 
            [
                'id' => 1429,
                'name' => 'Khios',
                'code' => '50',
                'country_id' => 83,
            ],
            429 => 
            [
                'id' => 1430,
                'name' => 'Arta',
                'code' => '20',
                'country_id' => 83,
            ],
            430 => 
            [
                'id' => 1431,
                'name' => 'Grevena',
                'code' => '10',
                'country_id' => 83,
            ],
            431 => 
            [
                'id' => 1432,
                'name' => 'Zakinthos',
                'code' => '28',
                'country_id' => 83,
            ],
            432 => 
            [
                'id' => 1433,
                'name' => 'Evritania',
                'code' => '30',
                'country_id' => 83,
            ],
            433 => 
            [
                'id' => 1434,
                'name' => 'Fthiotis',
                'code' => '29',
                'country_id' => 83,
            ],
            434 => 
            [
                'id' => 1435,
                'name' => 'Kastoria',
                'code' => '09',
                'country_id' => 83,
            ],
            435 => 
            [
                'id' => 1436,
                'name' => 'Samos',
                'code' => '48',
                'country_id' => 83,
            ],
            436 => 
            [
                'id' => 1437,
                'name' => 'Imathia',
                'code' => '12',
                'country_id' => 83,
            ],
            437 => 
            [
                'id' => 1438,
                'name' => 'Florina',
                'code' => '08',
                'country_id' => 83,
            ],
            438 => 
            [
                'id' => 1439,
                'name' => 'Pieria',
                'code' => '16',
                'country_id' => 83,
            ],
            439 => 
            [
                'id' => 1440,
                'name' => 'Levkas',
                'code' => '26',
                'country_id' => 83,
            ],
            440 => 
            [
                'id' => 1441,
                'name' => 'Fokis',
                'code' => '32',
                'country_id' => 83,
            ],
            441 => 
            [
                'id' => 1442,
                'name' => 'Ilia',
                'code' => '39',
                'country_id' => 83,
            ],
            442 => 
            [
                'id' => 1443,
                'name' => 'Korinthia',
                'code' => '37',
                'country_id' => 83,
            ],
            443 => 
            [
                'id' => 1444,
                'name' => 'Xanthi',
                'code' => '03',
                'country_id' => 83,
            ],
            444 => 
            [
                'id' => 1445,
                'name' => 'Khalkidhiki',
                'code' => '15',
                'country_id' => 83,
            ],
            445 => 
            [
                'id' => 1446,
                'name' => '',
                'code' => '00',
                'country_id' => 83,
            ],
            446 => 
            [
                'id' => 1447,
                'name' => '',
                'code' => '00',
                'country_id' => 84,
            ],
            447 => 
            [
                'id' => 1448,
                'name' => 'Izabal',
                'code' => '09',
                'country_id' => 85,
            ],
            448 => 
            [
                'id' => 1449,
                'name' => 'Alta Verapaz',
                'code' => '01',
                'country_id' => 85,
            ],
            449 => 
            [
                'id' => 1450,
                'name' => 'Retalhuleu',
                'code' => '15',
                'country_id' => 85,
            ],
            450 => 
            [
                'id' => 1451,
                'name' => 'El Progreso',
                'code' => '05',
                'country_id' => 85,
            ],
            451 => 
            [
                'id' => 1452,
                'name' => 'Guatemala',
                'code' => '07',
                'country_id' => 85,
            ],
            452 => 
            [
                'id' => 1453,
                'name' => 'Jutiapa',
                'code' => '11',
                'country_id' => 85,
            ],
            453 => 
            [
                'id' => 1454,
                'name' => 'Chimaltenango',
                'code' => '03',
                'country_id' => 85,
            ],
            454 => 
            [
                'id' => 1455,
                'name' => 'Chiquimula',
                'code' => '04',
                'country_id' => 85,
            ],
            455 => 
            [
                'id' => 1456,
                'name' => 'Zacapa',
                'code' => '22',
                'country_id' => 85,
            ],
            456 => 
            [
                'id' => 1457,
                'name' => 'Jalapa',
                'code' => '10',
                'country_id' => 85,
            ],
            457 => 
            [
                'id' => 1458,
                'name' => 'San Marcos',
                'code' => '17',
                'country_id' => 85,
            ],
            458 => 
            [
                'id' => 1459,
                'name' => 'Quiche',
                'code' => '14',
                'country_id' => 85,
            ],
            459 => 
            [
                'id' => 1460,
                'name' => 'Huehuetenango',
                'code' => '08',
                'country_id' => 85,
            ],
            460 => 
            [
                'id' => 1461,
                'name' => 'Quetzaltenango',
                'code' => '13',
                'country_id' => 85,
            ],
            461 => 
            [
                'id' => 1462,
                'name' => 'Baja Verapaz',
                'code' => '02',
                'country_id' => 85,
            ],
            462 => 
            [
                'id' => 1463,
                'name' => 'Santa Rosa',
                'code' => '18',
                'country_id' => 85,
            ],
            463 => 
            [
                'id' => 1464,
                'name' => 'Solola',
                'code' => '19',
                'country_id' => 85,
            ],
            464 => 
            [
                'id' => 1465,
                'name' => 'Peten',
                'code' => '12',
                'country_id' => 85,
            ],
            465 => 
            [
                'id' => 1466,
                'name' => 'Escuintla',
                'code' => '06',
                'country_id' => 85,
            ],
            466 => 
            [
                'id' => 1467,
                'name' => 'Sacatepequez',
                'code' => '16',
                'country_id' => 85,
            ],
            467 => 
            [
                'id' => 1468,
                'name' => 'Totonicapan',
                'code' => '21',
                'country_id' => 85,
            ],
            468 => 
            [
                'id' => 1469,
                'name' => 'Suchitepequez',
                'code' => '20',
                'country_id' => 85,
            ],
            469 => 
            [
                'id' => 1470,
                'name' => 'Cacheu',
                'code' => '06',
                'country_id' => 86,
            ],
            470 => 
            [
                'id' => 1471,
                'name' => 'Biombo',
                'code' => '12',
                'country_id' => 86,
            ],
            471 => 
            [
                'id' => 1472,
                'name' => 'Oio',
                'code' => '04',
                'country_id' => 86,
            ],
            472 => 
            [
                'id' => 1473,
                'name' => 'Bissau',
                'code' => '11',
                'country_id' => 86,
            ],
            473 => 
            [
                'id' => 1474,
                'name' => 'Bafata',
                'code' => '01',
                'country_id' => 86,
            ],
            474 => 
            [
                'id' => 1475,
                'name' => 'Tombali',
                'code' => '07',
                'country_id' => 86,
            ],
            475 => 
            [
                'id' => 1476,
                'name' => 'Gabu',
                'code' => '10',
                'country_id' => 86,
            ],
            476 => 
            [
                'id' => 1477,
                'name' => 'Bolama',
                'code' => '05',
                'country_id' => 86,
            ],
            477 => 
            [
                'id' => 1478,
                'name' => 'Quinara',
                'code' => '02',
                'country_id' => 86,
            ],
            478 => 
            [
                'id' => 1479,
                'name' => 'Mahaica-Berbice',
                'code' => '15',
                'country_id' => 87,
            ],
            479 => 
            [
                'id' => 1480,
                'name' => 'Upper Takutu-Upper Essequibo',
                'code' => '19',
                'country_id' => 87,
            ],
            480 => 
            [
                'id' => 1481,
                'name' => 'Barima-Waini',
                'code' => '10',
                'country_id' => 87,
            ],
            481 => 
            [
                'id' => 1482,
                'name' => 'Pomeroon-Supenaam',
                'code' => '16',
                'country_id' => 87,
            ],
            482 => 
            [
                'id' => 1483,
                'name' => 'Demerara-Mahaica',
                'code' => '12',
                'country_id' => 87,
            ],
            483 => 
            [
                'id' => 1484,
                'name' => 'Cuyuni-Mazaruni',
                'code' => '11',
                'country_id' => 87,
            ],
            484 => 
            [
                'id' => 1485,
                'name' => 'East Berbice-Corentyne',
                'code' => '13',
                'country_id' => 87,
            ],
            485 => 
            [
                'id' => 1486,
                'name' => 'Essequibo Islands-West Demerara',
                'code' => '14',
                'country_id' => 87,
            ],
            486 => 
            [
                'id' => 1487,
                'name' => 'Potaro-Siparuni',
                'code' => '17',
                'country_id' => 87,
            ],
            487 => 
            [
                'id' => 1488,
                'name' => 'Upper Demerara-Berbice',
                'code' => '18',
                'country_id' => 87,
            ],
            488 => 
            [
                'id' => 1489,
                'name' => '',
                'code' => '00',
                'country_id' => 88,
            ],
            489 => 
            [
                'id' => 1490,
                'name' => 'Colon',
                'code' => '03',
                'country_id' => 89,
            ],
            490 => 
            [
                'id' => 1491,
                'name' => 'Choluteca',
                'code' => '02',
                'country_id' => 89,
            ],
            491 => 
            [
                'id' => 1492,
                'name' => 'Comayagua',
                'code' => '04',
                'country_id' => 89,
            ],
            492 => 
            [
                'id' => 1493,
                'name' => 'Valle',
                'code' => '17',
                'country_id' => 89,
            ],
            493 => 
            [
                'id' => 1494,
                'name' => 'Santa Barbara',
                'code' => '16',
                'country_id' => 89,
            ],
            494 => 
            [
                'id' => 1495,
                'name' => 'Francisco Morazan',
                'code' => '08',
                'country_id' => 89,
            ],
            495 => 
            [
                'id' => 1496,
                'name' => 'El Paraiso',
                'code' => '07',
                'country_id' => 89,
            ],
            496 => 
            [
                'id' => 1497,
                'name' => 'Lempira',
                'code' => '13',
                'country_id' => 89,
            ],
            497 => 
            [
                'id' => 1498,
                'name' => 'Copan',
                'code' => '05',
                'country_id' => 89,
            ],
            498 => 
            [
                'id' => 1499,
                'name' => 'Olancho',
                'code' => '15',
                'country_id' => 89,
            ],
            499 => 
            [
                'id' => 1500,
                'name' => 'Cortes',
                'code' => '06',
                'country_id' => 89,
            ],
        ]);
        \DB::table('regions')->insert([
            0 => 
            [
                'id' => 1501,
                'name' => 'Yoro',
                'code' => '18',
                'country_id' => 89,
            ],
            1 => 
            [
                'id' => 1502,
                'name' => 'Atlantida',
                'code' => '01',
                'country_id' => 89,
            ],
            2 => 
            [
                'id' => 1503,
                'name' => 'Intibuca',
                'code' => '10',
                'country_id' => 89,
            ],
            3 => 
            [
                'id' => 1504,
                'name' => 'La Paz',
                'code' => '12',
                'country_id' => 89,
            ],
            4 => 
            [
                'id' => 1505,
                'name' => 'Ocotepeque',
                'code' => '14',
                'country_id' => 89,
            ],
            5 => 
            [
                'id' => 1506,
                'name' => 'Gracias a Dios',
                'code' => '09',
                'country_id' => 89,
            ],
            6 => 
            [
                'id' => 1507,
                'name' => 'Islas de la Bahia',
                'code' => '11',
                'country_id' => 89,
            ],
            7 => 
            [
                'id' => 1508,
                'name' => 'Primorsko-Goranska',
                'code' => '12',
                'country_id' => 90,
            ],
            8 => 
            [
                'id' => 1509,
                'name' => 'Splitsko-Dalmatinska',
                'code' => '15',
                'country_id' => 90,
            ],
            9 => 
            [
                'id' => 1510,
                'name' => 'Istarska',
                'code' => '04',
                'country_id' => 90,
            ],
            10 => 
            [
                'id' => 1511,
                'name' => 'Osjecko-Baranjska',
                'code' => '10',
                'country_id' => 90,
            ],
            11 => 
            [
                'id' => 1512,
                'name' => 'Viroviticko-Podravska',
                'code' => '17',
                'country_id' => 90,
            ],
            12 => 
            [
                'id' => 1513,
                'name' => 'Grad Zagreb',
                'code' => '21',
                'country_id' => 90,
            ],
            13 => 
            [
                'id' => 1514,
                'name' => 'Sisacko-Moslavacka',
                'code' => '14',
                'country_id' => 90,
            ],
            14 => 
            [
                'id' => 1515,
                'name' => 'Licko-Senjska',
                'code' => '08',
                'country_id' => 90,
            ],
            15 => 
            [
                'id' => 1516,
                'name' => 'Brodsko-Posavska',
                'code' => '02',
                'country_id' => 90,
            ],
            16 => 
            [
                'id' => 1517,
                'name' => 'Dubrovacko-Neretvanska',
                'code' => '03',
                'country_id' => 90,
            ],
            17 => 
            [
                'id' => 1518,
                'name' => 'Pozesko-Slavonska',
                'code' => '11',
                'country_id' => 90,
            ],
            18 => 
            [
                'id' => 1519,
                'name' => 'Zagrebacka',
                'code' => '20',
                'country_id' => 90,
            ],
            19 => 
            [
                'id' => 1520,
                'name' => 'Bjelovarsko-Bilogorska',
                'code' => '01',
                'country_id' => 90,
            ],
            20 => 
            [
                'id' => 1521,
                'name' => 'Varazdinska',
                'code' => '16',
                'country_id' => 90,
            ],
            21 => 
            [
                'id' => 1522,
                'name' => 'Vukovarsko-Srijemska',
                'code' => '18',
                'country_id' => 90,
            ],
            22 => 
            [
                'id' => 1523,
                'name' => 'Krapinsko-Zagorska',
                'code' => '07',
                'country_id' => 90,
            ],
            23 => 
            [
                'id' => 1524,
                'name' => 'Koprivnicko-Krizevacka',
                'code' => '06',
                'country_id' => 90,
            ],
            24 => 
            [
                'id' => 1525,
                'name' => 'Karlovacka',
                'code' => '05',
                'country_id' => 90,
            ],
            25 => 
            [
                'id' => 1526,
                'name' => 'Sibensko-Kninska',
                'code' => '13',
                'country_id' => 90,
            ],
            26 => 
            [
                'id' => 1527,
                'name' => 'Medimurska',
                'code' => '09',
                'country_id' => 90,
            ],
            27 => 
            [
                'id' => 1528,
                'name' => '',
                'code' => '00',
                'country_id' => 90,
            ],
            28 => 
            [
                'id' => 1529,
                'name' => 'Sud',
                'code' => '12',
                'country_id' => 91,
            ],
            29 => 
            [
                'id' => 1530,
                'name' => 'Centre',
                'code' => '07',
                'country_id' => 91,
            ],
            30 => 
            [
                'id' => 1531,
                'name' => '',
                'code' => '08',
                'country_id' => 91,
            ],
            31 => 
            [
                'id' => 1532,
                'name' => 'Ouest',
                'code' => '11',
                'country_id' => 91,
            ],
            32 => 
            [
                'id' => 1533,
                'name' => 'Nord',
                'code' => '09',
                'country_id' => 91,
            ],
            33 => 
            [
                'id' => 1534,
                'name' => 'Nord-Ouest',
                'code' => '03',
                'country_id' => 91,
            ],
            34 => 
            [
                'id' => 1535,
                'name' => 'Nord-Est',
                'code' => '10',
                'country_id' => 91,
            ],
            35 => 
            [
                'id' => 1536,
                'name' => 'Sud-Est',
                'code' => '13',
                'country_id' => 91,
            ],
            36 => 
            [
                'id' => 1537,
                'name' => 'Artibonite',
                'code' => '06',
                'country_id' => 91,
            ],
            37 => 
            [
                'id' => 1538,
                'name' => 'Komarom-Esztergom',
                'code' => '12',
                'country_id' => 92,
            ],
            38 => 
            [
                'id' => 1539,
                'name' => 'Fejer',
                'code' => '08',
                'country_id' => 92,
            ],
            39 => 
            [
                'id' => 1540,
                'name' => 'Jasz-Nagykun-Szolnok',
                'code' => '20',
                'country_id' => 92,
            ],
            40 => 
            [
                'id' => 1541,
                'name' => 'Baranya',
                'code' => '02',
                'country_id' => 92,
            ],
            41 => 
            [
                'id' => 1542,
                'name' => 'Szabolcs-Szatmar-Bereg',
                'code' => '18',
                'country_id' => 92,
            ],
            42 => 
            [
                'id' => 1543,
                'name' => 'Heves',
                'code' => '11',
                'country_id' => 92,
            ],
            43 => 
            [
                'id' => 1544,
                'name' => 'Borsod-Abauj-Zemplen',
                'code' => '04',
                'country_id' => 92,
            ],
            44 => 
            [
                'id' => 1545,
                'name' => 'Gyor-Moson-Sopron',
                'code' => '09',
                'country_id' => 92,
            ],
            45 => 
            [
                'id' => 1546,
                'name' => 'Pest',
                'code' => '16',
                'country_id' => 92,
            ],
            46 => 
            [
                'id' => 1547,
                'name' => 'Veszprem',
                'code' => '23',
                'country_id' => 92,
            ],
            47 => 
            [
                'id' => 1548,
                'name' => 'Bacs-Kiskun',
                'code' => '01',
                'country_id' => 92,
            ],
            48 => 
            [
                'id' => 1549,
                'name' => 'Vas',
                'code' => '22',
                'country_id' => 92,
            ],
            49 => 
            [
                'id' => 1550,
                'name' => 'Hajdu-Bihar',
                'code' => '10',
                'country_id' => 92,
            ],
            50 => 
            [
                'id' => 1551,
                'name' => 'Zala',
                'code' => '24',
                'country_id' => 92,
            ],
            51 => 
            [
                'id' => 1552,
                'name' => 'Somogy',
                'code' => '17',
                'country_id' => 92,
            ],
            52 => 
            [
                'id' => 1553,
                'name' => 'Tolna',
                'code' => '21',
                'country_id' => 92,
            ],
            53 => 
            [
                'id' => 1554,
                'name' => 'Nograd',
                'code' => '14',
                'country_id' => 92,
            ],
            54 => 
            [
                'id' => 1555,
                'name' => 'Budapest',
                'code' => '05',
                'country_id' => 92,
            ],
            55 => 
            [
                'id' => 1556,
                'name' => 'Miskolc',
                'code' => '13',
                'country_id' => 92,
            ],
            56 => 
            [
                'id' => 1557,
                'name' => 'Csongrad',
                'code' => '06',
                'country_id' => 92,
            ],
            57 => 
            [
                'id' => 1558,
                'name' => 'Debrecen',
                'code' => '07',
                'country_id' => 92,
            ],
            58 => 
            [
                'id' => 1559,
                'name' => 'Bekes',
                'code' => '03',
                'country_id' => 92,
            ],
            59 => 
            [
                'id' => 1560,
                'name' => 'Szeged',
                'code' => '19',
                'country_id' => 92,
            ],
            60 => 
            [
                'id' => 1561,
                'name' => 'Pecs',
                'code' => '15',
                'country_id' => 92,
            ],
            61 => 
            [
                'id' => 1562,
                'name' => 'Gyor',
                'code' => '25',
                'country_id' => 92,
            ],
            62 => 
            [
                'id' => 1563,
                'name' => 'Jawa Timur',
                'code' => '08',
                'country_id' => 93,
            ],
            63 => 
            [
                'id' => 1564,
                'name' => '',
                'code' => '09',
                'country_id' => 93,
            ],
            64 => 
            [
                'id' => 1565,
                'name' => 'Sulawesi Tenggara',
                'code' => '22',
                'country_id' => 93,
            ],
            65 => 
            [
                'id' => 1566,
                'name' => '',
                'code' => '25',
                'country_id' => 93,
            ],
            66 => 
            [
                'id' => 1567,
                'name' => 'Nusa Tenggara Timur',
                'code' => '18',
                'country_id' => 93,
            ],
            67 => 
            [
                'id' => 1568,
                'name' => 'Sulawesi Utara',
                'code' => '31',
                'country_id' => 93,
            ],
            68 => 
            [
                'id' => 1569,
                'name' => 'Sumatera Barat',
                'code' => '24',
                'country_id' => 93,
            ],
            69 => 
            [
                'id' => 1570,
                'name' => 'Aceh',
                'code' => '01',
                'country_id' => 93,
            ],
            70 => 
            [
                'id' => 1571,
                'name' => 'Sulawesi Tengah',
                'code' => '21',
                'country_id' => 93,
            ],
            71 => 
            [
                'id' => 1572,
                'name' => '',
                'code' => '16',
                'country_id' => 93,
            ],
            72 => 
            [
                'id' => 1573,
                'name' => '',
                'code' => '20',
                'country_id' => 93,
            ],
            73 => 
            [
                'id' => 1574,
                'name' => '',
                'code' => '23',
                'country_id' => 93,
            ],
            74 => 
            [
                'id' => 1575,
                'name' => 'Jawa Tengah',
                'code' => '07',
                'country_id' => 93,
            ],
            75 => 
            [
                'id' => 1576,
                'name' => 'Jawa Barat',
                'code' => '30',
                'country_id' => 93,
            ],
            76 => 
            [
                'id' => 1577,
                'name' => 'Bali',
                'code' => '02',
                'country_id' => 93,
            ],
            77 => 
            [
                'id' => 1578,
                'name' => '',
                'code' => '00',
                'country_id' => 93,
            ],
            78 => 
            [
                'id' => 1579,
                'name' => 'Banten',
                'code' => '33',
                'country_id' => 93,
            ],
            79 => 
            [
                'id' => 1580,
                'name' => 'Sumatera Utara',
                'code' => '26',
                'country_id' => 93,
            ],
            80 => 
            [
                'id' => 1581,
                'name' => 'Kalimantan Timur',
                'code' => '14',
                'country_id' => 93,
            ],
            81 => 
            [
                'id' => 1582,
                'name' => 'Lampung',
                'code' => '15',
                'country_id' => 93,
            ],
            82 => 
            [
                'id' => 1583,
                'name' => 'Nusa Tenggara Barat',
                'code' => '17',
                'country_id' => 93,
            ],
            83 => 
            [
                'id' => 1584,
                'name' => 'Kalimantan Barat',
                'code' => '11',
                'country_id' => 93,
            ],
            84 => 
            [
                'id' => 1585,
                'name' => 'Kalimantan Tengah',
                'code' => '13',
                'country_id' => 93,
            ],
            85 => 
            [
                'id' => 1586,
                'name' => '',
                'code' => '19',
                'country_id' => 93,
            ],
            86 => 
            [
                'id' => 1587,
                'name' => 'Bengkulu',
                'code' => '03',
                'country_id' => 93,
            ],
            87 => 
            [
                'id' => 1588,
                'name' => 'Jambi',
                'code' => '05',
                'country_id' => 93,
            ],
            88 => 
            [
                'id' => 1589,
                'name' => 'Kalimantan Selatan',
                'code' => '12',
                'country_id' => 93,
            ],
            89 => 
            [
                'id' => 1590,
                'name' => 'Yogyakarta',
                'code' => '10',
                'country_id' => 93,
            ],
            90 => 
            [
                'id' => 1591,
                'name' => 'Jakarta Raya',
                'code' => '04',
                'country_id' => 93,
            ],
            91 => 
            [
                'id' => 1592,
                'name' => '',
                'code' => '06',
                'country_id' => 93,
            ],
            92 => 
            [
                'id' => 1593,
                'name' => 'Maluku',
                'code' => '28',
                'country_id' => 93,
            ],
            93 => 
            [
                'id' => 1594,
                'name' => 'Galway',
                'code' => '10',
                'country_id' => 94,
            ],
            94 => 
            [
                'id' => 1595,
                'name' => 'Cork',
                'code' => '04',
                'country_id' => 94,
            ],
            95 => 
            [
                'id' => 1596,
                'name' => 'Kerry',
                'code' => '11',
                'country_id' => 94,
            ],
            96 => 
            [
                'id' => 1597,
                'name' => 'Limerick',
                'code' => '16',
                'country_id' => 94,
            ],
            97 => 
            [
                'id' => 1598,
                'name' => 'Longford',
                'code' => '18',
                'country_id' => 94,
            ],
            98 => 
            [
                'id' => 1599,
                'name' => 'Laois',
                'code' => '15',
                'country_id' => 94,
            ],
            99 => 
            [
                'id' => 1600,
                'name' => 'Waterford',
                'code' => '27',
                'country_id' => 94,
            ],
            100 => 
            [
                'id' => 1601,
                'name' => 'Mayo',
                'code' => '20',
                'country_id' => 94,
            ],
            101 => 
            [
                'id' => 1602,
                'name' => 'Sligo',
                'code' => '25',
                'country_id' => 94,
            ],
            102 => 
            [
                'id' => 1603,
                'name' => 'Kildare',
                'code' => '12',
                'country_id' => 94,
            ],
            103 => 
            [
                'id' => 1604,
                'name' => 'Dublin',
                'code' => '07',
                'country_id' => 94,
            ],
            104 => 
            [
                'id' => 1605,
                'name' => 'Wicklow',
                'code' => '31',
                'country_id' => 94,
            ],
            105 => 
            [
                'id' => 1606,
                'name' => 'Cavan',
                'code' => '02',
                'country_id' => 94,
            ],
            106 => 
            [
                'id' => 1607,
                'name' => 'Kilkenny',
                'code' => '13',
                'country_id' => 94,
            ],
            107 => 
            [
                'id' => 1608,
                'name' => 'Wexford',
                'code' => '30',
                'country_id' => 94,
            ],
            108 => 
            [
                'id' => 1609,
                'name' => 'Carlow',
                'code' => '01',
                'country_id' => 94,
            ],
            109 => 
            [
                'id' => 1610,
                'name' => 'Offaly',
                'code' => '23',
                'country_id' => 94,
            ],
            110 => 
            [
                'id' => 1611,
                'name' => 'Monaghan',
                'code' => '22',
                'country_id' => 94,
            ],
            111 => 
            [
                'id' => 1612,
                'name' => 'Leitrim',
                'code' => '14',
                'country_id' => 94,
            ],
            112 => 
            [
                'id' => 1613,
                'name' => 'Clare',
                'code' => '03',
                'country_id' => 94,
            ],
            113 => 
            [
                'id' => 1614,
                'name' => 'Donegal',
                'code' => '06',
                'country_id' => 94,
            ],
            114 => 
            [
                'id' => 1615,
                'name' => 'Louth',
                'code' => '19',
                'country_id' => 94,
            ],
            115 => 
            [
                'id' => 1616,
                'name' => 'Roscommon',
                'code' => '24',
                'country_id' => 94,
            ],
            116 => 
            [
                'id' => 1617,
                'name' => 'Tipperary',
                'code' => '26',
                'country_id' => 94,
            ],
            117 => 
            [
                'id' => 1618,
                'name' => 'Westmeath',
                'code' => '29',
                'country_id' => 94,
            ],
            118 => 
            [
                'id' => 1619,
                'name' => 'Meath',
                'code' => '21',
                'country_id' => 94,
            ],
            119 => 
            [
                'id' => 1620,
                'name' => 'HaZafon',
                'code' => '03',
                'country_id' => 95,
            ],
            120 => 
            [
                'id' => 1621,
                'name' => 'HaDarom',
                'code' => '01',
                'country_id' => 95,
            ],
            121 => 
            [
                'id' => 1622,
                'name' => 'HaMerkaz',
                'code' => '02',
                'country_id' => 95,
            ],
            122 => 
            [
                'id' => 1623,
                'name' => 'Yerushalayim',
                'code' => '06',
                'country_id' => 95,
            ],
            123 => 
            [
                'id' => 1624,
                'name' => 'Tel Aviv',
                'code' => '05',
                'country_id' => 95,
            ],
            124 => 
            [
                'id' => 1625,
                'name' => 'Hefa',
                'code' => '04',
                'country_id' => 95,
            ],
            125 => 
            [
                'id' => 1626,
                'name' => '',
                'code' => '00',
                'country_id' => 0,
            ],
            126 => 
            [
                'id' => 1627,
                'name' => 'West Bengal',
                'code' => '28',
                'country_id' => 96,
            ],
            127 => 
            [
                'id' => 1628,
                'name' => 'Uttar Pradesh',
                'code' => '36',
                'country_id' => 96,
            ],
            128 => 
            [
                'id' => 1629,
                'name' => 'Punjab',
                'code' => '23',
                'country_id' => 96,
            ],
            129 => 
            [
                'id' => 1630,
                'name' => 'Madhya Pradesh',
                'code' => '35',
                'country_id' => 96,
            ],
            130 => 
            [
                'id' => 1631,
                'name' => 'Karnataka',
                'code' => '19',
                'country_id' => 96,
            ],
            131 => 
            [
                'id' => 1632,
                'name' => 'Maharashtra',
                'code' => '16',
                'country_id' => 96,
            ],
            132 => 
            [
                'id' => 1633,
                'name' => 'Haryana',
                'code' => '10',
                'country_id' => 96,
            ],
            133 => 
            [
                'id' => 1634,
                'name' => 'Uttarakhand',
                'code' => '39',
                'country_id' => 96,
            ],
            134 => 
            [
                'id' => 1635,
                'name' => 'Andhra Pradesh',
                'code' => '02',
                'country_id' => 96,
            ],
            135 => 
            [
                'id' => 1636,
                'name' => 'Tripura',
                'code' => '26',
                'country_id' => 96,
            ],
            136 => 
            [
                'id' => 1637,
                'name' => 'Tamil Nadu',
                'code' => '25',
                'country_id' => 96,
            ],
            137 => 
            [
                'id' => 1638,
                'name' => 'Jammu and Kashmir',
                'code' => '12',
                'country_id' => 96,
            ],
            138 => 
            [
                'id' => 1639,
                'name' => 'Andaman and Nicobar Islands',
                'code' => '01',
                'country_id' => 96,
            ],
            139 => 
            [
                'id' => 1640,
                'name' => 'Assam',
                'code' => '03',
                'country_id' => 96,
            ],
            140 => 
            [
                'id' => 1641,
                'name' => 'Chhattisgarh',
                'code' => '37',
                'country_id' => 96,
            ],
            141 => 
            [
                'id' => 1642,
                'name' => 'Rajasthan',
                'code' => '24',
                'country_id' => 96,
            ],
            142 => 
            [
                'id' => 1643,
                'name' => 'Goa',
                'code' => '33',
                'country_id' => 96,
            ],
            143 => 
            [
                'id' => 1644,
                'name' => 'Puducherry',
                'code' => '22',
                'country_id' => 96,
            ],
            144 => 
            [
                'id' => 1645,
                'name' => 'Gujarat',
                'code' => '09',
                'country_id' => 96,
            ],
            145 => 
            [
                'id' => 1646,
                'name' => 'Kerala',
                'code' => '13',
                'country_id' => 96,
            ],
            146 => 
            [
                'id' => 1647,
                'name' => 'Arunachal Pradesh',
                'code' => '30',
                'country_id' => 96,
            ],
            147 => 
            [
                'id' => 1648,
                'name' => 'Orissa',
                'code' => '21',
                'country_id' => 96,
            ],
            148 => 
            [
                'id' => 1649,
                'name' => 'Himachal Pradesh',
                'code' => '11',
                'country_id' => 96,
            ],
            149 => 
            [
                'id' => 1650,
                'name' => 'Bihar',
                'code' => '34',
                'country_id' => 96,
            ],
            150 => 
            [
                'id' => 1651,
                'name' => 'Meghalaya',
                'code' => '18',
                'country_id' => 96,
            ],
            151 => 
            [
                'id' => 1652,
                'name' => 'Nagaland',
                'code' => '20',
                'country_id' => 96,
            ],
            152 => 
            [
                'id' => 1653,
                'name' => 'Manipur',
                'code' => '17',
                'country_id' => 96,
            ],
            153 => 
            [
                'id' => 1654,
                'name' => 'Mizoram',
                'code' => '31',
                'country_id' => 96,
            ],
            154 => 
            [
                'id' => 1655,
                'name' => 'Jharkhand',
                'code' => '38',
                'country_id' => 96,
            ],
            155 => 
            [
                'id' => 1656,
                'name' => '',
                'code' => '27',
                'country_id' => 96,
            ],
            156 => 
            [
                'id' => 1657,
                'name' => 'Delhi',
                'code' => '07',
                'country_id' => 96,
            ],
            157 => 
            [
                'id' => 1658,
                'name' => 'Dadra and Nagar Haveli',
                'code' => '06',
                'country_id' => 96,
            ],
            158 => 
            [
                'id' => 1659,
                'name' => '',
                'code' => '15',
                'country_id' => 96,
            ],
            159 => 
            [
                'id' => 1660,
                'name' => 'Daman and Diu',
                'code' => '32',
                'country_id' => 96,
            ],
            160 => 
            [
                'id' => 1661,
                'name' => 'Sikkim',
                'code' => '29',
                'country_id' => 96,
            ],
            161 => 
            [
                'id' => 1662,
                'name' => 'Chandigarh',
                'code' => '05',
                'country_id' => 96,
            ],
            162 => 
            [
                'id' => 1663,
                'name' => 'Lakshadweep',
                'code' => '14',
                'country_id' => 96,
            ],
            163 => 
            [
                'id' => 1664,
                'name' => 'As Sulaymaniyah',
                'code' => '05',
                'country_id' => 97,
            ],
            164 => 
            [
                'id' => 1665,
                'name' => 'Dhi Qar',
                'code' => '09',
                'country_id' => 97,
            ],
            165 => 
            [
                'id' => 1666,
                'name' => 'Maysan',
                'code' => '14',
                'country_id' => 97,
            ],
            166 => 
            [
                'id' => 1667,
                'name' => 'Diyala',
                'code' => '10',
                'country_id' => 97,
            ],
            167 => 
            [
                'id' => 1668,
                'name' => 'Baghdad',
                'code' => '07',
                'country_id' => 97,
            ],
            168 => 
            [
                'id' => 1669,
                'name' => 'Wasit',
                'code' => '16',
                'country_id' => 97,
            ],
            169 => 
            [
                'id' => 1670,
                'name' => 'Salah ad Din',
                'code' => '18',
                'country_id' => 97,
            ],
            170 => 
            [
                'id' => 1671,
                'name' => 'Al Qadisiyah',
                'code' => '04',
                'country_id' => 97,
            ],
            171 => 
            [
                'id' => 1672,
                'name' => 'Babil',
                'code' => '06',
                'country_id' => 97,
            ],
            172 => 
            [
                'id' => 1673,
                'name' => 'Karbala\'',
                'code' => '12',
                'country_id' => 97,
            ],
            173 => 
            [
                'id' => 1674,
                'name' => 'An Najaf',
                'code' => '17',
                'country_id' => 97,
            ],
            174 => 
            [
                'id' => 1675,
                'name' => 'Al Muthanna',
                'code' => '03',
                'country_id' => 97,
            ],
            175 => 
            [
                'id' => 1676,
                'name' => 'Al Anbar',
                'code' => '01',
                'country_id' => 97,
            ],
            176 => 
            [
                'id' => 1677,
                'name' => 'Dahuk',
                'code' => '08',
                'country_id' => 97,
            ],
            177 => 
            [
                'id' => 1678,
                'name' => 'Ninawa',
                'code' => '15',
                'country_id' => 97,
            ],
            178 => 
            [
                'id' => 1679,
                'name' => 'Arbil',
                'code' => '11',
                'country_id' => 97,
            ],
            179 => 
            [
                'id' => 1680,
                'name' => 'Al Basrah',
                'code' => '02',
                'country_id' => 97,
            ],
            180 => 
            [
                'id' => 1681,
                'name' => 'At Ta\'mim',
                'code' => '13',
                'country_id' => 97,
            ],
            181 => 
            [
                'id' => 1682,
                'name' => 'Zanjan',
                'code' => '27',
                'country_id' => 98,
            ],
            182 => 
            [
                'id' => 1683,
                'name' => 'Azarbayjan-e Bakhtari',
                'code' => '01',
                'country_id' => 98,
            ],
            183 => 
            [
                'id' => 1684,
                'name' => 'Yazd',
                'code' => '31',
                'country_id' => 98,
            ],
            184 => 
            [
                'id' => 1685,
                'name' => 'Khuzestan',
                'code' => '15',
                'country_id' => 98,
            ],
            185 => 
            [
                'id' => 1686,
                'name' => 'Esfahan',
                'code' => '28',
                'country_id' => 98,
            ],
            186 => 
            [
                'id' => 1687,
                'name' => 'Ardabil',
                'code' => '32',
                'country_id' => 98,
            ],
            187 => 
            [
                'id' => 1688,
                'name' => 'Tehran',
                'code' => '26',
                'country_id' => 98,
            ],
            188 => 
            [
                'id' => 1689,
                'name' => 'East Azarbaijan',
                'code' => '33',
                'country_id' => 98,
            ],
            189 => 
            [
                'id' => 1690,
                'name' => 'Bushehr',
                'code' => '22',
                'country_id' => 98,
            ],
            190 => 
            [
                'id' => 1691,
                'name' => 'Hormozgan',
                'code' => '11',
                'country_id' => 98,
            ],
            191 => 
            [
                'id' => 1692,
                'name' => 'Mazandaran',
                'code' => '17',
                'country_id' => 98,
            ],
            192 => 
            [
                'id' => 1693,
                'name' => 'Kerman',
                'code' => '29',
                'country_id' => 98,
            ],
            193 => 
            [
                'id' => 1694,
                'name' => 'Fars',
                'code' => '07',
                'country_id' => 98,
            ],
            194 => 
            [
                'id' => 1695,
                'name' => 'Kohkiluyeh va Buyer Ahmadi',
                'code' => '05',
                'country_id' => 98,
            ],
            195 => 
            [
                'id' => 1696,
                'name' => 'Khorasan',
                'code' => '30',
                'country_id' => 98,
            ],
            196 => 
            [
                'id' => 1697,
                'name' => 'Sistan va Baluchestan',
                'code' => '04',
                'country_id' => 98,
            ],
            197 => 
            [
                'id' => 1698,
                'name' => 'Chahar Mahall va Bakhtiari',
                'code' => '03',
                'country_id' => 98,
            ],
            198 => 
            [
                'id' => 1699,
                'name' => 'Kerman',
                'code' => '12',
                'country_id' => 98,
            ],
            199 => 
            [
                'id' => 1700,
                'name' => 'Mazandaran',
                'code' => '35',
                'country_id' => 98,
            ],
            200 => 
            [
                'id' => 1701,
                'name' => 'Qazvin',
                'code' => '38',
                'country_id' => 98,
            ],
            201 => 
            [
                'id' => 1702,
                'name' => 'Zanjan',
                'code' => '36',
                'country_id' => 98,
            ],
            202 => 
            [
                'id' => 1703,
                'name' => 'Markazi',
                'code' => '24',
                'country_id' => 98,
            ],
            203 => 
            [
                'id' => 1704,
                'name' => 'Markazi',
                'code' => '19',
                'country_id' => 98,
            ],
            204 => 
            [
                'id' => 1705,
                'name' => 'Lorestan',
                'code' => '23',
                'country_id' => 98,
            ],
            205 => 
            [
                'id' => 1706,
                'name' => 'Markazi',
                'code' => '34',
                'country_id' => 98,
            ],
            206 => 
            [
                'id' => 1707,
                'name' => 'Khorasan-e Razavi',
                'code' => '42',
                'country_id' => 98,
            ],
            207 => 
            [
                'id' => 1708,
                'name' => 'Hamadan',
                'code' => '09',
                'country_id' => 98,
            ],
            208 => 
            [
                'id' => 1709,
                'name' => 'Semnan',
                'code' => '25',
                'country_id' => 98,
            ],
            209 => 
            [
                'id' => 1710,
                'name' => 'Gilan',
                'code' => '08',
                'country_id' => 98,
            ],
            210 => 
            [
                'id' => 1711,
                'name' => 'Kordestan',
                'code' => '16',
                'country_id' => 98,
            ],
            211 => 
            [
                'id' => 1712,
                'name' => 'Bakhtaran',
                'code' => '13',
                'country_id' => 98,
            ],
            212 => 
            [
                'id' => 1713,
                'name' => 'Ilam',
                'code' => '10',
                'country_id' => 98,
            ],
            213 => 
            [
                'id' => 1714,
                'name' => 'Semnan Province',
                'code' => '18',
                'country_id' => 98,
            ],
            214 => 
            [
                'id' => 1715,
                'name' => 'Golestan',
                'code' => '37',
                'country_id' => 98,
            ],
            215 => 
            [
                'id' => 1716,
                'name' => 'Qom',
                'code' => '39',
                'country_id' => 98,
            ],
            216 => 
            [
                'id' => 1717,
                'name' => '',
                'code' => '02',
                'country_id' => 98,
            ],
            217 => 
            [
                'id' => 1718,
                'name' => 'Zanjan',
                'code' => '21',
                'country_id' => 98,
            ],
            218 => 
            [
                'id' => 1719,
                'name' => '',
                'code' => '00',
                'country_id' => 98,
            ],
            219 => 
            [
                'id' => 1720,
                'name' => 'Skagafjardarsysla',
                'code' => '28',
                'country_id' => 99,
            ],
            220 => 
            [
                'id' => 1721,
                'name' => 'Borgarfjardarsysla',
                'code' => '07',
                'country_id' => 99,
            ],
            221 => 
            [
                'id' => 1722,
                'name' => 'Myrasysla',
                'code' => '17',
                'country_id' => 99,
            ],
            222 => 
            [
                'id' => 1723,
                'name' => 'Rangarvallasysla',
                'code' => '23',
                'country_id' => 99,
            ],
            223 => 
            [
                'id' => 1724,
                'name' => 'Eyjafjardarsysla',
                'code' => '09',
                'country_id' => 99,
            ],
            224 => 
            [
                'id' => 1725,
                'name' => 'Kjosarsysla',
                'code' => '15',
                'country_id' => 99,
            ],
            225 => 
            [
                'id' => 1726,
                'name' => 'Vestur-Isafjardarsysla',
                'code' => '36',
                'country_id' => 99,
            ],
            226 => 
            [
                'id' => 1727,
                'name' => '',
                'code' => '19',
                'country_id' => 99,
            ],
            227 => 
            [
                'id' => 1728,
                'name' => 'Strandasysla',
                'code' => '30',
                'country_id' => 99,
            ],
            228 => 
            [
                'id' => 1729,
                'name' => 'Gullbringusysla',
                'code' => '10',
                'country_id' => 99,
            ],
            229 => 
            [
                'id' => 1730,
                'name' => 'Austur-Hunavatnssysla',
                'code' => '05',
                'country_id' => 99,
            ],
            230 => 
            [
                'id' => 1731,
                'name' => 'Austur-Skaftafellssysla',
                'code' => '06',
                'country_id' => 99,
            ],
            231 => 
            [
                'id' => 1732,
                'name' => 'Nordur-Mulasysla',
                'code' => '20',
                'country_id' => 99,
            ],
            232 => 
            [
                'id' => 1733,
                'name' => 'Sudur-Mulasysla',
                'code' => '31',
                'country_id' => 99,
            ],
            233 => 
            [
                'id' => 1734,
                'name' => 'Vestur-Bardastrandarsysla',
                'code' => '34',
                'country_id' => 99,
            ],
            234 => 
            [
                'id' => 1735,
                'name' => 'Snafellsnes- og Hnappadalssysla',
                'code' => '29',
                'country_id' => 99,
            ],
            235 => 
            [
                'id' => 1736,
                'name' => 'Arnessysla',
                'code' => '03',
                'country_id' => 99,
            ],
            236 => 
            [
                'id' => 1737,
                'name' => 'Vestur-Hunavatnssysla',
                'code' => '35',
                'country_id' => 99,
            ],
            237 => 
            [
                'id' => 1738,
                'name' => 'Sudur-Tingeyjarsysla',
                'code' => '32',
                'country_id' => 99,
            ],
            238 => 
            [
                'id' => 1739,
                'name' => '',
                'code' => '08',
                'country_id' => 99,
            ],
            239 => 
            [
                'id' => 1740,
                'name' => 'Vestur-Skaftafellssysla',
                'code' => '37',
                'country_id' => 99,
            ],
            240 => 
            [
                'id' => 1741,
                'name' => '',
                'code' => '04',
                'country_id' => 99,
            ],
            241 => 
            [
                'id' => 1742,
                'name' => 'Nordur-Tingeyjarsysla',
                'code' => '21',
                'country_id' => 99,
            ],
            242 => 
            [
                'id' => 1743,
                'name' => 'Toscana',
                'code' => '16',
                'country_id' => 100,
            ],
            243 => 
            [
                'id' => 1744,
                'name' => 'Veneto',
                'code' => '20',
                'country_id' => 100,
            ],
            244 => 
            [
                'id' => 1745,
                'name' => 'Campania',
                'code' => '04',
                'country_id' => 100,
            ],
            245 => 
            [
                'id' => 1746,
                'name' => 'Marche',
                'code' => '10',
                'country_id' => 100,
            ],
            246 => 
            [
                'id' => 1747,
                'name' => 'Piemonte',
                'code' => '12',
                'country_id' => 100,
            ],
            247 => 
            [
                'id' => 1748,
                'name' => 'Lombardia',
                'code' => '09',
                'country_id' => 100,
            ],
            248 => 
            [
                'id' => 1749,
                'name' => 'Sardegna',
                'code' => '14',
                'country_id' => 100,
            ],
            249 => 
            [
                'id' => 1750,
                'name' => 'Abruzzi',
                'code' => '01',
                'country_id' => 100,
            ],
            250 => 
            [
                'id' => 1751,
                'name' => 'Emilia-Romagna',
                'code' => '05',
                'country_id' => 100,
            ],
            251 => 
            [
                'id' => 1752,
                'name' => 'Trentino-Alto Adige',
                'code' => '17',
                'country_id' => 100,
            ],
            252 => 
            [
                'id' => 1753,
                'name' => 'Umbria',
                'code' => '18',
                'country_id' => 100,
            ],
            253 => 
            [
                'id' => 1754,
                'name' => 'Basilicata',
                'code' => '02',
                'country_id' => 100,
            ],
            254 => 
            [
                'id' => 1755,
                'name' => 'Puglia',
                'code' => '13',
                'country_id' => 100,
            ],
            255 => 
            [
                'id' => 1756,
                'name' => 'Sicilia',
                'code' => '15',
                'country_id' => 100,
            ],
            256 => 
            [
                'id' => 1757,
                'name' => 'Lazio',
                'code' => '07',
                'country_id' => 100,
            ],
            257 => 
            [
                'id' => 1758,
                'name' => 'Liguria',
                'code' => '08',
                'country_id' => 100,
            ],
            258 => 
            [
                'id' => 1759,
                'name' => 'Calabria',
                'code' => '03',
                'country_id' => 100,
            ],
            259 => 
            [
                'id' => 1760,
                'name' => 'Molise',
                'code' => '11',
                'country_id' => 100,
            ],
            260 => 
            [
                'id' => 1761,
                'name' => 'Friuli-Venezia Giulia',
                'code' => '06',
                'country_id' => 100,
            ],
            261 => 
            [
                'id' => 1762,
                'name' => 'Valle d\'Aosta',
                'code' => '19',
                'country_id' => 100,
            ],
            262 => 
            [
                'id' => 1763,
                'name' => '',
                'code' => '00',
                'country_id' => 100,
            ],
            263 => 
            [
                'id' => 1764,
                'name' => 'Saint Ann',
                'code' => '09',
                'country_id' => 101,
            ],
            264 => 
            [
                'id' => 1765,
                'name' => 'Saint Elizabeth',
                'code' => '11',
                'country_id' => 101,
            ],
            265 => 
            [
                'id' => 1766,
                'name' => 'Hanover',
                'code' => '02',
                'country_id' => 101,
            ],
            266 => 
            [
                'id' => 1767,
                'name' => 'Westmoreland',
                'code' => '16',
                'country_id' => 101,
            ],
            267 => 
            [
                'id' => 1768,
                'name' => 'Trelawny',
                'code' => '15',
                'country_id' => 101,
            ],
            268 => 
            [
                'id' => 1769,
                'name' => 'Manchester',
                'code' => '04',
                'country_id' => 101,
            ],
            269 => 
            [
                'id' => 1770,
                'name' => 'Saint James',
                'code' => '12',
                'country_id' => 101,
            ],
            270 => 
            [
                'id' => 1771,
                'name' => 'Saint Andrew',
                'code' => '08',
                'country_id' => 101,
            ],
            271 => 
            [
                'id' => 1772,
                'name' => 'Saint Thomas',
                'code' => '14',
                'country_id' => 101,
            ],
            272 => 
            [
                'id' => 1773,
                'name' => 'Saint Mary',
                'code' => '13',
                'country_id' => 101,
            ],
            273 => 
            [
                'id' => 1774,
                'name' => 'Portland',
                'code' => '07',
                'country_id' => 101,
            ],
            274 => 
            [
                'id' => 1775,
                'name' => 'Clarendon',
                'code' => '01',
                'country_id' => 101,
            ],
            275 => 
            [
                'id' => 1776,
                'name' => 'Saint Catherine',
                'code' => '10',
                'country_id' => 101,
            ],
            276 => 
            [
                'id' => 1777,
                'name' => 'Kingston',
                'code' => '17',
                'country_id' => 101,
            ],
            277 => 
            [
                'id' => 1778,
                'name' => '',
                'code' => '11',
                'country_id' => 102,
            ],
            278 => 
            [
                'id' => 1779,
                'name' => 'At Tafilah',
                'code' => '12',
                'country_id' => 102,
            ],
            279 => 
            [
                'id' => 1780,
                'name' => '',
                'code' => '07',
                'country_id' => 102,
            ],
            280 => 
            [
                'id' => 1781,
                'name' => '',
                'code' => '14',
                'country_id' => 102,
            ],
            281 => 
            [
                'id' => 1782,
                'name' => 'Al Karak',
                'code' => '09',
                'country_id' => 102,
            ],
            282 => 
            [
                'id' => 1783,
                'name' => '',
                'code' => '13',
                'country_id' => 102,
            ],
            283 => 
            [
                'id' => 1784,
                'name' => 'Al Balqa\'',
                'code' => '02',
                'country_id' => 102,
            ],
            284 => 
            [
                'id' => 1785,
                'name' => '',
                'code' => '10',
                'country_id' => 102,
            ],
            285 => 
            [
                'id' => 1786,
                'name' => 'Amman',
                'code' => '16',
                'country_id' => 102,
            ],
            286 => 
            [
                'id' => 1787,
                'name' => 'Al Aqabah',
                'code' => '21',
                'country_id' => 102,
            ],
            287 => 
            [
                'id' => 1788,
                'name' => 'Okinawa',
                'code' => '47',
                'country_id' => 103,
            ],
            288 => 
            [
                'id' => 1789,
                'name' => 'Nagasaki',
                'code' => '27',
                'country_id' => 103,
            ],
            289 => 
            [
                'id' => 1790,
                'name' => 'Hokkaido',
                'code' => '12',
                'country_id' => 103,
            ],
            290 => 
            [
                'id' => 1791,
                'name' => 'Tokushima',
                'code' => '39',
                'country_id' => 103,
            ],
            291 => 
            [
                'id' => 1792,
                'name' => 'Mie',
                'code' => '23',
                'country_id' => 103,
            ],
            292 => 
            [
                'id' => 1793,
                'name' => 'Kanagawa',
                'code' => '19',
                'country_id' => 103,
            ],
            293 => 
            [
                'id' => 1794,
                'name' => 'Chiba',
                'code' => '04',
                'country_id' => 103,
            ],
            294 => 
            [
                'id' => 1795,
                'name' => 'Hyogo',
                'code' => '13',
                'country_id' => 103,
            ],
            295 => 
            [
                'id' => 1796,
                'name' => 'Yamaguchi',
                'code' => '45',
                'country_id' => 103,
            ],
            296 => 
            [
                'id' => 1797,
                'name' => 'Aomori',
                'code' => '03',
                'country_id' => 103,
            ],
            297 => 
            [
                'id' => 1798,
                'name' => 'Miyazaki',
                'code' => '25',
                'country_id' => 103,
            ],
            298 => 
            [
                'id' => 1799,
                'name' => 'Shizuoka',
                'code' => '37',
                'country_id' => 103,
            ],
            299 => 
            [
                'id' => 1800,
                'name' => 'Shimane',
                'code' => '36',
                'country_id' => 103,
            ],
            300 => 
            [
                'id' => 1801,
                'name' => 'Fukushima',
                'code' => '08',
                'country_id' => 103,
            ],
            301 => 
            [
                'id' => 1802,
                'name' => 'Okayama',
                'code' => '31',
                'country_id' => 103,
            ],
            302 => 
            [
                'id' => 1803,
                'name' => 'Shiga',
                'code' => '35',
                'country_id' => 103,
            ],
            303 => 
            [
                'id' => 1804,
                'name' => 'Kagoshima',
                'code' => '18',
                'country_id' => 103,
            ],
            304 => 
            [
                'id' => 1805,
                'name' => 'Hiroshima',
                'code' => '11',
                'country_id' => 103,
            ],
            305 => 
            [
                'id' => 1806,
                'name' => 'Tottori',
                'code' => '41',
                'country_id' => 103,
            ],
            306 => 
            [
                'id' => 1807,
                'name' => 'Akita',
                'code' => '02',
                'country_id' => 103,
            ],
            307 => 
            [
                'id' => 1808,
                'name' => 'Nagano',
                'code' => '26',
                'country_id' => 103,
            ],
            308 => 
            [
                'id' => 1809,
                'name' => 'Fukui',
                'code' => '06',
                'country_id' => 103,
            ],
            309 => 
            [
                'id' => 1810,
                'name' => 'Saitama',
                'code' => '34',
                'country_id' => 103,
            ],
            310 => 
            [
                'id' => 1811,
                'name' => 'Wakayama',
                'code' => '43',
                'country_id' => 103,
            ],
            311 => 
            [
                'id' => 1812,
                'name' => 'Kochi',
                'code' => '20',
                'country_id' => 103,
            ],
            312 => 
            [
                'id' => 1813,
                'name' => 'Iwate',
                'code' => '16',
                'country_id' => 103,
            ],
            313 => 
            [
                'id' => 1814,
                'name' => 'Miyagi',
                'code' => '24',
                'country_id' => 103,
            ],
            314 => 
            [
                'id' => 1815,
                'name' => 'Niigata',
                'code' => '29',
                'country_id' => 103,
            ],
            315 => 
            [
                'id' => 1816,
                'name' => 'Gumma',
                'code' => '10',
                'country_id' => 103,
            ],
            316 => 
            [
                'id' => 1817,
                'name' => 'Aichi',
                'code' => '01',
                'country_id' => 103,
            ],
            317 => 
            [
                'id' => 1818,
                'name' => 'Toyama',
                'code' => '42',
                'country_id' => 103,
            ],
            318 => 
            [
                'id' => 1819,
                'name' => 'Kumamoto',
                'code' => '21',
                'country_id' => 103,
            ],
            319 => 
            [
                'id' => 1820,
                'name' => 'Kagawa',
                'code' => '17',
                'country_id' => 103,
            ],
            320 => 
            [
                'id' => 1821,
                'name' => 'Ehime',
                'code' => '05',
                'country_id' => 103,
            ],
            321 => 
            [
                'id' => 1822,
                'name' => 'Tokyo',
                'code' => '40',
                'country_id' => 103,
            ],
            322 => 
            [
                'id' => 1823,
                'name' => 'Fukuoka',
                'code' => '07',
                'country_id' => 103,
            ],
            323 => 
            [
                'id' => 1824,
                'name' => 'Tochigi',
                'code' => '38',
                'country_id' => 103,
            ],
            324 => 
            [
                'id' => 1825,
                'name' => 'Yamagata',
                'code' => '44',
                'country_id' => 103,
            ],
            325 => 
            [
                'id' => 1826,
                'name' => 'Saga',
                'code' => '33',
                'country_id' => 103,
            ],
            326 => 
            [
                'id' => 1827,
                'name' => 'Oita',
                'code' => '30',
                'country_id' => 103,
            ],
            327 => 
            [
                'id' => 1828,
                'name' => 'Gifu',
                'code' => '09',
                'country_id' => 103,
            ],
            328 => 
            [
                'id' => 1829,
                'name' => 'Ishikawa',
                'code' => '15',
                'country_id' => 103,
            ],
            329 => 
            [
                'id' => 1830,
                'name' => 'Nara',
                'code' => '28',
                'country_id' => 103,
            ],
            330 => 
            [
                'id' => 1831,
                'name' => 'Ibaraki',
                'code' => '14',
                'country_id' => 103,
            ],
            331 => 
            [
                'id' => 1832,
                'name' => 'Kyoto',
                'code' => '22',
                'country_id' => 103,
            ],
            332 => 
            [
                'id' => 1833,
                'name' => 'Yamanashi',
                'code' => '46',
                'country_id' => 103,
            ],
            333 => 
            [
                'id' => 1834,
                'name' => 'Osaka',
                'code' => '32',
                'country_id' => 103,
            ],
            334 => 
            [
                'id' => 1835,
                'name' => 'Coast',
                'code' => '02',
                'country_id' => 104,
            ],
            335 => 
            [
                'id' => 1836,
                'name' => 'Nyanza',
                'code' => '07',
                'country_id' => 104,
            ],
            336 => 
            [
                'id' => 1837,
                'name' => 'Rift Valley',
                'code' => '08',
                'country_id' => 104,
            ],
            337 => 
            [
                'id' => 1838,
                'name' => 'Western',
                'code' => '09',
                'country_id' => 104,
            ],
            338 => 
            [
                'id' => 1839,
                'name' => 'North-Eastern',
                'code' => '06',
                'country_id' => 104,
            ],
            339 => 
            [
                'id' => 1840,
                'name' => 'Eastern',
                'code' => '03',
                'country_id' => 104,
            ],
            340 => 
            [
                'id' => 1841,
                'name' => 'Nairobi Area',
                'code' => '05',
                'country_id' => 104,
            ],
            341 => 
            [
                'id' => 1842,
                'name' => 'Central',
                'code' => '01',
                'country_id' => 104,
            ],
            342 => 
            [
                'id' => 1843,
                'name' => 'Jalal-Abad',
                'code' => '03',
                'country_id' => 105,
            ],
            343 => 
            [
                'id' => 1844,
                'name' => 'Naryn',
                'code' => '04',
                'country_id' => 105,
            ],
            344 => 
            [
                'id' => 1845,
                'name' => 'Osh',
                'code' => '05',
                'country_id' => 105,
            ],
            345 => 
            [
                'id' => 1846,
                'name' => 'Chuy',
                'code' => '02',
                'country_id' => 105,
            ],
            346 => 
            [
                'id' => 1847,
                'name' => 'Ysyk-Kol',
                'code' => '07',
                'country_id' => 105,
            ],
            347 => 
            [
                'id' => 1848,
                'name' => 'Bishkek',
                'code' => '01',
                'country_id' => 105,
            ],
            348 => 
            [
                'id' => 1849,
                'name' => 'Talas',
                'code' => '06',
                'country_id' => 105,
            ],
            349 => 
            [
                'id' => 1850,
                'name' => 'Batken',
                'code' => '09',
                'country_id' => 105,
            ],
            350 => 
            [
                'id' => 1851,
                'name' => '',
                'code' => '00',
                'country_id' => 105,
            ],
            351 => 
            [
                'id' => 1852,
                'name' => 'Siem Reap',
                'code' => '16',
                'country_id' => 106,
            ],
            352 => 
            [
                'id' => 1853,
                'name' => 'Kracheh',
                'code' => '09',
                'country_id' => 106,
            ],
            353 => 
            [
                'id' => 1854,
                'name' => 'Kampong Thum',
                'code' => '05',
                'country_id' => 106,
            ],
            354 => 
            [
                'id' => 1855,
                'name' => 'Kampong Chhnang',
                'code' => '03',
                'country_id' => 106,
            ],
            355 => 
            [
                'id' => 1856,
                'name' => '',
                'code' => '20',
                'country_id' => 106,
            ],
            356 => 
            [
                'id' => 1857,
                'name' => 'Kampong Cham',
                'code' => '02',
                'country_id' => 106,
            ],
            357 => 
            [
                'id' => 1858,
                'name' => 'Kampong Speu',
                'code' => '04',
                'country_id' => 106,
            ],
            358 => 
            [
                'id' => 1859,
                'name' => 'Takeo',
                'code' => '19',
                'country_id' => 106,
            ],
            359 => 
            [
                'id' => 1860,
                'name' => 'Batdambang',
                'code' => '01',
                'country_id' => 106,
            ],
            360 => 
            [
                'id' => 1861,
                'name' => 'Prey Veng',
                'code' => '14',
                'country_id' => 106,
            ],
            361 => 
            [
                'id' => 1862,
                'name' => 'Ratanakiri Kiri',
                'code' => '15',
                'country_id' => 106,
            ],
            362 => 
            [
                'id' => 1863,
                'name' => 'Svay Rieng',
                'code' => '18',
                'country_id' => 106,
            ],
            363 => 
            [
                'id' => 1864,
                'name' => 'Koh Kong',
                'code' => '08',
                'country_id' => 106,
            ],
            364 => 
            [
                'id' => 1865,
                'name' => 'Pursat',
                'code' => '12',
                'country_id' => 106,
            ],
            365 => 
            [
                'id' => 1866,
                'name' => 'Phnum Penh',
                'code' => '11',
                'country_id' => 106,
            ],
            366 => 
            [
                'id' => 1867,
                'name' => 'Mondulkiri',
                'code' => '10',
                'country_id' => 106,
            ],
            367 => 
            [
                'id' => 1868,
                'name' => 'Stung Treng',
                'code' => '17',
                'country_id' => 106,
            ],
            368 => 
            [
                'id' => 1869,
                'name' => 'Kampot',
                'code' => '06',
                'country_id' => 106,
            ],
            369 => 
            [
                'id' => 1870,
                'name' => 'Banteay Meanchey',
                'code' => '25',
                'country_id' => 106,
            ],
            370 => 
            [
                'id' => 1871,
                'name' => 'Preah Vihear',
                'code' => '13',
                'country_id' => 106,
            ],
            371 => 
            [
                'id' => 1872,
                'name' => 'Kandal',
                'code' => '07',
                'country_id' => 106,
            ],
            372 => 
            [
                'id' => 1873,
                'name' => '',
                'code' => '00',
                'country_id' => 107,
            ],
            373 => 
            [
                'id' => 1874,
                'name' => 'Anjouan',
                'code' => '01',
                'country_id' => 108,
            ],
            374 => 
            [
                'id' => 1875,
                'name' => 'Moheli',
                'code' => '03',
                'country_id' => 108,
            ],
            375 => 
            [
                'id' => 1876,
                'name' => 'Grande Comore',
                'code' => '02',
                'country_id' => 108,
            ],
            376 => 
            [
                'id' => 1877,
                'name' => 'Saint George Gingerland',
                'code' => '04',
                'country_id' => 109,
            ],
            377 => 
            [
                'id' => 1878,
                'name' => 'Saint James Windward',
                'code' => '05',
                'country_id' => 109,
            ],
            378 => 
            [
                'id' => 1879,
                'name' => 'Saint Thomas Lowland',
                'code' => '12',
                'country_id' => 109,
            ],
            379 => 
            [
                'id' => 1880,
                'name' => 'Saint George Basseterre',
                'code' => '03',
                'country_id' => 109,
            ],
            380 => 
            [
                'id' => 1881,
                'name' => 'Saint John Figtree',
                'code' => '07',
                'country_id' => 109,
            ],
            381 => 
            [
                'id' => 1882,
                'name' => 'Saint Peter Basseterre',
                'code' => '11',
                'country_id' => 109,
            ],
            382 => 
            [
                'id' => 1883,
                'name' => 'Saint John Capisterre',
                'code' => '06',
                'country_id' => 109,
            ],
            383 => 
            [
                'id' => 1884,
                'name' => 'Christ Church Nichola Town',
                'code' => '01',
                'country_id' => 109,
            ],
            384 => 
            [
                'id' => 1885,
                'name' => 'Trinity Palmetto Point',
                'code' => '15',
                'country_id' => 109,
            ],
            385 => 
            [
                'id' => 1886,
                'name' => 'Saint Anne Sandy Point',
                'code' => '02',
                'country_id' => 109,
            ],
            386 => 
            [
                'id' => 1887,
                'name' => 'Saint Mary Cayon',
                'code' => '08',
                'country_id' => 109,
            ],
            387 => 
            [
                'id' => 1888,
                'name' => 'Saint Thomas Middle Island',
                'code' => '13',
                'country_id' => 109,
            ],
            388 => 
            [
                'id' => 1889,
                'name' => 'Saint Paul Capisterre',
                'code' => '09',
                'country_id' => 109,
            ],
            389 => 
            [
                'id' => 1890,
                'name' => 'P\'yongan-namdo',
                'code' => '15',
                'country_id' => 110,
            ],
            390 => 
            [
                'id' => 1891,
                'name' => 'P\'yongan-bukto',
                'code' => '11',
                'country_id' => 110,
            ],
            391 => 
            [
                'id' => 1892,
                'name' => 'P\'yongyang-si',
                'code' => '12',
                'country_id' => 110,
            ],
            392 => 
            [
                'id' => 1893,
                'name' => 'Kangwon-do',
                'code' => '09',
                'country_id' => 110,
            ],
            393 => 
            [
                'id' => 1894,
                'name' => 'Hwanghae-bukto',
                'code' => '07',
                'country_id' => 110,
            ],
            394 => 
            [
                'id' => 1895,
                'name' => 'Hamgyong-namdo',
                'code' => '03',
                'country_id' => 110,
            ],
            395 => 
            [
                'id' => 1896,
                'name' => 'Chagang-do',
                'code' => '01',
                'country_id' => 110,
            ],
            396 => 
            [
                'id' => 1897,
                'name' => 'Hamgyong-bukto',
                'code' => '17',
                'country_id' => 110,
            ],
            397 => 
            [
                'id' => 1898,
                'name' => 'Hwanghae-namdo',
                'code' => '06',
                'country_id' => 110,
            ],
            398 => 
            [
                'id' => 1899,
                'name' => 'Namp\'o-si',
                'code' => '14',
                'country_id' => 110,
            ],
            399 => 
            [
                'id' => 1900,
                'name' => 'Kaesong-si',
                'code' => '08',
                'country_id' => 110,
            ],
            400 => 
            [
                'id' => 1901,
                'name' => 'Yanggang-do',
                'code' => '13',
                'country_id' => 110,
            ],
            401 => 
            [
                'id' => 1902,
                'name' => 'Najin Sonbong-si',
                'code' => '18',
                'country_id' => 110,
            ],
            402 => 
            [
                'id' => 1903,
                'name' => 'Ch\'ungch\'ong-bukto',
                'code' => '05',
                'country_id' => 111,
            ],
            403 => 
            [
                'id' => 1904,
                'name' => 'Kangwon-do',
                'code' => '06',
                'country_id' => 111,
            ],
            404 => 
            [
                'id' => 1905,
                'name' => 'Ch\'ungch\'ong-namdo',
                'code' => '17',
                'country_id' => 111,
            ],
            405 => 
            [
                'id' => 1906,
                'name' => 'Kyongsang-bukto',
                'code' => '14',
                'country_id' => 111,
            ],
            406 => 
            [
                'id' => 1907,
                'name' => 'Cholla-namdo',
                'code' => '16',
                'country_id' => 111,
            ],
            407 => 
            [
                'id' => 1908,
                'name' => 'Kyonggi-do',
                'code' => '13',
                'country_id' => 111,
            ],
            408 => 
            [
                'id' => 1909,
                'name' => 'Cheju-do',
                'code' => '01',
                'country_id' => 111,
            ],
            409 => 
            [
                'id' => 1910,
                'name' => 'Cholla-bukto',
                'code' => '03',
                'country_id' => 111,
            ],
            410 => 
            [
                'id' => 1911,
                'name' => 'Seoul-t\'ukpyolsi',
                'code' => '11',
                'country_id' => 111,
            ],
            411 => 
            [
                'id' => 1912,
                'name' => 'Kyongsang-namdo',
                'code' => '20',
                'country_id' => 111,
            ],
            412 => 
            [
                'id' => 1913,
                'name' => 'Taegu-jikhalsi',
                'code' => '15',
                'country_id' => 111,
            ],
            413 => 
            [
                'id' => 1914,
                'name' => 'Pusan-jikhalsi',
                'code' => '10',
                'country_id' => 111,
            ],
            414 => 
            [
                'id' => 1915,
                'name' => 'Kwangju-jikhalsi',
                'code' => '18',
                'country_id' => 111,
            ],
            415 => 
            [
                'id' => 1916,
                'name' => 'Ulsan-gwangyoksi',
                'code' => '21',
                'country_id' => 111,
            ],
            416 => 
            [
                'id' => 1917,
                'name' => 'Inch\'on-jikhalsi',
                'code' => '12',
                'country_id' => 111,
            ],
            417 => 
            [
                'id' => 1918,
                'name' => 'Taejon-jikhalsi',
                'code' => '19',
                'country_id' => 111,
            ],
            418 => 
            [
                'id' => 1919,
                'name' => 'Al Kuwayt',
                'code' => '02',
                'country_id' => 112,
            ],
            419 => 
            [
                'id' => 1920,
                'name' => 'Al Jahra',
                'code' => '05',
                'country_id' => 112,
            ],
            420 => 
            [
                'id' => 1921,
                'name' => '',
                'code' => '04',
                'country_id' => 112,
            ],
            421 => 
            [
                'id' => 1922,
                'name' => '',
                'code' => '00',
                'country_id' => 113,
            ],
            422 => 
            [
                'id' => 1923,
                'name' => 'Almaty',
                'code' => '01',
                'country_id' => 114,
            ],
            423 => 
            [
                'id' => 1924,
                'name' => 'South Kazakhstan',
                'code' => '10',
                'country_id' => 114,
            ],
            424 => 
            [
                'id' => 1925,
                'name' => 'North Kazakhstan',
                'code' => '16',
                'country_id' => 114,
            ],
            425 => 
            [
                'id' => 1926,
                'name' => 'Pavlodar',
                'code' => '11',
                'country_id' => 114,
            ],
            426 => 
            [
                'id' => 1927,
                'name' => 'Qaraghandy',
                'code' => '12',
                'country_id' => 114,
            ],
            427 => 
            [
                'id' => 1928,
                'name' => 'Qyzylorda',
                'code' => '14',
                'country_id' => 114,
            ],
            428 => 
            [
                'id' => 1929,
                'name' => 'East Kazakhstan',
                'code' => '15',
                'country_id' => 114,
            ],
            429 => 
            [
                'id' => 1930,
                'name' => 'Aqmola',
                'code' => '03',
                'country_id' => 114,
            ],
            430 => 
            [
                'id' => 1931,
                'name' => 'Aqtobe',
                'code' => '04',
                'country_id' => 114,
            ],
            431 => 
            [
                'id' => 1932,
                'name' => 'Qostanay',
                'code' => '13',
                'country_id' => 114,
            ],
            432 => 
            [
                'id' => 1933,
                'name' => 'West Kazakhstan',
                'code' => '07',
                'country_id' => 114,
            ],
            433 => 
            [
                'id' => 1934,
                'name' => 'Atyrau',
                'code' => '06',
                'country_id' => 114,
            ],
            434 => 
            [
                'id' => 1935,
                'name' => 'Zhambyl',
                'code' => '17',
                'country_id' => 114,
            ],
            435 => 
            [
                'id' => 1936,
                'name' => 'Astana',
                'code' => '05',
                'country_id' => 114,
            ],
            436 => 
            [
                'id' => 1937,
                'name' => 'Mangghystau',
                'code' => '09',
                'country_id' => 114,
            ],
            437 => 
            [
                'id' => 1938,
                'name' => 'Almaty City',
                'code' => '02',
                'country_id' => 114,
            ],
            438 => 
            [
                'id' => 1939,
                'name' => 'Bayqonyr',
                'code' => '08',
                'country_id' => 114,
            ],
            439 => 
            [
                'id' => 1940,
                'name' => '',
                'code' => '00',
                'country_id' => 114,
            ],
            440 => 
            [
                'id' => 1941,
                'name' => 'Savannakhet',
                'code' => '10',
                'country_id' => 115,
            ],
            441 => 
            [
                'id' => 1942,
                'name' => 'Phongsali',
                'code' => '08',
                'country_id' => 115,
            ],
            442 => 
            [
                'id' => 1943,
                'name' => 'Saravan',
                'code' => '09',
                'country_id' => 115,
            ],
            443 => 
            [
                'id' => 1944,
                'name' => '',
                'code' => '18',
                'country_id' => 115,
            ],
            444 => 
            [
                'id' => 1945,
                'name' => '',
                'code' => '20',
                'country_id' => 115,
            ],
            445 => 
            [
                'id' => 1946,
                'name' => 'Houaphan',
                'code' => '03',
                'country_id' => 115,
            ],
            446 => 
            [
                'id' => 1947,
                'name' => 'Attapu',
                'code' => '01',
                'country_id' => 115,
            ],
            447 => 
            [
                'id' => 1948,
                'name' => '',
                'code' => '06',
                'country_id' => 115,
            ],
            448 => 
            [
                'id' => 1949,
                'name' => 'Champasak',
                'code' => '02',
                'country_id' => 115,
            ],
            449 => 
            [
                'id' => 1950,
                'name' => 'Louangphrabang',
                'code' => '17',
                'country_id' => 115,
            ],
            450 => 
            [
                'id' => 1951,
                'name' => 'Oudomxai',
                'code' => '07',
                'country_id' => 115,
            ],
            451 => 
            [
                'id' => 1952,
                'name' => '',
                'code' => '16',
                'country_id' => 115,
            ],
            452 => 
            [
                'id' => 1953,
                'name' => '',
                'code' => '15',
                'country_id' => 115,
            ],
            453 => 
            [
                'id' => 1954,
                'name' => '',
                'code' => '21',
                'country_id' => 115,
            ],
            454 => 
            [
                'id' => 1955,
                'name' => 'Xiangkhoang',
                'code' => '14',
                'country_id' => 115,
            ],
            455 => 
            [
                'id' => 1956,
                'name' => 'Vientiane',
                'code' => '11',
                'country_id' => 115,
            ],
            456 => 
            [
                'id' => 1957,
                'name' => '',
                'code' => '25',
                'country_id' => 115,
            ],
            457 => 
            [
                'id' => 1958,
                'name' => '',
                'code' => '22',
                'country_id' => 115,
            ],
            458 => 
            [
                'id' => 1959,
                'name' => '',
                'code' => '19',
                'country_id' => 115,
            ],
            459 => 
            [
                'id' => 1960,
                'name' => 'Xaignabouri',
                'code' => '13',
                'country_id' => 115,
            ],
            460 => 
            [
                'id' => 1961,
                'name' => 'Khammouan',
                'code' => '04',
                'country_id' => 115,
            ],
            461 => 
            [
                'id' => 1962,
                'name' => '',
                'code' => '26',
                'country_id' => 115,
            ],
            462 => 
            [
                'id' => 1963,
                'name' => '',
                'code' => '27',
                'country_id' => 115,
            ],
            463 => 
            [
                'id' => 1964,
                'name' => '',
                'code' => '23',
                'country_id' => 115,
            ],
            464 => 
            [
                'id' => 1965,
                'name' => '',
                'code' => '24',
                'country_id' => 115,
            ],
            465 => 
            [
                'id' => 1966,
                'name' => 'Liban-Nord',
                'code' => '03',
                'country_id' => 116,
            ],
            466 => 
            [
                'id' => 1967,
                'name' => 'Al Janub',
                'code' => '02',
                'country_id' => 116,
            ],
            467 => 
            [
                'id' => 1968,
                'name' => 'Beyrouth',
                'code' => '04',
                'country_id' => 116,
            ],
            468 => 
            [
                'id' => 1969,
                'name' => 'Mont-Liban',
                'code' => '05',
                'country_id' => 116,
            ],
            469 => 
            [
                'id' => 1970,
                'name' => 'Beqaa',
                'code' => '01',
                'country_id' => 116,
            ],
            470 => 
            [
                'id' => 1971,
                'name' => 'Liban-Sud',
                'code' => '06',
                'country_id' => 116,
            ],
            471 => 
            [
                'id' => 1972,
                'name' => 'Micoud',
                'code' => '08',
                'country_id' => 117,
            ],
            472 => 
            [
                'id' => 1973,
                'name' => 'Laborie',
                'code' => '07',
                'country_id' => 117,
            ],
            473 => 
            [
                'id' => 1974,
                'name' => 'Dennery',
                'code' => '05',
                'country_id' => 117,
            ],
            474 => 
            [
                'id' => 1975,
                'name' => 'Anse-la-Raye',
                'code' => '01',
                'country_id' => 117,
            ],
            475 => 
            [
                'id' => 1976,
                'name' => 'Vieux-Fort',
                'code' => '10',
                'country_id' => 117,
            ],
            476 => 
            [
                'id' => 1977,
                'name' => 'Castries',
                'code' => '03',
                'country_id' => 117,
            ],
            477 => 
            [
                'id' => 1978,
                'name' => 'Soufriere',
                'code' => '09',
                'country_id' => 117,
            ],
            478 => 
            [
                'id' => 1979,
                'name' => 'Gros-Islet',
                'code' => '06',
                'country_id' => 117,
            ],
            479 => 
            [
                'id' => 1980,
                'name' => 'Choiseul',
                'code' => '04',
                'country_id' => 117,
            ],
            480 => 
            [
                'id' => 1981,
                'name' => 'Dauphin',
                'code' => '02',
                'country_id' => 117,
            ],
            481 => 
            [
                'id' => 1982,
                'name' => 'Praslin',
                'code' => '11',
                'country_id' => 117,
            ],
            482 => 
            [
                'id' => 1983,
                'name' => 'Balzers',
                'code' => '01',
                'country_id' => 118,
            ],
            483 => 
            [
                'id' => 1984,
                'name' => 'Gamprin',
                'code' => '03',
                'country_id' => 118,
            ],
            484 => 
            [
                'id' => 1985,
                'name' => 'Planken',
                'code' => '05',
                'country_id' => 118,
            ],
            485 => 
            [
                'id' => 1986,
                'name' => 'Vaduz',
                'code' => '11',
                'country_id' => 118,
            ],
            486 => 
            [
                'id' => 1987,
                'name' => 'Eschen',
                'code' => '02',
                'country_id' => 118,
            ],
            487 => 
            [
                'id' => 1988,
                'name' => 'Triesenberg',
                'code' => '10',
                'country_id' => 118,
            ],
            488 => 
            [
                'id' => 1989,
                'name' => 'Schellenberg',
                'code' => '08',
                'country_id' => 118,
            ],
            489 => 
            [
                'id' => 1990,
                'name' => 'Mauren',
                'code' => '04',
                'country_id' => 118,
            ],
            490 => 
            [
                'id' => 1991,
                'name' => 'Ruggell',
                'code' => '06',
                'country_id' => 118,
            ],
            491 => 
            [
                'id' => 1992,
                'name' => 'Schaan',
                'code' => '07',
                'country_id' => 118,
            ],
            492 => 
            [
                'id' => 1993,
                'name' => 'Triesen',
                'code' => '09',
                'country_id' => 118,
            ],
            493 => 
            [
                'id' => 1994,
                'name' => 'North Western',
                'code' => '32',
                'country_id' => 119,
            ],
            494 => 
            [
                'id' => 1995,
                'name' => 'Southern',
                'code' => '34',
                'country_id' => 119,
            ],
            495 => 
            [
                'id' => 1996,
                'name' => 'Central',
                'code' => '29',
                'country_id' => 119,
            ],
            496 => 
            [
                'id' => 1997,
                'name' => 'Sabaragamuwa',
                'code' => '33',
                'country_id' => 119,
            ],
            497 => 
            [
                'id' => 1998,
                'name' => 'North Central',
                'code' => '30',
                'country_id' => 119,
            ],
            498 => 
            [
                'id' => 1999,
                'name' => '',
                'code' => '31',
                'country_id' => 119,
            ],
            499 => 
            [
                'id' => 2000,
                'name' => 'Western',
                'code' => '36',
                'country_id' => 119,
            ],
        ]);
        \DB::table('regions')->insert([
            0 => 
            [
                'id' => 2001,
                'name' => 'Uva',
                'code' => '35',
                'country_id' => 119,
            ],
            1 => 
            [
                'id' => 2002,
                'name' => 'Nimba',
                'code' => '09',
                'country_id' => 120,
            ],
            2 => 
            [
                'id' => 2003,
                'name' => 'Grand Bassa',
                'code' => '11',
                'country_id' => 120,
            ],
            3 => 
            [
                'id' => 2004,
                'name' => 'Lofa',
                'code' => '05',
                'country_id' => 120,
            ],
            4 => 
            [
                'id' => 2005,
                'name' => 'Bong',
                'code' => '01',
                'country_id' => 120,
            ],
            5 => 
            [
                'id' => 2006,
                'name' => '',
                'code' => '02',
                'country_id' => 120,
            ],
            6 => 
            [
                'id' => 2007,
                'name' => 'Montserrado',
                'code' => '14',
                'country_id' => 120,
            ],
            7 => 
            [
                'id' => 2008,
                'name' => '',
                'code' => '15',
                'country_id' => 120,
            ],
            8 => 
            [
                'id' => 2009,
                'name' => 'Margibi',
                'code' => '17',
                'country_id' => 120,
            ],
            9 => 
            [
                'id' => 2010,
                'name' => '',
                'code' => '08',
                'country_id' => 120,
            ],
            10 => 
            [
                'id' => 2011,
                'name' => 'Sino',
                'code' => '10',
                'country_id' => 120,
            ],
            11 => 
            [
                'id' => 2012,
                'name' => 'River Cess',
                'code' => '18',
                'country_id' => 120,
            ],
            12 => 
            [
                'id' => 2013,
                'name' => 'Grand Cape Mount',
                'code' => '12',
                'country_id' => 120,
            ],
            13 => 
            [
                'id' => 2014,
                'name' => '',
                'code' => '16',
                'country_id' => 120,
            ],
            14 => 
            [
                'id' => 2015,
                'name' => 'Maryland',
                'code' => '13',
                'country_id' => 120,
            ],
            15 => 
            [
                'id' => 2016,
                'name' => 'Grand Cape Mount',
                'code' => '04',
                'country_id' => 120,
            ],
            16 => 
            [
                'id' => 2017,
                'name' => 'Gbarpolu',
                'code' => '21',
                'country_id' => 120,
            ],
            17 => 
            [
                'id' => 2018,
                'name' => 'River Gee',
                'code' => '22',
                'country_id' => 120,
            ],
            18 => 
            [
                'id' => 2019,
                'name' => 'Grand Gedeh',
                'code' => '19',
                'country_id' => 120,
            ],
            19 => 
            [
                'id' => 2020,
                'name' => 'Lofa',
                'code' => '20',
                'country_id' => 120,
            ],
            20 => 
            [
                'id' => 2021,
                'name' => 'Maseru',
                'code' => '14',
                'country_id' => 121,
            ],
            21 => 
            [
                'id' => 2022,
                'name' => 'Quthing',
                'code' => '18',
                'country_id' => 121,
            ],
            22 => 
            [
                'id' => 2023,
                'name' => 'Mafeteng',
                'code' => '13',
                'country_id' => 121,
            ],
            23 => 
            [
                'id' => 2024,
                'name' => 'Berea',
                'code' => '10',
                'country_id' => 121,
            ],
            24 => 
            [
                'id' => 2025,
                'name' => 'Mohales Hoek',
                'code' => '15',
                'country_id' => 121,
            ],
            25 => 
            [
                'id' => 2026,
                'name' => 'Thaba-Tseka',
                'code' => '19',
                'country_id' => 121,
            ],
            26 => 
            [
                'id' => 2027,
                'name' => 'Butha-Buthe',
                'code' => '11',
                'country_id' => 121,
            ],
            27 => 
            [
                'id' => 2028,
                'name' => 'Leribe',
                'code' => '12',
                'country_id' => 121,
            ],
            28 => 
            [
                'id' => 2029,
                'name' => 'Qachas Nek',
                'code' => '17',
                'country_id' => 121,
            ],
            29 => 
            [
                'id' => 2030,
                'name' => 'Mokhotlong',
                'code' => '16',
                'country_id' => 121,
            ],
            30 => 
            [
                'id' => 2031,
                'name' => '',
                'code' => '03',
                'country_id' => 121,
            ],
            31 => 
            [
                'id' => 2032,
                'name' => 'Panevezio Apskritis',
                'code' => '60',
                'country_id' => 122,
            ],
            32 => 
            [
                'id' => 2033,
                'name' => 'Telsiu Apskritis',
                'code' => '63',
                'country_id' => 122,
            ],
            33 => 
            [
                'id' => 2034,
                'name' => 'Klaipedos Apskritis',
                'code' => '58',
                'country_id' => 122,
            ],
            34 => 
            [
                'id' => 2035,
                'name' => 'Vilniaus Apskritis',
                'code' => '65',
                'country_id' => 122,
            ],
            35 => 
            [
                'id' => 2036,
                'name' => 'Siauliu Apskritis',
                'code' => '61',
                'country_id' => 122,
            ],
            36 => 
            [
                'id' => 2037,
                'name' => 'Taurages Apskritis',
                'code' => '62',
                'country_id' => 122,
            ],
            37 => 
            [
                'id' => 2038,
                'name' => 'Marijampoles Apskritis',
                'code' => '59',
                'country_id' => 122,
            ],
            38 => 
            [
                'id' => 2039,
                'name' => '',
                'code' => '40',
                'country_id' => 122,
            ],
            39 => 
            [
                'id' => 2040,
                'name' => 'Utenos Apskritis',
                'code' => '64',
                'country_id' => 122,
            ],
            40 => 
            [
                'id' => 2041,
                'name' => 'Alytaus Apskritis',
                'code' => '56',
                'country_id' => 122,
            ],
            41 => 
            [
                'id' => 2042,
                'name' => 'Kauno Apskritis',
                'code' => '57',
                'country_id' => 122,
            ],
            42 => 
            [
                'id' => 2043,
                'name' => 'Luxembourg',
                'code' => '03',
                'country_id' => 123,
            ],
            43 => 
            [
                'id' => 2044,
                'name' => 'Grevenmacher',
                'code' => '02',
                'country_id' => 123,
            ],
            44 => 
            [
                'id' => 2045,
                'name' => 'Diekirch',
                'code' => '01',
                'country_id' => 123,
            ],
            45 => 
            [
                'id' => 2046,
                'name' => 'Madonas',
                'code' => '20',
                'country_id' => 124,
            ],
            46 => 
            [
                'id' => 2047,
                'name' => 'Kuldigas',
                'code' => '15',
                'country_id' => 124,
            ],
            47 => 
            [
                'id' => 2048,
                'name' => 'Daugavpils',
                'code' => '07',
                'country_id' => 124,
            ],
            48 => 
            [
                'id' => 2049,
                'name' => 'Tukuma',
                'code' => '29',
                'country_id' => 124,
            ],
            49 => 
            [
                'id' => 2050,
                'name' => 'Ventspils',
                'code' => '33',
                'country_id' => 124,
            ],
            50 => 
            [
                'id' => 2051,
                'name' => 'Dobeles',
                'code' => '08',
                'country_id' => 124,
            ],
            51 => 
            [
                'id' => 2052,
                'name' => 'Liepajas',
                'code' => '17',
                'country_id' => 124,
            ],
            52 => 
            [
                'id' => 2053,
                'name' => 'Balvu',
                'code' => '03',
                'country_id' => 124,
            ],
            53 => 
            [
                'id' => 2054,
                'name' => 'Saldus',
                'code' => '27',
                'country_id' => 124,
            ],
            54 => 
            [
                'id' => 2055,
                'name' => 'Bauskas',
                'code' => '04',
                'country_id' => 124,
            ],
            55 => 
            [
                'id' => 2056,
                'name' => 'Limbazu',
                'code' => '18',
                'country_id' => 124,
            ],
            56 => 
            [
                'id' => 2057,
                'name' => 'Ludzas',
                'code' => '19',
                'country_id' => 124,
            ],
            57 => 
            [
                'id' => 2058,
                'name' => 'Cesu',
                'code' => '05',
                'country_id' => 124,
            ],
            58 => 
            [
                'id' => 2059,
                'name' => 'Jekabpils',
                'code' => '10',
                'country_id' => 124,
            ],
            59 => 
            [
                'id' => 2060,
                'name' => 'Aluksnes',
                'code' => '02',
                'country_id' => 124,
            ],
            60 => 
            [
                'id' => 2061,
                'name' => 'Rezeknes',
                'code' => '24',
                'country_id' => 124,
            ],
            61 => 
            [
                'id' => 2062,
                'name' => 'Rigas',
                'code' => '26',
                'country_id' => 124,
            ],
            62 => 
            [
                'id' => 2063,
                'name' => 'Ogres',
                'code' => '21',
                'country_id' => 124,
            ],
            63 => 
            [
                'id' => 2064,
                'name' => 'Kraslavas',
                'code' => '14',
                'country_id' => 124,
            ],
            64 => 
            [
                'id' => 2065,
                'name' => 'Gulbenes',
                'code' => '09',
                'country_id' => 124,
            ],
            65 => 
            [
                'id' => 2066,
                'name' => 'Riga',
                'code' => '25',
                'country_id' => 124,
            ],
            66 => 
            [
                'id' => 2067,
                'name' => 'Preilu',
                'code' => '22',
                'country_id' => 124,
            ],
            67 => 
            [
                'id' => 2068,
                'name' => 'Aizkraukles',
                'code' => '01',
                'country_id' => 124,
            ],
            68 => 
            [
                'id' => 2069,
                'name' => 'Talsu',
                'code' => '28',
                'country_id' => 124,
            ],
            69 => 
            [
                'id' => 2070,
                'name' => 'Jelgavas',
                'code' => '12',
                'country_id' => 124,
            ],
            70 => 
            [
                'id' => 2071,
                'name' => 'Valkas',
                'code' => '30',
                'country_id' => 124,
            ],
            71 => 
            [
                'id' => 2072,
                'name' => 'Valmieras',
                'code' => '31',
                'country_id' => 124,
            ],
            72 => 
            [
                'id' => 2073,
                'name' => 'Liepaja',
                'code' => '16',
                'country_id' => 124,
            ],
            73 => 
            [
                'id' => 2074,
                'name' => 'Ventspils',
                'code' => '32',
                'country_id' => 124,
            ],
            74 => 
            [
                'id' => 2075,
                'name' => 'Daugavpils',
                'code' => '06',
                'country_id' => 124,
            ],
            75 => 
            [
                'id' => 2076,
                'name' => 'Rezekne',
                'code' => '23',
                'country_id' => 124,
            ],
            76 => 
            [
                'id' => 2077,
                'name' => 'Yafran',
                'code' => '62',
                'country_id' => 125,
            ],
            77 => 
            [
                'id' => 2078,
                'name' => 'Tarabulus',
                'code' => '61',
                'country_id' => 125,
            ],
            78 => 
            [
                'id' => 2079,
                'name' => 'An Nuqat al Khams',
                'code' => '51',
                'country_id' => 125,
            ],
            79 => 
            [
                'id' => 2080,
                'name' => 'Al Aziziyah',
                'code' => '03',
                'country_id' => 125,
            ],
            80 => 
            [
                'id' => 2081,
                'name' => 'Az Zawiyah',
                'code' => '53',
                'country_id' => 125,
            ],
            81 => 
            [
                'id' => 2082,
                'name' => 'Misratah',
                'code' => '58',
                'country_id' => 125,
            ],
            82 => 
            [
                'id' => 2083,
                'name' => 'Gharyan',
                'code' => '57',
                'country_id' => 125,
            ],
            83 => 
            [
                'id' => 2084,
                'name' => 'Tubruq',
                'code' => '42',
                'country_id' => 125,
            ],
            84 => 
            [
                'id' => 2085,
                'name' => 'Tarhunah',
                'code' => '41',
                'country_id' => 125,
            ],
            85 => 
            [
                'id' => 2086,
                'name' => '',
                'code' => '09',
                'country_id' => 125,
            ],
            86 => 
            [
                'id' => 2087,
                'name' => 'Ash Shati\'',
                'code' => '13',
                'country_id' => 125,
            ],
            87 => 
            [
                'id' => 2088,
                'name' => 'Ajdabiya',
                'code' => '47',
                'country_id' => 125,
            ],
            88 => 
            [
                'id' => 2089,
                'name' => 'Murzuq',
                'code' => '30',
                'country_id' => 125,
            ],
            89 => 
            [
                'id' => 2090,
                'name' => 'Al Jabal al Akhdar',
                'code' => '49',
                'country_id' => 125,
            ],
            90 => 
            [
                'id' => 2091,
                'name' => '',
                'code' => '36',
                'country_id' => 125,
            ],
            91 => 
            [
                'id' => 2092,
                'name' => '',
                'code' => '43',
                'country_id' => 125,
            ],
            92 => 
            [
                'id' => 2093,
                'name' => 'Ghadamis',
                'code' => '56',
                'country_id' => 125,
            ],
            93 => 
            [
                'id' => 2094,
                'name' => '',
                'code' => '11',
                'country_id' => 125,
            ],
            94 => 
            [
                'id' => 2095,
                'name' => '',
                'code' => '02',
                'country_id' => 125,
            ],
            95 => 
            [
                'id' => 2096,
                'name' => 'Awbari',
                'code' => '52',
                'country_id' => 125,
            ],
            96 => 
            [
                'id' => 2097,
                'name' => 'Al Khums',
                'code' => '50',
                'country_id' => 125,
            ],
            97 => 
            [
                'id' => 2098,
                'name' => '',
                'code' => '06',
                'country_id' => 125,
            ],
            98 => 
            [
                'id' => 2099,
                'name' => 'Al Kufrah',
                'code' => '08',
                'country_id' => 125,
            ],
            99 => 
            [
                'id' => 2100,
                'name' => '',
                'code' => '04',
                'country_id' => 125,
            ],
            100 => 
            [
                'id' => 2101,
                'name' => '',
                'code' => '24',
                'country_id' => 125,
            ],
            101 => 
            [
                'id' => 2102,
                'name' => 'Al Fatih',
                'code' => '48',
                'country_id' => 125,
            ],
            102 => 
            [
                'id' => 2103,
                'name' => 'Banghazi',
                'code' => '54',
                'country_id' => 125,
            ],
            103 => 
            [
                'id' => 2104,
                'name' => 'Zlitan',
                'code' => '45',
                'country_id' => 125,
            ],
            104 => 
            [
                'id' => 2105,
                'name' => 'Al Jufrah',
                'code' => '05',
                'country_id' => 125,
            ],
            105 => 
            [
                'id' => 2106,
                'name' => '',
                'code' => '18',
                'country_id' => 125,
            ],
            106 => 
            [
                'id' => 2107,
                'name' => '',
                'code' => '31',
                'country_id' => 125,
            ],
            107 => 
            [
                'id' => 2108,
                'name' => 'Sawfajjin',
                'code' => '59',
                'country_id' => 125,
            ],
            108 => 
            [
                'id' => 2109,
                'name' => '',
                'code' => '25',
                'country_id' => 125,
            ],
            109 => 
            [
                'id' => 2110,
                'name' => 'Darnah',
                'code' => '55',
                'country_id' => 125,
            ],
            110 => 
            [
                'id' => 2111,
                'name' => 'Sabha',
                'code' => '34',
                'country_id' => 125,
            ],
            111 => 
            [
                'id' => 2112,
                'name' => '',
                'code' => '29',
                'country_id' => 125,
            ],
            112 => 
            [
                'id' => 2113,
                'name' => '',
                'code' => '23',
                'country_id' => 125,
            ],
            113 => 
            [
                'id' => 2114,
                'name' => '',
                'code' => '19',
                'country_id' => 125,
            ],
            114 => 
            [
                'id' => 2115,
                'name' => '',
                'code' => '27',
                'country_id' => 125,
            ],
            115 => 
            [
                'id' => 2116,
                'name' => 'Surt',
                'code' => '60',
                'country_id' => 125,
            ],
            116 => 
            [
                'id' => 2117,
                'name' => '',
                'code' => '10',
                'country_id' => 125,
            ],
            117 => 
            [
                'id' => 2118,
                'name' => '',
                'code' => '32',
                'country_id' => 125,
            ],
            118 => 
            [
                'id' => 2119,
                'name' => '',
                'code' => '33',
                'country_id' => 125,
            ],
            119 => 
            [
                'id' => 2120,
                'name' => '',
                'code' => '26',
                'country_id' => 125,
            ],
            120 => 
            [
                'id' => 2121,
                'name' => '',
                'code' => '35',
                'country_id' => 125,
            ],
            121 => 
            [
                'id' => 2122,
                'name' => '',
                'code' => '08',
                'country_id' => 126,
            ],
            122 => 
            [
                'id' => 2123,
                'name' => '',
                'code' => '02',
                'country_id' => 126,
            ],
            123 => 
            [
                'id' => 2124,
                'name' => '',
                'code' => '22',
                'country_id' => 126,
            ],
            124 => 
            [
                'id' => 2125,
                'name' => '',
                'code' => '40',
                'country_id' => 126,
            ],
            125 => 
            [
                'id' => 2126,
                'name' => '',
                'code' => '19',
                'country_id' => 126,
            ],
            126 => 
            [
                'id' => 2127,
                'name' => '',
                'code' => '32',
                'country_id' => 126,
            ],
            127 => 
            [
                'id' => 2128,
                'name' => '',
                'code' => '41',
                'country_id' => 126,
            ],
            128 => 
            [
                'id' => 2129,
                'name' => '',
                'code' => '21',
                'country_id' => 126,
            ],
            129 => 
            [
                'id' => 2130,
                'name' => '',
                'code' => '38',
                'country_id' => 126,
            ],
            130 => 
            [
                'id' => 2131,
                'name' => '',
                'code' => '17',
                'country_id' => 126,
            ],
            131 => 
            [
                'id' => 2132,
                'name' => '',
                'code' => '39',
                'country_id' => 126,
            ],
            132 => 
            [
                'id' => 2133,
                'name' => '',
                'code' => '30',
                'country_id' => 126,
            ],
            133 => 
            [
                'id' => 2134,
                'name' => '',
                'code' => '27',
                'country_id' => 126,
            ],
            134 => 
            [
                'id' => 2135,
                'name' => '',
                'code' => '10',
                'country_id' => 126,
            ],
            135 => 
            [
                'id' => 2136,
                'name' => '',
                'code' => '03',
                'country_id' => 126,
            ],
            136 => 
            [
                'id' => 2137,
                'name' => '',
                'code' => '15',
                'country_id' => 126,
            ],
            137 => 
            [
                'id' => 2138,
                'name' => '',
                'code' => '25',
                'country_id' => 126,
            ],
            138 => 
            [
                'id' => 2139,
                'name' => '',
                'code' => '01',
                'country_id' => 126,
            ],
            139 => 
            [
                'id' => 2140,
                'name' => '',
                'code' => '36',
                'country_id' => 126,
            ],
            140 => 
            [
                'id' => 2141,
                'name' => '',
                'code' => '33',
                'country_id' => 126,
            ],
            141 => 
            [
                'id' => 2142,
                'name' => '',
                'code' => '37',
                'country_id' => 126,
            ],
            142 => 
            [
                'id' => 2143,
                'name' => '',
                'code' => '12',
                'country_id' => 126,
            ],
            143 => 
            [
                'id' => 2144,
                'name' => '',
                'code' => '11',
                'country_id' => 126,
            ],
            144 => 
            [
                'id' => 2145,
                'name' => '',
                'code' => '09',
                'country_id' => 126,
            ],
            145 => 
            [
                'id' => 2146,
                'name' => '',
                'code' => '06',
                'country_id' => 126,
            ],
            146 => 
            [
                'id' => 2147,
                'name' => '',
                'code' => '24',
                'country_id' => 126,
            ],
            147 => 
            [
                'id' => 2148,
                'name' => '',
                'code' => '18',
                'country_id' => 126,
            ],
            148 => 
            [
                'id' => 2149,
                'name' => '',
                'code' => '07',
                'country_id' => 126,
            ],
            149 => 
            [
                'id' => 2150,
                'name' => '',
                'code' => '29',
                'country_id' => 126,
            ],
            150 => 
            [
                'id' => 2151,
                'name' => '',
                'code' => '05',
                'country_id' => 126,
            ],
            151 => 
            [
                'id' => 2152,
                'name' => '',
                'code' => '13',
                'country_id' => 126,
            ],
            152 => 
            [
                'id' => 2153,
                'name' => '',
                'code' => '14',
                'country_id' => 126,
            ],
            153 => 
            [
                'id' => 2154,
                'name' => '',
                'code' => '42',
                'country_id' => 126,
            ],
            154 => 
            [
                'id' => 2155,
                'name' => '',
                'code' => '20',
                'country_id' => 126,
            ],
            155 => 
            [
                'id' => 2156,
                'name' => '',
                'code' => '23',
                'country_id' => 126,
            ],
            156 => 
            [
                'id' => 2157,
                'name' => '',
                'code' => '04',
                'country_id' => 126,
            ],
            157 => 
            [
                'id' => 2158,
                'name' => '',
                'code' => '26',
                'country_id' => 126,
            ],
            158 => 
            [
                'id' => 2159,
                'name' => '',
                'code' => '16',
                'country_id' => 126,
            ],
            159 => 
            [
                'id' => 2160,
                'name' => '',
                'code' => '34',
                'country_id' => 126,
            ],
            160 => 
            [
                'id' => 2161,
                'name' => '',
                'code' => '43',
                'country_id' => 126,
            ],
            161 => 
            [
                'id' => 2162,
                'name' => '',
                'code' => '35',
                'country_id' => 126,
            ],
            162 => 
            [
                'id' => 2163,
                'name' => '',
                'code' => '00',
                'country_id' => 127,
            ],
            163 => 
            [
                'id' => 2164,
                'name' => '',
                'code' => '52',
                'country_id' => 128,
            ],
            164 => 
            [
                'id' => 2165,
                'name' => '',
                'code' => '47',
                'country_id' => 128,
            ],
            165 => 
            [
                'id' => 2166,
                'name' => 'Gagauzia',
                'code' => '51',
                'country_id' => 128,
            ],
            166 => 
            [
                'id' => 2167,
                'name' => '',
                'code' => '55',
                'country_id' => 128,
            ],
            167 => 
            [
                'id' => 2168,
                'name' => '',
                'code' => '49',
                'country_id' => 128,
            ],
            168 => 
            [
                'id' => 2169,
                'name' => '',
                'code' => '56',
                'country_id' => 128,
            ],
            169 => 
            [
                'id' => 2170,
                'name' => '',
                'code' => '46',
                'country_id' => 128,
            ],
            170 => 
            [
                'id' => 2171,
                'name' => '',
                'code' => '48',
                'country_id' => 128,
            ],
            171 => 
            [
                'id' => 2172,
                'name' => '',
                'code' => '54',
                'country_id' => 128,
            ],
            172 => 
            [
                'id' => 2173,
                'name' => '',
                'code' => '50',
                'country_id' => 128,
            ],
            173 => 
            [
                'id' => 2174,
                'name' => '',
                'code' => '53',
                'country_id' => 128,
            ],
            174 => 
            [
                'id' => 2175,
                'name' => 'Antananarivo',
                'code' => '05',
                'country_id' => 129,
            ],
            175 => 
            [
                'id' => 2176,
                'name' => 'Mahajanga',
                'code' => '03',
                'country_id' => 129,
            ],
            176 => 
            [
                'id' => 2177,
                'name' => 'Toliara',
                'code' => '06',
                'country_id' => 129,
            ],
            177 => 
            [
                'id' => 2178,
                'name' => 'Fianarantsoa',
                'code' => '02',
                'country_id' => 129,
            ],
            178 => 
            [
                'id' => 2179,
                'name' => 'Antsiranana',
                'code' => '01',
                'country_id' => 129,
            ],
            179 => 
            [
                'id' => 2180,
                'name' => 'Toamasina',
                'code' => '04',
                'country_id' => 129,
            ],
            180 => 
            [
                'id' => 2181,
                'name' => '',
                'code' => '00',
                'country_id' => 130,
            ],
            181 => 
            [
                'id' => 2182,
                'name' => 'Petrovec',
                'code' => '79',
                'country_id' => 131,
            ],
            182 => 
            [
                'id' => 2183,
                'name' => 'Bogovinje',
                'code' => '10',
                'country_id' => 131,
            ],
            183 => 
            [
                'id' => 2184,
                'name' => 'Lozovo',
                'code' => '60',
                'country_id' => 131,
            ],
            184 => 
            [
                'id' => 2185,
                'name' => 'Rostusa',
                'code' => '88',
                'country_id' => 131,
            ],
            185 => 
            [
                'id' => 2186,
                'name' => 'Staro Nagoricane',
                'code' => '97',
                'country_id' => 131,
            ],
            186 => 
            [
                'id' => 2187,
                'name' => 'Gevgelija',
                'code' => '33',
                'country_id' => 131,
            ],
            187 => 
            [
                'id' => 2188,
                'name' => 'Srbinovo',
                'code' => '94',
                'country_id' => 131,
            ],
            188 => 
            [
                'id' => 2189,
                'name' => 'Orasac',
                'code' => '75',
                'country_id' => 131,
            ],
            189 => 
            [
                'id' => 2190,
                'name' => 'Valandovo',
                'code' => 'A8',
                'country_id' => 131,
            ],
            190 => 
            [
                'id' => 2191,
                'name' => 'Ilinden',
                'code' => '36',
                'country_id' => 131,
            ],
            191 => 
            [
                'id' => 2192,
                'name' => 'Ohrid',
                'code' => '74',
                'country_id' => 131,
            ],
            192 => 
            [
                'id' => 2193,
                'name' => 'Sveti Nikole',
                'code' => 'A4',
                'country_id' => 131,
            ],
            193 => 
            [
                'id' => 2194,
                'name' => 'Lipkovo',
                'code' => '59',
                'country_id' => 131,
            ],
            194 => 
            [
                'id' => 2195,
                'name' => 'Zitose',
                'code' => 'C4',
                'country_id' => 131,
            ],
            195 => 
            [
                'id' => 2196,
                'name' => 'Studenicani',
                'code' => 'A2',
                'country_id' => 131,
            ],
            196 => 
            [
                'id' => 2197,
                'name' => 'Krivogastani',
                'code' => '53',
                'country_id' => 131,
            ],
            197 => 
            [
                'id' => 2198,
                'name' => 'Radovis',
                'code' => '84',
                'country_id' => 131,
            ],
            198 => 
            [
                'id' => 2199,
                'name' => 'Dobrusevo',
                'code' => '26',
                'country_id' => 131,
            ],
            199 => 
            [
                'id' => 2200,
                'name' => 'Rankovce',
                'code' => '85',
                'country_id' => 131,
            ],
            200 => 
            [
                'id' => 2201,
                'name' => 'Topolcani',
                'code' => 'A7',
                'country_id' => 131,
            ],
            201 => 
            [
                'id' => 2202,
                'name' => 'Kriva Palanka',
                'code' => '52',
                'country_id' => 131,
            ],
            202 => 
            [
                'id' => 2203,
                'name' => 'Zajas',
                'code' => 'C1',
                'country_id' => 131,
            ],
            203 => 
            [
                'id' => 2204,
                'name' => 'Vitoliste',
                'code' => 'B5',
                'country_id' => 131,
            ],
            204 => 
            [
                'id' => 2205,
                'name' => 'Debar',
                'code' => '21',
                'country_id' => 131,
            ],
            205 => 
            [
                'id' => 2206,
                'name' => 'Bosilovo',
                'code' => '11',
                'country_id' => 131,
            ],
            206 => 
            [
                'id' => 2207,
                'name' => 'Dzepciste',
                'code' => '31',
                'country_id' => 131,
            ],
            207 => 
            [
                'id' => 2208,
                'name' => 'Vasilevo',
                'code' => 'A9',
                'country_id' => 131,
            ],
            208 => 
            [
                'id' => 2209,
                'name' => 'Star Dojran',
                'code' => '96',
                'country_id' => 131,
            ],
            209 => 
            [
                'id' => 2210,
                'name' => 'Saraj',
                'code' => '90',
                'country_id' => 131,
            ],
            210 => 
            [
                'id' => 2211,
                'name' => 'Aracinovo',
                'code' => '01',
                'country_id' => 131,
            ],
            211 => 
            [
                'id' => 2212,
                'name' => 'Oslomej',
                'code' => '77',
                'country_id' => 131,
            ],
            212 => 
            [
                'id' => 2213,
                'name' => 'Miravci',
                'code' => '66',
                'country_id' => 131,
            ],
            213 => 
            [
                'id' => 2214,
                'name' => 'Belcista',
                'code' => '03',
                'country_id' => 131,
            ],
            214 => 
            [
                'id' => 2215,
                'name' => 'Karbinci',
                'code' => '40',
                'country_id' => 131,
            ],
            215 => 
            [
                'id' => 2216,
                'name' => 'Krusevo',
                'code' => '54',
                'country_id' => 131,
            ],
            216 => 
            [
                'id' => 2217,
                'name' => 'Kondovo',
                'code' => '48',
                'country_id' => 131,
            ],
            217 => 
            [
                'id' => 2218,
                'name' => 'Resen',
                'code' => '86',
                'country_id' => 131,
            ],
            218 => 
            [
                'id' => 2219,
                'name' => 'Lukovo',
                'code' => '61',
                'country_id' => 131,
            ],
            219 => 
            [
                'id' => 2220,
                'name' => 'Vranestica',
                'code' => 'B6',
                'country_id' => 131,
            ],
            220 => 
            [
                'id' => 2221,
                'name' => 'Negotino-Polosko',
                'code' => '70',
                'country_id' => 131,
            ],
            221 => 
            [
                'id' => 2222,
                'name' => 'Stip',
                'code' => '98',
                'country_id' => 131,
            ],
            222 => 
            [
                'id' => 2223,
                'name' => 'Sopotnica',
                'code' => '93',
                'country_id' => 131,
            ],
            223 => 
            [
                'id' => 2224,
                'name' => 'Orizari',
                'code' => '76',
                'country_id' => 131,
            ],
            224 => 
            [
                'id' => 2225,
                'name' => 'Veles',
                'code' => 'B1',
                'country_id' => 131,
            ],
            225 => 
            [
                'id' => 2226,
                'name' => 'Bac',
                'code' => '02',
                'country_id' => 131,
            ],
            226 => 
            [
                'id' => 2227,
                'name' => 'Zelenikovo',
                'code' => 'C2',
                'country_id' => 131,
            ],
            227 => 
            [
                'id' => 2228,
                'name' => 'Novo Selo',
                'code' => '72',
                'country_id' => 131,
            ],
            228 => 
            [
                'id' => 2229,
                'name' => 'Strumica',
                'code' => 'A1',
                'country_id' => 131,
            ],
            229 => 
            [
                'id' => 2230,
                'name' => 'Mavrovi Anovi',
                'code' => '64',
                'country_id' => 131,
            ],
            230 => 
            [
                'id' => 2231,
                'name' => 'Novaci',
                'code' => '71',
                'country_id' => 131,
            ],
            231 => 
            [
                'id' => 2232,
                'name' => 'Gostivar',
                'code' => '34',
                'country_id' => 131,
            ],
            232 => 
            [
                'id' => 2233,
                'name' => 'Cucer-Sandevo',
                'code' => '20',
                'country_id' => 131,
            ],
            233 => 
            [
                'id' => 2234,
                'name' => 'Demir Kapija',
                'code' => '25',
                'country_id' => 131,
            ],
            234 => 
            [
                'id' => 2235,
                'name' => 'Oblesevo',
                'code' => '73',
                'country_id' => 131,
            ],
            235 => 
            [
                'id' => 2236,
                'name' => 'Caska',
                'code' => '15',
                'country_id' => 131,
            ],
            236 => 
            [
                'id' => 2237,
                'name' => 'Murtino',
                'code' => '68',
                'country_id' => 131,
            ],
            237 => 
            [
                'id' => 2238,
                'name' => 'Demir Hisar',
                'code' => '24',
                'country_id' => 131,
            ],
            238 => 
            [
                'id' => 2239,
                'name' => 'Probistip',
                'code' => '83',
                'country_id' => 131,
            ],
            239 => 
            [
                'id' => 2240,
                'name' => 'Makedonski Brod',
                'code' => '63',
                'country_id' => 131,
            ],
            240 => 
            [
                'id' => 2241,
                'name' => 'Karpos',
                'code' => '41',
                'country_id' => 131,
            ],
            241 => 
            [
                'id' => 2242,
                'name' => 'Bistrica',
                'code' => '05',
                'country_id' => 131,
            ],
            242 => 
            [
                'id' => 2243,
                'name' => 'Sopiste',
                'code' => '92',
                'country_id' => 131,
            ],
            243 => 
            [
                'id' => 2244,
                'name' => 'Kumanovo',
                'code' => '57',
                'country_id' => 131,
            ],
            244 => 
            [
                'id' => 2245,
                'name' => 'Kavadarci',
                'code' => '42',
                'country_id' => 131,
            ],
            245 => 
            [
                'id' => 2246,
                'name' => 'Prilep',
                'code' => '82',
                'country_id' => 131,
            ],
            246 => 
            [
                'id' => 2247,
                'name' => 'Kocani',
                'code' => '46',
                'country_id' => 131,
            ],
            247 => 
            [
                'id' => 2248,
                'name' => 'Samokov',
                'code' => '89',
                'country_id' => 131,
            ],
            248 => 
            [
                'id' => 2249,
                'name' => 'Klecevce',
                'code' => '45',
                'country_id' => 131,
            ],
            249 => 
            [
                'id' => 2250,
                'name' => 'Dolneni',
                'code' => '28',
                'country_id' => 131,
            ],
            250 => 
            [
                'id' => 2251,
                'name' => 'Dolna Banjica',
                'code' => '27',
                'country_id' => 131,
            ],
            251 => 
            [
                'id' => 2252,
                'name' => 'Vratnica',
                'code' => 'B8',
                'country_id' => 131,
            ],
            252 => 
            [
                'id' => 2253,
                'name' => 'Mogila',
                'code' => '67',
                'country_id' => 131,
            ],
            253 => 
            [
                'id' => 2254,
                'name' => 'Berovo',
                'code' => '04',
                'country_id' => 131,
            ],
            254 => 
            [
                'id' => 2255,
                'name' => 'Brvenica',
                'code' => '12',
                'country_id' => 131,
            ],
            255 => 
            [
                'id' => 2256,
                'name' => 'Makedonska Kamenica',
                'code' => '62',
                'country_id' => 131,
            ],
            256 => 
            [
                'id' => 2257,
                'name' => 'Sipkovica',
                'code' => '91',
                'country_id' => 131,
            ],
            257 => 
            [
                'id' => 2258,
                'name' => 'Delogozdi',
                'code' => '23',
                'country_id' => 131,
            ],
            258 => 
            [
                'id' => 2259,
                'name' => 'Delcevo',
                'code' => '22',
                'country_id' => 131,
            ],
            259 => 
            [
                'id' => 2260,
                'name' => 'Vinica',
                'code' => 'B4',
                'country_id' => 131,
            ],
            260 => 
            [
                'id' => 2261,
                'name' => 'Bogomila',
                'code' => '09',
                'country_id' => 131,
            ],
            261 => 
            [
                'id' => 2262,
                'name' => 'Bitola',
                'code' => '06',
                'country_id' => 131,
            ],
            262 => 
            [
                'id' => 2263,
                'name' => 'Blatec',
                'code' => '07',
                'country_id' => 131,
            ],
            263 => 
            [
                'id' => 2264,
                'name' => 'Cegrane',
                'code' => '16',
                'country_id' => 131,
            ],
            264 => 
            [
                'id' => 2265,
                'name' => 'Kratovo',
                'code' => '51',
                'country_id' => 131,
            ],
            265 => 
            [
                'id' => 2266,
                'name' => 'Bogdanci',
                'code' => '08',
                'country_id' => 131,
            ],
            266 => 
            [
                'id' => 2267,
                'name' => 'Konopiste',
                'code' => '49',
                'country_id' => 131,
            ],
            267 => 
            [
                'id' => 2268,
                'name' => 'Zelino',
                'code' => 'C3',
                'country_id' => 131,
            ],
            268 => 
            [
                'id' => 2269,
                'name' => 'Labunista',
                'code' => '58',
                'country_id' => 131,
            ],
            269 => 
            [
                'id' => 2270,
                'name' => 'Suto Orizari',
                'code' => 'A3',
                'country_id' => 131,
            ],
            270 => 
            [
                'id' => 2271,
                'name' => 'Tearce',
                'code' => 'A5',
                'country_id' => 131,
            ],
            271 => 
            [
                'id' => 2272,
                'name' => 'Vrutok',
                'code' => 'B9',
                'country_id' => 131,
            ],
            272 => 
            [
                'id' => 2273,
                'name' => 'Staravina',
                'code' => '95',
                'country_id' => 131,
            ],
            273 => 
            [
                'id' => 2274,
                'name' => 'Negotino',
                'code' => '69',
                'country_id' => 131,
            ],
            274 => 
            [
                'id' => 2275,
                'name' => 'Drugovo',
                'code' => '30',
                'country_id' => 131,
            ],
            275 => 
            [
                'id' => 2276,
                'name' => 'Zletovo',
                'code' => 'C5',
                'country_id' => 131,
            ],
            276 => 
            [
                'id' => 2277,
                'name' => 'Pehcevo',
                'code' => '78',
                'country_id' => 131,
            ],
            277 => 
            [
                'id' => 2278,
                'name' => 'Cesinovo',
                'code' => '19',
                'country_id' => 131,
            ],
            278 => 
            [
                'id' => 2279,
                'name' => 'Capari',
                'code' => '14',
                'country_id' => 131,
            ],
            279 => 
            [
                'id' => 2280,
                'name' => 'Kukurecani',
                'code' => '56',
                'country_id' => 131,
            ],
            280 => 
            [
                'id' => 2281,
                'name' => 'Vrapciste',
                'code' => 'B7',
                'country_id' => 131,
            ],
            281 => 
            [
                'id' => 2282,
                'name' => 'Rosoman',
                'code' => '87',
                'country_id' => 131,
            ],
            282 => 
            [
                'id' => 2283,
                'name' => 'Velesta',
                'code' => 'B2',
                'country_id' => 131,
            ],
            283 => 
            [
                'id' => 2284,
                'name' => 'Konce',
                'code' => '47',
                'country_id' => 131,
            ],
            284 => 
            [
                'id' => 2285,
                'name' => 'Gradsko',
                'code' => '35',
                'country_id' => 131,
            ],
            285 => 
            [
                'id' => 2286,
                'name' => 'Kosel',
                'code' => '50',
                'country_id' => 131,
            ],
            286 => 
            [
                'id' => 2287,
                'name' => 'Kisela Voda',
                'code' => '44',
                'country_id' => 131,
            ],
            287 => 
            [
                'id' => 2288,
                'name' => 'Jegunovce',
                'code' => '38',
                'country_id' => 131,
            ],
            288 => 
            [
                'id' => 2289,
                'name' => 'Plasnica',
                'code' => '80',
                'country_id' => 131,
            ],
            289 => 
            [
                'id' => 2290,
                'name' => 'Kamenjane',
                'code' => '39',
                'country_id' => 131,
            ],
            290 => 
            [
                'id' => 2291,
                'name' => 'Izvor',
                'code' => '37',
                'country_id' => 131,
            ],
            291 => 
            [
                'id' => 2292,
                'name' => 'Struga',
                'code' => '99',
                'country_id' => 131,
            ],
            292 => 
            [
                'id' => 2293,
                'name' => 'Podares',
                'code' => '81',
                'country_id' => 131,
            ],
            293 => 
            [
                'id' => 2294,
                'name' => 'Tetovo',
                'code' => 'A6',
                'country_id' => 131,
            ],
            294 => 
            [
                'id' => 2295,
                'name' => 'Meseista',
                'code' => '65',
                'country_id' => 131,
            ],
            295 => 
            [
                'id' => 2296,
                'name' => 'Vevcani',
                'code' => 'B3',
                'country_id' => 131,
            ],
            296 => 
            [
                'id' => 2297,
                'name' => 'Zrnovci',
                'code' => 'C6',
                'country_id' => 131,
            ],
            297 => 
            [
                'id' => 2298,
                'name' => 'Kicevo',
                'code' => '43',
                'country_id' => 131,
            ],
            298 => 
            [
                'id' => 2299,
                'name' => 'Kuklis',
                'code' => '55',
                'country_id' => 131,
            ],
            299 => 
            [
                'id' => 2300,
                'name' => 'Koulikoro',
                'code' => '07',
                'country_id' => 132,
            ],
            300 => 
            [
                'id' => 2301,
                'name' => 'Mopti',
                'code' => '04',
                'country_id' => 132,
            ],
            301 => 
            [
                'id' => 2302,
                'name' => 'Kayes',
                'code' => '03',
                'country_id' => 132,
            ],
            302 => 
            [
                'id' => 2303,
                'name' => '',
                'code' => '02',
                'country_id' => 132,
            ],
            303 => 
            [
                'id' => 2304,
                'name' => '',
                'code' => '00',
                'country_id' => 132,
            ],
            304 => 
            [
                'id' => 2305,
                'name' => 'Tombouctou',
                'code' => '08',
                'country_id' => 132,
            ],
            305 => 
            [
                'id' => 2306,
                'name' => 'Segou',
                'code' => '05',
                'country_id' => 132,
            ],
            306 => 
            [
                'id' => 2307,
                'name' => 'Sikasso',
                'code' => '06',
                'country_id' => 132,
            ],
            307 => 
            [
                'id' => 2308,
                'name' => 'Bamako',
                'code' => '01',
                'country_id' => 132,
            ],
            308 => 
            [
                'id' => 2309,
                'name' => 'Gao',
                'code' => '09',
                'country_id' => 132,
            ],
            309 => 
            [
                'id' => 2310,
                'name' => 'Kidal',
                'code' => '10',
                'country_id' => 132,
            ],
            310 => 
            [
                'id' => 2311,
                'name' => 'Pegu',
                'code' => '09',
                'country_id' => 133,
            ],
            311 => 
            [
                'id' => 2312,
                'name' => 'Mon State',
                'code' => '13',
                'country_id' => 133,
            ],
            312 => 
            [
                'id' => 2313,
                'name' => 'Kachin State',
                'code' => '04',
                'country_id' => 133,
            ],
            313 => 
            [
                'id' => 2314,
                'name' => 'Rakhine State',
                'code' => '01',
                'country_id' => 133,
            ],
            314 => 
            [
                'id' => 2315,
                'name' => 'Yangon',
                'code' => '17',
                'country_id' => 133,
            ],
            315 => 
            [
                'id' => 2316,
                'name' => 'Irrawaddy',
                'code' => '03',
                'country_id' => 133,
            ],
            316 => 
            [
                'id' => 2317,
                'name' => 'Tenasserim',
                'code' => '12',
                'country_id' => 133,
            ],
            317 => 
            [
                'id' => 2318,
                'name' => 'Karan State',
                'code' => '05',
                'country_id' => 133,
            ],
            318 => 
            [
                'id' => 2319,
                'name' => 'Sagaing',
                'code' => '10',
                'country_id' => 133,
            ],
            319 => 
            [
                'id' => 2320,
                'name' => 'Magwe',
                'code' => '07',
                'country_id' => 133,
            ],
            320 => 
            [
                'id' => 2321,
                'name' => 'Chin State',
                'code' => '02',
                'country_id' => 133,
            ],
            321 => 
            [
                'id' => 2322,
                'name' => 'Shan State',
                'code' => '11',
                'country_id' => 133,
            ],
            322 => 
            [
                'id' => 2323,
                'name' => 'Mandalay',
                'code' => '08',
                'country_id' => 133,
            ],
            323 => 
            [
                'id' => 2324,
                'name' => '',
                'code' => '15',
                'country_id' => 133,
            ],
            324 => 
            [
                'id' => 2325,
                'name' => '',
                'code' => '16',
                'country_id' => 133,
            ],
            325 => 
            [
                'id' => 2326,
                'name' => 'Kayah State',
                'code' => '06',
                'country_id' => 133,
            ],
            326 => 
            [
                'id' => 2327,
                'name' => '',
                'code' => '22',
                'country_id' => 133,
            ],
            327 => 
            [
                'id' => 2328,
                'name' => 'Dornogovi',
                'code' => '07',
                'country_id' => 134,
            ],
            328 => 
            [
                'id' => 2329,
                'name' => 'Omnogovi',
                'code' => '14',
                'country_id' => 134,
            ],
            329 => 
            [
                'id' => 2330,
                'name' => 'Dundgovi',
                'code' => '08',
                'country_id' => 134,
            ],
            330 => 
            [
                'id' => 2331,
                'name' => 'Dzavhan',
                'code' => '09',
                'country_id' => 134,
            ],
            331 => 
            [
                'id' => 2332,
                'name' => 'Tov',
                'code' => '18',
                'country_id' => 134,
            ],
            332 => 
            [
                'id' => 2333,
                'name' => 'Suhbaatar',
                'code' => '17',
                'country_id' => 134,
            ],
            333 => 
            [
                'id' => 2334,
                'name' => 'Bulgan',
                'code' => '21',
                'country_id' => 134,
            ],
            334 => 
            [
                'id' => 2335,
                'name' => 'Arhangay',
                'code' => '01',
                'country_id' => 134,
            ],
            335 => 
            [
                'id' => 2336,
                'name' => 'Govisumber',
                'code' => '24',
                'country_id' => 134,
            ],
            336 => 
            [
                'id' => 2337,
                'name' => 'Hentiy',
                'code' => '11',
                'country_id' => 134,
            ],
            337 => 
            [
                'id' => 2338,
                'name' => 'Bayan-Olgiy',
                'code' => '03',
                'country_id' => 134,
            ],
            338 => 
            [
                'id' => 2339,
                'name' => 'Dornod',
                'code' => '06',
                'country_id' => 134,
            ],
            339 => 
            [
                'id' => 2340,
                'name' => 'Hovsgol',
                'code' => '13',
                'country_id' => 134,
            ],
            340 => 
            [
                'id' => 2341,
                'name' => 'Govi-Altay',
                'code' => '10',
                'country_id' => 134,
            ],
            341 => 
            [
                'id' => 2342,
                'name' => 'Hovd',
                'code' => '12',
                'country_id' => 134,
            ],
            342 => 
            [
                'id' => 2343,
                'name' => 'Selenge',
                'code' => '16',
                'country_id' => 134,
            ],
            343 => 
            [
                'id' => 2344,
                'name' => 'Bayanhongor',
                'code' => '02',
                'country_id' => 134,
            ],
            344 => 
            [
                'id' => 2345,
                'name' => 'Ulaanbaatar',
                'code' => '20',
                'country_id' => 134,
            ],
            345 => 
            [
                'id' => 2346,
                'name' => 'Ovorhangay',
                'code' => '15',
                'country_id' => 134,
            ],
            346 => 
            [
                'id' => 2347,
                'name' => 'Uvs',
                'code' => '19',
                'country_id' => 134,
            ],
            347 => 
            [
                'id' => 2348,
                'name' => 'Darhan-Uul',
                'code' => '23',
                'country_id' => 134,
            ],
            348 => 
            [
                'id' => 2349,
                'name' => 'Orhon',
                'code' => '25',
                'country_id' => 134,
            ],
            349 => 
            [
                'id' => 2350,
                'name' => 'Ilhas',
                'code' => '01',
                'country_id' => 135,
            ],
            350 => 
            [
                'id' => 2351,
                'name' => '',
                'code' => '18',
                'country_id' => 136,
            ],
            351 => 
            [
                'id' => 2352,
                'name' => '',
                'code' => '12',
                'country_id' => 136,
            ],
            352 => 
            [
                'id' => 2353,
                'name' => '',
                'code' => '15',
                'country_id' => 136,
            ],
            353 => 
            [
                'id' => 2354,
                'name' => '',
                'code' => '19',
                'country_id' => 136,
            ],
            354 => 
            [
                'id' => 2355,
                'name' => '',
                'code' => '16',
                'country_id' => 136,
            ],
            355 => 
            [
                'id' => 2356,
                'name' => '',
                'code' => '23',
                'country_id' => 136,
            ],
            356 => 
            [
                'id' => 2357,
                'name' => '',
                'code' => '14',
                'country_id' => 136,
            ],
            357 => 
            [
                'id' => 2358,
                'name' => '',
                'code' => '13',
                'country_id' => 136,
            ],
            358 => 
            [
                'id' => 2359,
                'name' => '',
                'code' => '17',
                'country_id' => 136,
            ],
            359 => 
            [
                'id' => 2360,
                'name' => '',
                'code' => '20',
                'country_id' => 136,
            ],
            360 => 
            [
                'id' => 2361,
                'name' => '',
                'code' => '00',
                'country_id' => 136,
            ],
            361 => 
            [
                'id' => 2362,
                'name' => '',
                'code' => '00',
                'country_id' => 137,
            ],
            362 => 
            [
                'id' => 2363,
                'name' => 'Brakna',
                'code' => '05',
                'country_id' => 138,
            ],
            363 => 
            [
                'id' => 2364,
                'name' => 'Hodh Ech Chargui',
                'code' => '01',
                'country_id' => 138,
            ],
            364 => 
            [
                'id' => 2365,
                'name' => 'Gorgol',
                'code' => '04',
                'country_id' => 138,
            ],
            365 => 
            [
                'id' => 2366,
                'name' => 'Assaba',
                'code' => '03',
                'country_id' => 138,
            ],
            366 => 
            [
                'id' => 2367,
                'name' => 'Guidimaka',
                'code' => '10',
                'country_id' => 138,
            ],
            367 => 
            [
                'id' => 2368,
                'name' => 'Adrar',
                'code' => '07',
                'country_id' => 138,
            ],
            368 => 
            [
                'id' => 2369,
                'name' => 'Hodh El Gharbi',
                'code' => '02',
                'country_id' => 138,
            ],
            369 => 
            [
                'id' => 2370,
                'name' => 'Tiris Zemmour',
                'code' => '11',
                'country_id' => 138,
            ],
            370 => 
            [
                'id' => 2371,
                'name' => 'Inchiri',
                'code' => '12',
                'country_id' => 138,
            ],
            371 => 
            [
                'id' => 2372,
                'name' => 'Trarza',
                'code' => '06',
                'country_id' => 138,
            ],
            372 => 
            [
                'id' => 2373,
                'name' => 'Dakhlet Nouadhibou',
                'code' => '08',
                'country_id' => 138,
            ],
            373 => 
            [
                'id' => 2374,
                'name' => '',
                'code' => '00',
                'country_id' => 138,
            ],
            374 => 
            [
                'id' => 2375,
                'name' => 'Tagant',
                'code' => '09',
                'country_id' => 138,
            ],
            375 => 
            [
                'id' => 2376,
                'name' => 'Saint Anthony',
                'code' => '01',
                'country_id' => 139,
            ],
            376 => 
            [
                'id' => 2377,
                'name' => 'Saint Peter',
                'code' => '03',
                'country_id' => 139,
            ],
            377 => 
            [
                'id' => 2378,
                'name' => 'Saint Georges',
                'code' => '02',
                'country_id' => 139,
            ],
            378 => 
            [
                'id' => 2379,
                'name' => '',
                'code' => '00',
                'country_id' => 140,
            ],
            379 => 
            [
                'id' => 2380,
                'name' => 'Port Louis',
                'code' => '18',
                'country_id' => 141,
            ],
            380 => 
            [
                'id' => 2381,
                'name' => 'Black River',
                'code' => '12',
                'country_id' => 141,
            ],
            381 => 
            [
                'id' => 2382,
                'name' => 'Moka',
                'code' => '15',
                'country_id' => 141,
            ],
            382 => 
            [
                'id' => 2383,
                'name' => 'Riviere du Rempart',
                'code' => '19',
                'country_id' => 141,
            ],
            383 => 
            [
                'id' => 2384,
                'name' => 'Pamplemousses',
                'code' => '16',
                'country_id' => 141,
            ],
            384 => 
            [
                'id' => 2385,
                'name' => 'Rodrigues',
                'code' => '23',
                'country_id' => 141,
            ],
            385 => 
            [
                'id' => 2386,
                'name' => 'Grand Port',
                'code' => '14',
                'country_id' => 141,
            ],
            386 => 
            [
                'id' => 2387,
                'name' => 'Flacq',
                'code' => '13',
                'country_id' => 141,
            ],
            387 => 
            [
                'id' => 2388,
                'name' => 'Plaines Wilhems',
                'code' => '17',
                'country_id' => 141,
            ],
            388 => 
            [
                'id' => 2389,
                'name' => 'Savanne',
                'code' => '20',
                'country_id' => 141,
            ],
            389 => 
            [
                'id' => 2390,
                'name' => '',
                'code' => '00',
                'country_id' => 141,
            ],
            390 => 
            [
                'id' => 2391,
                'name' => '',
                'code' => '26',
                'country_id' => 142,
            ],
            391 => 
            [
                'id' => 2392,
                'name' => 'Seenu',
                'code' => '01',
                'country_id' => 142,
            ],
            392 => 
            [
                'id' => 2393,
                'name' => 'Maale',
                'code' => '40',
                'country_id' => 142,
            ],
            393 => 
            [
                'id' => 2394,
                'name' => 'Nkhotakota',
                'code' => '18',
                'country_id' => 143,
            ],
            394 => 
            [
                'id' => 2395,
                'name' => 'Rumphi',
                'code' => '21',
                'country_id' => 143,
            ],
            395 => 
            [
                'id' => 2396,
                'name' => 'Mzimba',
                'code' => '15',
                'country_id' => 143,
            ],
            396 => 
            [
                'id' => 2397,
                'name' => 'Lilongwe',
                'code' => '11',
                'country_id' => 143,
            ],
            397 => 
            [
                'id' => 2398,
                'name' => 'Ntchisi',
                'code' => '20',
                'country_id' => 143,
            ],
            398 => 
            [
                'id' => 2399,
                'name' => 'Salima',
                'code' => '22',
                'country_id' => 143,
            ],
            399 => 
            [
                'id' => 2400,
                'name' => 'Mchinji',
                'code' => '13',
                'country_id' => 143,
            ],
            400 => 
            [
                'id' => 2401,
                'name' => 'Chitipa',
                'code' => '04',
                'country_id' => 143,
            ],
            401 => 
            [
                'id' => 2402,
                'name' => 'Ntcheu',
                'code' => '16',
                'country_id' => 143,
            ],
            402 => 
            [
                'id' => 2403,
                'name' => 'Dowa',
                'code' => '07',
                'country_id' => 143,
            ],
            403 => 
            [
                'id' => 2404,
                'name' => 'Kasungu',
                'code' => '09',
                'country_id' => 143,
            ],
            404 => 
            [
                'id' => 2405,
                'name' => 'Zomba',
                'code' => '23',
                'country_id' => 143,
            ],
            405 => 
            [
                'id' => 2406,
                'name' => 'Nsanje',
                'code' => '19',
                'country_id' => 143,
            ],
            406 => 
            [
                'id' => 2407,
                'name' => 'Chikwawa',
                'code' => '02',
                'country_id' => 143,
            ],
            407 => 
            [
                'id' => 2408,
                'name' => 'Thyolo',
                'code' => '05',
                'country_id' => 143,
            ],
            408 => 
            [
                'id' => 2409,
                'name' => 'Dedza',
                'code' => '06',
                'country_id' => 143,
            ],
            409 => 
            [
                'id' => 2410,
                'name' => 'Balaka',
                'code' => '26',
                'country_id' => 143,
            ],
            410 => 
            [
                'id' => 2411,
                'name' => 'Mangochi',
                'code' => '12',
                'country_id' => 143,
            ],
            411 => 
            [
                'id' => 2412,
                'name' => 'Machinga',
                'code' => '28',
                'country_id' => 143,
            ],
            412 => 
            [
                'id' => 2413,
                'name' => 'Nkhata Bay',
                'code' => '17',
                'country_id' => 143,
            ],
            413 => 
            [
                'id' => 2414,
                'name' => 'Chiradzulu',
                'code' => '03',
                'country_id' => 143,
            ],
            414 => 
            [
                'id' => 2415,
                'name' => 'Blantyre',
                'code' => '24',
                'country_id' => 143,
            ],
            415 => 
            [
                'id' => 2416,
                'name' => 'Karonga',
                'code' => '08',
                'country_id' => 143,
            ],
            416 => 
            [
                'id' => 2417,
                'name' => 'Phalombe',
                'code' => '30',
                'country_id' => 143,
            ],
            417 => 
            [
                'id' => 2418,
                'name' => 'Mwanza',
                'code' => '25',
                'country_id' => 143,
            ],
            418 => 
            [
                'id' => 2419,
                'name' => 'Mulanje',
                'code' => '29',
                'country_id' => 143,
            ],
            419 => 
            [
                'id' => 2420,
                'name' => 'Michoacan de Ocampo',
                'code' => '16',
                'country_id' => 144,
            ],
            420 => 
            [
                'id' => 2421,
                'name' => 'Chihuahua',
                'code' => '06',
                'country_id' => 144,
            ],
            421 => 
            [
                'id' => 2422,
                'name' => 'Veracruz-Llave',
                'code' => '30',
                'country_id' => 144,
            ],
            422 => 
            [
                'id' => 2423,
                'name' => 'Yucatan',
                'code' => '31',
                'country_id' => 144,
            ],
            423 => 
            [
                'id' => 2424,
                'name' => 'Quintana Roo',
                'code' => '23',
                'country_id' => 144,
            ],
            424 => 
            [
                'id' => 2425,
                'name' => 'Sonora',
                'code' => '26',
                'country_id' => 144,
            ],
            425 => 
            [
                'id' => 2426,
                'name' => 'Tlaxcala',
                'code' => '29',
                'country_id' => 144,
            ],
            426 => 
            [
                'id' => 2427,
                'name' => 'Chiapas',
                'code' => '05',
                'country_id' => 144,
            ],
            427 => 
            [
                'id' => 2428,
                'name' => 'Coahuila de Zaragoza',
                'code' => '07',
                'country_id' => 144,
            ],
            428 => 
            [
                'id' => 2429,
                'name' => 'Durango',
                'code' => '10',
                'country_id' => 144,
            ],
            429 => 
            [
                'id' => 2430,
                'name' => 'Guanajuato',
                'code' => '11',
                'country_id' => 144,
            ],
            430 => 
            [
                'id' => 2431,
                'name' => 'Nuevo Leon',
                'code' => '19',
                'country_id' => 144,
            ],
            431 => 
            [
                'id' => 2432,
                'name' => 'Oaxaca',
                'code' => '20',
                'country_id' => 144,
            ],
            432 => 
            [
                'id' => 2433,
                'name' => 'Tabasco',
                'code' => '27',
                'country_id' => 144,
            ],
            433 => 
            [
                'id' => 2434,
                'name' => 'Tamaulipas',
                'code' => '28',
                'country_id' => 144,
            ],
            434 => 
            [
                'id' => 2435,
                'name' => 'Guerrero',
                'code' => '12',
                'country_id' => 144,
            ],
            435 => 
            [
                'id' => 2436,
                'name' => 'Baja California',
                'code' => '02',
                'country_id' => 144,
            ],
            436 => 
            [
                'id' => 2437,
                'name' => 'Campeche',
                'code' => '04',
                'country_id' => 144,
            ],
            437 => 
            [
                'id' => 2438,
                'name' => 'Nayarit',
                'code' => '18',
                'country_id' => 144,
            ],
            438 => 
            [
                'id' => 2439,
                'name' => 'Puebla',
                'code' => '21',
                'country_id' => 144,
            ],
            439 => 
            [
                'id' => 2440,
                'name' => 'Sinaloa',
                'code' => '25',
                'country_id' => 144,
            ],
            440 => 
            [
                'id' => 2441,
                'name' => 'Aguascalientes',
                'code' => '01',
                'country_id' => 144,
            ],
            441 => 
            [
                'id' => 2442,
                'name' => 'San Luis Potosi',
                'code' => '24',
                'country_id' => 144,
            ],
            442 => 
            [
                'id' => 2443,
                'name' => 'Zacatecas',
                'code' => '32',
                'country_id' => 144,
            ],
            443 => 
            [
                'id' => 2444,
                'name' => 'Mexico',
                'code' => '15',
                'country_id' => 144,
            ],
            444 => 
            [
                'id' => 2445,
                'name' => 'Jalisco',
                'code' => '14',
                'country_id' => 144,
            ],
            445 => 
            [
                'id' => 2446,
                'name' => 'Hidalgo',
                'code' => '13',
                'country_id' => 144,
            ],
            446 => 
            [
                'id' => 2447,
                'name' => 'Morelos',
                'code' => '17',
                'country_id' => 144,
            ],
            447 => 
            [
                'id' => 2448,
                'name' => 'Colima',
                'code' => '08',
                'country_id' => 144,
            ],
            448 => 
            [
                'id' => 2449,
                'name' => 'Queretaro de Arteaga',
                'code' => '22',
                'country_id' => 144,
            ],
            449 => 
            [
                'id' => 2450,
                'name' => 'Baja California Sur',
                'code' => '03',
                'country_id' => 144,
            ],
            450 => 
            [
                'id' => 2451,
                'name' => 'Distrito Federal',
                'code' => '09',
                'country_id' => 144,
            ],
            451 => 
            [
                'id' => 2452,
                'name' => 'Sarawak',
                'code' => '11',
                'country_id' => 145,
            ],
            452 => 
            [
                'id' => 2453,
                'name' => 'Sabah',
                'code' => '16',
                'country_id' => 145,
            ],
            453 => 
            [
                'id' => 2454,
                'name' => 'Melaka',
                'code' => '04',
                'country_id' => 145,
            ],
            454 => 
            [
                'id' => 2455,
                'name' => 'Perlis',
                'code' => '08',
                'country_id' => 145,
            ],
            455 => 
            [
                'id' => 2456,
                'name' => 'Negeri Sembilan',
                'code' => '05',
                'country_id' => 145,
            ],
            456 => 
            [
                'id' => 2457,
                'name' => 'Kedah',
                'code' => '02',
                'country_id' => 145,
            ],
            457 => 
            [
                'id' => 2458,
                'name' => 'Johor',
                'code' => '01',
                'country_id' => 145,
            ],
            458 => 
            [
                'id' => 2459,
                'name' => 'Perak',
                'code' => '07',
                'country_id' => 145,
            ],
            459 => 
            [
                'id' => 2460,
                'name' => 'Pulau Pinang',
                'code' => '09',
                'country_id' => 145,
            ],
            460 => 
            [
                'id' => 2461,
                'name' => 'Terengganu',
                'code' => '13',
                'country_id' => 145,
            ],
            461 => 
            [
                'id' => 2462,
                'name' => 'Kelantan',
                'code' => '03',
                'country_id' => 145,
            ],
            462 => 
            [
                'id' => 2463,
                'name' => 'Pahang',
                'code' => '06',
                'country_id' => 145,
            ],
            463 => 
            [
                'id' => 2464,
                'name' => 'Kuala Lumpur',
                'code' => '14',
                'country_id' => 145,
            ],
            464 => 
            [
                'id' => 2465,
                'name' => 'Selangor',
                'code' => '12',
                'country_id' => 145,
            ],
            465 => 
            [
                'id' => 2466,
                'name' => 'Labuan',
                'code' => '15',
                'country_id' => 145,
            ],
            466 => 
            [
                'id' => 2467,
                'name' => 'Maputo',
                'code' => '04',
                'country_id' => 146,
            ],
            467 => 
            [
                'id' => 2468,
                'name' => 'Nampula',
                'code' => '06',
                'country_id' => 146,
            ],
            468 => 
            [
                'id' => 2469,
                'name' => 'Zambezia',
                'code' => '09',
                'country_id' => 146,
            ],
            469 => 
            [
                'id' => 2470,
                'name' => 'Niassa',
                'code' => '07',
                'country_id' => 146,
            ],
            470 => 
            [
                'id' => 2471,
                'name' => 'Cabo Delgado',
                'code' => '01',
                'country_id' => 146,
            ],
            471 => 
            [
                'id' => 2472,
                'name' => 'Gaza',
                'code' => '02',
                'country_id' => 146,
            ],
            472 => 
            [
                'id' => 2473,
                'name' => 'Inhambane',
                'code' => '03',
                'country_id' => 146,
            ],
            473 => 
            [
                'id' => 2474,
                'name' => 'Manica',
                'code' => '10',
                'country_id' => 146,
            ],
            474 => 
            [
                'id' => 2475,
                'name' => 'Tete',
                'code' => '08',
                'country_id' => 146,
            ],
            475 => 
            [
                'id' => 2476,
                'name' => 'Sofala',
                'code' => '05',
                'country_id' => 146,
            ],
            476 => 
            [
                'id' => 2477,
                'name' => '',
                'code' => '00',
                'country_id' => 146,
            ],
            477 => 
            [
                'id' => 2478,
                'name' => 'Hardap',
                'code' => '30',
                'country_id' => 147,
            ],
            478 => 
            [
                'id' => 2479,
                'name' => 'Otjozondjupa',
                'code' => '39',
                'country_id' => 147,
            ],
            479 => 
            [
                'id' => 2480,
                'name' => '',
                'code' => '40',
                'country_id' => 147,
            ],
            480 => 
            [
                'id' => 2481,
                'name' => 'Karas',
                'code' => '31',
                'country_id' => 147,
            ],
            481 => 
            [
                'id' => 2482,
                'name' => 'Omusati',
                'code' => '36',
                'country_id' => 147,
            ],
            482 => 
            [
                'id' => 2483,
                'name' => 'Oshana',
                'code' => '37',
                'country_id' => 147,
            ],
            483 => 
            [
                'id' => 2484,
                'name' => 'Kunene',
                'code' => '32',
                'country_id' => 147,
            ],
            484 => 
            [
                'id' => 2485,
                'name' => 'Erongo',
                'code' => '29',
                'country_id' => 147,
            ],
            485 => 
            [
                'id' => 2486,
                'name' => 'Oshikoto',
                'code' => '38',
                'country_id' => 147,
            ],
            486 => 
            [
                'id' => 2487,
                'name' => 'Omaheke',
                'code' => '35',
                'country_id' => 147,
            ],
            487 => 
            [
                'id' => 2488,
                'name' => 'Caprivi',
                'code' => '28',
                'country_id' => 147,
            ],
            488 => 
            [
                'id' => 2489,
                'name' => 'Okavango',
                'code' => '34',
                'country_id' => 147,
            ],
            489 => 
            [
                'id' => 2490,
                'name' => 'Ohangwena',
                'code' => '33',
                'country_id' => 147,
            ],
            490 => 
            [
                'id' => 2491,
                'name' => 'Windhoek',
                'code' => '21',
                'country_id' => 147,
            ],
            491 => 
            [
                'id' => 2492,
                'name' => '',
                'code' => '00',
                'country_id' => 148,
            ],
            492 => 
            [
                'id' => 2493,
                'name' => 'Niamey',
                'code' => '05',
                'country_id' => 149,
            ],
            493 => 
            [
                'id' => 2494,
                'name' => 'Diffa',
                'code' => '02',
                'country_id' => 149,
            ],
            494 => 
            [
                'id' => 2495,
                'name' => '',
                'code' => '09',
                'country_id' => 149,
            ],
            495 => 
            [
                'id' => 2496,
                'name' => 'Tahoua',
                'code' => '06',
                'country_id' => 149,
            ],
            496 => 
            [
                'id' => 2497,
                'name' => 'Agadez',
                'code' => '01',
                'country_id' => 149,
            ],
            497 => 
            [
                'id' => 2498,
                'name' => 'Zinder',
                'code' => '07',
                'country_id' => 149,
            ],
            498 => 
            [
                'id' => 2499,
                'name' => 'Dosso',
                'code' => '03',
                'country_id' => 149,
            ],
            499 => 
            [
                'id' => 2500,
                'name' => 'Maradi',
                'code' => '04',
                'country_id' => 149,
            ],
        ]);
        \DB::table('regions')->insert([
            0 => 
            [
                'id' => 2501,
                'name' => 'Niamey',
                'code' => '08',
                'country_id' => 149,
            ],
            1 => 
            [
                'id' => 2502,
                'name' => '',
                'code' => '00',
                'country_id' => 150,
            ],
            2 => 
            [
                'id' => 2503,
                'name' => '',
                'code' => '10',
                'country_id' => 151,
            ],
            3 => 
            [
                'id' => 2504,
                'name' => 'Benue',
                'code' => '26',
                'country_id' => 151,
            ],
            4 => 
            [
                'id' => 2505,
                'name' => 'Nassarawa',
                'code' => '56',
                'country_id' => 151,
            ],
            5 => 
            [
                'id' => 2506,
                'name' => 'Kaduna',
                'code' => '23',
                'country_id' => 151,
            ],
            6 => 
            [
                'id' => 2507,
                'name' => 'Oyo',
                'code' => '32',
                'country_id' => 151,
            ],
            7 => 
            [
                'id' => 2508,
                'name' => 'Adamawa',
                'code' => '35',
                'country_id' => 151,
            ],
            8 => 
            [
                'id' => 2509,
                'name' => 'Osun',
                'code' => '42',
                'country_id' => 151,
            ],
            9 => 
            [
                'id' => 2510,
                'name' => 'Borno',
                'code' => '27',
                'country_id' => 151,
            ],
            10 => 
            [
                'id' => 2511,
                'name' => 'Bauchi',
                'code' => '46',
                'country_id' => 151,
            ],
            11 => 
            [
                'id' => 2512,
                'name' => '',
                'code' => '12',
                'country_id' => 151,
            ],
            12 => 
            [
                'id' => 2513,
                'name' => 'Ogun',
                'code' => '16',
                'country_id' => 151,
            ],
            13 => 
            [
                'id' => 2514,
                'name' => 'Anambra',
                'code' => '25',
                'country_id' => 151,
            ],
            14 => 
            [
                'id' => 2515,
                'name' => 'Yobe',
                'code' => '44',
                'country_id' => 151,
            ],
            15 => 
            [
                'id' => 2516,
                'name' => 'Lagos',
                'code' => '05',
                'country_id' => 151,
            ],
            16 => 
            [
                'id' => 2517,
                'name' => 'Delta',
                'code' => '36',
                'country_id' => 151,
            ],
            17 => 
            [
                'id' => 2518,
                'name' => 'Enugu',
                'code' => '47',
                'country_id' => 151,
            ],
            18 => 
            [
                'id' => 2519,
                'name' => 'Federal Capital Territory',
                'code' => '11',
                'country_id' => 151,
            ],
            19 => 
            [
                'id' => 2520,
                'name' => 'Kogi',
                'code' => '41',
                'country_id' => 151,
            ],
            20 => 
            [
                'id' => 2521,
                'name' => 'Taraba',
                'code' => '43',
                'country_id' => 151,
            ],
            21 => 
            [
                'id' => 2522,
                'name' => 'Akwa Ibom',
                'code' => '21',
                'country_id' => 151,
            ],
            22 => 
            [
                'id' => 2523,
                'name' => 'Ebonyi',
                'code' => '53',
                'country_id' => 151,
            ],
            23 => 
            [
                'id' => 2524,
                'name' => '',
                'code' => '18',
                'country_id' => 151,
            ],
            24 => 
            [
                'id' => 2525,
                'name' => 'Imo',
                'code' => '28',
                'country_id' => 151,
            ],
            25 => 
            [
                'id' => 2526,
                'name' => 'Jigawa',
                'code' => '39',
                'country_id' => 151,
            ],
            26 => 
            [
                'id' => 2527,
                'name' => '',
                'code' => '17',
                'country_id' => 151,
            ],
            27 => 
            [
                'id' => 2528,
                'name' => 'Kwara',
                'code' => '30',
                'country_id' => 151,
            ],
            28 => 
            [
                'id' => 2529,
                'name' => 'Abia',
                'code' => '45',
                'country_id' => 151,
            ],
            29 => 
            [
                'id' => 2530,
                'name' => 'Gombe',
                'code' => '55',
                'country_id' => 151,
            ],
            30 => 
            [
                'id' => 2531,
                'name' => 'Cross River',
                'code' => '22',
                'country_id' => 151,
            ],
            31 => 
            [
                'id' => 2532,
                'name' => 'Katsina',
                'code' => '24',
                'country_id' => 151,
            ],
            32 => 
            [
                'id' => 2533,
                'name' => 'Sokoto',
                'code' => '51',
                'country_id' => 151,
            ],
            33 => 
            [
                'id' => 2534,
                'name' => 'Niger',
                'code' => '31',
                'country_id' => 151,
            ],
            34 => 
            [
                'id' => 2535,
                'name' => 'Zamfara',
                'code' => '57',
                'country_id' => 151,
            ],
            35 => 
            [
                'id' => 2536,
                'name' => 'Edo',
                'code' => '37',
                'country_id' => 151,
            ],
            36 => 
            [
                'id' => 2537,
                'name' => '',
                'code' => '34',
                'country_id' => 151,
            ],
            37 => 
            [
                'id' => 2538,
                'name' => 'Kano',
                'code' => '29',
                'country_id' => 151,
            ],
            38 => 
            [
                'id' => 2539,
                'name' => 'Kebbi',
                'code' => '40',
                'country_id' => 151,
            ],
            39 => 
            [
                'id' => 2540,
                'name' => 'Ekiti',
                'code' => '54',
                'country_id' => 151,
            ],
            40 => 
            [
                'id' => 2541,
                'name' => 'Bayelsa',
                'code' => '52',
                'country_id' => 151,
            ],
            41 => 
            [
                'id' => 2542,
                'name' => 'Plateau',
                'code' => '49',
                'country_id' => 151,
            ],
            42 => 
            [
                'id' => 2543,
                'name' => 'Ondo',
                'code' => '48',
                'country_id' => 151,
            ],
            43 => 
            [
                'id' => 2544,
                'name' => 'Rivers',
                'code' => '50',
                'country_id' => 151,
            ],
            44 => 
            [
                'id' => 2545,
                'name' => '',
                'code' => '00',
                'country_id' => 151,
            ],
            45 => 
            [
                'id' => 2546,
                'name' => '',
                'code' => '20',
                'country_id' => 151,
            ],
            46 => 
            [
                'id' => 2547,
                'name' => 'Leon',
                'code' => '08',
                'country_id' => 152,
            ],
            47 => 
            [
                'id' => 2548,
                'name' => 'Chontales',
                'code' => '04',
                'country_id' => 152,
            ],
            48 => 
            [
                'id' => 2549,
                'name' => 'Managua',
                'code' => '10',
                'country_id' => 152,
            ],
            49 => 
            [
                'id' => 2550,
                'name' => 'Autonoma Atlantico Norte',
                'code' => '17',
                'country_id' => 152,
            ],
            50 => 
            [
                'id' => 2551,
                'name' => 'Granada',
                'code' => '06',
                'country_id' => 152,
            ],
            51 => 
            [
                'id' => 2552,
                'name' => 'Matagalpa',
                'code' => '12',
                'country_id' => 152,
            ],
            52 => 
            [
                'id' => 2553,
                'name' => 'Boaco',
                'code' => '01',
                'country_id' => 152,
            ],
            53 => 
            [
                'id' => 2554,
                'name' => 'Carazo',
                'code' => '02',
                'country_id' => 152,
            ],
            54 => 
            [
                'id' => 2555,
                'name' => 'Chinandega',
                'code' => '03',
                'country_id' => 152,
            ],
            55 => 
            [
                'id' => 2556,
                'name' => 'Rio San Juan',
                'code' => '14',
                'country_id' => 152,
            ],
            56 => 
            [
                'id' => 2557,
                'name' => 'Rivas',
                'code' => '15',
                'country_id' => 152,
            ],
            57 => 
            [
                'id' => 2558,
                'name' => 'Masaya',
                'code' => '11',
                'country_id' => 152,
            ],
            58 => 
            [
                'id' => 2559,
                'name' => 'Jinotega',
                'code' => '07',
                'country_id' => 152,
            ],
            59 => 
            [
                'id' => 2560,
                'name' => 'Nueva Segovia',
                'code' => '13',
                'country_id' => 152,
            ],
            60 => 
            [
                'id' => 2561,
                'name' => 'Region Autonoma Atlantico Sur',
                'code' => '18',
                'country_id' => 152,
            ],
            61 => 
            [
                'id' => 2562,
                'name' => 'Madriz',
                'code' => '09',
                'country_id' => 152,
            ],
            62 => 
            [
                'id' => 2563,
                'name' => 'Esteli',
                'code' => '05',
                'country_id' => 152,
            ],
            63 => 
            [
                'id' => 2564,
                'name' => 'Drenthe',
                'code' => '01',
                'country_id' => 153,
            ],
            64 => 
            [
                'id' => 2565,
                'name' => 'Zuid-Holland',
                'code' => '11',
                'country_id' => 153,
            ],
            65 => 
            [
                'id' => 2566,
                'name' => 'Overijssel',
                'code' => '15',
                'country_id' => 153,
            ],
            66 => 
            [
                'id' => 2567,
                'name' => 'Noord-Holland',
                'code' => '07',
                'country_id' => 153,
            ],
            67 => 
            [
                'id' => 2568,
                'name' => 'Zeeland',
                'code' => '10',
                'country_id' => 153,
            ],
            68 => 
            [
                'id' => 2569,
                'name' => 'Limburg',
                'code' => '05',
                'country_id' => 153,
            ],
            69 => 
            [
                'id' => 2570,
                'name' => 'Noord-Brabant',
                'code' => '06',
                'country_id' => 153,
            ],
            70 => 
            [
                'id' => 2571,
                'name' => 'Gelderland',
                'code' => '03',
                'country_id' => 153,
            ],
            71 => 
            [
                'id' => 2572,
                'name' => 'Friesland',
                'code' => '02',
                'country_id' => 153,
            ],
            72 => 
            [
                'id' => 2573,
                'name' => 'Groningen',
                'code' => '04',
                'country_id' => 153,
            ],
            73 => 
            [
                'id' => 2574,
                'name' => 'Utrecht',
                'code' => '09',
                'country_id' => 153,
            ],
            74 => 
            [
                'id' => 2575,
                'name' => 'Flevoland',
                'code' => '16',
                'country_id' => 153,
            ],
            75 => 
            [
                'id' => 2576,
                'name' => 'Nordland',
                'code' => '09',
                'country_id' => 154,
            ],
            76 => 
            [
                'id' => 2577,
                'name' => 'Sor-Trondelag',
                'code' => '16',
                'country_id' => 154,
            ],
            77 => 
            [
                'id' => 2578,
                'name' => 'Troms',
                'code' => '18',
                'country_id' => 154,
            ],
            78 => 
            [
                'id' => 2579,
                'name' => 'Vestfold',
                'code' => '20',
                'country_id' => 154,
            ],
            79 => 
            [
                'id' => 2580,
                'name' => 'Hedmark',
                'code' => '06',
                'country_id' => 154,
            ],
            80 => 
            [
                'id' => 2581,
                'name' => 'Hordaland',
                'code' => '07',
                'country_id' => 154,
            ],
            81 => 
            [
                'id' => 2582,
                'name' => 'Vest-Agder',
                'code' => '19',
                'country_id' => 154,
            ],
            82 => 
            [
                'id' => 2583,
                'name' => 'More og Romsdal',
                'code' => '08',
                'country_id' => 154,
            ],
            83 => 
            [
                'id' => 2584,
                'name' => 'Telemark',
                'code' => '17',
                'country_id' => 154,
            ],
            84 => 
            [
                'id' => 2585,
                'name' => 'Buskerud',
                'code' => '04',
                'country_id' => 154,
            ],
            85 => 
            [
                'id' => 2586,
                'name' => 'Rogaland',
                'code' => '14',
                'country_id' => 154,
            ],
            86 => 
            [
                'id' => 2587,
                'name' => 'Aust-Agder',
                'code' => '02',
                'country_id' => 154,
            ],
            87 => 
            [
                'id' => 2588,
                'name' => 'Oppland',
                'code' => '11',
                'country_id' => 154,
            ],
            88 => 
            [
                'id' => 2589,
                'name' => 'Sogn og Fjordane',
                'code' => '15',
                'country_id' => 154,
            ],
            89 => 
            [
                'id' => 2590,
                'name' => 'Akershus',
                'code' => '01',
                'country_id' => 154,
            ],
            90 => 
            [
                'id' => 2591,
                'name' => 'Nord-Trondelag',
                'code' => '10',
                'country_id' => 154,
            ],
            91 => 
            [
                'id' => 2592,
                'name' => 'Ostfold',
                'code' => '13',
                'country_id' => 154,
            ],
            92 => 
            [
                'id' => 2593,
                'name' => 'Finnmark',
                'code' => '05',
                'country_id' => 154,
            ],
            93 => 
            [
                'id' => 2594,
                'name' => 'Oslo',
                'code' => '12',
                'country_id' => 154,
            ],
            94 => 
            [
                'id' => 2595,
                'name' => '',
                'code' => '00',
                'country_id' => 155,
            ],
            95 => 
            [
                'id' => 2596,
                'name' => '',
                'code' => '00',
                'country_id' => 156,
            ],
            96 => 
            [
                'id' => 2597,
                'name' => '',
                'code' => '00',
                'country_id' => 157,
            ],
            97 => 
            [
                'id' => 2598,
                'name' => 'Wellington',
                'code' => 'G2',
                'country_id' => 158,
            ],
            98 => 
            [
                'id' => 2599,
                'name' => 'West Coast',
                'code' => 'G3',
                'country_id' => 158,
            ],
            99 => 
            [
                'id' => 2600,
                'name' => 'Canterbury',
                'code' => 'E9',
                'country_id' => 158,
            ],
            100 => 
            [
                'id' => 2601,
                'name' => 'Otago',
                'code' => 'F7',
                'country_id' => 158,
            ],
            101 => 
            [
                'id' => 2602,
                'name' => 'Auckland',
                'code' => 'E7',
                'country_id' => 158,
            ],
            102 => 
            [
                'id' => 2603,
                'name' => 'Gisborne',
                'code' => 'F1',
                'country_id' => 158,
            ],
            103 => 
            [
                'id' => 2604,
                'name' => 'Hawke\'s Bay',
                'code' => 'F2',
                'country_id' => 158,
            ],
            104 => 
            [
                'id' => 2605,
                'name' => 'Taranaki',
                'code' => 'F9',
                'country_id' => 158,
            ],
            105 => 
            [
                'id' => 2606,
                'name' => 'Marlborough',
                'code' => 'F4',
                'country_id' => 158,
            ],
            106 => 
            [
                'id' => 2607,
                'name' => 'Nelson',
                'code' => 'F5',
                'country_id' => 158,
            ],
            107 => 
            [
                'id' => 2608,
                'name' => 'Waikato',
                'code' => 'G1',
                'country_id' => 158,
            ],
            108 => 
            [
                'id' => 2609,
                'name' => 'Southland',
                'code' => 'F8',
                'country_id' => 158,
            ],
            109 => 
            [
                'id' => 2610,
                'name' => '',
                'code' => '85',
                'country_id' => 158,
            ],
            110 => 
            [
                'id' => 2611,
                'name' => 'Bay of Plenty',
                'code' => 'E8',
                'country_id' => 158,
            ],
            111 => 
            [
                'id' => 2612,
                'name' => '',
                'code' => '00',
                'country_id' => 158,
            ],
            112 => 
            [
                'id' => 2613,
                'name' => 'Manawatu-Wanganui',
                'code' => 'F3',
                'country_id' => 158,
            ],
            113 => 
            [
                'id' => 2614,
                'name' => 'Al Batinah',
                'code' => '02',
                'country_id' => 159,
            ],
            114 => 
            [
                'id' => 2615,
                'name' => 'Az Zahirah',
                'code' => '05',
                'country_id' => 159,
            ],
            115 => 
            [
                'id' => 2616,
                'name' => 'Ash Sharqiyah',
                'code' => '04',
                'country_id' => 159,
            ],
            116 => 
            [
                'id' => 2617,
                'name' => 'Masqat',
                'code' => '06',
                'country_id' => 159,
            ],
            117 => 
            [
                'id' => 2618,
                'name' => 'Musandam',
                'code' => '07',
                'country_id' => 159,
            ],
            118 => 
            [
                'id' => 2619,
                'name' => 'Zufar',
                'code' => '08',
                'country_id' => 159,
            ],
            119 => 
            [
                'id' => 2620,
                'name' => '',
                'code' => '00',
                'country_id' => 159,
            ],
            120 => 
            [
                'id' => 2621,
                'name' => 'Los Santos',
                'code' => '07',
                'country_id' => 160,
            ],
            121 => 
            [
                'id' => 2622,
                'name' => 'Darien',
                'code' => '05',
                'country_id' => 160,
            ],
            122 => 
            [
                'id' => 2623,
                'name' => 'Chiriqui',
                'code' => '02',
                'country_id' => 160,
            ],
            123 => 
            [
                'id' => 2624,
                'name' => 'Colon',
                'code' => '04',
                'country_id' => 160,
            ],
            124 => 
            [
                'id' => 2625,
                'name' => 'Veraguas',
                'code' => '10',
                'country_id' => 160,
            ],
            125 => 
            [
                'id' => 2626,
                'name' => 'San Blas',
                'code' => '09',
                'country_id' => 160,
            ],
            126 => 
            [
                'id' => 2627,
                'name' => 'Bocas del Toro',
                'code' => '01',
                'country_id' => 160,
            ],
            127 => 
            [
                'id' => 2628,
                'name' => 'Herrera',
                'code' => '06',
                'country_id' => 160,
            ],
            128 => 
            [
                'id' => 2629,
                'name' => 'Panama',
                'code' => '08',
                'country_id' => 160,
            ],
            129 => 
            [
                'id' => 2630,
                'name' => 'Cocle',
                'code' => '03',
                'country_id' => 160,
            ],
            130 => 
            [
                'id' => 2631,
                'name' => 'Ancash',
                'code' => '02',
                'country_id' => 161,
            ],
            131 => 
            [
                'id' => 2632,
                'name' => 'Apurimac',
                'code' => '03',
                'country_id' => 161,
            ],
            132 => 
            [
                'id' => 2633,
                'name' => 'Arequipa',
                'code' => '04',
                'country_id' => 161,
            ],
            133 => 
            [
                'id' => 2634,
                'name' => 'Ica',
                'code' => '11',
                'country_id' => 161,
            ],
            134 => 
            [
                'id' => 2635,
                'name' => 'Cusco',
                'code' => '08',
                'country_id' => 161,
            ],
            135 => 
            [
                'id' => 2636,
                'name' => 'Lambayeque',
                'code' => '14',
                'country_id' => 161,
            ],
            136 => 
            [
                'id' => 2637,
                'name' => 'Ucayali',
                'code' => '25',
                'country_id' => 161,
            ],
            137 => 
            [
                'id' => 2638,
                'name' => 'La Libertad',
                'code' => '13',
                'country_id' => 161,
            ],
            138 => 
            [
                'id' => 2639,
                'name' => 'Ayacucho',
                'code' => '05',
                'country_id' => 161,
            ],
            139 => 
            [
                'id' => 2640,
                'name' => 'Lima',
                'code' => '15',
                'country_id' => 161,
            ],
            140 => 
            [
                'id' => 2641,
                'name' => 'Puno',
                'code' => '21',
                'country_id' => 161,
            ],
            141 => 
            [
                'id' => 2642,
                'name' => 'Junin',
                'code' => '12',
                'country_id' => 161,
            ],
            142 => 
            [
                'id' => 2643,
                'name' => 'Tumbes',
                'code' => '24',
                'country_id' => 161,
            ],
            143 => 
            [
                'id' => 2644,
                'name' => 'Tacna',
                'code' => '23',
                'country_id' => 161,
            ],
            144 => 
            [
                'id' => 2645,
                'name' => 'Cajamarca',
                'code' => '06',
                'country_id' => 161,
            ],
            145 => 
            [
                'id' => 2646,
                'name' => 'Huancavelica',
                'code' => '09',
                'country_id' => 161,
            ],
            146 => 
            [
                'id' => 2647,
                'name' => 'Moquegua',
                'code' => '18',
                'country_id' => 161,
            ],
            147 => 
            [
                'id' => 2648,
                'name' => 'Amazonas',
                'code' => '01',
                'country_id' => 161,
            ],
            148 => 
            [
                'id' => 2649,
                'name' => 'Huanuco',
                'code' => '10',
                'country_id' => 161,
            ],
            149 => 
            [
                'id' => 2650,
                'name' => 'San Martin',
                'code' => '22',
                'country_id' => 161,
            ],
            150 => 
            [
                'id' => 2651,
                'name' => 'Piura',
                'code' => '20',
                'country_id' => 161,
            ],
            151 => 
            [
                'id' => 2652,
                'name' => 'Loreto',
                'code' => '16',
                'country_id' => 161,
            ],
            152 => 
            [
                'id' => 2653,
                'name' => 'Pasco',
                'code' => '19',
                'country_id' => 161,
            ],
            153 => 
            [
                'id' => 2654,
                'name' => 'Madre de Dios',
                'code' => '17',
                'country_id' => 161,
            ],
            154 => 
            [
                'id' => 2655,
                'name' => 'Callao',
                'code' => '07',
                'country_id' => 161,
            ],
            155 => 
            [
                'id' => 2656,
                'name' => '',
                'code' => '00',
                'country_id' => 162,
            ],
            156 => 
            [
                'id' => 2657,
                'name' => 'Eastern Highlands',
                'code' => '09',
                'country_id' => 163,
            ],
            157 => 
            [
                'id' => 2658,
                'name' => 'Madang',
                'code' => '12',
                'country_id' => 163,
            ],
            158 => 
            [
                'id' => 2659,
                'name' => 'Milne Bay',
                'code' => '03',
                'country_id' => 163,
            ],
            159 => 
            [
                'id' => 2660,
                'name' => 'Western',
                'code' => '06',
                'country_id' => 163,
            ],
            160 => 
            [
                'id' => 2661,
                'name' => 'Central',
                'code' => '01',
                'country_id' => 163,
            ],
            161 => 
            [
                'id' => 2662,
                'name' => 'Sandaun',
                'code' => '18',
                'country_id' => 163,
            ],
            162 => 
            [
                'id' => 2663,
                'name' => 'East Sepik',
                'code' => '11',
                'country_id' => 163,
            ],
            163 => 
            [
                'id' => 2664,
                'name' => 'West New Britain',
                'code' => '17',
                'country_id' => 163,
            ],
            164 => 
            [
                'id' => 2665,
                'name' => 'Southern Highlands',
                'code' => '05',
                'country_id' => 163,
            ],
            165 => 
            [
                'id' => 2666,
                'name' => 'Northern',
                'code' => '04',
                'country_id' => 163,
            ],
            166 => 
            [
                'id' => 2667,
                'name' => 'Gulf',
                'code' => '02',
                'country_id' => 163,
            ],
            167 => 
            [
                'id' => 2668,
                'name' => 'Western Highlands',
                'code' => '16',
                'country_id' => 163,
            ],
            168 => 
            [
                'id' => 2669,
                'name' => 'Morobe',
                'code' => '14',
                'country_id' => 163,
            ],
            169 => 
            [
                'id' => 2670,
                'name' => 'Chimbu',
                'code' => '08',
                'country_id' => 163,
            ],
            170 => 
            [
                'id' => 2671,
                'name' => 'East New Britain',
                'code' => '10',
                'country_id' => 163,
            ],
            171 => 
            [
                'id' => 2672,
                'name' => 'North Solomons',
                'code' => '07',
                'country_id' => 163,
            ],
            172 => 
            [
                'id' => 2673,
                'name' => 'Enga',
                'code' => '19',
                'country_id' => 163,
            ],
            173 => 
            [
                'id' => 2674,
                'name' => 'Manus',
                'code' => '13',
                'country_id' => 163,
            ],
            174 => 
            [
                'id' => 2675,
                'name' => 'New Ireland',
                'code' => '15',
                'country_id' => 163,
            ],
            175 => 
            [
                'id' => 2676,
                'name' => 'National Capital',
                'code' => '20',
                'country_id' => 163,
            ],
            176 => 
            [
                'id' => 2677,
                'name' => 'Pangasinan',
                'code' => '51',
                'country_id' => 164,
            ],
            177 => 
            [
                'id' => 2678,
                'name' => 'Cebu',
                'code' => '21',
                'country_id' => 164,
            ],
            178 => 
            [
                'id' => 2679,
                'name' => 'Samar',
                'code' => '55',
                'country_id' => 164,
            ],
            179 => 
            [
                'id' => 2680,
                'name' => 'Camarines Sur',
                'code' => '16',
                'country_id' => 164,
            ],
            180 => 
            [
                'id' => 2681,
                'name' => 'Iloilo',
                'code' => '30',
                'country_id' => 164,
            ],
            181 => 
            [
                'id' => 2682,
                'name' => 'Ilocos Norte',
                'code' => '28',
                'country_id' => 164,
            ],
            182 => 
            [
                'id' => 2683,
                'name' => 'Antique',
                'code' => '06',
                'country_id' => 164,
            ],
            183 => 
            [
                'id' => 2684,
                'name' => 'Bohol',
                'code' => '11',
                'country_id' => 164,
            ],
            184 => 
            [
                'id' => 2685,
                'name' => 'Cagayan',
                'code' => '14',
                'country_id' => 164,
            ],
            185 => 
            [
                'id' => 2686,
                'name' => 'Eastern Samar',
                'code' => '23',
                'country_id' => 164,
            ],
            186 => 
            [
                'id' => 2687,
                'name' => 'Davao',
                'code' => '24',
                'country_id' => 164,
            ],
            187 => 
            [
                'id' => 2688,
                'name' => 'Leyte',
                'code' => '37',
                'country_id' => 164,
            ],
            188 => 
            [
                'id' => 2689,
                'name' => 'Masbate',
                'code' => '39',
                'country_id' => 164,
            ],
            189 => 
            [
                'id' => 2690,
                'name' => 'Negros Occidental',
                'code' => '45',
                'country_id' => 164,
            ],
            190 => 
            [
                'id' => 2691,
                'name' => 'Nueva Vizcaya',
                'code' => '48',
                'country_id' => 164,
            ],
            191 => 
            [
                'id' => 2692,
                'name' => 'Romblon',
                'code' => '54',
                'country_id' => 164,
            ],
            192 => 
            [
                'id' => 2693,
                'name' => 'South Cotabato',
                'code' => '70',
                'country_id' => 164,
            ],
            193 => 
            [
                'id' => 2694,
                'name' => 'Ilocos Sur',
                'code' => '29',
                'country_id' => 164,
            ],
            194 => 
            [
                'id' => 2695,
                'name' => 'Quezon',
                'code' => 'H2',
                'country_id' => 164,
            ],
            195 => 
            [
                'id' => 2696,
                'name' => 'Lanao del Norte',
                'code' => '34',
                'country_id' => 164,
            ],
            196 => 
            [
                'id' => 2697,
                'name' => 'North Cotabato',
                'code' => '57',
                'country_id' => 164,
            ],
            197 => 
            [
                'id' => 2698,
                'name' => 'Surigao del Sur',
                'code' => '62',
                'country_id' => 164,
            ],
            198 => 
            [
                'id' => 2699,
                'name' => 'Iligan',
                'code' => 'C8',
                'country_id' => 164,
            ],
            199 => 
            [
                'id' => 2700,
                'name' => 'Southern Leyte',
                'code' => '59',
                'country_id' => 164,
            ],
            200 => 
            [
                'id' => 2701,
                'name' => 'Tarlac',
                'code' => '63',
                'country_id' => 164,
            ],
            201 => 
            [
                'id' => 2702,
                'name' => 'Bukidnon',
                'code' => '12',
                'country_id' => 164,
            ],
            202 => 
            [
                'id' => 2703,
                'name' => 'Mindoro Occidental',
                'code' => '40',
                'country_id' => 164,
            ],
            203 => 
            [
                'id' => 2704,
                'name' => 'Palawan',
                'code' => '49',
                'country_id' => 164,
            ],
            204 => 
            [
                'id' => 2705,
                'name' => 'Abra',
                'code' => '01',
                'country_id' => 164,
            ],
            205 => 
            [
                'id' => 2706,
                'name' => 'Bulacan',
                'code' => '13',
                'country_id' => 164,
            ],
            206 => 
            [
                'id' => 2707,
                'name' => 'Capiz',
                'code' => '18',
                'country_id' => 164,
            ],
            207 => 
            [
                'id' => 2708,
                'name' => 'Nueva Ecija',
                'code' => '47',
                'country_id' => 164,
            ],
            208 => 
            [
                'id' => 2709,
                'name' => 'Sorsogon',
                'code' => '58',
                'country_id' => 164,
            ],
            209 => 
            [
                'id' => 2710,
                'name' => 'Benguet',
                'code' => '10',
                'country_id' => 164,
            ],
            210 => 
            [
                'id' => 2711,
                'name' => 'Northern Samar',
                'code' => '67',
                'country_id' => 164,
            ],
            211 => 
            [
                'id' => 2712,
                'name' => 'Quirino',
                'code' => '68',
                'country_id' => 164,
            ],
            212 => 
            [
                'id' => 2713,
                'name' => 'Isabela',
                'code' => '31',
                'country_id' => 164,
            ],
            213 => 
            [
                'id' => 2714,
                'name' => 'Kalinga-Apayao',
                'code' => '32',
                'country_id' => 164,
            ],
            214 => 
            [
                'id' => 2715,
                'name' => 'Mountain',
                'code' => '44',
                'country_id' => 164,
            ],
            215 => 
            [
                'id' => 2716,
                'name' => 'Albay',
                'code' => '05',
                'country_id' => 164,
            ],
            216 => 
            [
                'id' => 2717,
                'name' => 'Batangas',
                'code' => '09',
                'country_id' => 164,
            ],
            217 => 
            [
                'id' => 2718,
                'name' => 'Catanduanes',
                'code' => '19',
                'country_id' => 164,
            ],
            218 => 
            [
                'id' => 2719,
                'name' => 'Negros Oriental',
                'code' => '46',
                'country_id' => 164,
            ],
            219 => 
            [
                'id' => 2720,
                'name' => 'Ifugao',
                'code' => '27',
                'country_id' => 164,
            ],
            220 => 
            [
                'id' => 2721,
                'name' => 'Misamis Oriental',
                'code' => '43',
                'country_id' => 164,
            ],
            221 => 
            [
                'id' => 2722,
                'name' => 'Laguna',
                'code' => '33',
                'country_id' => 164,
            ],
            222 => 
            [
                'id' => 2723,
                'name' => 'Zamboanga del Sur',
                'code' => '66',
                'country_id' => 164,
            ],
            223 => 
            [
                'id' => 2724,
                'name' => 'Camiguin',
                'code' => '17',
                'country_id' => 164,
            ],
            224 => 
            [
                'id' => 2725,
                'name' => 'Negros Occidental',
                'code' => 'H3',
                'country_id' => 164,
            ],
            225 => 
            [
                'id' => 2726,
                'name' => 'Bataan',
                'code' => '07',
                'country_id' => 164,
            ],
            226 => 
            [
                'id' => 2727,
                'name' => 'Lanao del Sur',
                'code' => '35',
                'country_id' => 164,
            ],
            227 => 
            [
                'id' => 2728,
                'name' => 'Basilan',
                'code' => '22',
                'country_id' => 164,
            ],
            228 => 
            [
                'id' => 2729,
                'name' => 'La Union',
                'code' => '36',
                'country_id' => 164,
            ],
            229 => 
            [
                'id' => 2730,
                'name' => 'Camarines Norte',
                'code' => '15',
                'country_id' => 164,
            ],
            230 => 
            [
                'id' => 2731,
                'name' => 'Caloocan',
                'code' => 'B4',
                'country_id' => 164,
            ],
            231 => 
            [
                'id' => 2732,
                'name' => 'Legaspi',
                'code' => 'D5',
                'country_id' => 164,
            ],
            232 => 
            [
                'id' => 2733,
                'name' => 'Calbayog',
                'code' => 'B3',
                'country_id' => 164,
            ],
            233 => 
            [
                'id' => 2734,
                'name' => 'Agusan del Norte',
                'code' => '02',
                'country_id' => 164,
            ],
            234 => 
            [
                'id' => 2735,
                'name' => 'Pampanga',
                'code' => '50',
                'country_id' => 164,
            ],
            235 => 
            [
                'id' => 2736,
                'name' => 'Mindoro Oriental',
                'code' => '41',
                'country_id' => 164,
            ],
            236 => 
            [
                'id' => 2737,
                'name' => '',
                'code' => 'K8',
                'country_id' => 164,
            ],
            237 => 
            [
                'id' => 2738,
                'name' => 'Sulu',
                'code' => '60',
                'country_id' => 164,
            ],
            238 => 
            [
                'id' => 2739,
                'name' => 'Cebu City',
                'code' => 'B7',
                'country_id' => 164,
            ],
            239 => 
            [
                'id' => 2740,
                'name' => 'Roxas',
                'code' => 'F3',
                'country_id' => 164,
            ],
            240 => 
            [
                'id' => 2741,
                'name' => 'Misamis Occidental',
                'code' => '42',
                'country_id' => 164,
            ],
            241 => 
            [
                'id' => 2742,
                'name' => 'Aklan',
                'code' => '04',
                'country_id' => 164,
            ],
            242 => 
            [
                'id' => 2743,
                'name' => 'Maguindanao',
                'code' => '56',
                'country_id' => 164,
            ],
            243 => 
            [
                'id' => 2744,
                'name' => 'Dumaguete',
                'code' => 'C5',
                'country_id' => 164,
            ],
            244 => 
            [
                'id' => 2745,
                'name' => 'Surigao del Norte',
                'code' => '61',
                'country_id' => 164,
            ],
            245 => 
            [
                'id' => 2746,
                'name' => 'Ormoc',
                'code' => 'E4',
                'country_id' => 164,
            ],
            246 => 
            [
                'id' => 2747,
                'name' => 'Davao del Sur',
                'code' => '25',
                'country_id' => 164,
            ],
            247 => 
            [
                'id' => 2748,
                'name' => 'Zambales',
                'code' => '64',
                'country_id' => 164,
            ],
            248 => 
            [
                'id' => 2749,
                'name' => 'Agusan del Sur',
                'code' => '03',
                'country_id' => 164,
            ],
            249 => 
            [
                'id' => 2750,
                'name' => '',
                'code' => 'K4',
                'country_id' => 164,
            ],
            250 => 
            [
                'id' => 2751,
                'name' => 'Lapu-Lapu',
                'code' => 'D4',
                'country_id' => 164,
            ],
            251 => 
            [
                'id' => 2752,
                'name' => 'Marinduque',
                'code' => '38',
                'country_id' => 164,
            ],
            252 => 
            [
                'id' => 2753,
                'name' => 'Rizal',
                'code' => '53',
                'country_id' => 164,
            ],
            253 => 
            [
                'id' => 2754,
                'name' => 'Butuan',
                'code' => 'A8',
                'country_id' => 164,
            ],
            254 => 
            [
                'id' => 2755,
                'name' => 'Cagayan de Oro',
                'code' => 'B2',
                'country_id' => 164,
            ],
            255 => 
            [
                'id' => 2756,
                'name' => 'Pasay',
                'code' => 'E9',
                'country_id' => 164,
            ],
            256 => 
            [
                'id' => 2757,
                'name' => 'Sultan Kudarat',
                'code' => '71',
                'country_id' => 164,
            ],
            257 => 
            [
                'id' => 2758,
                'name' => 'Davao City',
                'code' => 'C3',
                'country_id' => 164,
            ],
            258 => 
            [
                'id' => 2759,
                'name' => 'Cavite',
                'code' => '20',
                'country_id' => 164,
            ],
            259 => 
            [
                'id' => 2760,
                'name' => 'Iloilo City',
                'code' => 'C9',
                'country_id' => 164,
            ],
            260 => 
            [
                'id' => 2761,
                'name' => 'Silay',
                'code' => 'F8',
                'country_id' => 164,
            ],
            261 => 
            [
                'id' => 2762,
                'name' => 'Pagadian',
                'code' => 'E7',
                'country_id' => 164,
            ],
            262 => 
            [
                'id' => 2763,
                'name' => 'Trece Martires',
                'code' => 'G6',
                'country_id' => 164,
            ],
            263 => 
            [
                'id' => 2764,
                'name' => 'Quezon City',
                'code' => 'F2',
                'country_id' => 164,
            ],
            264 => 
            [
                'id' => 2765,
                'name' => 'Siquijor',
                'code' => '69',
                'country_id' => 164,
            ],
            265 => 
            [
                'id' => 2766,
                'name' => 'Cotabato',
                'code' => 'B8',
                'country_id' => 164,
            ],
            266 => 
            [
                'id' => 2767,
                'name' => 'Angeles',
                'code' => 'A1',
                'country_id' => 164,
            ],
            267 => 
            [
                'id' => 2768,
                'name' => 'Toledo',
                'code' => 'G5',
                'country_id' => 164,
            ],
            268 => 
            [
                'id' => 2769,
                'name' => 'San Carlos',
                'code' => 'F4',
                'country_id' => 164,
            ],
            269 => 
            [
                'id' => 2770,
                'name' => 'Lipa',
                'code' => 'D6',
                'country_id' => 164,
            ],
            270 => 
            [
                'id' => 2771,
                'name' => 'Davao Oriental',
                'code' => '26',
                'country_id' => 164,
            ],
            271 => 
            [
                'id' => 2772,
                'name' => 'Tacloban',
                'code' => 'G1',
                'country_id' => 164,
            ],
            272 => 
            [
                'id' => 2773,
                'name' => 'Tawitawi',
                'code' => '72',
                'country_id' => 164,
            ],
            273 => 
            [
                'id' => 2774,
                'name' => '',
                'code' => 'H5',
                'country_id' => 164,
            ],
            274 => 
            [
                'id' => 2775,
                'name' => 'Zamboanga del Norte',
                'code' => '65',
                'country_id' => 164,
            ],
            275 => 
            [
                'id' => 2776,
                'name' => 'Zamboanga',
                'code' => 'G7',
                'country_id' => 164,
            ],
            276 => 
            [
                'id' => 2777,
                'name' => 'Bacolod',
                'code' => 'A2',
                'country_id' => 164,
            ],
            277 => 
            [
                'id' => 2778,
                'name' => 'Marawi',
                'code' => 'E1',
                'country_id' => 164,
            ],
            278 => 
            [
                'id' => 2779,
                'name' => 'Aurora',
                'code' => 'G8',
                'country_id' => 164,
            ],
            279 => 
            [
                'id' => 2780,
                'name' => 'Ozamis',
                'code' => 'E6',
                'country_id' => 164,
            ],
            280 => 
            [
                'id' => 2781,
                'name' => 'Danao',
                'code' => 'C1',
                'country_id' => 164,
            ],
            281 => 
            [
                'id' => 2782,
                'name' => 'Bago',
                'code' => 'A3',
                'country_id' => 164,
            ],
            282 => 
            [
                'id' => 2783,
                'name' => 'Cabanatuan',
                'code' => 'A9',
                'country_id' => 164,
            ],
            283 => 
            [
                'id' => 2784,
                'name' => '',
                'code' => 'L8',
                'country_id' => 164,
            ],
            284 => 
            [
                'id' => 2785,
                'name' => 'Baguio',
                'code' => 'A4',
                'country_id' => 164,
            ],
            285 => 
            [
                'id' => 2786,
                'name' => 'Tangub',
                'code' => 'G4',
                'country_id' => 164,
            ],
            286 => 
            [
                'id' => 2787,
                'name' => 'Naga',
                'code' => 'E2',
                'country_id' => 164,
            ],
            287 => 
            [
                'id' => 2788,
                'name' => 'Olongapo',
                'code' => 'E3',
                'country_id' => 164,
            ],
            288 => 
            [
                'id' => 2789,
                'name' => 'San Pablo',
                'code' => 'F7',
                'country_id' => 164,
            ],
            289 => 
            [
                'id' => 2790,
                'name' => 'Oroquieta',
                'code' => 'E5',
                'country_id' => 164,
            ],
            290 => 
            [
                'id' => 2791,
                'name' => 'Manila',
                'code' => 'D9',
                'country_id' => 164,
            ],
            291 => 
            [
                'id' => 2792,
                'name' => 'San Juan',
                'code' => 'M6',
                'country_id' => 164,
            ],
            292 => 
            [
                'id' => 2793,
                'name' => 'General Santos',
                'code' => 'C6',
                'country_id' => 164,
            ],
            293 => 
            [
                'id' => 2794,
                'name' => 'Dapitan',
                'code' => 'C2',
                'country_id' => 164,
            ],
            294 => 
            [
                'id' => 2795,
                'name' => 'Canlaon',
                'code' => 'B5',
                'country_id' => 164,
            ],
            295 => 
            [
                'id' => 2796,
                'name' => 'Dagupan',
                'code' => 'B9',
                'country_id' => 164,
            ],
            296 => 
            [
                'id' => 2797,
                'name' => '',
                'code' => 'K9',
                'country_id' => 164,
            ],
            297 => 
            [
                'id' => 2798,
                'name' => 'Batanes',
                'code' => '08',
                'country_id' => 164,
            ],
            298 => 
            [
                'id' => 2799,
                'name' => 'Batangas City',
                'code' => 'A7',
                'country_id' => 164,
            ],
            299 => 
            [
                'id' => 2800,
                'name' => 'Dipolog',
                'code' => 'C4',
                'country_id' => 164,
            ],
            300 => 
            [
                'id' => 2801,
                'name' => '',
                'code' => 'N6',
                'country_id' => 164,
            ],
            301 => 
            [
                'id' => 2802,
                'name' => 'Tagbilaran',
                'code' => 'G3',
                'country_id' => 164,
            ],
            302 => 
            [
                'id' => 2803,
                'name' => 'Cadiz',
                'code' => 'B1',
                'country_id' => 164,
            ],
            303 => 
            [
                'id' => 2804,
                'name' => 'Mandaue',
                'code' => 'D8',
                'country_id' => 164,
            ],
            304 => 
            [
                'id' => 2805,
                'name' => 'Cavite City',
                'code' => 'B6',
                'country_id' => 164,
            ],
            305 => 
            [
                'id' => 2806,
                'name' => 'Tagaytay',
                'code' => 'G2',
                'country_id' => 164,
            ],
            306 => 
            [
                'id' => 2807,
                'name' => 'Gingoog',
                'code' => 'C7',
                'country_id' => 164,
            ],
            307 => 
            [
                'id' => 2808,
                'name' => 'Iriga',
                'code' => 'D1',
                'country_id' => 164,
            ],
            308 => 
            [
                'id' => 2809,
                'name' => 'Paranaque',
                'code' => 'L7',
                'country_id' => 164,
            ],
            309 => 
            [
                'id' => 2810,
                'name' => '',
                'code' => 'O7',
                'country_id' => 164,
            ],
            310 => 
            [
                'id' => 2811,
                'name' => 'La Carlota',
                'code' => 'D2',
                'country_id' => 164,
            ],
            311 => 
            [
                'id' => 2812,
                'name' => 'Laoag',
                'code' => 'D3',
                'country_id' => 164,
            ],
            312 => 
            [
                'id' => 2813,
                'name' => 'Lucena',
                'code' => 'D7',
                'country_id' => 164,
            ],
            313 => 
            [
                'id' => 2814,
                'name' => 'Malaybalay',
                'code' => 'K6',
                'country_id' => 164,
            ],
            314 => 
            [
                'id' => 2815,
                'name' => 'Palayan',
                'code' => 'E8',
                'country_id' => 164,
            ],
            315 => 
            [
                'id' => 2816,
                'name' => 'Puerto Princesa',
                'code' => 'F1',
                'country_id' => 164,
            ],
            316 => 
            [
                'id' => 2817,
                'name' => 'Surigao',
                'code' => 'F9',
                'country_id' => 164,
            ],
            317 => 
            [
                'id' => 2818,
                'name' => 'Punjab',
                'code' => '04',
                'country_id' => 165,
            ],
            318 => 
            [
                'id' => 2819,
                'name' => 'Sindh',
                'code' => '05',
                'country_id' => 165,
            ],
            319 => 
            [
                'id' => 2820,
                'name' => 'Balochistan',
                'code' => '02',
                'country_id' => 165,
            ],
            320 => 
            [
                'id' => 2821,
                'name' => 'North-West Frontier',
                'code' => '03',
                'country_id' => 165,
            ],
            321 => 
            [
                'id' => 2822,
                'name' => 'Northern Areas',
                'code' => '07',
                'country_id' => 165,
            ],
            322 => 
            [
                'id' => 2823,
                'name' => 'Federally Administered Tribal Areas',
                'code' => '01',
                'country_id' => 165,
            ],
            323 => 
            [
                'id' => 2824,
                'name' => 'Azad Kashmir',
                'code' => '06',
                'country_id' => 165,
            ],
            324 => 
            [
                'id' => 2825,
                'name' => 'Islamabad',
                'code' => '08',
                'country_id' => 165,
            ],
            325 => 
            [
                'id' => 2826,
                'name' => '',
                'code' => '47',
                'country_id' => 166,
            ],
            326 => 
            [
                'id' => 2827,
                'name' => '',
                'code' => '45',
                'country_id' => 166,
            ],
            327 => 
            [
                'id' => 2828,
                'name' => '',
                'code' => '70',
                'country_id' => 166,
            ],
            328 => 
            [
                'id' => 2829,
                'name' => '',
                'code' => '39',
                'country_id' => 166,
            ],
            329 => 
            [
                'id' => 2830,
                'name' => '',
                'code' => '27',
                'country_id' => 166,
            ],
            330 => 
            [
                'id' => 2831,
                'name' => '',
                'code' => '49',
                'country_id' => 166,
            ],
            331 => 
            [
                'id' => 2832,
                'name' => '',
                'code' => '61',
                'country_id' => 166,
            ],
            332 => 
            [
                'id' => 2833,
                'name' => 'Zachodniopomorskie',
                'code' => '87',
                'country_id' => 166,
            ],
            333 => 
            [
                'id' => 2834,
                'name' => '',
                'code' => '24',
                'country_id' => 166,
            ],
            334 => 
            [
                'id' => 2835,
                'name' => 'Swietokrzyskie',
                'code' => '84',
                'country_id' => 166,
            ],
            335 => 
            [
                'id' => 2836,
                'name' => '',
                'code' => '63',
                'country_id' => 166,
            ],
            336 => 
            [
                'id' => 2837,
                'name' => '',
                'code' => '36',
                'country_id' => 166,
            ],
            337 => 
            [
                'id' => 2838,
                'name' => '',
                'code' => '37',
                'country_id' => 166,
            ],
            338 => 
            [
                'id' => 2839,
                'name' => '',
                'code' => '64',
                'country_id' => 166,
            ],
            339 => 
            [
                'id' => 2840,
                'name' => '',
                'code' => '68',
                'country_id' => 166,
            ],
            340 => 
            [
                'id' => 2841,
                'name' => '',
                'code' => '25',
                'country_id' => 166,
            ],
            341 => 
            [
                'id' => 2842,
                'name' => '',
                'code' => '26',
                'country_id' => 166,
            ],
            342 => 
            [
                'id' => 2843,
                'name' => '',
                'code' => '29',
                'country_id' => 166,
            ],
            343 => 
            [
                'id' => 2844,
                'name' => '',
                'code' => '43',
                'country_id' => 166,
            ],
            344 => 
            [
                'id' => 2845,
                'name' => '',
                'code' => '51',
                'country_id' => 166,
            ],
            345 => 
            [
                'id' => 2846,
                'name' => '',
                'code' => '52',
                'country_id' => 166,
            ],
            346 => 
            [
                'id' => 2847,
                'name' => '',
                'code' => '55',
                'country_id' => 166,
            ],
            347 => 
            [
                'id' => 2848,
                'name' => '',
                'code' => '57',
                'country_id' => 166,
            ],
            348 => 
            [
                'id' => 2849,
                'name' => '',
                'code' => '58',
                'country_id' => 166,
            ],
            349 => 
            [
                'id' => 2850,
                'name' => '',
                'code' => '59',
                'country_id' => 166,
            ],
            350 => 
            [
                'id' => 2851,
                'name' => '',
                'code' => '67',
                'country_id' => 166,
            ],
            351 => 
            [
                'id' => 2852,
                'name' => '',
                'code' => '35',
                'country_id' => 166,
            ],
            352 => 
            [
                'id' => 2853,
                'name' => '',
                'code' => '54',
                'country_id' => 166,
            ],
            353 => 
            [
                'id' => 2854,
                'name' => '',
                'code' => '28',
                'country_id' => 166,
            ],
            354 => 
            [
                'id' => 2855,
                'name' => '',
                'code' => '71',
                'country_id' => 166,
            ],
            355 => 
            [
                'id' => 2856,
                'name' => '',
                'code' => '30',
                'country_id' => 166,
            ],
            356 => 
            [
                'id' => 2857,
                'name' => '',
                'code' => '50',
                'country_id' => 166,
            ],
            357 => 
            [
                'id' => 2858,
                'name' => '',
                'code' => '53',
                'country_id' => 166,
            ],
            358 => 
            [
                'id' => 2859,
                'name' => '',
                'code' => '48',
                'country_id' => 166,
            ],
            359 => 
            [
                'id' => 2860,
                'name' => '',
                'code' => '34',
                'country_id' => 166,
            ],
            360 => 
            [
                'id' => 2861,
                'name' => '',
                'code' => '56',
                'country_id' => 166,
            ],
            361 => 
            [
                'id' => 2862,
                'name' => '',
                'code' => '66',
                'country_id' => 166,
            ],
            362 => 
            [
                'id' => 2863,
                'name' => '',
                'code' => '23',
                'country_id' => 166,
            ],
            363 => 
            [
                'id' => 2864,
                'name' => 'Lodzkie',
                'code' => '74',
                'country_id' => 166,
            ],
            364 => 
            [
                'id' => 2865,
                'name' => '',
                'code' => '44',
                'country_id' => 166,
            ],
            365 => 
            [
                'id' => 2866,
                'name' => 'Warminsko-Mazurskie',
                'code' => '85',
                'country_id' => 166,
            ],
            366 => 
            [
                'id' => 2867,
                'name' => '',
                'code' => '32',
                'country_id' => 166,
            ],
            367 => 
            [
                'id' => 2868,
                'name' => '',
                'code' => '69',
                'country_id' => 166,
            ],
            368 => 
            [
                'id' => 2869,
                'name' => '',
                'code' => '31',
                'country_id' => 166,
            ],
            369 => 
            [
                'id' => 2870,
                'name' => '',
                'code' => '60',
                'country_id' => 166,
            ],
            370 => 
            [
                'id' => 2871,
                'name' => '',
                'code' => '33',
                'country_id' => 166,
            ],
            371 => 
            [
                'id' => 2872,
                'name' => 'Malopolskie',
                'code' => '77',
                'country_id' => 166,
            ],
            372 => 
            [
                'id' => 2873,
                'name' => '',
                'code' => '46',
                'country_id' => 166,
            ],
            373 => 
            [
                'id' => 2874,
                'name' => 'Mazowieckie',
                'code' => '78',
                'country_id' => 166,
            ],
            374 => 
            [
                'id' => 2875,
                'name' => '',
                'code' => '65',
                'country_id' => 166,
            ],
            375 => 
            [
                'id' => 2876,
                'name' => 'Podlaskie',
                'code' => '81',
                'country_id' => 166,
            ],
            376 => 
            [
                'id' => 2877,
                'name' => '',
                'code' => '40',
                'country_id' => 166,
            ],
            377 => 
            [
                'id' => 2878,
                'name' => '',
                'code' => '41',
                'country_id' => 166,
            ],
            378 => 
            [
                'id' => 2879,
                'name' => '',
                'code' => '42',
                'country_id' => 166,
            ],
            379 => 
            [
                'id' => 2880,
                'name' => 'Podkarpackie',
                'code' => '80',
                'country_id' => 166,
            ],
            380 => 
            [
                'id' => 2881,
                'name' => 'Lubuskie',
                'code' => '76',
                'country_id' => 166,
            ],
            381 => 
            [
                'id' => 2882,
                'name' => 'Dolnoslaskie',
                'code' => '72',
                'country_id' => 166,
            ],
            382 => 
            [
                'id' => 2883,
                'name' => 'Lubelskie',
                'code' => '75',
                'country_id' => 166,
            ],
            383 => 
            [
                'id' => 2884,
                'name' => 'Pomorskie',
                'code' => '82',
                'country_id' => 166,
            ],
            384 => 
            [
                'id' => 2885,
                'name' => 'Kujawsko-Pomorskie',
                'code' => '73',
                'country_id' => 166,
            ],
            385 => 
            [
                'id' => 2886,
                'name' => 'Wielkopolskie',
                'code' => '86',
                'country_id' => 166,
            ],
            386 => 
            [
                'id' => 2887,
                'name' => 'Slaskie',
                'code' => '83',
                'country_id' => 166,
            ],
            387 => 
            [
                'id' => 2888,
                'name' => 'Opolskie',
                'code' => '79',
                'country_id' => 166,
            ],
            388 => 
            [
                'id' => 2889,
                'name' => '',
                'code' => '38',
                'country_id' => 166,
            ],
            389 => 
            [
                'id' => 2890,
                'name' => '',
                'code' => '00',
                'country_id' => 167,
            ],
            390 => 
            [
                'id' => 2891,
                'name' => '',
                'code' => '00',
                'country_id' => 168,
            ],
            391 => 
            [
                'id' => 2892,
                'name' => '',
                'code' => '00',
                'country_id' => 169,
            ],
            392 => 
            [
                'id' => 2893,
                'name' => 'Braga',
                'code' => '04',
                'country_id' => 170,
            ],
            393 => 
            [
                'id' => 2894,
                'name' => 'Vila Real',
                'code' => '21',
                'country_id' => 170,
            ],
            394 => 
            [
                'id' => 2895,
                'name' => 'Santarem',
                'code' => '18',
                'country_id' => 170,
            ],
            395 => 
            [
                'id' => 2896,
                'name' => 'Leiria',
                'code' => '13',
                'country_id' => 170,
            ],
            396 => 
            [
                'id' => 2897,
                'name' => 'Lisboa',
                'code' => '14',
                'country_id' => 170,
            ],
            397 => 
            [
                'id' => 2898,
                'name' => 'Braganca',
                'code' => '05',
                'country_id' => 170,
            ],
            398 => 
            [
                'id' => 2899,
                'name' => 'Viana do Castelo',
                'code' => '20',
                'country_id' => 170,
            ],
            399 => 
            [
                'id' => 2900,
                'name' => 'Portalegre',
                'code' => '16',
                'country_id' => 170,
            ],
            400 => 
            [
                'id' => 2901,
                'name' => 'Setubal',
                'code' => '19',
                'country_id' => 170,
            ],
            401 => 
            [
                'id' => 2902,
                'name' => 'Azores',
                'code' => '23',
                'country_id' => 170,
            ],
            402 => 
            [
                'id' => 2903,
                'name' => 'Viseu',
                'code' => '22',
                'country_id' => 170,
            ],
            403 => 
            [
                'id' => 2904,
                'name' => 'Porto',
                'code' => '17',
                'country_id' => 170,
            ],
            404 => 
            [
                'id' => 2905,
                'name' => 'Aveiro',
                'code' => '02',
                'country_id' => 170,
            ],
            405 => 
            [
                'id' => 2906,
                'name' => 'Castelo Branco',
                'code' => '06',
                'country_id' => 170,
            ],
            406 => 
            [
                'id' => 2907,
                'name' => 'Faro',
                'code' => '09',
                'country_id' => 170,
            ],
            407 => 
            [
                'id' => 2908,
                'name' => 'Coimbra',
                'code' => '07',
                'country_id' => 170,
            ],
            408 => 
            [
                'id' => 2909,
                'name' => 'Madeira',
                'code' => '10',
                'country_id' => 170,
            ],
            409 => 
            [
                'id' => 2910,
                'name' => 'Beja',
                'code' => '03',
                'country_id' => 170,
            ],
            410 => 
            [
                'id' => 2911,
                'name' => 'Guarda',
                'code' => '11',
                'country_id' => 170,
            ],
            411 => 
            [
                'id' => 2912,
                'name' => 'Evora',
                'code' => '08',
                'country_id' => 170,
            ],
            412 => 
            [
                'id' => 2913,
                'name' => '',
                'code' => '00',
                'country_id' => 171,
            ],
            413 => 
            [
                'id' => 2914,
                'name' => 'Cordillera',
                'code' => '08',
                'country_id' => 172,
            ],
            414 => 
            [
                'id' => 2915,
                'name' => 'Alto Parana',
                'code' => '01',
                'country_id' => 172,
            ],
            415 => 
            [
                'id' => 2916,
                'name' => 'Caazapa',
                'code' => '05',
                'country_id' => 172,
            ],
            416 => 
            [
                'id' => 2917,
                'name' => 'Boqueron',
                'code' => '24',
                'country_id' => 172,
            ],
            417 => 
            [
                'id' => 2918,
                'name' => 'Paraguari',
                'code' => '15',
                'country_id' => 172,
            ],
            418 => 
            [
                'id' => 2919,
                'name' => 'Amambay',
                'code' => '02',
                'country_id' => 172,
            ],
            419 => 
            [
                'id' => 2920,
                'name' => 'Alto Paraguay',
                'code' => '23',
                'country_id' => 172,
            ],
            420 => 
            [
                'id' => 2921,
                'name' => 'Canindeyu',
                'code' => '19',
                'country_id' => 172,
            ],
            421 => 
            [
                'id' => 2922,
                'name' => 'Concepcion',
                'code' => '07',
                'country_id' => 172,
            ],
            422 => 
            [
                'id' => 2923,
                'name' => 'Misiones',
                'code' => '12',
                'country_id' => 172,
            ],
            423 => 
            [
                'id' => 2924,
                'name' => 'Caaguazu',
                'code' => '04',
                'country_id' => 172,
            ],
            424 => 
            [
                'id' => 2925,
                'name' => 'Neembucu',
                'code' => '13',
                'country_id' => 172,
            ],
            425 => 
            [
                'id' => 2926,
                'name' => 'Itapua',
                'code' => '11',
                'country_id' => 172,
            ],
            426 => 
            [
                'id' => 2927,
                'name' => 'Central',
                'code' => '06',
                'country_id' => 172,
            ],
            427 => 
            [
                'id' => 2928,
                'name' => 'San Pedro',
                'code' => '17',
                'country_id' => 172,
            ],
            428 => 
            [
                'id' => 2929,
                'name' => 'Presidente Hayes',
                'code' => '16',
                'country_id' => 172,
            ],
            429 => 
            [
                'id' => 2930,
                'name' => 'Guaira',
                'code' => '10',
                'country_id' => 172,
            ],
            430 => 
            [
                'id' => 2931,
                'name' => 'Madinat ach Shamal',
                'code' => '08',
                'country_id' => 173,
            ],
            431 => 
            [
                'id' => 2932,
                'name' => 'Ad Dawhah',
                'code' => '01',
                'country_id' => 173,
            ],
            432 => 
            [
                'id' => 2933,
                'name' => 'Umm Salal',
                'code' => '09',
                'country_id' => 173,
            ],
            433 => 
            [
                'id' => 2934,
                'name' => 'Al Khawr',
                'code' => '04',
                'country_id' => 173,
            ],
            434 => 
            [
                'id' => 2935,
                'name' => 'Al Jumaliyah',
                'code' => '03',
                'country_id' => 173,
            ],
            435 => 
            [
                'id' => 2936,
                'name' => 'Al Wakrah Municipality',
                'code' => '05',
                'country_id' => 173,
            ],
            436 => 
            [
                'id' => 2937,
                'name' => '',
                'code' => '00',
                'country_id' => 174,
            ],
            437 => 
            [
                'id' => 2938,
                'name' => 'Ilfov',
                'code' => '43',
                'country_id' => 175,
            ],
            438 => 
            [
                'id' => 2939,
                'name' => 'Giurgiu',
                'code' => '42',
                'country_id' => 175,
            ],
            439 => 
            [
                'id' => 2940,
                'name' => 'Bihor',
                'code' => '05',
                'country_id' => 175,
            ],
            440 => 
            [
                'id' => 2941,
                'name' => 'Caras-Severin',
                'code' => '12',
                'country_id' => 175,
            ],
            441 => 
            [
                'id' => 2942,
                'name' => 'Mehedinti',
                'code' => '26',
                'country_id' => 175,
            ],
            442 => 
            [
                'id' => 2943,
                'name' => 'Vaslui',
                'code' => '38',
                'country_id' => 175,
            ],
            443 => 
            [
                'id' => 2944,
                'name' => 'Tulcea',
                'code' => '37',
                'country_id' => 175,
            ],
            444 => 
            [
                'id' => 2945,
                'name' => 'Constanta',
                'code' => '14',
                'country_id' => 175,
            ],
            445 => 
            [
                'id' => 2946,
                'name' => 'Mures',
                'code' => '27',
                'country_id' => 175,
            ],
            446 => 
            [
                'id' => 2947,
                'name' => 'Harghita',
                'code' => '20',
                'country_id' => 175,
            ],
            447 => 
            [
                'id' => 2948,
                'name' => 'Alba',
                'code' => '01',
                'country_id' => 175,
            ],
            448 => 
            [
                'id' => 2949,
                'name' => 'Arad',
                'code' => '02',
                'country_id' => 175,
            ],
            449 => 
            [
                'id' => 2950,
                'name' => 'Hunedoara',
                'code' => '21',
                'country_id' => 175,
            ],
            450 => 
            [
                'id' => 2951,
                'name' => 'Satu Mare',
                'code' => '32',
                'country_id' => 175,
            ],
            451 => 
            [
                'id' => 2952,
                'name' => 'Sibiu',
                'code' => '33',
                'country_id' => 175,
            ],
            452 => 
            [
                'id' => 2953,
                'name' => 'Maramures',
                'code' => '25',
                'country_id' => 175,
            ],
            453 => 
            [
                'id' => 2954,
                'name' => 'Brasov',
                'code' => '09',
                'country_id' => 175,
            ],
            454 => 
            [
                'id' => 2955,
                'name' => 'Cluj',
                'code' => '13',
                'country_id' => 175,
            ],
            455 => 
            [
                'id' => 2956,
                'name' => 'Teleorman',
                'code' => '35',
                'country_id' => 175,
            ],
            456 => 
            [
                'id' => 2957,
                'name' => 'Dambovita',
                'code' => '16',
                'country_id' => 175,
            ],
            457 => 
            [
                'id' => 2958,
                'name' => 'Dolj',
                'code' => '17',
                'country_id' => 175,
            ],
            458 => 
            [
                'id' => 2959,
                'name' => 'Suceava',
                'code' => '34',
                'country_id' => 175,
            ],
            459 => 
            [
                'id' => 2960,
                'name' => 'Botosani',
                'code' => '07',
                'country_id' => 175,
            ],
            460 => 
            [
                'id' => 2961,
                'name' => 'Iasi',
                'code' => '23',
                'country_id' => 175,
            ],
            461 => 
            [
                'id' => 2962,
                'name' => 'Arges',
                'code' => '03',
                'country_id' => 175,
            ],
            462 => 
            [
                'id' => 2963,
                'name' => 'Buzau',
                'code' => '11',
                'country_id' => 175,
            ],
            463 => 
            [
                'id' => 2964,
                'name' => 'Timis',
                'code' => '36',
                'country_id' => 175,
            ],
            464 => 
            [
                'id' => 2965,
                'name' => 'Neamt',
                'code' => '28',
                'country_id' => 175,
            ],
            465 => 
            [
                'id' => 2966,
                'name' => 'Bacau',
                'code' => '04',
                'country_id' => 175,
            ],
            466 => 
            [
                'id' => 2967,
                'name' => 'Braila',
                'code' => '08',
                'country_id' => 175,
            ],
            467 => 
            [
                'id' => 2968,
                'name' => 'Salaj',
                'code' => '31',
                'country_id' => 175,
            ],
            468 => 
            [
                'id' => 2969,
                'name' => 'Covasna',
                'code' => '15',
                'country_id' => 175,
            ],
            469 => 
            [
                'id' => 2970,
                'name' => 'Bistrita-Nasaud',
                'code' => '06',
                'country_id' => 175,
            ],
            470 => 
            [
                'id' => 2971,
                'name' => 'Calarasi',
                'code' => '41',
                'country_id' => 175,
            ],
            471 => 
            [
                'id' => 2972,
                'name' => 'Gorj',
                'code' => '19',
                'country_id' => 175,
            ],
            472 => 
            [
                'id' => 2973,
                'name' => 'Ialomita',
                'code' => '22',
                'country_id' => 175,
            ],
            473 => 
            [
                'id' => 2974,
                'name' => 'Olt',
                'code' => '29',
                'country_id' => 175,
            ],
            474 => 
            [
                'id' => 2975,
                'name' => 'Valcea',
                'code' => '39',
                'country_id' => 175,
            ],
            475 => 
            [
                'id' => 2976,
                'name' => 'Prahova',
                'code' => '30',
                'country_id' => 175,
            ],
            476 => 
            [
                'id' => 2977,
                'name' => 'Vrancea',
                'code' => '40',
                'country_id' => 175,
            ],
            477 => 
            [
                'id' => 2978,
                'name' => 'Bucuresti',
                'code' => '10',
                'country_id' => 175,
            ],
            478 => 
            [
                'id' => 2979,
                'name' => 'Galati',
                'code' => '18',
                'country_id' => 175,
            ],
            479 => 
            [
                'id' => 2980,
                'name' => '"Vojvodina"',
                'code' => '02',
                'country_id' => 0,
            ],
            480 => 
            [
                'id' => 2981,
                'name' => '"Kosovo"',
                'code' => '01',
                'country_id' => 0,
            ],
            481 => 
            [
                'id' => 2982,
                'name' => '',
                'code' => '12',
                'country_id' => 0,
            ],
            482 => 
            [
                'id' => 2983,
                'name' => 'Moskva',
                'code' => '47',
                'country_id' => 176,
            ],
            483 => 
            [
                'id' => 2984,
                'name' => 'Karelia',
                'code' => '28',
                'country_id' => 176,
            ],
            484 => 
            [
                'id' => 2985,
                'name' => 'Sakha',
                'code' => '63',
                'country_id' => 176,
            ],
            485 => 
            [
                'id' => 2986,
                'name' => '',
                'code' => 'CI',
                'country_id' => 176,
            ],
            486 => 
            [
                'id' => 2987,
                'name' => 'Altaisky krai',
                'code' => '04',
                'country_id' => 176,
            ],
            487 => 
            [
                'id' => 2988,
                'name' => 'Ivanovo',
                'code' => '21',
                'country_id' => 176,
            ],
            488 => 
            [
                'id' => 2989,
                'name' => 'Kostroma',
                'code' => '37',
                'country_id' => 176,
            ],
            489 => 
            [
                'id' => 2990,
                'name' => 'Nizhegorod',
                'code' => '51',
                'country_id' => 176,
            ],
            490 => 
            [
                'id' => 2991,
                'name' => 'Tver\'',
                'code' => '77',
                'country_id' => 176,
            ],
            491 => 
            [
                'id' => 2992,
                'name' => 'Vladimir',
                'code' => '83',
                'country_id' => 176,
            ],
            492 => 
            [
                'id' => 2993,
                'name' => 'Perm\'',
                'code' => '58',
                'country_id' => 176,
            ],
            493 => 
            [
                'id' => 2994,
                'name' => 'Adygeya',
                'code' => '01',
                'country_id' => 176,
            ],
            494 => 
            [
                'id' => 2995,
                'name' => 'Chita',
                'code' => '14',
                'country_id' => 176,
            ],
            495 => 
            [
                'id' => 2996,
                'name' => 'Taymyr',
                'code' => '74',
                'country_id' => 176,
            ],
            496 => 
            [
                'id' => 2997,
                'name' => 'Kemerovo',
                'code' => '29',
                'country_id' => 176,
            ],
            497 => 
            [
                'id' => 2998,
                'name' => 'Udmurt',
                'code' => '80',
                'country_id' => 176,
            ],
            498 => 
            [
                'id' => 2999,
                'name' => 'Khakass',
                'code' => '31',
                'country_id' => 176,
            ],
            499 => 
            [
                'id' => 3000,
                'name' => 'Vologda',
                'code' => '85',
                'country_id' => 176,
            ],
        ]);
        \DB::table('regions')->insert([
            0 => 
            [
                'id' => 3001,
                'name' => 'Omsk',
                'code' => '54',
                'country_id' => 176,
            ],
            1 => 
            [
                'id' => 3002,
                'name' => 'Orenburg',
                'code' => '55',
                'country_id' => 176,
            ],
            2 => 
            [
                'id' => 3003,
                'name' => 'Irkutsk',
                'code' => '20',
                'country_id' => 176,
            ],
            3 => 
            [
                'id' => 3004,
                'name' => 'Krasnoyarsk',
                'code' => '39',
                'country_id' => 176,
            ],
            4 => 
            [
                'id' => 3005,
                'name' => 'Sverdlovsk',
                'code' => '71',
                'country_id' => 176,
            ],
            5 => 
            [
                'id' => 3006,
                'name' => 'Tambovskaya oblast',
                'code' => '72',
                'country_id' => 176,
            ],
            6 => 
            [
                'id' => 3007,
                'name' => 'Arkhangel\'sk',
                'code' => '06',
                'country_id' => 176,
            ],
            7 => 
            [
                'id' => 3008,
                'name' => 'Novosibirsk',
                'code' => '53',
                'country_id' => 176,
            ],
            8 => 
            [
                'id' => 3009,
                'name' => 'Ryazan\'',
                'code' => '62',
                'country_id' => 176,
            ],
            9 => 
            [
                'id' => 3010,
                'name' => 'Tula',
                'code' => '76',
                'country_id' => 176,
            ],
            10 => 
            [
                'id' => 3011,
                'name' => 'Rostov',
                'code' => '61',
                'country_id' => 176,
            ],
            11 => 
            [
                'id' => 3012,
                'name' => 'Yaroslavl\'',
                'code' => '88',
                'country_id' => 176,
            ],
            12 => 
            [
                'id' => 3013,
                'name' => 'Tatarstan',
                'code' => '73',
                'country_id' => 176,
            ],
            13 => 
            [
                'id' => 3014,
                'name' => 'Tyumen\'',
                'code' => '78',
                'country_id' => 176,
            ],
            14 => 
            [
                'id' => 3015,
                'name' => 'Penza',
                'code' => '57',
                'country_id' => 176,
            ],
            15 => 
            [
                'id' => 3016,
                'name' => 'Saratov',
                'code' => '67',
                'country_id' => 176,
            ],
            16 => 
            [
                'id' => 3017,
                'name' => 'Chuvashia',
                'code' => '16',
                'country_id' => 176,
            ],
            17 => 
            [
                'id' => 3018,
                'name' => 'Komi',
                'code' => '34',
                'country_id' => 176,
            ],
            18 => 
            [
                'id' => 3019,
                'name' => 'Bryansk',
                'code' => '10',
                'country_id' => 176,
            ],
            19 => 
            [
                'id' => 3020,
                'name' => 'Samara',
                'code' => '65',
                'country_id' => 176,
            ],
            20 => 
            [
                'id' => 3021,
                'name' => '',
                'code' => '82',
                'country_id' => 176,
            ],
            21 => 
            [
                'id' => 3022,
                'name' => 'Mariy-El',
                'code' => '45',
                'country_id' => 176,
            ],
            22 => 
            [
                'id' => 3023,
                'name' => 'Leningrad',
                'code' => '42',
                'country_id' => 176,
            ],
            23 => 
            [
                'id' => 3024,
                'name' => 'Kirov',
                'code' => '33',
                'country_id' => 176,
            ],
            24 => 
            [
                'id' => 3025,
                'name' => 'Gorno-Altay',
                'code' => '03',
                'country_id' => 176,
            ],
            25 => 
            [
                'id' => 3026,
                'name' => 'Dagestan',
                'code' => '17',
                'country_id' => 176,
            ],
            26 => 
            [
                'id' => 3027,
                'name' => 'Kabardin-Balkar',
                'code' => '22',
                'country_id' => 176,
            ],
            27 => 
            [
                'id' => 3028,
                'name' => 'Amur',
                'code' => '05',
                'country_id' => 176,
            ],
            28 => 
            [
                'id' => 3029,
                'name' => 'North Ossetia',
                'code' => '68',
                'country_id' => 176,
            ],
            29 => 
            [
                'id' => 3030,
                'name' => 'Karachay-Cherkess',
                'code' => '27',
                'country_id' => 176,
            ],
            30 => 
            [
                'id' => 3031,
                'name' => 'Krasnodar',
                'code' => '38',
                'country_id' => 176,
            ],
            31 => 
            [
                'id' => 3032,
                'name' => 'Lipetsk',
                'code' => '43',
                'country_id' => 176,
            ],
            32 => 
            [
                'id' => 3033,
                'name' => 'Smolensk',
                'code' => '69',
                'country_id' => 176,
            ],
            33 => 
            [
                'id' => 3034,
                'name' => 'Kaliningrad',
                'code' => '23',
                'country_id' => 176,
            ],
            34 => 
            [
                'id' => 3035,
                'name' => 'Bashkortostan',
                'code' => '08',
                'country_id' => 176,
            ],
            35 => 
            [
                'id' => 3036,
                'name' => 'Chelyabinsk',
                'code' => '13',
                'country_id' => 176,
            ],
            36 => 
            [
                'id' => 3037,
                'name' => 'Ul\'yanovsk',
                'code' => '81',
                'country_id' => 176,
            ],
            37 => 
            [
                'id' => 3038,
                'name' => 'Stavropol\'',
                'code' => '70',
                'country_id' => 176,
            ],
            38 => 
            [
                'id' => 3039,
                'name' => 'Kurgan',
                'code' => '40',
                'country_id' => 176,
            ],
            39 => 
            [
                'id' => 3040,
                'name' => 'Astrakhan\'',
                'code' => '07',
                'country_id' => 176,
            ],
            40 => 
            [
                'id' => 3041,
                'name' => 'Volgograd',
                'code' => '84',
                'country_id' => 176,
            ],
            41 => 
            [
                'id' => 3042,
                'name' => 'Kalmyk',
                'code' => '24',
                'country_id' => 176,
            ],
            42 => 
            [
                'id' => 3043,
                'name' => 'Kaluga',
                'code' => '25',
                'country_id' => 176,
            ],
            43 => 
            [
                'id' => 3044,
                'name' => 'Magadan',
                'code' => '44',
                'country_id' => 176,
            ],
            44 => 
            [
                'id' => 3045,
                'name' => 'Pskov',
                'code' => '60',
                'country_id' => 176,
            ],
            45 => 
            [
                'id' => 3046,
                'name' => 'Orel',
                'code' => '56',
                'country_id' => 176,
            ],
            46 => 
            [
                'id' => 3047,
                'name' => 'Primor\'ye',
                'code' => '59',
                'country_id' => 176,
            ],
            47 => 
            [
                'id' => 3048,
                'name' => 'Belgorod',
                'code' => '09',
                'country_id' => 176,
            ],
            48 => 
            [
                'id' => 3049,
                'name' => 'Buryat',
                'code' => '11',
                'country_id' => 176,
            ],
            49 => 
            [
                'id' => 3050,
                'name' => 'Tomsk',
                'code' => '75',
                'country_id' => 176,
            ],
            50 => 
            [
                'id' => 3051,
                'name' => 'Murmansk',
                'code' => '49',
                'country_id' => 176,
            ],
            51 => 
            [
                'id' => 3052,
                'name' => '',
                'code' => '35',
                'country_id' => 176,
            ],
            52 => 
            [
                'id' => 3053,
                'name' => 'Sakhalin',
                'code' => '64',
                'country_id' => 176,
            ],
            53 => 
            [
                'id' => 3054,
                'name' => 'Voronezh',
                'code' => '86',
                'country_id' => 176,
            ],
            54 => 
            [
                'id' => 3055,
                'name' => 'Novgorod',
                'code' => '52',
                'country_id' => 176,
            ],
            55 => 
            [
                'id' => 3056,
                'name' => 'Mordovia',
                'code' => '46',
                'country_id' => 176,
            ],
            56 => 
            [
                'id' => 3057,
                'name' => 'Kamchatka',
                'code' => '26',
                'country_id' => 176,
            ],
            57 => 
            [
                'id' => 3058,
                'name' => 'Khabarovsk',
                'code' => '30',
                'country_id' => 176,
            ],
            58 => 
            [
                'id' => 3059,
                'name' => 'Koryak',
                'code' => '36',
                'country_id' => 176,
            ],
            59 => 
            [
                'id' => 3060,
                'name' => 'Chukot',
                'code' => '15',
                'country_id' => 176,
            ],
            60 => 
            [
                'id' => 3061,
                'name' => 'Khanty-Mansiy',
                'code' => '32',
                'country_id' => 176,
            ],
            61 => 
            [
                'id' => 3062,
                'name' => 'Kursk',
                'code' => '41',
                'country_id' => 176,
            ],
            62 => 
            [
                'id' => 3063,
                'name' => 'Aginsky Buryatsky AO',
                'code' => '02',
                'country_id' => 176,
            ],
            63 => 
            [
                'id' => 3064,
                'name' => 'Tuva',
                'code' => '79',
                'country_id' => 176,
            ],
            64 => 
            [
                'id' => 3065,
                'name' => 'Nenets',
                'code' => '50',
                'country_id' => 176,
            ],
            65 => 
            [
                'id' => 3066,
                'name' => 'Evenk',
                'code' => '18',
                'country_id' => 176,
            ],
            66 => 
            [
                'id' => 3067,
                'name' => 'Yevrey',
                'code' => '89',
                'country_id' => 176,
            ],
            67 => 
            [
                'id' => 3068,
                'name' => '',
                'code' => 'JA',
                'country_id' => 176,
            ],
            68 => 
            [
                'id' => 3069,
                'name' => 'Yamal-Nenets',
                'code' => '87',
                'country_id' => 176,
            ],
            69 => 
            [
                'id' => 3070,
                'name' => 'Saint Petersburg City',
                'code' => '66',
                'country_id' => 176,
            ],
            70 => 
            [
                'id' => 3071,
                'name' => 'Moscow City',
                'code' => '48',
                'country_id' => 176,
            ],
            71 => 
            [
                'id' => 3072,
                'name' => 'Kigali',
                'code' => '09',
                'country_id' => 177,
            ],
            72 => 
            [
                'id' => 3073,
                'name' => 'Butare',
                'code' => '01',
                'country_id' => 177,
            ],
            73 => 
            [
                'id' => 3074,
                'name' => '',
                'code' => '03',
                'country_id' => 177,
            ],
            74 => 
            [
                'id' => 3075,
                'name' => '',
                'code' => '05',
                'country_id' => 177,
            ],
            75 => 
            [
                'id' => 3076,
                'name' => '',
                'code' => '02',
                'country_id' => 177,
            ],
            76 => 
            [
                'id' => 3077,
                'name' => 'Kibungo',
                'code' => '07',
                'country_id' => 177,
            ],
            77 => 
            [
                'id' => 3078,
                'name' => '',
                'code' => '10',
                'country_id' => 177,
            ],
            78 => 
            [
                'id' => 3079,
                'name' => '',
                'code' => '04',
                'country_id' => 177,
            ],
            79 => 
            [
                'id' => 3080,
                'name' => 'Gitarama',
                'code' => '06',
                'country_id' => 177,
            ],
            80 => 
            [
                'id' => 3081,
                'name' => '',
                'code' => '08',
                'country_id' => 177,
            ],
            81 => 
            [
                'id' => 3082,
                'name' => 'Makkah',
                'code' => '14',
                'country_id' => 178,
            ],
            82 => 
            [
                'id' => 3083,
                'name' => 'Ar Riyad',
                'code' => '10',
                'country_id' => 178,
            ],
            83 => 
            [
                'id' => 3084,
                'name' => 'Ha\'il',
                'code' => '13',
                'country_id' => 178,
            ],
            84 => 
            [
                'id' => 3085,
                'name' => 'Al Hudud ash Shamaliyah',
                'code' => '15',
                'country_id' => 178,
            ],
            85 => 
            [
                'id' => 3086,
                'name' => 'Jizan',
                'code' => '17',
                'country_id' => 178,
            ],
            86 => 
            [
                'id' => 3087,
                'name' => 'Ash Sharqiyah',
                'code' => '06',
                'country_id' => 178,
            ],
            87 => 
            [
                'id' => 3088,
                'name' => 'Al Madinah',
                'code' => '05',
                'country_id' => 178,
            ],
            88 => 
            [
                'id' => 3089,
                'name' => 'Al Qasim',
                'code' => '08',
                'country_id' => 178,
            ],
            89 => 
            [
                'id' => 3090,
                'name' => 'Al Bahah',
                'code' => '02',
                'country_id' => 178,
            ],
            90 => 
            [
                'id' => 3091,
                'name' => 'Tabuk',
                'code' => '19',
                'country_id' => 178,
            ],
            91 => 
            [
                'id' => 3092,
                'name' => 'Al Jawf',
                'code' => '20',
                'country_id' => 178,
            ],
            92 => 
            [
                'id' => 3093,
                'name' => '',
                'code' => '00',
                'country_id' => 178,
            ],
            93 => 
            [
                'id' => 3094,
                'name' => 'Makira',
                'code' => '08',
                'country_id' => 179,
            ],
            94 => 
            [
                'id' => 3095,
                'name' => '',
                'code' => '00',
                'country_id' => 179,
            ],
            95 => 
            [
                'id' => 3096,
                'name' => 'Beau Vallon',
                'code' => '08',
                'country_id' => 180,
            ],
            96 => 
            [
                'id' => 3097,
                'name' => '',
                'code' => '00',
                'country_id' => 180,
            ],
            97 => 
            [
                'id' => 3098,
                'name' => 'Bahr al Ghazal',
                'code' => '32',
                'country_id' => 181,
            ],
            98 => 
            [
                'id' => 3099,
                'name' => '',
                'code' => '26',
                'country_id' => 181,
            ],
            99 => 
            [
                'id' => 3100,
                'name' => 'River Nile',
                'code' => '53',
                'country_id' => 181,
            ],
            100 => 
            [
                'id' => 3101,
                'name' => 'Darfur',
                'code' => '33',
                'country_id' => 181,
            ],
            101 => 
            [
                'id' => 3102,
                'name' => 'Kurdufan',
                'code' => '34',
                'country_id' => 181,
            ],
            102 => 
            [
                'id' => 3103,
                'name' => 'Al Wusta',
                'code' => '27',
                'country_id' => 181,
            ],
            103 => 
            [
                'id' => 3104,
                'name' => 'Ash Shamaliyah',
                'code' => '30',
                'country_id' => 181,
            ],
            104 => 
            [
                'id' => 3105,
                'name' => 'Ash Sharqiyah',
                'code' => '31',
                'country_id' => 181,
            ],
            105 => 
            [
                'id' => 3106,
                'name' => 'Al Istiwa\'iyah',
                'code' => '28',
                'country_id' => 181,
            ],
            106 => 
            [
                'id' => 3107,
                'name' => '',
                'code' => '38',
                'country_id' => 181,
            ],
            107 => 
            [
                'id' => 3108,
                'name' => '',
                'code' => '37',
                'country_id' => 181,
            ],
            108 => 
            [
                'id' => 3109,
                'name' => '',
                'code' => '51',
                'country_id' => 181,
            ],
            109 => 
            [
                'id' => 3110,
                'name' => 'Al Khartum',
                'code' => '29',
                'country_id' => 181,
            ],
            110 => 
            [
                'id' => 3111,
                'name' => '',
                'code' => '42',
                'country_id' => 181,
            ],
            111 => 
            [
                'id' => 3112,
                'name' => '',
                'code' => '47',
                'country_id' => 181,
            ],
            112 => 
            [
                'id' => 3113,
                'name' => 'Northern Darfur',
                'code' => '55',
                'country_id' => 181,
            ],
            113 => 
            [
                'id' => 3114,
                'name' => '',
                'code' => '48',
                'country_id' => 181,
            ],
            114 => 
            [
                'id' => 3115,
                'name' => '',
                'code' => '56',
                'country_id' => 181,
            ],
            115 => 
            [
                'id' => 3116,
                'name' => '',
                'code' => '39',
                'country_id' => 181,
            ],
            116 => 
            [
                'id' => 3117,
                'name' => '',
                'code' => '36',
                'country_id' => 181,
            ],
            117 => 
            [
                'id' => 3118,
                'name' => '',
                'code' => '54',
                'country_id' => 181,
            ],
            118 => 
            [
                'id' => 3119,
                'name' => 'Central Equatoria State',
                'code' => '44',
                'country_id' => 181,
            ],
            119 => 
            [
                'id' => 3120,
                'name' => 'Al Wahadah State',
                'code' => '40',
                'country_id' => 181,
            ],
            120 => 
            [
                'id' => 3121,
                'name' => 'Kassala',
                'code' => '52',
                'country_id' => 181,
            ],
            121 => 
            [
                'id' => 3122,
                'name' => '',
                'code' => '43',
                'country_id' => 181,
            ],
            122 => 
            [
                'id' => 3123,
                'name' => '',
                'code' => '57',
                'country_id' => 181,
            ],
            123 => 
            [
                'id' => 3124,
                'name' => 'Southern Kordofan',
                'code' => '50',
                'country_id' => 181,
            ],
            124 => 
            [
                'id' => 3125,
                'name' => '',
                'code' => '',
                'country_id' => 181,
            ],
            125 => 
            [
                'id' => 3126,
                'name' => 'Upper Nile',
                'code' => '35',
                'country_id' => 181,
            ],
            126 => 
            [
                'id' => 3127,
                'name' => 'Southern Darfur',
                'code' => '49',
                'country_id' => 181,
            ],
            127 => 
            [
                'id' => 3128,
                'name' => '',
                'code' => '41',
                'country_id' => 181,
            ],
            128 => 
            [
                'id' => 3129,
                'name' => '',
                'code' => '58',
                'country_id' => 181,
            ],
            129 => 
            [
                'id' => 3130,
                'name' => '',
                'code' => '59',
                'country_id' => 181,
            ],
            130 => 
            [
                'id' => 3131,
                'name' => '',
                'code' => '46',
                'country_id' => 181,
            ],
            131 => 
            [
                'id' => 3132,
                'name' => '',
                'code' => '45',
                'country_id' => 181,
            ],
            132 => 
            [
                'id' => 3133,
                'name' => 'Vasternorrlands Lan',
                'code' => '24',
                'country_id' => 182,
            ],
            133 => 
            [
                'id' => 3134,
                'name' => 'Vastra Gotaland',
                'code' => '28',
                'country_id' => 182,
            ],
            134 => 
            [
                'id' => 3135,
                'name' => 'Norrbottens Lan',
                'code' => '14',
                'country_id' => 182,
            ],
            135 => 
            [
                'id' => 3136,
                'name' => 'Vasterbottens Lan',
                'code' => '23',
                'country_id' => 182,
            ],
            136 => 
            [
                'id' => 3137,
                'name' => 'Skane Lan',
                'code' => '27',
                'country_id' => 182,
            ],
            137 => 
            [
                'id' => 3138,
                'name' => 'Kalmar Lan',
                'code' => '09',
                'country_id' => 182,
            ],
            138 => 
            [
                'id' => 3139,
                'name' => 'Jamtlands Lan',
                'code' => '07',
                'country_id' => 182,
            ],
            139 => 
            [
                'id' => 3140,
                'name' => 'Kronobergs Lan',
                'code' => '12',
                'country_id' => 182,
            ],
            140 => 
            [
                'id' => 3141,
                'name' => 'Ostergotlands Lan',
                'code' => '16',
                'country_id' => 182,
            ],
            141 => 
            [
                'id' => 3142,
                'name' => 'Stockholms Lan',
                'code' => '26',
                'country_id' => 182,
            ],
            142 => 
            [
                'id' => 3143,
                'name' => 'Dalarnas Lan',
                'code' => '10',
                'country_id' => 182,
            ],
            143 => 
            [
                'id' => 3144,
                'name' => 'Blekinge Lan',
                'code' => '02',
                'country_id' => 182,
            ],
            144 => 
            [
                'id' => 3145,
                'name' => 'Gavleborgs Lan',
                'code' => '03',
                'country_id' => 182,
            ],
            145 => 
            [
                'id' => 3146,
                'name' => 'Sodermanlands Lan',
                'code' => '18',
                'country_id' => 182,
            ],
            146 => 
            [
                'id' => 3147,
                'name' => 'Vastmanlands Lan',
                'code' => '25',
                'country_id' => 182,
            ],
            147 => 
            [
                'id' => 3148,
                'name' => 'Varmlands Lan',
                'code' => '22',
                'country_id' => 182,
            ],
            148 => 
            [
                'id' => 3149,
                'name' => 'Hallands Lan',
                'code' => '06',
                'country_id' => 182,
            ],
            149 => 
            [
                'id' => 3150,
                'name' => 'Orebro Lan',
                'code' => '15',
                'country_id' => 182,
            ],
            150 => 
            [
                'id' => 3151,
                'name' => 'Uppsala Lan',
                'code' => '21',
                'country_id' => 182,
            ],
            151 => 
            [
                'id' => 3152,
                'name' => 'Jonkopings Lan',
                'code' => '08',
                'country_id' => 182,
            ],
            152 => 
            [
                'id' => 3153,
                'name' => 'Gotlands Lan',
                'code' => '05',
                'country_id' => 182,
            ],
            153 => 
            [
                'id' => 3154,
                'name' => '',
                'code' => '00',
                'country_id' => 183,
            ],
            154 => 
            [
                'id' => 3155,
                'name' => '',
                'code' => '00',
                'country_id' => 184,
            ],
            155 => 
            [
                'id' => 3156,
                'name' => 'Bohinj Commune',
                'code' => '04',
                'country_id' => 185,
            ],
            156 => 
            [
                'id' => 3157,
                'name' => 'Brezovica Commune',
                'code' => '09',
                'country_id' => 185,
            ],
            157 => 
            [
                'id' => 3158,
                'name' => '',
                'code' => '00',
                'country_id' => 185,
            ],
            158 => 
            [
                'id' => 3159,
                'name' => '',
                'code' => '00',
                'country_id' => 186,
            ],
            159 => 
            [
                'id' => 3160,
                'name' => 'Kosice',
                'code' => '03',
                'country_id' => 187,
            ],
            160 => 
            [
                'id' => 3161,
                'name' => 'Banska Bystrica',
                'code' => '01',
                'country_id' => 187,
            ],
            161 => 
            [
                'id' => 3162,
                'name' => 'Nitra',
                'code' => '04',
                'country_id' => 187,
            ],
            162 => 
            [
                'id' => 3163,
                'name' => 'Trnava',
                'code' => '07',
                'country_id' => 187,
            ],
            163 => 
            [
                'id' => 3164,
                'name' => 'Presov',
                'code' => '05',
                'country_id' => 187,
            ],
            164 => 
            [
                'id' => 3165,
                'name' => 'Zilina',
                'code' => '08',
                'country_id' => 187,
            ],
            165 => 
            [
                'id' => 3166,
                'name' => 'Bratislava',
                'code' => '02',
                'country_id' => 187,
            ],
            166 => 
            [
                'id' => 3167,
                'name' => 'Trencin',
                'code' => '06',
                'country_id' => 187,
            ],
            167 => 
            [
                'id' => 3168,
                'name' => '',
                'code' => '00',
                'country_id' => 187,
            ],
            168 => 
            [
                'id' => 3169,
                'name' => 'Western Area',
                'code' => '04',
                'country_id' => 188,
            ],
            169 => 
            [
                'id' => 3170,
                'name' => 'Northern',
                'code' => '02',
                'country_id' => 188,
            ],
            170 => 
            [
                'id' => 3171,
                'name' => 'Eastern',
                'code' => '01',
                'country_id' => 188,
            ],
            171 => 
            [
                'id' => 3172,
                'name' => 'Southern',
                'code' => '03',
                'country_id' => 188,
            ],
            172 => 
            [
                'id' => 3173,
                'name' => 'Acquaviva',
                'code' => '01',
                'country_id' => 189,
            ],
            173 => 
            [
                'id' => 3174,
                'name' => 'Chiesanuova',
                'code' => '02',
                'country_id' => 189,
            ],
            174 => 
            [
                'id' => 3175,
                'name' => 'San Marino',
                'code' => '07',
                'country_id' => 189,
            ],
            175 => 
            [
                'id' => 3176,
                'name' => 'Serravalle',
                'code' => '09',
                'country_id' => 189,
            ],
            176 => 
            [
                'id' => 3177,
                'name' => 'Dakar',
                'code' => '01',
                'country_id' => 190,
            ],
            177 => 
            [
                'id' => 3178,
                'name' => '',
                'code' => '04',
                'country_id' => 190,
            ],
            178 => 
            [
                'id' => 3179,
                'name' => 'Diourbel',
                'code' => '03',
                'country_id' => 190,
            ],
            179 => 
            [
                'id' => 3180,
                'name' => '',
                'code' => '08',
                'country_id' => 190,
            ],
            180 => 
            [
                'id' => 3181,
                'name' => 'Kolda',
                'code' => '11',
                'country_id' => 190,
            ],
            181 => 
            [
                'id' => 3182,
                'name' => 'Ziguinchor',
                'code' => '12',
                'country_id' => 190,
            ],
            182 => 
            [
                'id' => 3183,
                'name' => 'Thies',
                'code' => '07',
                'country_id' => 190,
            ],
            183 => 
            [
                'id' => 3184,
                'name' => 'Fatick',
                'code' => '09',
                'country_id' => 190,
            ],
            184 => 
            [
                'id' => 3185,
                'name' => 'Kaolack',
                'code' => '10',
                'country_id' => 190,
            ],
            185 => 
            [
                'id' => 3186,
                'name' => 'Tambacounda',
                'code' => '05',
                'country_id' => 190,
            ],
            186 => 
            [
                'id' => 3187,
                'name' => 'Louga',
                'code' => '13',
                'country_id' => 190,
            ],
            187 => 
            [
                'id' => 3188,
                'name' => 'Matam',
                'code' => '15',
                'country_id' => 190,
            ],
            188 => 
            [
                'id' => 3189,
                'name' => 'Saint-Louis',
                'code' => '14',
                'country_id' => 190,
            ],
            189 => 
            [
                'id' => 3190,
                'name' => '',
                'code' => '',
                'country_id' => 190,
            ],
            190 => 
            [
                'id' => 3191,
                'name' => 'Bay',
                'code' => '04',
                'country_id' => 191,
            ],
            191 => 
            [
                'id' => 3192,
                'name' => 'Shabeellaha Hoose',
                'code' => '14',
                'country_id' => 191,
            ],
            192 => 
            [
                'id' => 3193,
                'name' => 'Bakool',
                'code' => '01',
                'country_id' => 191,
            ],
            193 => 
            [
                'id' => 3194,
                'name' => 'Hiiraan',
                'code' => '07',
                'country_id' => 191,
            ],
            194 => 
            [
                'id' => 3195,
                'name' => 'Gedo',
                'code' => '06',
                'country_id' => 191,
            ],
            195 => 
            [
                'id' => 3196,
                'name' => 'Bari',
                'code' => '03',
                'country_id' => 191,
            ],
            196 => 
            [
                'id' => 3197,
                'name' => 'Galguduud',
                'code' => '05',
                'country_id' => 191,
            ],
            197 => 
            [
                'id' => 3198,
                'name' => 'Mudug',
                'code' => '10',
                'country_id' => 191,
            ],
            198 => 
            [
                'id' => 3199,
                'name' => 'Woqooyi Galbeed',
                'code' => '16',
                'country_id' => 191,
            ],
            199 => 
            [
                'id' => 3200,
                'name' => 'Jubbada Dhexe',
                'code' => '08',
                'country_id' => 191,
            ],
            200 => 
            [
                'id' => 3201,
                'name' => 'Shabeellaha Dhexe',
                'code' => '13',
                'country_id' => 191,
            ],
            201 => 
            [
                'id' => 3202,
                'name' => 'Jubbada Hoose',
                'code' => '09',
                'country_id' => 191,
            ],
            202 => 
            [
                'id' => 3203,
                'name' => '',
                'code' => '15',
                'country_id' => 191,
            ],
            203 => 
            [
                'id' => 3204,
                'name' => 'Nugaal',
                'code' => '11',
                'country_id' => 191,
            ],
            204 => 
            [
                'id' => 3205,
                'name' => 'Sanaag',
                'code' => '12',
                'country_id' => 191,
            ],
            205 => 
            [
                'id' => 3206,
                'name' => 'Banaadir',
                'code' => '02',
                'country_id' => 191,
            ],
            206 => 
            [
                'id' => 3207,
                'name' => '',
                'code' => '17',
                'country_id' => 191,
            ],
            207 => 
            [
                'id' => 3208,
                'name' => 'Brokopondo',
                'code' => '10',
                'country_id' => 192,
            ],
            208 => 
            [
                'id' => 3209,
                'name' => 'Sipaliwini',
                'code' => '18',
                'country_id' => 192,
            ],
            209 => 
            [
                'id' => 3210,
                'name' => 'Marowijne',
                'code' => '13',
                'country_id' => 192,
            ],
            210 => 
            [
                'id' => 3211,
                'name' => 'Para',
                'code' => '15',
                'country_id' => 192,
            ],
            211 => 
            [
                'id' => 3212,
                'name' => 'Commewijne',
                'code' => '11',
                'country_id' => 192,
            ],
            212 => 
            [
                'id' => 3213,
                'name' => 'Saramacca',
                'code' => '17',
                'country_id' => 192,
            ],
            213 => 
            [
                'id' => 3214,
                'name' => 'Nickerie',
                'code' => '14',
                'country_id' => 192,
            ],
            214 => 
            [
                'id' => 3215,
                'name' => 'Coronie',
                'code' => '12',
                'country_id' => 192,
            ],
            215 => 
            [
                'id' => 3216,
                'name' => 'Wanica',
                'code' => '19',
                'country_id' => 192,
            ],
            216 => 
            [
                'id' => 3217,
                'name' => 'Paramaribo',
                'code' => '16',
                'country_id' => 192,
            ],
            217 => 
            [
                'id' => 3218,
                'name' => 'Sao Tome',
                'code' => '02',
                'country_id' => 193,
            ],
            218 => 
            [
                'id' => 3219,
                'name' => 'Principe',
                'code' => '01',
                'country_id' => 193,
            ],
            219 => 
            [
                'id' => 3220,
                'name' => 'Sonsonate',
                'code' => '13',
                'country_id' => 194,
            ],
            220 => 
            [
                'id' => 3221,
                'name' => 'Morazan',
                'code' => '08',
                'country_id' => 194,
            ],
            221 => 
            [
                'id' => 3222,
                'name' => 'San Vicente',
                'code' => '12',
                'country_id' => 194,
            ],
            222 => 
            [
                'id' => 3223,
                'name' => 'La Union',
                'code' => '07',
                'country_id' => 194,
            ],
            223 => 
            [
                'id' => 3224,
                'name' => 'San Salvador',
                'code' => '10',
                'country_id' => 194,
            ],
            224 => 
            [
                'id' => 3225,
                'name' => 'Chalatenango',
                'code' => '03',
                'country_id' => 194,
            ],
            225 => 
            [
                'id' => 3226,
                'name' => 'La Libertad',
                'code' => '05',
                'country_id' => 194,
            ],
            226 => 
            [
                'id' => 3227,
                'name' => 'Cabanas',
                'code' => '02',
                'country_id' => 194,
            ],
            227 => 
            [
                'id' => 3228,
                'name' => 'Cuscatlan',
                'code' => '04',
                'country_id' => 194,
            ],
            228 => 
            [
                'id' => 3229,
                'name' => 'Usulutan',
                'code' => '14',
                'country_id' => 194,
            ],
            229 => 
            [
                'id' => 3230,
                'name' => 'Ahuachapan',
                'code' => '01',
                'country_id' => 194,
            ],
            230 => 
            [
                'id' => 3231,
                'name' => 'Santa Ana',
                'code' => '11',
                'country_id' => 194,
            ],
            231 => 
            [
                'id' => 3232,
                'name' => 'San Miguel',
                'code' => '09',
                'country_id' => 194,
            ],
            232 => 
            [
                'id' => 3233,
                'name' => 'La Paz',
                'code' => '06',
                'country_id' => 194,
            ],
            233 => 
            [
                'id' => 3234,
                'name' => 'Al Hasakah',
                'code' => '01',
                'country_id' => 195,
            ],
            234 => 
            [
                'id' => 3235,
                'name' => 'Ar Raqqah',
                'code' => '04',
                'country_id' => 195,
            ],
            235 => 
            [
                'id' => 3236,
                'name' => 'Tartus',
                'code' => '14',
                'country_id' => 195,
            ],
            236 => 
            [
                'id' => 3237,
                'name' => 'Rif Dimashq',
                'code' => '08',
                'country_id' => 195,
            ],
            237 => 
            [
                'id' => 3238,
                'name' => 'Hims',
                'code' => '11',
                'country_id' => 195,
            ],
            238 => 
            [
                'id' => 3239,
                'name' => 'Idlib',
                'code' => '12',
                'country_id' => 195,
            ],
            239 => 
            [
                'id' => 3240,
                'name' => 'Hamah',
                'code' => '10',
                'country_id' => 195,
            ],
            240 => 
            [
                'id' => 3241,
                'name' => 'Halab',
                'code' => '09',
                'country_id' => 195,
            ],
            241 => 
            [
                'id' => 3242,
                'name' => 'Al Qunaytirah',
                'code' => '03',
                'country_id' => 195,
            ],
            242 => 
            [
                'id' => 3243,
                'name' => 'Dar',
                'code' => '06',
                'country_id' => 195,
            ],
            243 => 
            [
                'id' => 3244,
                'name' => 'As Suwayda\'',
                'code' => '05',
                'country_id' => 195,
            ],
            244 => 
            [
                'id' => 3245,
                'name' => 'Al Ladhiqiyah',
                'code' => '02',
                'country_id' => 195,
            ],
            245 => 
            [
                'id' => 3246,
                'name' => 'Dayr az Zawr',
                'code' => '07',
                'country_id' => 195,
            ],
            246 => 
            [
                'id' => 3247,
                'name' => 'Dimashq',
                'code' => '13',
                'country_id' => 195,
            ],
            247 => 
            [
                'id' => 3248,
                'name' => 'Lubombo',
                'code' => '02',
                'country_id' => 196,
            ],
            248 => 
            [
                'id' => 3249,
                'name' => 'Hhohho',
                'code' => '01',
                'country_id' => 196,
            ],
            249 => 
            [
                'id' => 3250,
                'name' => 'Manzini',
                'code' => '03',
                'country_id' => 196,
            ],
            250 => 
            [
                'id' => 3251,
                'name' => 'Shiselweni',
                'code' => '04',
                'country_id' => 196,
            ],
            251 => 
            [
                'id' => 3252,
                'name' => '',
                'code' => '00',
                'country_id' => 197,
            ],
            252 => 
            [
                'id' => 3253,
                'name' => 'Ouaddai',
                'code' => '12',
                'country_id' => 198,
            ],
            253 => 
            [
                'id' => 3254,
                'name' => 'Biltine',
                'code' => '02',
                'country_id' => 198,
            ],
            254 => 
            [
                'id' => 3255,
                'name' => 'Batha',
                'code' => '01',
                'country_id' => 198,
            ],
            255 => 
            [
                'id' => 3256,
                'name' => 'Mayo-Kebbi',
                'code' => '10',
                'country_id' => 198,
            ],
            256 => 
            [
                'id' => 3257,
                'name' => 'Chari-Baguirmi',
                'code' => '04',
                'country_id' => 198,
            ],
            257 => 
            [
                'id' => 3258,
                'name' => 'Guera',
                'code' => '05',
                'country_id' => 198,
            ],
            258 => 
            [
                'id' => 3259,
                'name' => 'Salamat',
                'code' => '13',
                'country_id' => 198,
            ],
            259 => 
            [
                'id' => 3260,
                'name' => 'Kanem',
                'code' => '06',
                'country_id' => 198,
            ],
            260 => 
            [
                'id' => 3261,
                'name' => 'Logone Occidental',
                'code' => '08',
                'country_id' => 198,
            ],
            261 => 
            [
                'id' => 3262,
                'name' => 'Lac',
                'code' => '07',
                'country_id' => 198,
            ],
            262 => 
            [
                'id' => 3263,
                'name' => 'Borkou-Ennedi-Tibesti',
                'code' => '03',
                'country_id' => 198,
            ],
            263 => 
            [
                'id' => 3264,
                'name' => 'Tandjile',
                'code' => '14',
                'country_id' => 198,
            ],
            264 => 
            [
                'id' => 3265,
                'name' => 'Moyen-Chari',
                'code' => '11',
                'country_id' => 198,
            ],
            265 => 
            [
                'id' => 3266,
                'name' => 'Logone Oriental',
                'code' => '09',
                'country_id' => 198,
            ],
            266 => 
            [
                'id' => 3267,
                'name' => '',
                'code' => '00',
                'country_id' => 199,
            ],
            267 => 
            [
                'id' => 3268,
                'name' => 'Plateaux',
                'code' => '25',
                'country_id' => 200,
            ],
            268 => 
            [
                'id' => 3269,
                'name' => '',
                'code' => '13',
                'country_id' => 200,
            ],
            269 => 
            [
                'id' => 3270,
                'name' => '',
                'code' => '04',
                'country_id' => 200,
            ],
            270 => 
            [
                'id' => 3271,
                'name' => '',
                'code' => '18',
                'country_id' => 200,
            ],
            271 => 
            [
                'id' => 3272,
                'name' => '',
                'code' => '03',
                'country_id' => 200,
            ],
            272 => 
            [
                'id' => 3273,
                'name' => '',
                'code' => '09',
                'country_id' => 200,
            ],
            273 => 
            [
                'id' => 3274,
                'name' => '',
                'code' => '02',
                'country_id' => 200,
            ],
            274 => 
            [
                'id' => 3275,
                'name' => '',
                'code' => '01',
                'country_id' => 200,
            ],
            275 => 
            [
                'id' => 3276,
                'name' => '',
                'code' => '10',
                'country_id' => 200,
            ],
            276 => 
            [
                'id' => 3277,
                'name' => '',
                'code' => '16',
                'country_id' => 200,
            ],
            277 => 
            [
                'id' => 3278,
                'name' => '',
                'code' => '17',
                'country_id' => 200,
            ],
            278 => 
            [
                'id' => 3279,
                'name' => '',
                'code' => '21',
                'country_id' => 200,
            ],
            279 => 
            [
                'id' => 3280,
                'name' => '',
                'code' => '08',
                'country_id' => 200,
            ],
            280 => 
            [
                'id' => 3281,
                'name' => 'Kara',
                'code' => '23',
                'country_id' => 200,
            ],
            281 => 
            [
                'id' => 3282,
                'name' => '',
                'code' => '19',
                'country_id' => 200,
            ],
            282 => 
            [
                'id' => 3283,
                'name' => '',
                'code' => '07',
                'country_id' => 200,
            ],
            283 => 
            [
                'id' => 3284,
                'name' => '',
                'code' => '14',
                'country_id' => 200,
            ],
            284 => 
            [
                'id' => 3285,
                'name' => '',
                'code' => '12',
                'country_id' => 200,
            ],
            285 => 
            [
                'id' => 3286,
                'name' => '',
                'code' => '00',
                'country_id' => 200,
            ],
            286 => 
            [
                'id' => 3287,
                'name' => '',
                'code' => '05',
                'country_id' => 200,
            ],
            287 => 
            [
                'id' => 3288,
                'name' => '',
                'code' => '11',
                'country_id' => 200,
            ],
            288 => 
            [
                'id' => 3289,
                'name' => 'Savanes',
                'code' => '26',
                'country_id' => 200,
            ],
            289 => 
            [
                'id' => 3290,
                'name' => 'Centrale',
                'code' => '22',
                'country_id' => 200,
            ],
            290 => 
            [
                'id' => 3291,
                'name' => '',
                'code' => '06',
                'country_id' => 200,
            ],
            291 => 
            [
                'id' => 3292,
                'name' => 'Maritime',
                'code' => '24',
                'country_id' => 200,
            ],
            292 => 
            [
                'id' => 3293,
                'name' => 'Trat',
                'code' => '49',
                'country_id' => 201,
            ],
            293 => 
            [
                'id' => 3294,
                'name' => 'Chiang Mai',
                'code' => '02',
                'country_id' => 201,
            ],
            294 => 
            [
                'id' => 3295,
                'name' => 'Nan',
                'code' => '04',
                'country_id' => 201,
            ],
            295 => 
            [
                'id' => 3296,
                'name' => 'Prachin Buri',
                'code' => '45',
                'country_id' => 201,
            ],
            296 => 
            [
                'id' => 3297,
                'name' => 'Krabi',
                'code' => '63',
                'country_id' => 201,
            ],
            297 => 
            [
                'id' => 3298,
                'name' => 'Sakon Nakhon',
                'code' => '20',
                'country_id' => 201,
            ],
            298 => 
            [
                'id' => 3299,
                'name' => 'Nakhon Phanom',
                'code' => '73',
                'country_id' => 201,
            ],
            299 => 
            [
                'id' => 3300,
                'name' => 'Amnat Charoen',
                'code' => '77',
                'country_id' => 201,
            ],
            300 => 
            [
                'id' => 3301,
                'name' => 'Samut Songkhram',
                'code' => '54',
                'country_id' => 201,
            ],
            301 => 
            [
                'id' => 3302,
                'name' => 'Nakhon Sawan',
                'code' => '16',
                'country_id' => 201,
            ],
            302 => 
            [
                'id' => 3303,
                'name' => 'Kanchanaburi',
                'code' => '50',
                'country_id' => 201,
            ],
            303 => 
            [
                'id' => 3304,
                'name' => 'Ubon Ratchathani',
                'code' => '71',
                'country_id' => 201,
            ],
            304 => 
            [
                'id' => 3305,
                'name' => 'Chumphon',
                'code' => '58',
                'country_id' => 201,
            ],
            305 => 
            [
                'id' => 3306,
                'name' => 'Chachoengsao',
                'code' => '44',
                'country_id' => 201,
            ],
            306 => 
            [
                'id' => 3307,
                'name' => 'Sa Kaeo',
                'code' => '80',
                'country_id' => 201,
            ],
            307 => 
            [
                'id' => 3308,
                'name' => 'Roi Et',
                'code' => '25',
                'country_id' => 201,
            ],
            308 => 
            [
                'id' => 3309,
                'name' => 'Narathiwat',
                'code' => '31',
                'country_id' => 201,
            ],
            309 => 
            [
                'id' => 3310,
                'name' => 'Pattani',
                'code' => '69',
                'country_id' => 201,
            ],
            310 => 
            [
                'id' => 3311,
                'name' => 'Chaiyaphum',
                'code' => '26',
                'country_id' => 201,
            ],
            311 => 
            [
                'id' => 3312,
                'name' => 'Kalasin',
                'code' => '23',
                'country_id' => 201,
            ],
            312 => 
            [
                'id' => 3313,
                'name' => 'Chon Buri',
                'code' => '46',
                'country_id' => 201,
            ],
            313 => 
            [
                'id' => 3314,
                'name' => 'Sukhothai',
                'code' => '09',
                'country_id' => 201,
            ],
            314 => 
            [
                'id' => 3315,
                'name' => 'Surat Thani',
                'code' => '60',
                'country_id' => 201,
            ],
            315 => 
            [
                'id' => 3316,
                'name' => '',
                'code' => '19',
                'country_id' => 201,
            ],
            316 => 
            [
                'id' => 3317,
                'name' => 'Phra Nakhon Si Ayutthaya',
                'code' => '36',
                'country_id' => 201,
            ],
            317 => 
            [
                'id' => 3318,
                'name' => 'Nonthaburi',
                'code' => '38',
                'country_id' => 201,
            ],
            318 => 
            [
                'id' => 3319,
                'name' => 'Samut Prakan',
                'code' => '42',
                'country_id' => 201,
            ],
            319 => 
            [
                'id' => 3320,
                'name' => 'Ang Thong',
                'code' => '35',
                'country_id' => 201,
            ],
            320 => 
            [
                'id' => 3321,
                'name' => 'Krung Thep',
                'code' => '40',
                'country_id' => 201,
            ],
            321 => 
            [
                'id' => 3322,
                'name' => 'Phitsanulok',
                'code' => '12',
                'country_id' => 201,
            ],
            322 => 
            [
                'id' => 3323,
                'name' => 'Nakhon Pathom',
                'code' => '53',
                'country_id' => 201,
            ],
            323 => 
            [
                'id' => 3324,
                'name' => 'Phichit',
                'code' => '13',
                'country_id' => 201,
            ],
            324 => 
            [
                'id' => 3325,
                'name' => 'Ratchaburi',
                'code' => '52',
                'country_id' => 201,
            ],
            325 => 
            [
                'id' => 3326,
                'name' => 'Suphan Buri',
                'code' => '51',
                'country_id' => 201,
            ],
            326 => 
            [
                'id' => 3327,
                'name' => 'Sing Buri',
                'code' => '33',
                'country_id' => 201,
            ],
            327 => 
            [
                'id' => 3328,
                'name' => 'Prachuap Khiri Khan',
                'code' => '57',
                'country_id' => 201,
            ],
            328 => 
            [
                'id' => 3329,
                'name' => 'Lamphun',
                'code' => '05',
                'country_id' => 201,
            ],
            329 => 
            [
                'id' => 3330,
                'name' => 'Rayong',
                'code' => '47',
                'country_id' => 201,
            ],
            330 => 
            [
                'id' => 3331,
                'name' => 'Ubon Ratchathani',
                'code' => '75',
                'country_id' => 201,
            ],
            331 => 
            [
                'id' => 3332,
                'name' => 'Chai Nat',
                'code' => '32',
                'country_id' => 201,
            ],
            332 => 
            [
                'id' => 3333,
                'name' => 'Buriram',
                'code' => '28',
                'country_id' => 201,
            ],
            333 => 
            [
                'id' => 3334,
                'name' => 'Phetchaburi',
                'code' => '56',
                'country_id' => 201,
            ],
            334 => 
            [
                'id' => 3335,
                'name' => 'Tak',
                'code' => '08',
                'country_id' => 201,
            ],
            335 => 
            [
                'id' => 3336,
                'name' => 'Phayao',
                'code' => '41',
                'country_id' => 201,
            ],
            336 => 
            [
                'id' => 3337,
                'name' => 'Lop Buri',
                'code' => '34',
                'country_id' => 201,
            ],
            337 => 
            [
                'id' => 3338,
                'name' => 'Saraburi',
                'code' => '37',
                'country_id' => 201,
            ],
            338 => 
            [
                'id' => 3339,
                'name' => 'Nakhon Nayok',
                'code' => '43',
                'country_id' => 201,
            ],
            339 => 
            [
                'id' => 3340,
                'name' => 'Yala',
                'code' => '70',
                'country_id' => 201,
            ],
            340 => 
            [
                'id' => 3341,
                'name' => 'Nakhon Ratchasima',
                'code' => '27',
                'country_id' => 201,
            ],
            341 => 
            [
                'id' => 3342,
                'name' => 'Samut Sakhon',
                'code' => '55',
                'country_id' => 201,
            ],
            342 => 
            [
                'id' => 3343,
                'name' => 'Khon Kaen',
                'code' => '22',
                'country_id' => 201,
            ],
            343 => 
            [
                'id' => 3344,
                'name' => 'Uthai Thani',
                'code' => '15',
                'country_id' => 201,
            ],
            344 => 
            [
                'id' => 3345,
                'name' => 'Nong Khai',
                'code' => '17',
                'country_id' => 201,
            ],
            345 => 
            [
                'id' => 3346,
                'name' => 'Maha Sarakham',
                'code' => '24',
                'country_id' => 201,
            ],
            346 => 
            [
                'id' => 3347,
                'name' => 'Lampang',
                'code' => '06',
                'country_id' => 201,
            ],
            347 => 
            [
                'id' => 3348,
                'name' => 'Songkhla',
                'code' => '68',
                'country_id' => 201,
            ],
            348 => 
            [
                'id' => 3349,
                'name' => 'Nakhon Si Thammarat',
                'code' => '64',
                'country_id' => 201,
            ],
            349 => 
            [
                'id' => 3350,
                'name' => 'Loei',
                'code' => '18',
                'country_id' => 201,
            ],
            350 => 
            [
                'id' => 3351,
                'name' => 'Chiang Rai',
                'code' => '03',
                'country_id' => 201,
            ],
            351 => 
            [
                'id' => 3352,
                'name' => 'Surin',
                'code' => '29',
                'country_id' => 201,
            ],
            352 => 
            [
                'id' => 3353,
                'name' => 'Phetchabun',
                'code' => '14',
                'country_id' => 201,
            ],
            353 => 
            [
                'id' => 3354,
                'name' => 'Phrae',
                'code' => '07',
                'country_id' => 201,
            ],
            354 => 
            [
                'id' => 3355,
                'name' => 'Phangnga',
                'code' => '61',
                'country_id' => 201,
            ],
            355 => 
            [
                'id' => 3356,
                'name' => 'Uttaradit',
                'code' => '10',
                'country_id' => 201,
            ],
            356 => 
            [
                'id' => 3357,
                'name' => 'Sisaket',
                'code' => '30',
                'country_id' => 201,
            ],
            357 => 
            [
                'id' => 3358,
                'name' => 'Trang',
                'code' => '65',
                'country_id' => 201,
            ],
            358 => 
            [
                'id' => 3359,
                'name' => 'Kamphaeng Phet',
                'code' => '11',
                'country_id' => 201,
            ],
            359 => 
            [
                'id' => 3360,
                'name' => 'Phuket',
                'code' => '62',
                'country_id' => 201,
            ],
            360 => 
            [
                'id' => 3361,
                'name' => 'Mukdahan',
                'code' => '78',
                'country_id' => 201,
            ],
            361 => 
            [
                'id' => 3362,
                'name' => 'Yasothon',
                'code' => '72',
                'country_id' => 201,
            ],
            362 => 
            [
                'id' => 3363,
                'name' => 'Phatthalung',
                'code' => '66',
                'country_id' => 201,
            ],
            363 => 
            [
                'id' => 3364,
                'name' => 'Pathum Thani',
                'code' => '39',
                'country_id' => 201,
            ],
            364 => 
            [
                'id' => 3365,
                'name' => 'Chanthaburi',
                'code' => '48',
                'country_id' => 201,
            ],
            365 => 
            [
                'id' => 3366,
                'name' => 'Mae Hong Son',
                'code' => '01',
                'country_id' => 201,
            ],
            366 => 
            [
                'id' => 3367,
                'name' => 'Ranong',
                'code' => '59',
                'country_id' => 201,
            ],
            367 => 
            [
                'id' => 3368,
                'name' => 'Udon Thani',
                'code' => '76',
                'country_id' => 201,
            ],
            368 => 
            [
                'id' => 3369,
                'name' => 'Satun',
                'code' => '67',
                'country_id' => 201,
            ],
            369 => 
            [
                'id' => 3370,
                'name' => 'Nong Bua Lamphu',
                'code' => '79',
                'country_id' => 201,
            ],
            370 => 
            [
                'id' => 3371,
                'name' => 'Nakhon Phanom',
                'code' => '21',
                'country_id' => 201,
            ],
            371 => 
            [
                'id' => 3372,
                'name' => 'Khatlon',
                'code' => '02',
                'country_id' => 202,
            ],
            372 => 
            [
                'id' => 3373,
                'name' => 'Sughd',
                'code' => '03',
                'country_id' => 202,
            ],
            373 => 
            [
                'id' => 3374,
                'name' => 'Kuhistoni Badakhshon',
                'code' => '01',
                'country_id' => 202,
            ],
            374 => 
            [
                'id' => 3375,
                'name' => '',
                'code' => '00',
                'country_id' => 203,
            ],
            375 => 
            [
                'id' => 3376,
                'name' => 'Lebap',
                'code' => '04',
                'country_id' => 204,
            ],
            376 => 
            [
                'id' => 3377,
                'name' => 'Balkan',
                'code' => '02',
                'country_id' => 204,
            ],
            377 => 
            [
                'id' => 3378,
                'name' => 'Ahal',
                'code' => '01',
                'country_id' => 204,
            ],
            378 => 
            [
                'id' => 3379,
                'name' => '',
                'code' => '00',
                'country_id' => 204,
            ],
            379 => 
            [
                'id' => 3380,
                'name' => 'Mary',
                'code' => '05',
                'country_id' => 204,
            ],
            380 => 
            [
                'id' => 3381,
                'name' => 'Dashoguz',
                'code' => '03',
                'country_id' => 204,
            ],
            381 => 
            [
                'id' => 3382,
                'name' => 'Madanin',
                'code' => '28',
                'country_id' => 205,
            ],
            382 => 
            [
                'id' => 3383,
                'name' => 'El Kef',
                'code' => '14',
                'country_id' => 205,
            ],
            383 => 
            [
                'id' => 3384,
                'name' => 'Tozeur',
                'code' => '35',
                'country_id' => 205,
            ],
            384 => 
            [
                'id' => 3385,
                'name' => 'Sousse',
                'code' => '23',
                'country_id' => 205,
            ],
            385 => 
            [
                'id' => 3386,
                'name' => 'Gabes',
                'code' => '29',
                'country_id' => 205,
            ],
            386 => 
            [
                'id' => 3387,
                'name' => 'Sfax',
                'code' => '32',
                'country_id' => 205,
            ],
            387 => 
            [
                'id' => 3388,
                'name' => 'Bizerte',
                'code' => '18',
                'country_id' => 205,
            ],
            388 => 
            [
                'id' => 3389,
                'name' => 'Al Munastir',
                'code' => '16',
                'country_id' => 205,
            ],
            389 => 
            [
                'id' => 3390,
                'name' => 'Nabeul',
                'code' => '19',
                'country_id' => 205,
            ],
            390 => 
            [
                'id' => 3391,
                'name' => 'Kasserine',
                'code' => '02',
                'country_id' => 205,
            ],
            391 => 
            [
                'id' => 3392,
                'name' => 'Tataouine',
                'code' => '34',
                'country_id' => 205,
            ],
            392 => 
            [
                'id' => 3393,
                'name' => 'Sidi Bou Zid',
                'code' => '33',
                'country_id' => 205,
            ],
            393 => 
            [
                'id' => 3394,
                'name' => 'Al Mahdia',
                'code' => '15',
                'country_id' => 205,
            ],
            394 => 
            [
                'id' => 3395,
                'name' => 'Jendouba',
                'code' => '06',
                'country_id' => 205,
            ],
            395 => 
            [
                'id' => 3396,
                'name' => 'Ben Arous',
                'code' => '27',
                'country_id' => 205,
            ],
            396 => 
            [
                'id' => 3397,
                'name' => 'Kairouan',
                'code' => '03',
                'country_id' => 205,
            ],
            397 => 
            [
                'id' => 3398,
                'name' => 'Zaghouan',
                'code' => '37',
                'country_id' => 205,
            ],
            398 => 
            [
                'id' => 3399,
                'name' => 'Kebili',
                'code' => '31',
                'country_id' => 205,
            ],
            399 => 
            [
                'id' => 3400,
                'name' => 'Bajah',
                'code' => '17',
                'country_id' => 205,
            ],
            400 => 
            [
                'id' => 3401,
                'name' => '',
                'code' => '30',
                'country_id' => 205,
            ],
            401 => 
            [
                'id' => 3402,
                'name' => 'Siliana',
                'code' => '22',
                'country_id' => 205,
            ],
            402 => 
            [
                'id' => 3403,
                'name' => '',
                'code' => '26',
                'country_id' => 205,
            ],
            403 => 
            [
                'id' => 3404,
                'name' => 'Tunis',
                'code' => '36',
                'country_id' => 205,
            ],
            404 => 
            [
                'id' => 3405,
                'name' => 'Tongatapu',
                'code' => '02',
                'country_id' => 206,
            ],
            405 => 
            [
                'id' => 3406,
                'name' => 'Ha',
                'code' => '01',
                'country_id' => 206,
            ],
            406 => 
            [
                'id' => 3407,
                'name' => 'Vava',
                'code' => '03',
                'country_id' => 206,
            ],
            407 => 
            [
                'id' => 3408,
                'name' => 'Amasya',
                'code' => '05',
                'country_id' => 207,
            ],
            408 => 
            [
                'id' => 3409,
                'name' => 'Hatay',
                'code' => '31',
                'country_id' => 207,
            ],
            409 => 
            [
                'id' => 3410,
                'name' => 'Diyarbakir',
                'code' => '21',
                'country_id' => 207,
            ],
            410 => 
            [
                'id' => 3411,
                'name' => 'Adana',
                'code' => '81',
                'country_id' => 207,
            ],
            411 => 
            [
                'id' => 3412,
                'name' => 'Bolu',
                'code' => '14',
                'country_id' => 207,
            ],
            412 => 
            [
                'id' => 3413,
                'name' => 'Ankara',
                'code' => '68',
                'country_id' => 207,
            ],
            413 => 
            [
                'id' => 3414,
                'name' => 'Konya',
                'code' => '71',
                'country_id' => 207,
            ],
            414 => 
            [
                'id' => 3415,
                'name' => 'Bilecik',
                'code' => '11',
                'country_id' => 207,
            ],
            415 => 
            [
                'id' => 3416,
                'name' => 'Izmir',
                'code' => '35',
                'country_id' => 207,
            ],
            416 => 
            [
                'id' => 3417,
                'name' => 'Tokat',
                'code' => '60',
                'country_id' => 207,
            ],
            417 => 
            [
                'id' => 3418,
                'name' => 'Edirne',
                'code' => '22',
                'country_id' => 207,
            ],
            418 => 
            [
                'id' => 3419,
                'name' => 'Kirsehir',
                'code' => '40',
                'country_id' => 207,
            ],
            419 => 
            [
                'id' => 3420,
                'name' => 'Van',
                'code' => '65',
                'country_id' => 207,
            ],
            420 => 
            [
                'id' => 3421,
                'name' => 'Kastamonu',
                'code' => '37',
                'country_id' => 207,
            ],
            421 => 
            [
                'id' => 3422,
                'name' => 'Sivas',
                'code' => '58',
                'country_id' => 207,
            ],
            422 => 
            [
                'id' => 3423,
                'name' => 'Denizli',
                'code' => '20',
                'country_id' => 207,
            ],
            423 => 
            [
                'id' => 3424,
                'name' => 'Kutahya',
                'code' => '43',
                'country_id' => 207,
            ],
            424 => 
            [
                'id' => 3425,
                'name' => 'Bingol',
                'code' => '12',
                'country_id' => 207,
            ],
            425 => 
            [
                'id' => 3426,
                'name' => 'Kahramanmaras',
                'code' => '46',
                'country_id' => 207,
            ],
            426 => 
            [
                'id' => 3427,
                'name' => 'Sanliurfa',
                'code' => '63',
                'country_id' => 207,
            ],
            427 => 
            [
                'id' => 3428,
                'name' => 'Agri',
                'code' => '04',
                'country_id' => 207,
            ],
            428 => 
            [
                'id' => 3429,
                'name' => 'Eskisehir',
                'code' => '26',
                'country_id' => 207,
            ],
            429 => 
            [
                'id' => 3430,
                'name' => 'Malatya',
                'code' => '44',
                'country_id' => 207,
            ],
            430 => 
            [
                'id' => 3431,
                'name' => 'Adiyaman',
                'code' => '02',
                'country_id' => 207,
            ],
            431 => 
            [
                'id' => 3432,
                'name' => 'Giresun',
                'code' => '28',
                'country_id' => 207,
            ],
            432 => 
            [
                'id' => 3433,
                'name' => 'Mus',
                'code' => '49',
                'country_id' => 207,
            ],
            433 => 
            [
                'id' => 3434,
                'name' => 'Corum',
                'code' => '19',
                'country_id' => 207,
            ],
            434 => 
            [
                'id' => 3435,
                'name' => 'Erzurum',
                'code' => '25',
                'country_id' => 207,
            ],
            435 => 
            [
                'id' => 3436,
                'name' => 'Mersin',
                'code' => '32',
                'country_id' => 207,
            ],
            436 => 
            [
                'id' => 3437,
                'name' => 'Aydin',
                'code' => '09',
                'country_id' => 207,
            ],
            437 => 
            [
                'id' => 3438,
                'name' => 'Nevsehir',
                'code' => '50',
                'country_id' => 207,
            ],
            438 => 
            [
                'id' => 3439,
                'name' => 'Manisa',
                'code' => '45',
                'country_id' => 207,
            ],
            439 => 
            [
                'id' => 3440,
                'name' => 'Canakkale',
                'code' => '17',
                'country_id' => 207,
            ],
            440 => 
            [
                'id' => 3441,
                'name' => 'Ordu',
                'code' => '52',
                'country_id' => 207,
            ],
            441 => 
            [
                'id' => 3442,
                'name' => 'Yozgat',
                'code' => '66',
                'country_id' => 207,
            ],
            442 => 
            [
                'id' => 3443,
                'name' => 'Tunceli',
                'code' => '62',
                'country_id' => 207,
            ],
            443 => 
            [
                'id' => 3444,
                'name' => 'Mardin',
                'code' => '72',
                'country_id' => 207,
            ],
            444 => 
            [
                'id' => 3445,
                'name' => 'Sinop',
                'code' => '57',
                'country_id' => 207,
            ],
            445 => 
            [
                'id' => 3446,
                'name' => 'Antalya',
                'code' => '07',
                'country_id' => 207,
            ],
            446 => 
            [
                'id' => 3447,
                'name' => 'Erzincan',
                'code' => '24',
                'country_id' => 207,
            ],
            447 => 
            [
                'id' => 3448,
                'name' => 'Artvin',
                'code' => '08',
                'country_id' => 207,
            ],
            448 => 
            [
                'id' => 3449,
                'name' => 'Sakarya',
                'code' => '54',
                'country_id' => 207,
            ],
            449 => 
            [
                'id' => 3450,
                'name' => 'Afyonkarahisar',
                'code' => '03',
                'country_id' => 207,
            ],
            450 => 
            [
                'id' => 3451,
                'name' => 'Bursa',
                'code' => '16',
                'country_id' => 207,
            ],
            451 => 
            [
                'id' => 3452,
                'name' => 'Trabzon',
                'code' => '61',
                'country_id' => 207,
            ],
            452 => 
            [
                'id' => 3453,
                'name' => 'Tekirdag',
                'code' => '59',
                'country_id' => 207,
            ],
            453 => 
            [
                'id' => 3454,
                'name' => 'Kilis',
                'code' => '90',
                'country_id' => 207,
            ],
            454 => 
            [
                'id' => 3455,
                'name' => 'Gaziantep',
                'code' => '83',
                'country_id' => 207,
            ],
            455 => 
            [
                'id' => 3456,
                'name' => 'Sirnak',
                'code' => '80',
                'country_id' => 207,
            ],
            456 => 
            [
                'id' => 3457,
                'name' => 'Balikesir',
                'code' => '10',
                'country_id' => 207,
            ],
            457 => 
            [
                'id' => 3458,
                'name' => 'Elazig',
                'code' => '23',
                'country_id' => 207,
            ],
            458 => 
            [
                'id' => 3459,
                'name' => 'Ardahan',
                'code' => '86',
                'country_id' => 207,
            ],
            459 => 
            [
                'id' => 3460,
                'name' => 'Batman',
                'code' => '76',
                'country_id' => 207,
            ],
            460 => 
            [
                'id' => 3461,
                'name' => 'Kayseri',
                'code' => '38',
                'country_id' => 207,
            ],
            461 => 
            [
                'id' => 3462,
                'name' => 'Kocaeli',
                'code' => '41',
                'country_id' => 207,
            ],
            462 => 
            [
                'id' => 3463,
                'name' => 'Samsun',
                'code' => '55',
                'country_id' => 207,
            ],
            463 => 
            [
                'id' => 3464,
                'name' => 'Isparta',
                'code' => '33',
                'country_id' => 207,
            ],
            464 => 
            [
                'id' => 3465,
                'name' => 'Mugla',
                'code' => '48',
                'country_id' => 207,
            ],
            465 => 
            [
                'id' => 3466,
                'name' => 'Bitlis',
                'code' => '13',
                'country_id' => 207,
            ],
            466 => 
            [
                'id' => 3467,
                'name' => 'Rize',
                'code' => '53',
                'country_id' => 207,
            ],
            467 => 
            [
                'id' => 3468,
                'name' => 'Hakkari',
                'code' => '70',
                'country_id' => 207,
            ],
            468 => 
            [
                'id' => 3469,
                'name' => 'Istanbul',
                'code' => '34',
                'country_id' => 207,
            ],
            469 => 
            [
                'id' => 3470,
                'name' => 'Karaman',
                'code' => '78',
                'country_id' => 207,
            ],
            470 => 
            [
                'id' => 3471,
                'name' => 'Igdir',
                'code' => '88',
                'country_id' => 207,
            ],
            471 => 
            [
                'id' => 3472,
                'name' => 'Nigde',
                'code' => '73',
                'country_id' => 207,
            ],
            472 => 
            [
                'id' => 3473,
                'name' => 'Usak',
                'code' => '64',
                'country_id' => 207,
            ],
            473 => 
            [
                'id' => 3474,
                'name' => 'Siirt',
                'code' => '74',
                'country_id' => 207,
            ],
            474 => 
            [
                'id' => 3475,
                'name' => 'Kirklareli',
                'code' => '39',
                'country_id' => 207,
            ],
            475 => 
            [
                'id' => 3476,
                'name' => 'Burdur',
                'code' => '15',
                'country_id' => 207,
            ],
            476 => 
            [
                'id' => 3477,
                'name' => 'Gumushane',
                'code' => '69',
                'country_id' => 207,
            ],
            477 => 
            [
                'id' => 3478,
                'name' => 'Osmaniye',
                'code' => '91',
                'country_id' => 207,
            ],
            478 => 
            [
                'id' => 3479,
                'name' => 'Yalova',
                'code' => '92',
                'country_id' => 207,
            ],
            479 => 
            [
                'id' => 3480,
                'name' => 'Kars',
                'code' => '84',
                'country_id' => 207,
            ],
            480 => 
            [
                'id' => 3481,
                'name' => 'Tobago',
                'code' => '11',
                'country_id' => 208,
            ],
            481 => 
            [
                'id' => 3482,
                'name' => 'Caroni',
                'code' => '02',
                'country_id' => 208,
            ],
            482 => 
            [
                'id' => 3483,
                'name' => 'Saint David',
                'code' => '07',
                'country_id' => 208,
            ],
            483 => 
            [
                'id' => 3484,
                'name' => 'Arima',
                'code' => '01',
                'country_id' => 208,
            ],
            484 => 
            [
                'id' => 3485,
                'name' => 'Saint George',
                'code' => '08',
                'country_id' => 208,
            ],
            485 => 
            [
                'id' => 3486,
                'name' => 'Saint Patrick',
                'code' => '09',
                'country_id' => 208,
            ],
            486 => 
            [
                'id' => 3487,
                'name' => 'Victoria',
                'code' => '12',
                'country_id' => 208,
            ],
            487 => 
            [
                'id' => 3488,
                'name' => 'Nariva',
                'code' => '04',
                'country_id' => 208,
            ],
            488 => 
            [
                'id' => 3489,
                'name' => 'Port-of-Spain',
                'code' => '05',
                'country_id' => 208,
            ],
            489 => 
            [
                'id' => 3490,
                'name' => 'Saint Andrew',
                'code' => '06',
                'country_id' => 208,
            ],
            490 => 
            [
                'id' => 3491,
                'name' => 'Mayaro',
                'code' => '03',
                'country_id' => 208,
            ],
            491 => 
            [
                'id' => 3492,
                'name' => 'San Fernando',
                'code' => '10',
                'country_id' => 208,
            ],
            492 => 
            [
                'id' => 3493,
                'name' => '',
                'code' => '00',
                'country_id' => 209,
            ],
            493 => 
            [
                'id' => 3494,
                'name' => 'T\'ai-wan',
                'code' => '04',
                'country_id' => 210,
            ],
            494 => 
            [
                'id' => 3495,
                'name' => 'T\'ai-pei',
                'code' => '03',
                'country_id' => 210,
            ],
            495 => 
            [
                'id' => 3496,
                'name' => 'Fu-chien',
                'code' => '01',
                'country_id' => 210,
            ],
            496 => 
            [
                'id' => 3497,
                'name' => 'Kao-hsiung',
                'code' => '02',
                'country_id' => 210,
            ],
            497 => 
            [
                'id' => 3498,
                'name' => '',
                'code' => '00',
                'country_id' => 211,
            ],
            498 => 
            [
                'id' => 3499,
                'name' => 'Tabora',
                'code' => '17',
                'country_id' => 211,
            ],
            499 => 
            [
                'id' => 3500,
                'name' => 'Manyara',
                'code' => '27',
                'country_id' => 211,
            ],
        ]);
        \DB::table('regions')->insert([
            0 => 
            [
                'id' => 3501,
                'name' => 'Mtwara',
                'code' => '11',
                'country_id' => 211,
            ],
            1 => 
            [
                'id' => 3502,
                'name' => 'Lindi',
                'code' => '07',
                'country_id' => 211,
            ],
            2 => 
            [
                'id' => 3503,
                'name' => 'Ruvuma',
                'code' => '14',
                'country_id' => 211,
            ],
            3 => 
            [
                'id' => 3504,
                'name' => 'Iringa',
                'code' => '04',
                'country_id' => 211,
            ],
            4 => 
            [
                'id' => 3505,
                'name' => 'Tanga',
                'code' => '18',
                'country_id' => 211,
            ],
            5 => 
            [
                'id' => 3506,
                'name' => 'Pemba South',
                'code' => '20',
                'country_id' => 211,
            ],
            6 => 
            [
                'id' => 3507,
                'name' => 'Kagera',
                'code' => '19',
                'country_id' => 211,
            ],
            7 => 
            [
                'id' => 3508,
                'name' => 'Arusha',
                'code' => '26',
                'country_id' => 211,
            ],
            8 => 
            [
                'id' => 3509,
                'name' => 'Mwanza',
                'code' => '12',
                'country_id' => 211,
            ],
            9 => 
            [
                'id' => 3510,
                'name' => 'Kilimanjaro',
                'code' => '06',
                'country_id' => 211,
            ],
            10 => 
            [
                'id' => 3511,
                'name' => 'Pwani',
                'code' => '02',
                'country_id' => 211,
            ],
            11 => 
            [
                'id' => 3512,
                'name' => 'Zanzibar Central',
                'code' => '21',
                'country_id' => 211,
            ],
            12 => 
            [
                'id' => 3513,
                'name' => 'Dodoma',
                'code' => '03',
                'country_id' => 211,
            ],
            13 => 
            [
                'id' => 3514,
                'name' => 'Shinyanga',
                'code' => '15',
                'country_id' => 211,
            ],
            14 => 
            [
                'id' => 3515,
                'name' => 'Zanzibar Urban',
                'code' => '25',
                'country_id' => 211,
            ],
            15 => 
            [
                'id' => 3516,
                'name' => 'Pemba North',
                'code' => '13',
                'country_id' => 211,
            ],
            16 => 
            [
                'id' => 3517,
                'name' => 'Mara',
                'code' => '08',
                'country_id' => 211,
            ],
            17 => 
            [
                'id' => 3518,
                'name' => 'Dar es Salaam',
                'code' => '23',
                'country_id' => 211,
            ],
            18 => 
            [
                'id' => 3519,
                'name' => 'Zanzibar North',
                'code' => '22',
                'country_id' => 211,
            ],
            19 => 
            [
                'id' => 3520,
                'name' => 'Mbeya',
                'code' => '09',
                'country_id' => 211,
            ],
            20 => 
            [
                'id' => 3521,
                'name' => 'Singida',
                'code' => '16',
                'country_id' => 211,
            ],
            21 => 
            [
                'id' => 3522,
                'name' => 'Kigoma',
                'code' => '05',
                'country_id' => 211,
            ],
            22 => 
            [
                'id' => 3523,
                'name' => 'Morogoro',
                'code' => '10',
                'country_id' => 211,
            ],
            23 => 
            [
                'id' => 3524,
                'name' => 'Rukwa',
                'code' => '24',
                'country_id' => 211,
            ],
            24 => 
            [
                'id' => 3525,
                'name' => 'Krym',
                'code' => '11',
                'country_id' => 212,
            ],
            25 => 
            [
                'id' => 3526,
                'name' => 'Odes\'ka Oblast\'',
                'code' => '17',
                'country_id' => 212,
            ],
            26 => 
            [
                'id' => 3527,
                'name' => 'Kharkivs\'ka Oblast\'',
                'code' => '07',
                'country_id' => 212,
            ],
            27 => 
            [
                'id' => 3528,
                'name' => 'Poltavs\'ka Oblast\'',
                'code' => '18',
                'country_id' => 212,
            ],
            28 => 
            [
                'id' => 3529,
                'name' => 'Kyyivs\'ka Oblast\'',
                'code' => '13',
                'country_id' => 212,
            ],
            29 => 
            [
                'id' => 3530,
                'name' => 'Zakarpats\'ka Oblast\'',
                'code' => '25',
                'country_id' => 212,
            ],
            30 => 
            [
                'id' => 3531,
                'name' => 'Sums\'ka Oblast\'',
                'code' => '21',
                'country_id' => 212,
            ],
            31 => 
            [
                'id' => 3532,
                'name' => 'Donets\'ka Oblast\'',
                'code' => '05',
                'country_id' => 212,
            ],
            32 => 
            [
                'id' => 3533,
                'name' => 'Khersons\'ka Oblast\'',
                'code' => '08',
                'country_id' => 212,
            ],
            33 => 
            [
                'id' => 3534,
                'name' => 'L\'vivs\'ka Oblast\'',
                'code' => '15',
                'country_id' => 212,
            ],
            34 => 
            [
                'id' => 3535,
                'name' => 'Cherkas\'ka Oblast\'',
                'code' => '01',
                'country_id' => 212,
            ],
            35 => 
            [
                'id' => 3536,
                'name' => 'Vinnyts\'ka Oblast\'',
                'code' => '23',
                'country_id' => 212,
            ],
            36 => 
            [
                'id' => 3537,
                'name' => 'Rivnens\'ka Oblast\'',
                'code' => '19',
                'country_id' => 212,
            ],
            37 => 
            [
                'id' => 3538,
                'name' => 'Khmel\'nyts\'ka Oblast\'',
                'code' => '09',
                'country_id' => 212,
            ],
            38 => 
            [
                'id' => 3539,
                'name' => 'Chernihivs\'ka Oblast\'',
                'code' => '02',
                'country_id' => 212,
            ],
            39 => 
            [
                'id' => 3540,
                'name' => 'Dnipropetrovs\'ka Oblast\'',
                'code' => '04',
                'country_id' => 212,
            ],
            40 => 
            [
                'id' => 3541,
                'name' => 'Mykolayivs\'ka Oblast\'',
                'code' => '16',
                'country_id' => 212,
            ],
            41 => 
            [
                'id' => 3542,
                'name' => 'Ternopil\'s\'ka Oblast\'',
                'code' => '22',
                'country_id' => 212,
            ],
            42 => 
            [
                'id' => 3543,
                'name' => 'Zhytomyrs\'ka Oblast\'',
                'code' => '27',
                'country_id' => 212,
            ],
            43 => 
            [
                'id' => 3544,
                'name' => 'Chernivets\'ka Oblast\'',
                'code' => '03',
                'country_id' => 212,
            ],
            44 => 
            [
                'id' => 3545,
                'name' => 'Luhans\'ka Oblast\'',
                'code' => '14',
                'country_id' => 212,
            ],
            45 => 
            [
                'id' => 3546,
                'name' => 'Sevastopol\'',
                'code' => '20',
                'country_id' => 212,
            ],
            46 => 
            [
                'id' => 3547,
                'name' => 'Kirovohrads\'ka Oblast\'',
                'code' => '10',
                'country_id' => 212,
            ],
            47 => 
            [
                'id' => 3548,
                'name' => 'Ivano-Frankivs\'ka Oblast\'',
                'code' => '06',
                'country_id' => 212,
            ],
            48 => 
            [
                'id' => 3549,
                'name' => 'Zaporiz\'ka Oblast\'',
                'code' => '26',
                'country_id' => 212,
            ],
            49 => 
            [
                'id' => 3550,
                'name' => 'Volyns\'ka Oblast\'',
                'code' => '24',
                'country_id' => 212,
            ],
            50 => 
            [
                'id' => 3551,
                'name' => '',
                'code' => '00',
                'country_id' => 212,
            ],
            51 => 
            [
                'id' => 3552,
                'name' => 'Nebbi',
                'code' => '58',
                'country_id' => 213,
            ],
            52 => 
            [
                'id' => 3553,
                'name' => 'Katakwi',
                'code' => '69',
                'country_id' => 213,
            ],
            53 => 
            [
                'id' => 3554,
                'name' => 'Lira',
                'code' => '47',
                'country_id' => 213,
            ],
            54 => 
            [
                'id' => 3555,
                'name' => 'Apac',
                'code' => '26',
                'country_id' => 213,
            ],
            55 => 
            [
                'id' => 3556,
                'name' => 'Kaberamaido',
                'code' => '80',
                'country_id' => 213,
            ],
            56 => 
            [
                'id' => 3557,
                'name' => 'Arua',
                'code' => '77',
                'country_id' => 213,
            ],
            57 => 
            [
                'id' => 3558,
                'name' => 'Soroti',
                'code' => '95',
                'country_id' => 213,
            ],
            58 => 
            [
                'id' => 3559,
                'name' => 'Tororo',
                'code' => '76',
                'country_id' => 213,
            ],
            59 => 
            [
                'id' => 3560,
                'name' => 'Gulu',
                'code' => '30',
                'country_id' => 213,
            ],
            60 => 
            [
                'id' => 3561,
                'name' => 'Pallisa',
                'code' => '60',
                'country_id' => 213,
            ],
            61 => 
            [
                'id' => 3562,
                'name' => 'Pader',
                'code' => '92',
                'country_id' => 213,
            ],
            62 => 
            [
                'id' => 3563,
                'name' => 'Kumi',
                'code' => '46',
                'country_id' => 213,
            ],
            63 => 
            [
                'id' => 3564,
                'name' => 'Adjumani',
                'code' => '65',
                'country_id' => 213,
            ],
            64 => 
            [
                'id' => 3565,
                'name' => 'Kotido',
                'code' => '45',
                'country_id' => 213,
            ],
            65 => 
            [
                'id' => 3566,
                'name' => 'Kitgum',
                'code' => '84',
                'country_id' => 213,
            ],
            66 => 
            [
                'id' => 3567,
                'name' => 'Masindi',
                'code' => '50',
                'country_id' => 213,
            ],
            67 => 
            [
                'id' => 3568,
                'name' => 'Mbarara',
                'code' => '52',
                'country_id' => 213,
            ],
            68 => 
            [
                'id' => 3569,
                'name' => '',
                'code' => '34',
                'country_id' => 213,
            ],
            69 => 
            [
                'id' => 3570,
                'name' => 'Bundibugyo',
                'code' => '28',
                'country_id' => 213,
            ],
            70 => 
            [
                'id' => 3571,
                'name' => 'Nakapiripirit',
                'code' => '91',
                'country_id' => 213,
            ],
            71 => 
            [
                'id' => 3572,
                'name' => 'Moroto',
                'code' => '88',
                'country_id' => 213,
            ],
            72 => 
            [
                'id' => 3573,
                'name' => 'Moyo',
                'code' => '72',
                'country_id' => 213,
            ],
            73 => 
            [
                'id' => 3574,
                'name' => 'Mbale',
                'code' => '87',
                'country_id' => 213,
            ],
            74 => 
            [
                'id' => 3575,
                'name' => 'Yumbe',
                'code' => '97',
                'country_id' => 213,
            ],
            75 => 
            [
                'id' => 3576,
                'name' => 'Kapchorwa',
                'code' => '39',
                'country_id' => 213,
            ],
            76 => 
            [
                'id' => 3577,
                'name' => 'Nakasongola',
                'code' => '73',
                'country_id' => 213,
            ],
            77 => 
            [
                'id' => 3578,
                'name' => 'Mubende',
                'code' => '56',
                'country_id' => 213,
            ],
            78 => 
            [
                'id' => 3579,
                'name' => 'Kisoro',
                'code' => '43',
                'country_id' => 213,
            ],
            79 => 
            [
                'id' => 3580,
                'name' => 'Iganga',
                'code' => '78',
                'country_id' => 213,
            ],
            80 => 
            [
                'id' => 3581,
                'name' => 'Kayunga',
                'code' => '83',
                'country_id' => 213,
            ],
            81 => 
            [
                'id' => 3582,
                'name' => 'Mukono',
                'code' => '90',
                'country_id' => 213,
            ],
            82 => 
            [
                'id' => 3583,
                'name' => 'Mpigi',
                'code' => '89',
                'country_id' => 213,
            ],
            83 => 
            [
                'id' => 3584,
                'name' => 'Kamuli',
                'code' => '38',
                'country_id' => 213,
            ],
            84 => 
            [
                'id' => 3585,
                'name' => 'Luwero',
                'code' => '70',
                'country_id' => 213,
            ],
            85 => 
            [
                'id' => 3586,
                'name' => 'Masaka',
                'code' => '71',
                'country_id' => 213,
            ],
            86 => 
            [
                'id' => 3587,
                'name' => 'Rakai',
                'code' => '61',
                'country_id' => 213,
            ],
            87 => 
            [
                'id' => 3588,
                'name' => 'Kalangala',
                'code' => '36',
                'country_id' => 213,
            ],
            88 => 
            [
                'id' => 3589,
                'name' => 'Kibale',
                'code' => '41',
                'country_id' => 213,
            ],
            89 => 
            [
                'id' => 3590,
                'name' => 'Bugiri',
                'code' => '66',
                'country_id' => 213,
            ],
            90 => 
            [
                'id' => 3591,
                'name' => 'Wakiso',
                'code' => '96',
                'country_id' => 213,
            ],
            91 => 
            [
                'id' => 3592,
                'name' => 'Kiboga',
                'code' => '42',
                'country_id' => 213,
            ],
            92 => 
            [
                'id' => 3593,
                'name' => 'Kampala',
                'code' => '37',
                'country_id' => 213,
            ],
            93 => 
            [
                'id' => 3594,
                'name' => 'Mayuge',
                'code' => '86',
                'country_id' => 213,
            ],
            94 => 
            [
                'id' => 3595,
                'name' => 'Kyenjojo',
                'code' => '85',
                'country_id' => 213,
            ],
            95 => 
            [
                'id' => 3596,
                'name' => 'Rukungiri',
                'code' => '93',
                'country_id' => 213,
            ],
            96 => 
            [
                'id' => 3597,
                'name' => 'Bushenyi',
                'code' => '29',
                'country_id' => 213,
            ],
            97 => 
            [
                'id' => 3598,
                'name' => 'Hoima',
                'code' => '31',
                'country_id' => 213,
            ],
            98 => 
            [
                'id' => 3599,
                'name' => 'Kamwenge',
                'code' => '81',
                'country_id' => 213,
            ],
            99 => 
            [
                'id' => 3600,
                'name' => 'Kabarole',
                'code' => '79',
                'country_id' => 213,
            ],
            100 => 
            [
                'id' => 3601,
                'name' => 'Sironko',
                'code' => '94',
                'country_id' => 213,
            ],
            101 => 
            [
                'id' => 3602,
                'name' => 'Kasese',
                'code' => '40',
                'country_id' => 213,
            ],
            102 => 
            [
                'id' => 3603,
                'name' => 'Sembabule',
                'code' => '74',
                'country_id' => 213,
            ],
            103 => 
            [
                'id' => 3604,
                'name' => '',
                'code' => '62',
                'country_id' => 213,
            ],
            104 => 
            [
                'id' => 3605,
                'name' => 'Jinja',
                'code' => '33',
                'country_id' => 213,
            ],
            105 => 
            [
                'id' => 3606,
                'name' => 'Busia',
                'code' => '67',
                'country_id' => 213,
            ],
            106 => 
            [
                'id' => 3607,
                'name' => 'Ntungamo',
                'code' => '59',
                'country_id' => 213,
            ],
            107 => 
            [
                'id' => 3608,
                'name' => 'Kanungu',
                'code' => '82',
                'country_id' => 213,
            ],
            108 => 
            [
                'id' => 3609,
                'name' => '',
                'code' => '35',
                'country_id' => 213,
            ],
            109 => 
            [
                'id' => 3610,
                'name' => 'Alabama',
                'code' => 'AL',
                'country_id' => 230,
            ],
            110 => 
            [
                'id' => 3611,
                'name' => 'Alaska',
                'code' => 'AK',
                'country_id' => 230,
            ],
            111 => 
            [
                'id' => 3612,
                'name' => 'American Samoa',
                'code' => 'AS',
                'country_id' => 230,
            ],
            112 => 
            [
                'id' => 3613,
                'name' => 'Arizona',
                'code' => 'AZ',
                'country_id' => 230,
            ],
            113 => 
            [
                'id' => 3614,
                'name' => 'Arkansas',
                'code' => 'AR',
                'country_id' => 230,
            ],
            114 => 
            [
                'id' => 3615,
                'name' => 'California',
                'code' => 'CA',
                'country_id' => 230,
            ],
            115 => 
            [
                'id' => 3616,
                'name' => 'Colorado',
                'code' => 'CO',
                'country_id' => 230,
            ],
            116 => 
            [
                'id' => 3617,
                'name' => 'Connecticut',
                'code' => 'CT',
                'country_id' => 230,
            ],
            117 => 
            [
                'id' => 3618,
                'name' => 'Delaware',
                'code' => 'DE',
                'country_id' => 230,
            ],
            118 => 
            [
                'id' => 3619,
                'name' => 'District of Columbia',
                'code' => 'DC',
                'country_id' => 230,
            ],
            119 => 
            [
                'id' => 3620,
                'name' => 'Florida',
                'code' => 'FL',
                'country_id' => 230,
            ],
            120 => 
            [
                'id' => 3621,
                'name' => 'Georgia',
                'code' => 'GA',
                'country_id' => 230,
            ],
            121 => 
            [
                'id' => 3622,
                'name' => 'Guam',
                'code' => 'GU',
                'country_id' => 230,
            ],
            122 => 
            [
                'id' => 3623,
                'name' => 'Hawaii',
                'code' => 'HI',
                'country_id' => 230,
            ],
            123 => 
            [
                'id' => 3624,
                'name' => 'Idaho',
                'code' => 'ID',
                'country_id' => 230,
            ],
            124 => 
            [
                'id' => 3625,
                'name' => 'Illinois',
                'code' => 'IL',
                'country_id' => 230,
            ],
            125 => 
            [
                'id' => 3626,
                'name' => 'Indiana',
                'code' => 'IN',
                'country_id' => 230,
            ],
            126 => 
            [
                'id' => 3627,
                'name' => 'Iowa',
                'code' => 'IA',
                'country_id' => 230,
            ],
            127 => 
            [
                'id' => 3628,
                'name' => 'Kansas',
                'code' => 'KS',
                'country_id' => 230,
            ],
            128 => 
            [
                'id' => 3629,
                'name' => 'Kentucky',
                'code' => 'KY',
                'country_id' => 230,
            ],
            129 => 
            [
                'id' => 3630,
                'name' => 'Louisiana',
                'code' => 'LA',
                'country_id' => 230,
            ],
            130 => 
            [
                'id' => 3631,
                'name' => 'Maine',
                'code' => 'ME',
                'country_id' => 230,
            ],
            131 => 
            [
                'id' => 3632,
                'name' => 'Marshall Islands',
                'code' => 'MH',
                'country_id' => 230,
            ],
            132 => 
            [
                'id' => 3633,
                'name' => 'Maryland',
                'code' => 'MD',
                'country_id' => 230,
            ],
            133 => 
            [
                'id' => 3634,
                'name' => 'Massachusetts',
                'code' => 'MA',
                'country_id' => 230,
            ],
            134 => 
            [
                'id' => 3635,
                'name' => 'Michigan',
                'code' => 'MI',
                'country_id' => 230,
            ],
            135 => 
            [
                'id' => 3636,
                'name' => 'Federated States of Micronesia',
                'code' => 'FM',
                'country_id' => 230,
            ],
            136 => 
            [
                'id' => 3637,
                'name' => 'Minnesota',
                'code' => 'MN',
                'country_id' => 230,
            ],
            137 => 
            [
                'id' => 3638,
                'name' => 'Mississippi',
                'code' => 'MS',
                'country_id' => 230,
            ],
            138 => 
            [
                'id' => 3639,
                'name' => 'Missouri',
                'code' => 'MO',
                'country_id' => 230,
            ],
            139 => 
            [
                'id' => 3640,
                'name' => 'Montana',
                'code' => 'MT',
                'country_id' => 230,
            ],
            140 => 
            [
                'id' => 3641,
                'name' => 'Nebraska',
                'code' => 'NE',
                'country_id' => 230,
            ],
            141 => 
            [
                'id' => 3642,
                'name' => 'Nevada',
                'code' => 'NV',
                'country_id' => 230,
            ],
            142 => 
            [
                'id' => 3643,
                'name' => 'New Hampshire',
                'code' => 'NH',
                'country_id' => 230,
            ],
            143 => 
            [
                'id' => 3644,
                'name' => 'New Jersey',
                'code' => 'NJ',
                'country_id' => 230,
            ],
            144 => 
            [
                'id' => 3645,
                'name' => 'New Mexico',
                'code' => 'NM',
                'country_id' => 230,
            ],
            145 => 
            [
                'id' => 3646,
                'name' => 'New York',
                'code' => 'NY',
                'country_id' => 230,
            ],
            146 => 
            [
                'id' => 3647,
                'name' => 'North Carolina',
                'code' => 'NC',
                'country_id' => 230,
            ],
            147 => 
            [
                'id' => 3648,
                'name' => 'North Dakota',
                'code' => 'ND',
                'country_id' => 230,
            ],
            148 => 
            [
                'id' => 3649,
                'name' => 'Northern Mariana Islands',
                'code' => 'MP',
                'country_id' => 230,
            ],
            149 => 
            [
                'id' => 3650,
                'name' => 'Ohio',
                'code' => 'OH',
                'country_id' => 230,
            ],
            150 => 
            [
                'id' => 3651,
                'name' => 'Oklahoma',
                'code' => 'OK',
                'country_id' => 230,
            ],
            151 => 
            [
                'id' => 3652,
                'name' => 'Oregon',
                'code' => 'OR',
                'country_id' => 230,
            ],
            152 => 
            [
                'id' => 3653,
                'name' => 'Palau',
                'code' => 'PW',
                'country_id' => 230,
            ],
            153 => 
            [
                'id' => 3654,
                'name' => 'Pennsylvania',
                'code' => 'PA',
                'country_id' => 230,
            ],
            154 => 
            [
                'id' => 3655,
                'name' => 'Puerto Rico',
                'code' => 'PR',
                'country_id' => 230,
            ],
            155 => 
            [
                'id' => 3656,
                'name' => 'Rhode Island',
                'code' => 'RI',
                'country_id' => 230,
            ],
            156 => 
            [
                'id' => 3657,
                'name' => 'South Carolina',
                'code' => 'SC',
                'country_id' => 230,
            ],
            157 => 
            [
                'id' => 3658,
                'name' => 'South Dakota',
                'code' => 'SD',
                'country_id' => 230,
            ],
            158 => 
            [
                'id' => 3659,
                'name' => 'Tennessee',
                'code' => 'TN',
                'country_id' => 230,
            ],
            159 => 
            [
                'id' => 3660,
                'name' => 'Texas',
                'code' => 'TX',
                'country_id' => 230,
            ],
            160 => 
            [
                'id' => 3661,
                'name' => 'Utah',
                'code' => 'UT',
                'country_id' => 230,
            ],
            161 => 
            [
                'id' => 3662,
                'name' => 'Vermont',
                'code' => 'VT',
                'country_id' => 230,
            ],
            162 => 
            [
                'id' => 3663,
                'name' => 'Virgin Islands',
                'code' => 'VI',
                'country_id' => 230,
            ],
            163 => 
            [
                'id' => 3664,
                'name' => 'Virginia',
                'code' => 'VA',
                'country_id' => 230,
            ],
            164 => 
            [
                'id' => 3665,
                'name' => 'Washington',
                'code' => 'WA',
                'country_id' => 230,
            ],
            165 => 
            [
                'id' => 3666,
                'name' => 'West Virginia',
                'code' => 'WV',
                'country_id' => 230,
            ],
            166 => 
            [
                'id' => 3667,
                'name' => 'Wisconsin',
                'code' => 'WI',
                'country_id' => 230,
            ],
            167 => 
            [
                'id' => 3668,
                'name' => 'Wyoming',
                'code' => 'WY',
                'country_id' => 230,
            ],
            168 => 
            [
                'id' => 3669,
                'name' => 'Rocha',
                'code' => '14',
                'country_id' => 214,
            ],
            169 => 
            [
                'id' => 3670,
                'name' => 'Florida',
                'code' => '07',
                'country_id' => 214,
            ],
            170 => 
            [
                'id' => 3671,
                'name' => 'Montevideo',
                'code' => '10',
                'country_id' => 214,
            ],
            171 => 
            [
                'id' => 3672,
                'name' => 'Rivera',
                'code' => '13',
                'country_id' => 214,
            ],
            172 => 
            [
                'id' => 3673,
                'name' => 'Cerro Largo',
                'code' => '03',
                'country_id' => 214,
            ],
            173 => 
            [
                'id' => 3674,
                'name' => 'Tacuarembo',
                'code' => '18',
                'country_id' => 214,
            ],
            174 => 
            [
                'id' => 3675,
                'name' => 'Lavalleja',
                'code' => '08',
                'country_id' => 214,
            ],
            175 => 
            [
                'id' => 3676,
                'name' => 'Treinta y Tres',
                'code' => '19',
                'country_id' => 214,
            ],
            176 => 
            [
                'id' => 3677,
                'name' => 'Soriano',
                'code' => '17',
                'country_id' => 214,
            ],
            177 => 
            [
                'id' => 3678,
                'name' => 'Durazno',
                'code' => '05',
                'country_id' => 214,
            ],
            178 => 
            [
                'id' => 3679,
                'name' => 'Canelones',
                'code' => '02',
                'country_id' => 214,
            ],
            179 => 
            [
                'id' => 3680,
                'name' => 'Flores',
                'code' => '06',
                'country_id' => 214,
            ],
            180 => 
            [
                'id' => 3681,
                'name' => 'Maldonado',
                'code' => '09',
                'country_id' => 214,
            ],
            181 => 
            [
                'id' => 3682,
                'name' => 'Salto',
                'code' => '15',
                'country_id' => 214,
            ],
            182 => 
            [
                'id' => 3683,
                'name' => 'Rio Negro',
                'code' => '12',
                'country_id' => 214,
            ],
            183 => 
            [
                'id' => 3684,
                'name' => 'Artigas',
                'code' => '01',
                'country_id' => 214,
            ],
            184 => 
            [
                'id' => 3685,
                'name' => 'Paysandu',
                'code' => '11',
                'country_id' => 214,
            ],
            185 => 
            [
                'id' => 3686,
                'name' => 'Colonia',
                'code' => '04',
                'country_id' => 214,
            ],
            186 => 
            [
                'id' => 3687,
                'name' => 'San Jose',
                'code' => '16',
                'country_id' => 214,
            ],
            187 => 
            [
                'id' => 3688,
                'name' => 'Khorazm',
                'code' => '05',
                'country_id' => 215,
            ],
            188 => 
            [
                'id' => 3689,
                'name' => 'Qashqadaryo',
                'code' => '08',
                'country_id' => 215,
            ],
            189 => 
            [
                'id' => 3690,
                'name' => 'Samarqand',
                'code' => '10',
                'country_id' => 215,
            ],
            190 => 
            [
                'id' => 3691,
                'name' => 'Andijon',
                'code' => '01',
                'country_id' => 215,
            ],
            191 => 
            [
                'id' => 3692,
                'name' => 'Jizzax',
                'code' => '15',
                'country_id' => 215,
            ],
            192 => 
            [
                'id' => 3693,
                'name' => 'Toshkent',
                'code' => '14',
                'country_id' => 215,
            ],
            193 => 
            [
                'id' => 3694,
                'name' => 'Surkhondaryo',
                'code' => '12',
                'country_id' => 215,
            ],
            194 => 
            [
                'id' => 3695,
                'name' => 'Qoraqalpoghiston',
                'code' => '09',
                'country_id' => 215,
            ],
            195 => 
            [
                'id' => 3696,
                'name' => 'Nawoiy',
                'code' => '07',
                'country_id' => 215,
            ],
            196 => 
            [
                'id' => 3697,
                'name' => '',
                'code' => '16',
                'country_id' => 215,
            ],
            197 => 
            [
                'id' => 3698,
                'name' => 'Namangan',
                'code' => '06',
                'country_id' => 215,
            ],
            198 => 
            [
                'id' => 3699,
                'name' => 'Farghona',
                'code' => '03',
                'country_id' => 215,
            ],
            199 => 
            [
                'id' => 3700,
                'name' => 'Bukhoro',
                'code' => '02',
                'country_id' => 215,
            ],
            200 => 
            [
                'id' => 3701,
                'name' => 'Toshkent',
                'code' => '13',
                'country_id' => 215,
            ],
            201 => 
            [
                'id' => 3702,
                'name' => '',
                'code' => '00',
                'country_id' => 215,
            ],
            202 => 
            [
                'id' => 3703,
                'name' => 'Charlotte',
                'code' => '01',
                'country_id' => 216,
            ],
            203 => 
            [
                'id' => 3704,
                'name' => 'Saint George',
                'code' => '04',
                'country_id' => 216,
            ],
            204 => 
            [
                'id' => 3705,
                'name' => 'Grenadines',
                'code' => '06',
                'country_id' => 216,
            ],
            205 => 
            [
                'id' => 3706,
                'name' => 'Saint Patrick',
                'code' => '05',
                'country_id' => 216,
            ],
            206 => 
            [
                'id' => 3707,
                'name' => 'Saint Andrew',
                'code' => '02',
                'country_id' => 216,
            ],
            207 => 
            [
                'id' => 3708,
                'name' => 'Saint David',
                'code' => '03',
                'country_id' => 216,
            ],
            208 => 
            [
                'id' => 3709,
                'name' => 'Falcon',
                'code' => '11',
                'country_id' => 217,
            ],
            209 => 
            [
                'id' => 3710,
                'name' => 'Apure',
                'code' => '03',
                'country_id' => 217,
            ],
            210 => 
            [
                'id' => 3711,
                'name' => 'Bolivar',
                'code' => '06',
                'country_id' => 217,
            ],
            211 => 
            [
                'id' => 3712,
                'name' => 'Tachira',
                'code' => '20',
                'country_id' => 217,
            ],
            212 => 
            [
                'id' => 3713,
                'name' => 'Miranda',
                'code' => '15',
                'country_id' => 217,
            ],
            213 => 
            [
                'id' => 3714,
                'name' => 'Guarico',
                'code' => '12',
                'country_id' => 217,
            ],
            214 => 
            [
                'id' => 3715,
                'name' => 'Anzoategui',
                'code' => '02',
                'country_id' => 217,
            ],
            215 => 
            [
                'id' => 3716,
                'name' => 'Nueva Esparta',
                'code' => '17',
                'country_id' => 217,
            ],
            216 => 
            [
                'id' => 3717,
                'name' => 'Portuguesa',
                'code' => '18',
                'country_id' => 217,
            ],
            217 => 
            [
                'id' => 3718,
                'name' => 'Sucre',
                'code' => '19',
                'country_id' => 217,
            ],
            218 => 
            [
                'id' => 3719,
                'name' => 'Barinas',
                'code' => '05',
                'country_id' => 217,
            ],
            219 => 
            [
                'id' => 3720,
                'name' => 'Lara',
                'code' => '13',
                'country_id' => 217,
            ],
            220 => 
            [
                'id' => 3721,
                'name' => 'Zulia',
                'code' => '23',
                'country_id' => 217,
            ],
            221 => 
            [
                'id' => 3722,
                'name' => 'Merida',
                'code' => '14',
                'country_id' => 217,
            ],
            222 => 
            [
                'id' => 3723,
                'name' => 'Carabobo',
                'code' => '07',
                'country_id' => 217,
            ],
            223 => 
            [
                'id' => 3724,
                'name' => 'Cojedes',
                'code' => '08',
                'country_id' => 217,
            ],
            224 => 
            [
                'id' => 3725,
                'name' => 'Aragua',
                'code' => '04',
                'country_id' => 217,
            ],
            225 => 
            [
                'id' => 3726,
                'name' => 'Yaracuy',
                'code' => '22',
                'country_id' => 217,
            ],
            226 => 
            [
                'id' => 3727,
                'name' => 'Amazonas',
                'code' => '01',
                'country_id' => 217,
            ],
            227 => 
            [
                'id' => 3728,
                'name' => 'Monagas',
                'code' => '16',
                'country_id' => 217,
            ],
            228 => 
            [
                'id' => 3729,
                'name' => 'Trujillo',
                'code' => '21',
                'country_id' => 217,
            ],
            229 => 
            [
                'id' => 3730,
                'name' => 'Vargas',
                'code' => '26',
                'country_id' => 217,
            ],
            230 => 
            [
                'id' => 3731,
                'name' => '',
                'code' => '99',
                'country_id' => 217,
            ],
            231 => 
            [
                'id' => 3732,
                'name' => 'Delta Amacuro',
                'code' => '09',
                'country_id' => 217,
            ],
            232 => 
            [
                'id' => 3733,
                'name' => 'Distrito Federal',
                'code' => '25',
                'country_id' => 217,
            ],
            233 => 
            [
                'id' => 3734,
                'name' => 'Dependencias Federales',
                'code' => '24',
                'country_id' => 217,
            ],
            234 => 
            [
                'id' => 3735,
                'name' => '',
                'code' => '00',
                'country_id' => 218,
            ],
            235 => 
            [
                'id' => 3736,
                'name' => '',
                'code' => '00',
                'country_id' => 219,
            ],
            236 => 
            [
                'id' => 3737,
                'name' => '',
                'code' => '36',
                'country_id' => 220,
            ],
            237 => 
            [
                'id' => 3738,
                'name' => '',
                'code' => '29',
                'country_id' => 220,
            ],
            238 => 
            [
                'id' => 3739,
                'name' => '',
                'code' => '04',
                'country_id' => 220,
            ],
            239 => 
            [
                'id' => 3740,
                'name' => '',
                'code' => '22',
                'country_id' => 220,
            ],
            240 => 
            [
                'id' => 3741,
                'name' => '',
                'code' => '07',
                'country_id' => 220,
            ],
            241 => 
            [
                'id' => 3742,
                'name' => 'Thanh Hoa',
                'code' => '34',
                'country_id' => 220,
            ],
            242 => 
            [
                'id' => 3743,
                'name' => '',
                'code' => '11',
                'country_id' => 220,
            ],
            243 => 
            [
                'id' => 3744,
                'name' => '',
                'code' => '15',
                'country_id' => 220,
            ],
            244 => 
            [
                'id' => 3745,
                'name' => 'Quang Nam',
                'code' => '84',
                'country_id' => 220,
            ],
            245 => 
            [
                'id' => 3746,
                'name' => 'Son La',
                'code' => '32',
                'country_id' => 220,
            ],
            246 => 
            [
                'id' => 3747,
                'name' => '',
                'code' => '06',
                'country_id' => 220,
            ],
            247 => 
            [
                'id' => 3748,
                'name' => '',
                'code' => '19',
                'country_id' => 220,
            ],
            248 => 
            [
                'id' => 3749,
                'name' => '',
                'code' => '38',
                'country_id' => 220,
            ],
            249 => 
            [
                'id' => 3750,
                'name' => '',
                'code' => '26',
                'country_id' => 220,
            ],
            250 => 
            [
                'id' => 3751,
                'name' => 'Tay Ninh',
                'code' => '33',
                'country_id' => 220,
            ],
            251 => 
            [
                'id' => 3752,
                'name' => '',
                'code' => '27',
                'country_id' => 220,
            ],
            252 => 
            [
                'id' => 3753,
                'name' => 'Thai Binh',
                'code' => '35',
                'country_id' => 220,
            ],
            253 => 
            [
                'id' => 3754,
                'name' => 'Kien Giang',
                'code' => '21',
                'country_id' => 220,
            ],
            254 => 
            [
                'id' => 3755,
                'name' => 'Dong Thap',
                'code' => '09',
                'country_id' => 220,
            ],
            255 => 
            [
                'id' => 3756,
                'name' => '',
                'code' => '10',
                'country_id' => 220,
            ],
            256 => 
            [
                'id' => 3757,
                'name' => '',
                'code' => '31',
                'country_id' => 220,
            ],
            257 => 
            [
                'id' => 3758,
                'name' => '',
                'code' => '41',
                'country_id' => 220,
            ],
            258 => 
            [
                'id' => 3759,
                'name' => '',
                'code' => '28',
                'country_id' => 220,
            ],
            259 => 
            [
                'id' => 3760,
                'name' => '',
                'code' => '12',
                'country_id' => 220,
            ],
            260 => 
            [
                'id' => 3761,
                'name' => 'Soc Trang',
                'code' => '65',
                'country_id' => 220,
            ],
            261 => 
            [
                'id' => 3762,
                'name' => '',
                'code' => '16',
                'country_id' => 220,
            ],
            262 => 
            [
                'id' => 3763,
                'name' => '',
                'code' => '14',
                'country_id' => 220,
            ],
            263 => 
            [
                'id' => 3764,
                'name' => 'Ben Tre',
                'code' => '03',
                'country_id' => 220,
            ],
            264 => 
            [
                'id' => 3765,
                'name' => 'Ho Chi Minh',
                'code' => '20',
                'country_id' => 220,
            ],
            265 => 
            [
                'id' => 3766,
                'name' => 'Tra Vinh',
                'code' => '67',
                'country_id' => 220,
            ],
            266 => 
            [
                'id' => 3767,
                'name' => 'Hai Phong',
                'code' => '13',
                'country_id' => 220,
            ],
            267 => 
            [
                'id' => 3768,
                'name' => 'Cao Bang',
                'code' => '05',
                'country_id' => 220,
            ],
            268 => 
            [
                'id' => 3769,
                'name' => 'An Giang',
                'code' => '01',
                'country_id' => 220,
            ],
            269 => 
            [
                'id' => 3770,
                'name' => '',
                'code' => '02',
                'country_id' => 220,
            ],
            270 => 
            [
                'id' => 3771,
                'name' => '',
                'code' => '40',
                'country_id' => 220,
            ],
            271 => 
            [
                'id' => 3772,
                'name' => 'Nghe An',
                'code' => '58',
                'country_id' => 220,
            ],
            272 => 
            [
                'id' => 3773,
                'name' => 'Gia Lai',
                'code' => '49',
                'country_id' => 220,
            ],
            273 => 
            [
                'id' => 3774,
                'name' => 'Lam Dong',
                'code' => '23',
                'country_id' => 220,
            ],
            274 => 
            [
                'id' => 3775,
                'name' => 'Binh Dinh',
                'code' => '46',
                'country_id' => 220,
            ],
            275 => 
            [
                'id' => 3776,
                'name' => 'Binh Phuoc',
                'code' => '76',
                'country_id' => 220,
            ],
            276 => 
            [
                'id' => 3777,
                'name' => 'Lang Son',
                'code' => '39',
                'country_id' => 220,
            ],
            277 => 
            [
                'id' => 3778,
                'name' => 'Tien Giang',
                'code' => '37',
                'country_id' => 220,
            ],
            278 => 
            [
                'id' => 3779,
                'name' => 'Long An',
                'code' => '24',
                'country_id' => 220,
            ],
            279 => 
            [
                'id' => 3780,
                'name' => 'Ninh Thuan',
                'code' => '60',
                'country_id' => 220,
            ],
            280 => 
            [
                'id' => 3781,
                'name' => 'Quang Ninh',
                'code' => '30',
                'country_id' => 220,
            ],
            281 => 
            [
                'id' => 3782,
                'name' => 'Bac Lieu',
                'code' => '73',
                'country_id' => 220,
            ],
            282 => 
            [
                'id' => 3783,
                'name' => 'Ca Mau',
                'code' => '77',
                'country_id' => 220,
            ],
            283 => 
            [
                'id' => 3784,
                'name' => '',
                'code' => '25',
                'country_id' => 220,
            ],
            284 => 
            [
                'id' => 3785,
                'name' => '',
                'code' => '48',
                'country_id' => 220,
            ],
            285 => 
            [
                'id' => 3786,
                'name' => 'Binh Duong',
                'code' => '75',
                'country_id' => 220,
            ],
            286 => 
            [
                'id' => 3787,
                'name' => 'Binh Thuan',
                'code' => '47',
                'country_id' => 220,
            ],
            287 => 
            [
                'id' => 3788,
                'name' => 'Vinh Long',
                'code' => '69',
                'country_id' => 220,
            ],
            288 => 
            [
                'id' => 3789,
                'name' => 'Dong Nai',
                'code' => '43',
                'country_id' => 220,
            ],
            289 => 
            [
                'id' => 3790,
                'name' => '',
                'code' => '17',
                'country_id' => 220,
            ],
            290 => 
            [
                'id' => 3791,
                'name' => 'Bac Kan',
                'code' => '72',
                'country_id' => 220,
            ],
            291 => 
            [
                'id' => 3792,
                'name' => 'Bac Giang',
                'code' => '71',
                'country_id' => 220,
            ],
            292 => 
            [
                'id' => 3793,
                'name' => 'Thua Thien-Hue',
                'code' => '66',
                'country_id' => 220,
            ],
            293 => 
            [
                'id' => 3794,
                'name' => 'Bac Ninh',
                'code' => '74',
                'country_id' => 220,
            ],
            294 => 
            [
                'id' => 3795,
                'name' => 'Ha Giang',
                'code' => '50',
                'country_id' => 220,
            ],
            295 => 
            [
                'id' => 3796,
                'name' => 'Tuyen Quang',
                'code' => '68',
                'country_id' => 220,
            ],
            296 => 
            [
                'id' => 3797,
                'name' => 'Thai Nguyen',
                'code' => '85',
                'country_id' => 220,
            ],
            297 => 
            [
                'id' => 3798,
                'name' => 'Da Nang',
                'code' => '78',
                'country_id' => 220,
            ],
            298 => 
            [
                'id' => 3799,
                'name' => 'Khanh Hoa',
                'code' => '54',
                'country_id' => 220,
            ],
            299 => 
            [
                'id' => 3800,
                'name' => 'Ba Ria-Vung Tau',
                'code' => '45',
                'country_id' => 220,
            ],
            300 => 
            [
                'id' => 3801,
                'name' => 'Quang Ngai',
                'code' => '63',
                'country_id' => 220,
            ],
            301 => 
            [
                'id' => 3802,
                'name' => '',
                'code' => '56',
                'country_id' => 220,
            ],
            302 => 
            [
                'id' => 3803,
                'name' => 'Ha Nam',
                'code' => '80',
                'country_id' => 220,
            ],
            303 => 
            [
                'id' => 3804,
                'name' => 'Phu Yen',
                'code' => '61',
                'country_id' => 220,
            ],
            304 => 
            [
                'id' => 3805,
                'name' => 'Quang Binh',
                'code' => '62',
                'country_id' => 220,
            ],
            305 => 
            [
                'id' => 3806,
                'name' => 'Phu Tho',
                'code' => '83',
                'country_id' => 220,
            ],
            306 => 
            [
                'id' => 3807,
                'name' => 'Quang Tri',
                'code' => '64',
                'country_id' => 220,
            ],
            307 => 
            [
                'id' => 3808,
                'name' => 'Ha Tinh',
                'code' => '52',
                'country_id' => 220,
            ],
            308 => 
            [
                'id' => 3809,
                'name' => 'Kon Tum',
                'code' => '55',
                'country_id' => 220,
            ],
            309 => 
            [
                'id' => 3810,
                'name' => '',
                'code' => '51',
                'country_id' => 220,
            ],
            310 => 
            [
                'id' => 3811,
                'name' => 'Yen Bai',
                'code' => '70',
                'country_id' => 220,
            ],
            311 => 
            [
                'id' => 3812,
                'name' => 'Ninh Binh',
                'code' => '59',
                'country_id' => 220,
            ],
            312 => 
            [
                'id' => 3813,
                'name' => 'Nam Dinh',
                'code' => '82',
                'country_id' => 220,
            ],
            313 => 
            [
                'id' => 3814,
                'name' => 'Hai Duong',
                'code' => '79',
                'country_id' => 220,
            ],
            314 => 
            [
                'id' => 3815,
                'name' => 'Ha Noi',
                'code' => '44',
                'country_id' => 220,
            ],
            315 => 
            [
                'id' => 3816,
                'name' => 'Hoa Binh',
                'code' => '53',
                'country_id' => 220,
            ],
            316 => 
            [
                'id' => 3817,
                'name' => 'Hung Yen',
                'code' => '81',
                'country_id' => 220,
            ],
            317 => 
            [
                'id' => 3818,
                'name' => 'Vinh Phuc',
                'code' => '86',
                'country_id' => 220,
            ],
            318 => 
            [
                'id' => 3819,
                'name' => 'Sanma',
                'code' => '13',
                'country_id' => 221,
            ],
            319 => 
            [
                'id' => 3820,
                'name' => 'Aoba',
                'code' => '06',
                'country_id' => 221,
            ],
            320 => 
            [
                'id' => 3821,
                'name' => 'Shepherd',
                'code' => '14',
                'country_id' => 221,
            ],
            321 => 
            [
                'id' => 3822,
                'name' => 'Malakula',
                'code' => '10',
                'country_id' => 221,
            ],
            322 => 
            [
                'id' => 3823,
                'name' => 'Pentecote',
                'code' => '12',
                'country_id' => 221,
            ],
            323 => 
            [
                'id' => 3824,
                'name' => 'Torba',
                'code' => '07',
                'country_id' => 221,
            ],
            324 => 
            [
                'id' => 3825,
                'name' => 'Efate',
                'code' => '08',
                'country_id' => 221,
            ],
            325 => 
            [
                'id' => 3826,
                'name' => 'Tafea',
                'code' => '15',
                'country_id' => 221,
            ],
            326 => 
            [
                'id' => 3827,
                'name' => 'Ambrym',
                'code' => '05',
                'country_id' => 221,
            ],
            327 => 
            [
                'id' => 3828,
                'name' => 'Epi',
                'code' => '09',
                'country_id' => 221,
            ],
            328 => 
            [
                'id' => 3829,
                'name' => 'Paama',
                'code' => '11',
                'country_id' => 221,
            ],
            329 => 
            [
                'id' => 3830,
                'name' => '',
                'code' => '00',
                'country_id' => 222,
            ],
            330 => 
            [
                'id' => 3831,
                'name' => '',
                'code' => '00',
                'country_id' => 223,
            ],
            331 => 
            [
                'id' => 3832,
                'name' => 'Lahij',
                'code' => '06',
                'country_id' => 224,
            ],
            332 => 
            [
                'id' => 3833,
                'name' => 'Sa\'dah',
                'code' => '15',
                'country_id' => 224,
            ],
            333 => 
            [
                'id' => 3834,
                'name' => 'Al Hudaydah',
                'code' => '08',
                'country_id' => 224,
            ],
            334 => 
            [
                'id' => 3835,
                'name' => 'Ma\'rib',
                'code' => '14',
                'country_id' => 224,
            ],
            335 => 
            [
                'id' => 3836,
                'name' => 'Al Bayda\'',
                'code' => '07',
                'country_id' => 224,
            ],
            336 => 
            [
                'id' => 3837,
                'name' => 'Dhamar',
                'code' => '11',
                'country_id' => 224,
            ],
            337 => 
            [
                'id' => 3838,
                'name' => 'San\'a\'',
                'code' => '16',
                'country_id' => 224,
            ],
            338 => 
            [
                'id' => 3839,
                'name' => 'Al Mahrah',
                'code' => '03',
                'country_id' => 224,
            ],
            339 => 
            [
                'id' => 3840,
                'name' => 'Hadramawt',
                'code' => '04',
                'country_id' => 224,
            ],
            340 => 
            [
                'id' => 3841,
                'name' => 'Taizz',
                'code' => '17',
                'country_id' => 224,
            ],
            341 => 
            [
                'id' => 3842,
                'name' => 'Hajjah',
                'code' => '12',
                'country_id' => 224,
            ],
            342 => 
            [
                'id' => 3843,
                'name' => 'Abyan',
                'code' => '01',
                'country_id' => 224,
            ],
            343 => 
            [
                'id' => 3844,
                'name' => 'Ibb',
                'code' => '13',
                'country_id' => 224,
            ],
            344 => 
            [
                'id' => 3845,
                'name' => 'Adan',
                'code' => '02',
                'country_id' => 224,
            ],
            345 => 
            [
                'id' => 3846,
                'name' => 'Al Mahwit',
                'code' => '10',
                'country_id' => 224,
            ],
            346 => 
            [
                'id' => 3847,
                'name' => 'Al Jawf',
                'code' => '09',
                'country_id' => 224,
            ],
            347 => 
            [
                'id' => 3848,
                'name' => '',
                'code' => '00',
                'country_id' => 224,
            ],
            348 => 
            [
                'id' => 3849,
                'name' => '',
                'code' => '00',
                'country_id' => 225,
            ],
            349 => 
            [
                'id' => 3850,
                'name' => 'Western Cape',
                'code' => '11',
                'country_id' => 226,
            ],
            350 => 
            [
                'id' => 3851,
                'name' => 'Eastern Cape',
                'code' => '05',
                'country_id' => 226,
            ],
            351 => 
            [
                'id' => 3852,
                'name' => 'Mpumalanga',
                'code' => '07',
                'country_id' => 226,
            ],
            352 => 
            [
                'id' => 3853,
                'name' => 'Free State',
                'code' => '03',
                'country_id' => 226,
            ],
            353 => 
            [
                'id' => 3854,
                'name' => 'North-West',
                'code' => '10',
                'country_id' => 226,
            ],
            354 => 
            [
                'id' => 3855,
                'name' => 'Limpopo',
                'code' => '09',
                'country_id' => 226,
            ],
            355 => 
            [
                'id' => 3856,
                'name' => 'KwaZulu-Natal',
                'code' => '02',
                'country_id' => 226,
            ],
            356 => 
            [
                'id' => 3857,
                'name' => 'North-Western Province',
                'code' => '01',
                'country_id' => 226,
            ],
            357 => 
            [
                'id' => 3858,
                'name' => 'Gauteng',
                'code' => '06',
                'country_id' => 226,
            ],
            358 => 
            [
                'id' => 3859,
                'name' => 'Northern Cape',
                'code' => '08',
                'country_id' => 226,
            ],
            359 => 
            [
                'id' => 3860,
                'name' => '',
                'code' => '04',
                'country_id' => 226,
            ],
            360 => 
            [
                'id' => 3861,
                'name' => 'Southern',
                'code' => '07',
                'country_id' => 227,
            ],
            361 => 
            [
                'id' => 3862,
                'name' => 'North-Western',
                'code' => '06',
                'country_id' => 227,
            ],
            362 => 
            [
                'id' => 3863,
                'name' => 'Northern',
                'code' => '05',
                'country_id' => 227,
            ],
            363 => 
            [
                'id' => 3864,
                'name' => 'Western',
                'code' => '01',
                'country_id' => 227,
            ],
            364 => 
            [
                'id' => 3865,
                'name' => 'Eastern',
                'code' => '03',
                'country_id' => 227,
            ],
            365 => 
            [
                'id' => 3866,
                'name' => 'Copperbelt',
                'code' => '08',
                'country_id' => 227,
            ],
            366 => 
            [
                'id' => 3867,
                'name' => 'Luapula',
                'code' => '04',
                'country_id' => 227,
            ],
            367 => 
            [
                'id' => 3868,
                'name' => 'Central',
                'code' => '02',
                'country_id' => 227,
            ],
            368 => 
            [
                'id' => 3869,
                'name' => 'Lusaka',
                'code' => '09',
                'country_id' => 227,
            ],
            369 => 
            [
                'id' => 3870,
                'name' => '',
                'code' => '02',
                'country_id' => 228,
            ],
            370 => 
            [
                'id' => 3871,
                'name' => '',
                'code' => '09',
                'country_id' => 228,
            ],
            371 => 
            [
                'id' => 3872,
                'name' => '',
                'code' => '00',
                'country_id' => 228,
            ],
            372 => 
            [
                'id' => 3873,
                'name' => '',
                'code' => '11',
                'country_id' => 228,
            ],
            373 => 
            [
                'id' => 3874,
                'name' => '',
                'code' => '07',
                'country_id' => 228,
            ],
            374 => 
            [
                'id' => 3875,
                'name' => '',
                'code' => '10',
                'country_id' => 228,
            ],
            375 => 
            [
                'id' => 3876,
                'name' => '',
                'code' => '01',
                'country_id' => 228,
            ],
            376 => 
            [
                'id' => 3877,
                'name' => '',
                'code' => '03',
                'country_id' => 228,
            ],
            377 => 
            [
                'id' => 3878,
                'name' => '',
                'code' => '05',
                'country_id' => 228,
            ],
            378 => 
            [
                'id' => 3879,
                'name' => '',
                'code' => '12',
                'country_id' => 228,
            ],
            379 => 
            [
                'id' => 3880,
                'name' => '',
                'code' => '08',
                'country_id' => 228,
            ],
            380 => 
            [
                'id' => 3881,
                'name' => '',
                'code' => '04',
                'country_id' => 228,
            ],
            381 => 
            [
                'id' => 3882,
                'name' => '',
                'code' => '06',
                'country_id' => 228,
            ],
            382 => 
            [
                'id' => 3883,
                'name' => 'Matabeleland North',
                'code' => '06',
                'country_id' => 229,
            ],
            383 => 
            [
                'id' => 3884,
                'name' => 'Mashonaland East',
                'code' => '04',
                'country_id' => 229,
            ],
            384 => 
            [
                'id' => 3885,
                'name' => 'Mashonaland Central',
                'code' => '03',
                'country_id' => 229,
            ],
            385 => 
            [
                'id' => 3886,
                'name' => 'Matabeleland South',
                'code' => '07',
                'country_id' => 229,
            ],
            386 => 
            [
                'id' => 3887,
                'name' => '',
                'code' => '00',
                'country_id' => 229,
            ],
            387 => 
            [
                'id' => 3888,
                'name' => 'Masvingo',
                'code' => '08',
                'country_id' => 229,
            ],
        ]);       
    }
}