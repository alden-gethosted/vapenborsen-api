<?php

namespace Database\Seeders;

use App\Models\ProductBrand;
use App\Models\ProductCategories;
use App\Models\ProductType;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategories::upsert([
            ['id' => 1,'name' => 'Weapons', 'parents_id' => null],
            ['id' => 2,'name' => 'Field & Weapon Depot', 'parents_id' => null],
            ['id' => 3,'name' => 'Weapon Parts', 'parents_id' => null],
            ['id' => 4,'name' => 'Mufflers', 'parents_id' => null],
            ['id' => 5,'name' => 'Optics', 'parents_id' => null],
            ['id' => 6,'name' => 'Weapons & Storage', 'parents_id' => null],
            ['id' => 7,'name' => 'Charging Presses & Tumblers', 'parents_id' => null],
            ['id' => 8,'name' => 'Rifles', 'parents_id' => 1],
            ['id' => 9,'name' => 'Shotguns', 'parents_id' => 1],
            ['id' => 10,'name' => 'Pistol', 'parents_id' => 1],
            ['id' => 11,'name' => 'Buer', 'parents_id' => 1],
            ['id' => 12,'name' => 'Air Weapons', 'parents_id' => 1],
            ['id' => 13,'name' => 'Accessories', 'parents_id' => 2],
            ['id' => 14,'name' => 'Collect & Antique', 'parents_id' => 2]
        ], [], ['id']);

        ProductType::upsert([
            ['id' => 1,'name' => 'Drilling', 'product_categories_id' => 8],
            ['id' => 2,'name' => 'Combi', 'product_categories_id' => 8],
            ['id' => 3,'name' => 'Double rifle', 'product_categories_id' => 8],
            ['id' => 4,'name' => 'Semi-automatic', 'product_categories_id' => 8],
            ['id' => 5,'name' => 'Bolt Action', 'product_categories_id' => 8],
            ['id' => 6,'name' => 'Single shot', 'product_categories_id' => 8],
            ['id' => 7,'name' => 'Lever Action', 'product_categories_id' => 8],
            ['id' => 8,'name' => 'Biathlete', 'product_categories_id' => 8],
            ['id' => 9,'name' => 'Salon Rifles-Semi-automatic-Single Shot-Bolt Action', 'product_categories_id' => 8],
            ['id' => 10,'name' => 'Half auto', 'product_categories_id' => 9],
            ['id' => 11,'name' => 'Over Under', 'product_categories_id' => 9],
            ['id' => 12,'name' => 'Side by Side', 'product_categories_id' => 9],
            ['id' => 13,'name' => 'Single shot', 'product_categories_id' => 9],
            ['id' => 14,'name' => 'Skeet-Trap', 'product_categories_id' => 9],
            ['id' => 15,'name' => 'Competition', 'product_categories_id' => 10],
            ['id' => 16,'name' => 'Semiauto', 'product_categories_id' => 10],
            ['id' => 17,'name' => 'Revolver', 'product_categories_id' => 10],
            ['id' => 18,'name' => 'Single shot', 'product_categories_id' => 10],
            ['id' => 19,'name' => 'Concave arches', 'product_categories_id' => 11],
            ['id' => 20,'name' => 'Competition booth', 'product_categories_id' => 11],
            ['id' => 21,'name' => 'Competition', 'product_categories_id' => 12],
            ['id' => 22,'name' => 'Semiauto', 'product_categories_id' => 12],
            ['id' => 23,'name' => 'Revolver', 'product_categories_id' => 12],
            ['id' => 24,'name' => 'Single shot', 'product_categories_id' => 12],
            ['id' => 25,'name' => 'Competition', 'product_categories_id' => 14],
            ['id' => 26,'name' => 'Semiauto', 'product_categories_id' => 14],
            ['id' => 27,'name' => 'Revolver', 'product_categories_id' => 14],
            ['id' => 28,'name' => 'Single shot', 'product_categories_id' => 14]
        ], [], ['id']);

        ProductBrand::upsert([
            ['id' => 1,'name' => 'Sako', 'product_categories_id' => 8],
            ['id' => 2,'name' => 'Schults & Larsen', 'product_categories_id' => 8],
            ['id' => 3,'name' => 'Blows', 'product_categories_id' => 8],
            ['id' => 4,'name' => 'Sauer', 'product_categories_id' => 8],
            ['id' => 5,'name' => 'Browning Remington', 'product_categories_id' => 8],
            ['id' => 6,'name' => 'Mauser', 'product_categories_id' => 8],
            ['id' => 7,'name' => 'Howa', 'product_categories_id' => 8],
            ['id' => 8,'name' => 'Weatherby', 'product_categories_id' => 8],
            ['id' => 9,'name' => 'Benelli', 'product_categories_id' => 8],
            ['id' => 10,'name' => 'Ruger', 'product_categories_id' => 8],
            ['id' => 11,'name' => 'Rossler', 'product_categories_id' => 8],
            ['id' => 12,'name' => 'Marlin', 'product_categories_id' => 8],
            ['id' => 13,'name' => 'Barett', 'product_categories_id' => 8],
            ['id' => 14,'name' => 'Christensen Arms', 'product_categories_id' => 8],
            ['id' => 15,'name' => 'Steyr Mannlicher', 'product_categories_id' => 8],
            ['id' => 16,'name' => 'Savage', 'product_categories_id' => 8],
            ['id' => 17,'name' => 'CZ', 'product_categories_id' => 8],
            ['id' => 18,'name' => 'Fabarm', 'product_categories_id' => 8],
            ['id' => 19,'name' => 'Heckler & Koch', 'product_categories_id' => 8],
            ['id' => 20,'name' => 'Hey', 'product_categories_id' => 8],
            ['id' => 21,'name' => 'Kimber', 'product_categories_id' => 8],
            ['id' => 22,'name' => 'Kimber', 'product_categories_id' => 8],
            ['id' => 23,'name' => 'Sabatti', 'product_categories_id' => 8],
            ['id' => 24,'name' => 'Zastava', 'product_categories_id' => 8],
            ['id' => 25,'name' => 'Incense', 'product_categories_id' => 8],
            ['id' => 26,'name' => 'Seekins Precision', 'product_categories_id' => 8],
            ['id' => 27,'name' => 'Protection', 'product_categories_id' => 8],
            ['id' => 28,'name' => 'Winchester', 'product_categories_id' => 9],
            ['id' => 29,'name' => 'Benelli', 'product_categories_id' => 9],
            ['id' => 30,'name' => 'Browning', 'product_categories_id' => 9],
            ['id' => 31,'name' => 'Beretta', 'product_categories_id' => 9],
            ['id' => 32,'name' => 'Franchi', 'product_categories_id' => 9],
            ['id' => 33,'name' => 'Remington', 'product_categories_id' => 9],
            ['id' => 34,'name' => 'Bettinsoli', 'product_categories_id' => 9],
            ['id' => 35,'name' => 'Huglu', 'product_categories_id' => 9],
            ['id' => 36,'name' => 'Darwin', 'product_categories_id' => 9],
            ['id' => 37,'name' => 'Churchill', 'product_categories_id' => 9],
            ['id' => 38,'name' => 'Altay', 'product_categories_id' => 9],
            ['id' => 39,'name' => 'Arrieta', 'product_categories_id' => 9],
            ['id' => 40,'name' => 'FAIR', 'product_categories_id' => 9],
            ['id' => 41,'name' => 'Merkel', 'product_categories_id' => 9],
            ['id' => 42,'name' => 'Fabarm', 'product_categories_id' => 9],
            ['id' => 43,'name' => 'Mossberg', 'product_categories_id' => 9],
            ['id' => 44,'name' => 'Blows', 'product_categories_id' => 9],
            ['id' => 45,'name' => 'Kreighoff', 'product_categories_id' => 9],
            ['id' => 46,'name' => 'Girsan', 'product_categories_id' => 9],
            ['id' => 47,'name' => 'Sabatti', 'product_categories_id' => 9],
            ['id' => 48,'name' => 'Sauer & Shon', 'product_categories_id' => 9],
            ['id' => 49,'name' => 'Pallas', 'product_categories_id' => 9],
            ['id' => 50,'name' => 'Find Classic', 'product_categories_id' => 9],
            ['id' => 51,'name' => 'Classic', 'product_categories_id' => 9],
            ['id' => 52,'name' => 'Baikal', 'product_categories_id' => 9],
            ['id' => 53,'name' => 'ATA Arms', 'product_categories_id' => 9],
            ['id' => 54,'name' => 'Smith & Wesson', 'product_categories_id' => 9],
            ['id' => 55,'name' => 'Beretta', 'product_categories_id' => 10],
            ['id' => 56,'name' => 'Browning', 'product_categories_id' => 10],
            ['id' => 57,'name' => 'Glock', 'product_categories_id' => 10],
            ['id' => 58,'name' => 'Smith & Wesson', 'product_categories_id' => 10],
            ['id' => 59,'name' => 'Ruger', 'product_categories_id' => 10],
            ['id' => 60,'name' => 'BUL', 'product_categories_id' => 10],
            ['id' => 61,'name' => 'Walther', 'product_categories_id' => 10],
            ['id' => 62,'name' => 'Remington', 'product_categories_id' => 10],
            ['id' => 63,'name' => 'Desert Eagle', 'product_categories_id' => 10],
            ['id' => 64,'name' => 'Kimber', 'product_categories_id' => 10],
            ['id' => 65,'name' => 'CZ', 'product_categories_id' => 10],
            ['id' => 66,'name' => 'GSG', 'product_categories_id' => 10],
            ['id' => 67,'name' => 'Hammerli', 'product_categories_id' => 10],
            ['id' => 68,'name' => 'Sig Sauer', 'product_categories_id' => 10],
            ['id' => 69,'name' => 'Springfield Armory', 'product_categories_id' => 10],
            ['id' => 70,'name' => 'Tanfoglio', 'product_categories_id' => 10],
            ['id' => 71,'name' => 'Beretta', 'product_categories_id' => 12],
            ['id' => 72,'name' => 'Browning', 'product_categories_id' => 12],
            ['id' => 73,'name' => 'Glock', 'product_categories_id' => 12],
            ['id' => 74,'name' => 'Smith & Wesson', 'product_categories_id' => 12],
            ['id' => 75,'name' => 'Ruger', 'product_categories_id' => 12],
            ['id' => 76,'name' => 'BUL', 'product_categories_id' => 12],
            ['id' => 77,'name' => 'Walther', 'product_categories_id' => 12],
            ['id' => 78,'name' => 'Remington', 'product_categories_id' => 12],
            ['id' => 79,'name' => 'Desert Eagle', 'product_categories_id' => 12],
            ['id' => 80,'name' => 'Kimber', 'product_categories_id' => 12],
            ['id' => 81,'name' => 'CZ', 'product_categories_id' => 12],
            ['id' => 82,'name' => 'GSG', 'product_categories_id' => 12],
            ['id' => 83,'name' => 'Hammerli', 'product_categories_id' => 12],
            ['id' => 84,'name' => 'Sig Sauer', 'product_categories_id' => 12],
            ['id' => 85,'name' => 'Springfield Armory', 'product_categories_id' => 12],
            ['id' => 86,'name' => 'Tanfoglio', 'product_categories_id' => 12],
            ['id' => 87,'name' => 'Black powder weapons', 'product_categories_id' => 12]
        ], [], ['id']);
    }
}
