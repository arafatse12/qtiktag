<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class StaticProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(
            ['product_no' => 'HM0004588C'],
            [
                'name'              => 'CONNERY Baby Woven Trousers',
                'description'       => '100% Organic Cotton Flat Woven Trousers for Babies inspired by Work Wear',
                'season'            => 'SS25',
                'customer_group'    => 'Baby',
                'construction_type' => 'Flat Woven',

                // mirrors
                'brand'       => 'H&M Group',
                'model'       => 'HM0004588CX000006',
                'sku_code'    => '0004588C-CONNERY',
                'barcode'     => '88768333888282',
                'gtin14'      => '07300235375212',
                'qr_base_url' => 'https://qr.hmgroup.com/01',
                'qr_url'      => 'https://qr.hmgroup.com/01/07300235375212/21/HM0004588CX000006',
                'shop_url'    => 'https://hm.com/product/88768333888282',

                // manufacturing scalars
                'supplier_code' => 'SUP001',
                'supplier_name' => 'Russell Garments SIM Fabrics Ltd.',
                'factory'       => 'Manufactured for H & M Hennes & Mauritz GBC AB by Russell Garments SIM Fabrics Ltd.',
                'address'       => '315, Road - 4, Baridhara DOHS, Dhaka, Bangladesh - 1206',
                'origin'        => 'Made in Bangladesh',
                'batch'         => '4588-3-2025',
                'manufactured_on' => '2025-04-08',
                'packaged_on'     => '2025-04-09',
                'publisher'     => 'H&M Group',
                'info_updated_at_pretty' => '08/04/2025 - 04:07:45',

                // images
                'image'   => 'https://cdn.qliktag.io/production/63ee03119cde45000847eb1c/HM%20Baby%20Trousers.png',
                'image_logo'   => '/hm-logo.svg',
                'image_hero'   => 'https://cdn.qliktag.io/production/63ee03119cde45000847eb1c/HM%20Baby%20Trousers.png',
                'image_footer' => '/images/brand-footer.jpg',
                'gear_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Industrial_robot_gear_icon.svg/640px-Industrial_robot_gear_icon.svg.png',

                // narratives
                'production_process'          => 'We are committed to long-term supplier relations based on trust and transparency...',
                'environmental_considerations'=> 'Water, energy and chemicals are managed to recognized standards (e.g., ZDHC)...',
                'worker_wellbeing'            => 'Facilities are assessed against the Code of Conduct; corrective action plans...',

                // JSON blocks
                'identity_json' => [
                    'brand'      => 'H&M Group',
                    'model'      => 'HM0004588CX000006',
                    'sku_code'   => '0004588C-CONNERY',
                    'barcode'    => '88768333888282',
                    'gtin14'     => '07300235375212',
                    'qr_base_url'=> 'https://qr.hmgroup.com/01',
                    'qr_url'     => 'https://qr.hmgroup.com/01/07300235375212/21/HM0004588CX000006',
                    'shop_url'   => 'https://hm.com/product/88768333888282',
                ],
                'manufacturing_json' => [
                    'supplier_code' => 'SUP001',
                    'supplier_name' => 'Russell Garments SIM Fabrics Ltd.',
                    'factory'       => 'Manufactured for H & M Hennes & Mauritz GBC AB by Russell Garments SIM Fabrics Ltd.',
                    'address'       => '315, Road - 4, Baridhara DOHS, Dhaka, Bangladesh - 1206',
                    'origin'        => 'Made in Bangladesh',
                    'batch'         => '4588-3-2025',
                    'manufactured_on' => '2025-04-08',
                    'packaged_on'     => '2025-04-09',
                    'publisher'     => 'QLIKTAG',
                    'info_updated_at_pretty' => '08/04/2025 - 04:07:45',
                ],
                'materials_json' => [
                    'made_from' => '100% organic cotton (BCI) incl. 20% recycled.',
                    'items' => [
                        ['title'=>'Organic Cotton (BCI)','id'=>'60977','percentage'=>80,'by_volume_g'=>65,'recyclable'=>true,'supplier'=>'Diganta Sweaters Ltd'],
                        ['title'=>'Recycled Cotton','id'=>'60974','percentage'=>20,'by_volume_g'=>35,'recyclable'=>true,'supplier'=>'Indigo Fabrics Pvt. Ltd.'],
                    ],
                ],
                'custody_json' => [
                    'serial' => 'HM0004588CX000006',
                    'events' => [
                        ['org'=>'VECTOR_GARMENT','status'=>'SHIPPED','when'=>'2025-03-14T12:43:00Z','lat'=>23.256832,'lng'=>91.7318],
                        ['org'=>'RED_EXPRESS','status'=>'SHIPPED','when'=>'2025-03-13T17:15:00Z','lat'=>23.810331,'lng'=>90.412521],
                        ['org'=>'ESPREQ_RETAIL','status'=>'RECEIVED','when'=>null,'lat'=>null,'lng'=>null],
                    ],
                ],
                'usage_json' => [
                    'guidelines' => 'Follow label. Wash cooler & less often.',
                    'end_of_life'=> 'Bring to collecting boxes; recycle/upcycle.',
                    'close_loop' => 'Garment Collecting program since 2013.',
                    'recycle_image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Recycling_symbol2.svg/512px-Recycling_symbol2.svg.png',
                ],
                'certs_json' => [
                    ['title'=>'Cradle to Cradle Certified® Gold','summary'=>'Supports circularity; fully compostable.','link'=>'https://hm.com/product/88768333888282'],
                ],
                'sustain_json' => [
                    'commitment'=>'High environmental and social standards.',
                    'supply_chain'=>'Independent manufacturers; transparent list.',
                    'textile_materials'=>'Eco-friendly fibers like organic cotton.',
                    'leaf_image_url'=>'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Leaf_icon_%28The_Noun_Project%29.svg/512px-Leaf_icon_%28The_Noun_Project%29.svg.png',
                ],
                'impact_json' => [
                    'co2e_kg'=>12.4,'water_l'=>980,'energy_kwh'=>18.2,
                    'earth_image_url'=>'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/CO2_footprint_icon.svg/512px-CO2_footprint_icon.svg.png',
                ],
            ]
        );
    }
}
