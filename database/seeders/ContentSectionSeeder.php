<?php

// database/seeders/ContentSectionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContentSection;

class ContentSectionSeeder extends Seeder
{
    public function run(): void
    {
        // MANUFACTURING
        ContentSection::updateOrCreate(
            ['slug' => 'manufacturing'],
            [
                'name' => 'Manufacturing',
                'content' => [
                    'title' => 'Production & Manufacturing Process',
                    'blocks' => [
                        ['kind' => 'image', 'name' => 'gear'], // for .gear-img
                        [
                            'kind' => 'rich',
                            'heading' => 'Production Process',
                            'paragraphs' => [
                                "We are committed to long-term supplier relations based on trust and transparency.",
                                "Our products pass through many suppliers from raw materials to finished goods.",
                                "We do not own factories; we work with independent manufacturers."
                            ]
                        ],
                        [
                            'kind' => 'rich',
                            'heading' => 'Environmental Considerations',
                            'paragraphs' => [
                                "Water, energy and chemicals are managed to recognized standards (e.g., ZDHC). Continuous improvement programs and audits help reduce the footprint."
                            ]
                        ],
                        [
                            'kind' => 'rich',
                            'heading' => 'Worker Wellbeing & Audits',
                            'paragraphs' => [
                                "Facilities are assessed against the Code of Conduct; corrective action plans are monitored to drive performance and positive impact."
                            ]
                        ]
                    ]
                ],
            ]
        );

        // MATERIALS
        ContentSection::updateOrCreate(
            ['slug' => 'materials'],
            [
                'name' => 'Materials',
                'content' => [
                    'title' => 'Key Materials & Components Used',
                    'blocks' => [
                        [
                            'kind' => 'rich',
                            'heading' => 'Made From',
                            'paragraphs' => [
                                'Made from 100% organic cotton (BCI) and contains at least 20% recycled cotton.'
                            ]
                        ],
                        [
                            'kind' => 'list',
                            'title' => 'Key Materials & Component List',
                            'items' => [
                                [
                                    'group' => 'Organic Cotton (BCI)',
                                    'rows' => [
                                        ['key' => 'ID', 'value' => '60977'],
                                        ['key' => 'PERCENTAGE', 'value' => '80%'],
                                        ['key' => 'BY VOLUME', 'value' => '65 GMS'],
                                        ['key' => 'RECYCLABLE', 'value' => true],
                                        ['key' => 'SUPPLIER', 'value' => 'Diganta Sweaters Ltd, Bangladesh'],
                                    ]
                                ],
                                [
                                    'group' => 'Recycled Cotton',
                                    'rows' => [
                                        ['key' => 'ID', 'value' => '60974'],
                                        ['key' => 'PERCENTAGE', 'value' => '20%'],
                                        ['key' => 'BY VOLUME', 'value' => '35 GMS'],
                                        ['key' => 'RECYCLABLE', 'value' => true],
                                        ['key' => 'SUPPLIER', 'value' => 'Indigo Fabrics Pvt. Ltd., Bangladesh'],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        );

        // CUSTODY
        ContentSection::updateOrCreate(
            ['slug' => 'custody'],
            [
                'name' => 'Custody',
                'content' => [
                    'title' => 'Chain of Custody & Traceability',
                    'blocks' => [
                        ['kind' => 'rich', 'heading' => 'Item Traceability Map', 'paragraphs' => ['placeholder']],
                        [
                            'kind' => 'eventLog',
                            'title' => 'Item Track & Trace Event Log',
                            'events' => [
                                ['actor' => 'VECTOR_GARMENT', 'status' => 'SHIPPED', 'at' => '2025-03-14T12:43:00Z', 'lat' => 23.256832, 'lng' => 91.7318],
                                ['actor' => 'RED_EXPRESS', 'status' => 'SHIPPED', 'at' => '2025-03-13T17:15:00Z', 'lat' => 23.810331, 'lng' => 90.412521],
                                ['actor' => 'ESPREQ_RETAIL', 'status' => 'RECEIVED', 'at' => null, 'lat' => null, 'lng' => null],
                            ]
                        ]
                    ]
                ],
            ]
        );

        // USAGE
        ContentSection::updateOrCreate(
            ['slug' => 'usage'],
            [
                'name' => 'Usage',
                'content' => [
                    'title' => 'Use, Care, Reuse & Recycle',
                    'blocks' => [
                        ['kind' => 'image', 'name' => 'recycle'],
                        ['kind' => 'rich', 'heading' => 'Usage & Care Guidelines', 'paragraphs' => ['Follow label instructions. Reshape chunky knits and dry flat. Reduce washing frequency and temperature to lower impact.']],
                        ['kind' => 'rich', 'heading' => 'End of Life & Recycling', 'paragraphs' => ['Bring unwanted clothes to our collecting boxes (any brand). Wearable items are resold; others become new products or cleaning cloths.']],
                        ['kind' => 'rich', 'heading' => 'Close the Loop', 'paragraphs' => ['Our Garment Collecting program has run since 2013, with recycling boxes in stores worldwide.']],
                    ]
                ],
            ]
        );

        // CERTS
        ContentSection::updateOrCreate(
            ['slug' => 'certs'],
            [
                'name' => 'Certifications',
                'content' => [
                    'title' => 'Certifications',
                    'blocks' => [
                        ['kind' => 'image', 'name' => 'medal'],
                        ['kind' => 'rich', 'heading' => 'Compliance Statement', 'paragraphs' => ['We increase transparency and pioneer new materials and business models toward net-zero (2040).']],
                        [
                            'kind' => 'cardGrid',
                            'title' => 'Programs',
                            'cards' => [
                                [
                                    'title' => 'Cradle to Cradle Certified® Gold',
                                    'text'  => 'Collection supports circularity; products are fully compostable.',
                                    'href'  => '#'
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        );

        // SUSTAIN
        ContentSection::updateOrCreate(
            ['slug' => 'sustain'],
            [
                'name' => 'Sustainability',
                'content' => [
                    'title' => 'Sustainability',
                    'blocks' => [
                        ['kind' => 'image', 'name' => 'leaf'],
                        ['kind' => 'rich', 'heading' => 'Our Commitment', 'paragraphs' => ['We collaborate with partners globally to meet high environmental and social standards.']],
                        ['kind' => 'rich', 'heading' => 'Manufacturing & Supply Chain', 'paragraphs' => ['Long-term relationships with independent manufacturers; supplier list published and updated regularly.']],
                        ['kind' => 'rich', 'heading' => 'Textile Materials', 'paragraphs' => ['Eco-friendly fibers like organic cotton; lower-impact processes.']],
                    ]
                ],
            ]
        );

        // IMPACT
        ContentSection::updateOrCreate(
            ['slug' => 'impact'],
            [
                'name' => 'Environmental Impact',
                'content' => [
                    'title' => 'Environmental Impact',
                    'blocks' => [
                        ['kind' => 'image', 'name' => 'earth'],
                        ['kind' => 'rich', 'heading' => 'Statement', 'paragraphs' => ['We aim for net-zero emissions by 2040, keeping products and materials in use for as long as possible.']],
                        [
                            'kind' => 'metricsGrid',
                            'cards' => [
                                ['label' => 'CO₂e',   'value' => '12.4 kg', 'note' => 'cradle-to-gate'],
                                ['label' => 'Water',  'value' => '980 L',   'note' => 'freshwater use'],
                                ['label' => 'Energy', 'value' => '18.2 kWh','note' => 'process energy'],
                            ]
                        ]
                    ]
                ],
            ]
        );
    }
}


