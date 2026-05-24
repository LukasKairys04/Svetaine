<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    // Fallback kategorinės nuotraukos — jei produktui nėra tikslios nuotraukos,
    // naudojama kategorijos nuotrauka (patikimesnė už LoremFlickr, nepriklauso
    // nuo išorinių paslaugų būsenos).
    protected array $categoryImages = [
        'sporto-papildai' => 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=600&h=600&fit=crop&q=80',
        'mityba'          => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=600&h=600&fit=crop&q=80',
        'sportas'         => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&h=600&fit=crop&q=80',
        'vitaminai'       => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&h=600&fit=crop&q=80',
        'gerimai'         => 'https://images.unsplash.com/photo-1622543925917-763c34d1a86e?w=600&h=600&fit=crop&q=80',
    ];

    // Konkrečios, kiekvienam produktui parinktos nuotraukos. Visos per Unsplash
    // images CDN — palaiko `auto=format` ir `q` parametrus, todėl krauna greitai.
    protected array $productOverrides = [
        // --- Sporto papildai ---
        'whey-protein-gold-1kg'         => 'https://images.unsplash.com/photo-1607863680198-23d4b2565df0?w=600&h=600&fit=crop&q=80', // protein indelis
        'kreatinas-monohidratas-500g'   => 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=600&h=600&fit=crop&q=80', // miltelis šaukšte
        'bcaa-211-400g'                 => 'https://images.unsplash.com/photo-1579722820308-d74e571900a9?w=600&h=600&fit=crop&q=80', // supplement bottle
        'pre-workout-c4-300g'           => 'https://images.unsplash.com/photo-1594882645126-14020914d58d?w=600&h=600&fit=crop&q=80', // energy preworkout
        'glutaminas-500g'               => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=600&fit=crop&q=80',   // white powder

        // --- Mityba ---
        'protein-bar-choco-60g'         => 'https://images.unsplash.com/photo-1622484212850-eb596d769edc?w=600&h=600&fit=crop&q=80',
        'avizine-kose-su-baltymais-500g'=> 'https://images.unsplash.com/photo-1517093157656-b9eccef91cb1?w=600&h=600&fit=crop&q=80',
        'riesutu-mix-250g'              => 'https://images.unsplash.com/photo-1599599810694-b5b37304c041?w=600&h=600&fit=crop&q=80',
        'energetinis-batonelis-50g'     => 'https://images.unsplash.com/photo-1571748982800-fa51082c2224?w=600&h=600&fit=crop&q=80',

        // --- Sportas ---
        'treniruociu-guma-set'          => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600&h=600&fit=crop&q=80', // resistance bands
        'hanteliai-2x5kg'               => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=600&h=600&fit=crop&q=80',
        'jogos-kilimelis-premium'       => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=600&h=600&fit=crop&q=80',
        'sokdyne-greitine'              => 'https://images.unsplash.com/photo-1599058917212-d750089bc07e?w=600&h=600&fit=crop&q=80',
        'sporto-krepsys-40l'            => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&h=600&fit=crop&q=80',

        // --- Vitaminai ---
        'vitaminas-d3-2000iu'           => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&h=600&fit=crop&q=80',
        'omega-3-1000mg'                => 'https://images.unsplash.com/photo-1550572017-edd951b55104?w=600&h=600&fit=crop&q=80',
        'multivitaminai-sport'          => 'https://images.unsplash.com/photo-1626716493137-b67fe9501e76?w=600&h=600&fit=crop&q=80',

        // --- Gėrimai ---
        'izotoninis-gerimas-500ml'      => 'https://images.unsplash.com/photo-1625772299848-391b6a87d7b3?w=600&h=600&fit=crop&q=80',
        'bcaa-gerimas-330ml'            => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=600&h=600&fit=crop&q=80',
        'baltyminis-kokteilis-330ml'    => 'https://images.unsplash.com/photo-1622543925917-763c34d1a86e?w=600&h=600&fit=crop&q=80',
    ];

    /**
     * Kiekvienam produktui — trumpas (1 sakinys), konkretus aprašas
     * ir šiek tiek ilgesnis description HTML (2–3 trumpi sakiniai).
     * Formatas: [slug => [short, description_html]]
     */
    protected function copy(string $slug): array
    {
        $map = [
            'whey-protein-gold-1kg' => [
                'Greitai pasisavinamas išrūgų baltymas — 24 g baltymų vienoje porcijoje.',
                '<p>Klasikinis šokoladinio skonio išrūgų baltymas iš Optimum Nutrition. Idealiai tinka po treniruotės — gerai tirpsta, malonus skonis, mažai cukraus.</p><p>Padeda atsistatyti raumenims ir pasiekti dienos baltymų normą be vargo.</p>',
            ],
            'kreatinas-monohidratas-500g' => [
                'Grynas monohidrato kreatinas jėgai ir ištvermei didinti.',
                '<p>Moksliškai labiausiai ištirtas sporto papildas. 5 g per dieną pakanka — netirpsta, nekvepia, maišoma su bet kokiu gėrimu.</p><p>Padeda padidinti kartojimų skaičių, pagreitina atsistatymą tarp serijų.</p>',
            ],
            'bcaa-211-400g' => [
                'Šakotos grandinės aminorūgštys 2:1:1 santykiu treniruotės metu.',
                '<p>Leucinas, izoleucinas ir valinas — pagrindinės aminorūgštys raumenų atsistatymui. Gerai tirpsta, subtilus citrinos skonis.</p><p>Ypač naudinga treniruojantis nevalgius arba ilgų kardio sesijų metu.</p>',
            ],
            'pre-workout-c4-300g' => [
                'Pre-workout su kofeinu ir beta-alaninu prieš intensyvią treniruotę.',
                '<p>Energijos ir koncentracijos formulė — 150 mg kofeino, beta-alaninas ir kreatinas. Vartoti 20–30 min. prieš treniruotę.</p><p>Dėmesio: nevartoti po 16 val., nes kofeinas gali trikdyti miegą.</p>',
            ],
            'glutaminas-500g' => [
                'L-glutaminas atsistatymui po sunkių treniruočių.',
                '<p>Svarbi aminorūgštis žarnyno sveikatai ir imunitetui. 5 g po treniruotės arba prieš miegą.</p><p>Be skonio — galima tiesiog sumaišyti su baltymų kokteiliu.</p>',
            ],
            'protein-bar-choco-60g' => [
                'Šokoladinis batonėlis — 20 g baltymų, tik 1 g cukraus.',
                '<p>Tikras šokolado skonis be kaltės. Naudingas užkandis kelyje arba vietoje deserto.</p><p>Didelis skaidulų kiekis (14 g) — gerai sotina ilgą laiką.</p>',
            ],
            'avizine-kose-su-baltymais-500g' => [
                'Instantinė avižinė košė, papildyta išrūgų baltymu.',
                '<p>Pilnavertis pusryčių variantas — 20 g baltymų ir 8 g skaidulų porcijoje. Paruošimui užtenka karšto vandens arba pieno.</p><p>Puikus variantas judantiems rytais, kai nėra laiko gaminti.</p>',
            ],
            'riesutu-mix-250g' => [
                'Migdolų, anakardžių ir graikiškų riešutų mišinys be druskos.',
                '<p>Sotus, natūralus užkandis su sveikaisiais riebalais ir baltymais. Be skrudinimo, be druskos, be cukraus.</p><p>Gera mikroelementų injekcija tarp valgymų.</p>',
            ],
            'energetinis-batonelis-50g' => [
                'Angliavandenių batonėlis greitai energijai treniruotės metu.',
                '<p>42 g angliavandenių — kuras ilgoms treniruotėms, bėgimui ar dviračiui. Lengvai virškinamas, nesukelia sunkumo skrandyje.</p><p>Valgyti 30–45 min. prieš krūvį arba jo metu.</p>',
            ],
            'treniruociu-guma-set' => [
                'Elastinių gumų komplektas (5 vnt.) — nuo lengvo iki sunkaus pasipriešinimo.',
                '<p>Universali treniruočių priemonė namams ar kelionei. Tinka kojų, nugaros ir pečių pratimams.</p><p>Mažai užima vietos — telpa į kelionės krepšį.</p>',
            ],
            'hanteliai-2x5kg' => [
                'Hantelių pora (2×5 kg) su guminiu paviršiumi — saugu grindims.',
                '<p>Tvirta plieninė konstrukcija, guminė danga apsaugo nuo triukšmo ir braižymų. Patogi rankena be slydimo.</p><p>Puikus pasirinkimas namų treniruotėms ar kaip papildymas esamam svoriui.</p>',
            ],
            'jogos-kilimelis-premium' => [
                'Storas (6 mm) nestandu TPE jogos kilimėlis su nešimo dirželiu.',
                '<p>Minkštas sąnariams, neslidus — stabilus net drėgnomis rankomis. Lengvas (~1 kg), lengvai plaunamas.</p><p>Tinka jogai, pilates ar namų treniruotėms ant grindų.</p>',
            ],
            'sokdyne-greitine' => [
                'Reguliuojamo ilgio greitinė šokdynė su guoliais.',
                '<p>Minkštos rankenos, greiti guoliai — virvė nesiveliuoja. Ilgis reguliuojamas pagal ūgį.</p><p>Puikus kardio įrankis — efektyvus 10 min. seansas atstoja 30 min. bėgimo.</p>',
            ],
            'sporto-krepsys-40l' => [
                'Patvarus 40 L sporto krepšys su atskira batų skiltimi.',
                '<p>Vandeniui atsparus audinys, ventiliuojamas batų skyrius, šoninė kišenė buteliukui. Sustiprinti dugno ir pečių dirželiai.</p><p>Pakankamai talpus dienos krūviui į sporto klubą ar į baseiną.</p>',
            ],
            'vitaminas-d3-2000iu' => [
                'Vitaminas D3 — imunitetui ir kaulams žiemos sezonu.',
                '<p>2000 IU cholekalciferolio vienoje kapsulėje. Vartoti 1 kapsulę per dieną su maistu, kuriame yra riebalų.</p><p>Ypač rekomenduojama spalio–balandžio mėn., kai saulės šviesos trūksta.</p>',
            ],
            'omega-3-1000mg' => [
                'Žuvų taukai — 1000 mg omega-3 (EPA + DHA) širdies sveikatai.',
                '<p>Molekulinis distiliavimas užtikrina aukštą grynumą. Be žuvies poskonio, kapsulės lengvai nuryjamos.</p><p>Vartoti 1–2 kapsules per dieną su maistu.</p>',
            ],
            'multivitaminai-sport' => [
                'Kompleksiniai multivitaminai su mineralų kompleksu aktyviems.',
                '<p>23 vitaminai ir mineralai, specialiai pritaikyti sportuojantiems. B grupės vitaminai energijai, magnis raumenims.</p><p>2 tabletės per dieną su pusryčiais.</p>',
            ],
            'izotoninis-gerimas-500ml' => [
                'Izotoninis gėrimas — skysčių ir elektrolitų pusiausvyrai.',
                '<p>Greitas sudėtis — natris, kalis, magnis ir angliavandeniai. Tinka po ilgos treniruotės arba karšto oro krūvio.</p><p>Citrusų skonis — atgaivina ir nepersaldintas.</p>',
            ],
            'bcaa-gerimas-330ml' => [
                'Gatavas BCAA gėrimas — patogus vartojimas be maišymo.',
                '<p>Subalansuota dozė treniruotės metu — 6 g BCAA indelyje. Gaivus vaisių skonis, be cukraus.</p><p>Patogi alternatyva miltelių forma BCAA.</p>',
            ],
            'baltyminis-kokteilis-330ml' => [
                'Paruoštas baltyminis kokteilis — 25 g baltymų vienoje buteliukuo.',
                '<p>Neštis ir gerti — jokio maišytuvo. Kreminė tekstūra, subtilus vanilės skonis, mažai cukraus.</p><p>Tinka po treniruotės arba kaip baltymų papildas dienos metu.</p>',
            ],
        ];

        return $map[$slug] ?? ['Kokybiškas produktas aktyviems žmonėms.', '<p>Kokybiškas produktas aktyviems žmonėms.</p>'];
    }

    public function run(): void
    {
        $byType = Category::where('type', 'product')->pluck('id', 'slug');

        $products = [
            // Sporto papildai
            ['Whey Protein Gold 1kg', 'sporto-papildai', 'Optimum Nutrition', 39.99, 34.99, 120, 4.8, ['calories'=>120,'protein'=>24,'carbs'=>3,'fat'=>1.5,'sugar'=>1,'sodium'=>50,'serving_size'=>'30g','servings_per_container'=>33]],
            ['Kreatinas Monohidratas 500g', 'sporto-papildai', 'MyProtein', 19.99, null, 80, 4.7, ['calories'=>0,'protein'=>0,'carbs'=>0,'fat'=>0,'serving_size'=>'5g','servings_per_container'=>100]],
            ['BCAA 2:1:1 400g', 'sporto-papildai', 'Scitec', 24.99, 21.99, 60, 4.5, ['calories'=>20,'protein'=>5,'carbs'=>0,'fat'=>0,'serving_size'=>'10g','servings_per_container'=>40]],
            ['Pre-Workout C4 300g', 'sporto-papildai', 'Cellucor', 29.99, null, 45, 4.3, ['calories'=>5,'protein'=>0,'carbs'=>1,'fat'=>0,'serving_size'=>'6g','servings_per_container'=>50]],
            ['Glutaminas 500g', 'sporto-papildai', 'Biotech', 22.99, null, 30, 4.4, ['calories'=>0,'protein'=>5,'carbs'=>0,'fat'=>0,'serving_size'=>'5g','servings_per_container'=>100]],
            // Mityba
            ['Protein Bar Choco 60g', 'mityba', 'Quest', 2.99, null, 500, 4.6, ['calories'=>200,'protein'=>20,'carbs'=>21,'fat'=>8,'sugar'=>1,'fiber'=>14,'sodium'=>220,'serving_size'=>'60g','servings_per_container'=>1]],
            ['Avižinė Košė su Baltymais 500g', 'mityba', 'FitShop', 5.99, 4.99, 200, 4.5, ['calories'=>350,'protein'=>20,'carbs'=>55,'fat'=>6,'fiber'=>8,'serving_size'=>'100g','servings_per_container'=>5]],
            ['Riešutų Mix 250g', 'mityba', 'NutriPro', 6.99, null, 150, 4.7, ['calories'=>580,'protein'=>18,'carbs'=>15,'fat'=>52,'fiber'=>7,'serving_size'=>'30g','servings_per_container'=>8]],
            ['Energetinis Batonėlis 50g', 'mityba', 'Clif', 2.49, null, 400, 4.2, ['calories'=>240,'protein'=>9,'carbs'=>42,'fat'=>5,'sugar'=>20,'serving_size'=>'50g','servings_per_container'=>1]],
            // Sportas
            ['Treniruočių Guma Set', 'sportas', 'Fitness Lab', 14.99, null, 100, 4.6, []],
            ['Hanteliai 2x5kg', 'sportas', 'PowerFit', 39.99, 34.99, 40, 4.8, []],
            ['Jogos Kilimėlis Premium', 'sportas', 'YogaX', 24.99, null, 70, 4.5, []],
            ['Šokdynė Greitinė', 'sportas', 'CrossPro', 9.99, null, 200, 4.3, []],
            ['Sporto Krepšys 40L', 'sportas', 'SportLine', 29.99, null, 60, 4.4, []],
            // Vitaminai
            ['Vitaminas D3 2000IU', 'vitaminai', 'Solgar', 12.99, null, 150, 4.7, ['serving_size'=>'1 kapsulė','servings_per_container'=>60]],
            ['Omega-3 1000mg', 'vitaminai', 'NOW', 16.99, 14.99, 120, 4.8, ['serving_size'=>'1 kapsulė','servings_per_container'=>90]],
            ['Multivitaminai Sport', 'vitaminai', 'Universal', 19.99, null, 90, 4.5, ['serving_size'=>'2 tabletės','servings_per_container'=>60]],
            // Gėrimai
            ['Izotoninis Gėrimas 500ml', 'gerimai', 'Powerade', 1.99, null, 300, 4.2, ['calories'=>90,'carbs'=>22,'sugar'=>20,'sodium'=>200,'serving_size'=>'500ml','servings_per_container'=>1]],
            ['BCAA Gėrimas 330ml', 'gerimai', 'Scitec', 2.49, null, 250, 4.3, ['calories'=>10,'protein'=>2,'serving_size'=>'330ml','servings_per_container'=>1]],
            ['Baltyminis Kokteilis 330ml', 'gerimai', 'Weider', 3.49, 2.99, 180, 4.5, ['calories'=>150,'protein'=>25,'carbs'=>8,'fat'=>2,'serving_size'=>'330ml','servings_per_container'=>1]],
        ];

        foreach ($products as $idx => $p) {
            [$name, $catSlug, $brand, $price, $salePrice, $stock, $rating, $nutrition] = $p;
            $slug = Str::slug($name);
            $catId = $byType[$catSlug] ?? $byType->first();
            [$short, $desc] = $this->copy($slug);

            // Naudojam konkrečią produkto nuotrauką; jei jos nėra — kategorijos
            // nuotrauką; kraštutiniu atveju — picsum.photos su stabiliu slug seed'u.
            $image = $this->productOverrides[$slug]
                ?? ($this->categoryImages[$catSlug] ?? "https://picsum.photos/seed/{$slug}/600/600");

            Product::updateOrCreate(
                ['slug' => $slug],
                array_merge([
                    'category_id' => $catId,
                    'name' => $name,
                    'brand' => $brand,
                    'short_description' => $short,
                    'description' => $desc,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'stock' => $stock,
                    'image' => $image,
                    'rating' => $rating,
                    'rating_count' => rand(15, 320),
                    'featured' => rand(0, 2) === 0,
                    'is_active' => true,
                ], $nutrition)
            );
        }
    }

}
