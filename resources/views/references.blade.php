@extends('layouts.app')
@section('title', 'Moksliniai šaltiniai')

@section('content')
<section class="container my-4" style="max-width:900px">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Pradžia</a></li>
            <li class="breadcrumb-item active">Moksliniai šaltiniai</li>
        </ol>
    </nav>

    <div class="mb-4">
        <div class="eyebrow">Skaidrumas</div>
        <h1 class="fw-bold">Kuo remiasi mūsų skaičiuoklės</h1>
        <p class="text-muted">
            Kalorijų ir treniruočių planuotojai nenaudoja atsitiktinių formulių — jų parametrai
            paimti iš šiuolaikinių (2016–2024 m.) meta-analizių ir sporto mokslo gairių.
            Žemiau pateikiame pilną šaltinių sąrašą su nuorodomis į originalias publikacijas.
        </p>
    </div>

    {{-- Kalorijų planuotojo šaltiniai --}}
    <div class="citations-block mb-4">
        <div class="section-label">Kalorijų planuotojas</div>
        <h3 class="mb-3">Mifflin-St Jeor, PAL, makroelementai</h3>
        <ol class="citations-list">
            <li>
                <strong>Mifflin MD ir kt. (1990).</strong>
                A new predictive equation for resting energy expenditure in healthy individuals.
                <em>American Journal of Clinical Nutrition, 51(2), 241–247.</em>
                Naudojama BMR formulė. Šiuolaikinis ADA standartas.
                <a href="https://pubmed.ncbi.nlm.nih.gov/2305711/" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>FAO/WHO/UNU (2004).</strong>
                Human energy requirements: report of a joint expert consultation.
                Fizinio aktyvumo koeficientų (PAL 1.2–1.9) šaltinis.
                <a href="https://www.fao.org/3/y5686e/y5686e.pdf" target="_blank" rel="noopener">Skaityti PDF</a>
            </li>
            <li>
                <strong>Nunes EA ir kt. (2022).</strong>
                Systematic review and meta-analysis of protein intake to support muscle mass and
                function in healthy adults. <em>Advances in Nutrition.</em>
                Pagrindas 1.6–2.2 g/kg baltymų rekomendacijai.
                <a href="https://pubmed.ncbi.nlm.nih.gov/35470388/" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>Helms ER ir kt. (2014).</strong>
                Evidence-based recommendations for natural bodybuilding contest preparation:
                nutrition and supplementation. <em>JISSN, 11(1), 20.</em>
                Baltymų ir riebalų minimumų pagrindas.
                <a href="https://pubmed.ncbi.nlm.nih.gov/24864135/" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>Roth C ir kt. (2022).</strong>
                Effects of moderate vs high energy deficits on body composition and strength
                in trained individuals. <em>Sports, 10(11), 171.</em>
                Rekomenduojamo svorio metimo greičio (0.5–1 % / sav.) šaltinis.
                <a href="https://pubmed.ncbi.nlm.nih.gov/36422940/" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>Iraki J ir kt. (2019).</strong>
                Nutrition recommendations for bodybuilders in the off-season.
                <em>Sports, 7(7), 154.</em>
                Pagrindas masės auginimo pertekliui (+5 % ... +15 % TDEE).
                <a href="https://pubmed.ncbi.nlm.nih.gov/31247944/" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>EFSA (2010).</strong>
                Scientific Opinion on Dietary Reference Values for water.
                <em>EFSA Journal, 8(3), 1459.</em>
                Dienos vandens poreikio skaičiavimo pagrindas (~35 ml/kg).
                <a href="https://efsa.onlinelibrary.wiley.com/doi/10.2903/j.efsa.2010.1459" target="_blank" rel="noopener">Skaityti</a>
            </li>
        </ol>
    </div>

    {{-- Treniruočių generatoriaus šaltiniai --}}
    <div class="citations-block mb-4">
        <div class="section-label">Treniruočių planas</div>
        <h3 class="mb-3">Tūris, dažnumas, RIR, poilsis</h3>
        <ol class="citations-list">
            <li>
                <strong>Schoenfeld BJ ir kt. (2016).</strong>
                Effects of resistance training frequency on measures of muscle hypertrophy: a
                systematic review and meta-analysis. <em>Sports Medicine, 46(11), 1689–1697.</em>
                Rekomendacija — raumenų grupę treniruoti ≥2× per savaitę.
                <a href="https://pubmed.ncbi.nlm.nih.gov/27102172/" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>Baz-Valle E ir kt. (2022).</strong>
                A systematic review of the effects of different resistance training volumes on
                muscle hypertrophy. <em>Journal of Human Kinetics.</em>
                Savaitinio tūrio tikslai (8–10 / 12–15 / 16–20 serijų).
                <a href="https://pubmed.ncbi.nlm.nih.gov/36196347/" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>Androulakis-Korakakis P ir kt. (2024).</strong>
                Give it a rest: a systematic review with Bayesian meta-analysis on the effect of
                inter-set rest interval duration on muscle hypertrophy.
                <em>Frontiers in Sports and Active Living.</em>
                Pagrindas poilsio trukmėms (90–150 s hipertrofijai, 180–300 s jėgai).
                <a href="https://www.frontiersin.org/journals/sports-and-active-living/articles/10.3389/fspor.2024.1429789/full" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>Robinson ZP ir kt. (2024).</strong>
                Exploring the dose-response relationship between estimated resistance training
                proximity to failure, strength gain, and muscle hypertrophy.
                <em>Sports Medicine.</em>
                RIR pasirinkimo (0–3) šaltinis.
                <a href="https://pubmed.ncbi.nlm.nih.gov/38970765/" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>Remmert JF ir kt. (2024).</strong>
                Training volume increases or maintenance based on previous volume: effects on
                muscle hypertrophy and strength. <em>Journal of Applied Physiology.</em>
                Progresyvaus tūrio didinimo pagrindas.
                <a href="https://journals.physiology.org/doi/full/10.1152/japplphysiol.00476.2024" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>ACSM (2022).</strong>
                Progression Models in Resistance Training for Healthy Adults. Position Stand.
                Kartojimų zonos pagal tikslą (3–6 jėgai, 6–12 masei, 15+ ištvermei).
                <a href="https://journals.lww.com/acsm-msse/fulltext/2009/03000/progression_models_in_resistance_training_for.26.aspx" target="_blank" rel="noopener">Skaityti</a>
            </li>
        </ol>
    </div>

    {{-- BMI ir kiti --}}
    <div class="citations-block mb-4">
        <div class="section-label">BMI ir kūno kompozicija</div>
        <h3 class="mb-3">Kūno masės indekso kategorijos</h3>
        <ol class="citations-list">
            <li>
                <strong>WHO (2024).</strong>
                A healthy lifestyle — WHO recommendations. Body mass index (BMI) categories.
                <em>Pasaulio sveikatos organizacija.</em>
                BMI kategorijų ribos (&lt;18.5 / 18.5–25 / 25–30 / 30+).
                <a href="https://www.who.int/europe/news-room/fact-sheets/item/a-healthy-lifestyle---who-recommendations" target="_blank" rel="noopener">Skaityti</a>
            </li>
            <li>
                <strong>Nuttall FQ (2015).</strong>
                Body Mass Index: obesity, BMI, and health: a critical review.
                <em>Nutrition Today, 50(3), 117–128.</em>
                BMI apribojimų — neatskiria raumenų nuo riebalų — apžvalga.
                <a href="https://pubmed.ncbi.nlm.nih.gov/27340299/" target="_blank" rel="noopener">Skaityti</a>
            </li>
        </ol>
    </div>

    <div class="alert alert-light border">
        <i class="bi bi-info-circle text-primary"></i>
        <strong>Svarbu:</strong> skaičiuoklės pateikia tik orientacines rekomendacijas,
        paremtas tyrimų vidurkiais. Individualus poreikis gali skirtis dėl sveikatos,
        genetikos, vaistų ar kitų aplinkybių — prieš darant reikšmingus mitybos ar
        treniruočių pokyčius pasitark su gydytoju ar kvalifikuotu specialistu.
    </div>
</section>
@endsection
