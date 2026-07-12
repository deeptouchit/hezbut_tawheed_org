@extends('theme::layouts.app')

@section('title', 'আমাদের কর্মসূচিসমূহ - হেযবুত তওহীদ')

@push('styles')
<style>
    /* ============================================================
       VERBATIM EDITORIAL STEPPER - PROGRAMS PAGE
       ============================================================ */
    .prog-section {
        background: #f8fafc;
        padding: 5rem 0 7rem;
    }

    .prog-header {
        max-width: 850px;
        margin: 0 auto 4rem;
        text-align: center;
    }

    .prog-title {
        font-size: 3rem;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.025em;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .prog-title .highlight {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        position: relative;
    }

    .prog-subtitle {
        color: #475569;
        font-size: 1.25rem;
        font-weight: 500;
        line-height: 1.6;
    }

    /* ---------- Editorial Intro ---------- */
    .prog-intro-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 28px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        padding: 4rem;
        max-width: 950px;
        margin: 0 auto 4rem;
    }

    .prog-intro-text {
        font-size: 1.125rem;
        line-height: 1.9;
        color: #334155;
    }

    .prog-intro-text p {
        margin-bottom: 1.75rem;
        font-weight: 500;
    }

    /* ---------- 5 Point Statement Callout ---------- */
    .statement-callout {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        border-radius: 0 20px 20px 0;
        padding: 2.5rem;
        margin: 2.5rem 0;
    }

    .statement-callout h3 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e40af;
        margin-bottom: 1.5rem;
    }

    .statement-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .statement-item {
        background: #ffffff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.04);
    }

    /* ---------- Interactive Stepper Container ---------- */
    .stepper-container {
        max-width: 950px;
        margin: 0 auto;
    }

    /* Stepper Row */
    .stepper-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin-bottom: 4rem;
        padding: 0 1rem;
    }

    .stepper-line-bg {
        position: absolute;
        top: 30px;
        left: 0;
        right: 0;
        height: 4px;
        background: #e2e8f0;
        z-index: 1;
    }

    .stepper-line-active {
        position: absolute;
        top: 30px;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #1e40af);
        z-index: 2;
        width: 0%;
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step-node {
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        flex: 1;
    }

    .step-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        font-weight: 800;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .step-label {
        margin-top: 0.75rem;
        font-size: 0.95rem;
        font-weight: 700;
        color: #64748b;
        transition: all 0.3s ease;
        text-align: center;
        white-space: nowrap;
    }

    /* Hover States */
    .step-node:hover .step-circle {
        border-color: #3b82f6;
        color: #3b82f6;
        transform: translateY(-2px);
    }

    /* Active States */
    .step-node.active .step-circle {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-color: #3b82f6;
        color: #ffffff;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
    }

    .step-node.active .step-label {
        color: #1e40af;
        font-size: 1.05rem;
    }

    /* Completed States */
    .step-node.completed .step-circle {
        border-color: #3b82f6;
        color: #3b82f6;
    }

    /* ---------- Content Display Panel ---------- */
    .step-panel-wrapper {
        position: relative;
        min-height: 400px;
    }

    .step-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 32px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02);
        padding: 3.5rem;
        display: none;
        opacity: 0;
        transform: translateY(15px);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step-panel.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .panel-grid {
        display: grid;
        grid-template-columns: 1.25fr 0.75fr;
        gap: 3rem;
        align-items: center;
    }

    .panel-details h3 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        line-height: 1.35;
    }

    .panel-details h3 i {
        color: #3b82f6;
    }

    .panel-text {
        font-size: 1.1rem;
        color: #334155;
        line-height: 1.9;
        font-weight: 500;
    }

    .panel-illustration {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 24px;
        padding: 3rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        height: 100%;
        min-height: 250px;
    }

    .illustration-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #ffffff;
        color: #1e40af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .illustration-quote {
        font-size: 1.1rem;
        font-style: italic;
        color: #1e40af;
        font-weight: 700;
        line-height: 1.6;
    }

    /* ---------- Navigation Buttons ---------- */
    .stepper-nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 3rem;
    }

    .btn-stepper {
        padding: 0.75rem 1.75rem;
        font-weight: 700;
        border-radius: 12px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.05rem;
    }

    .btn-prev {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    .btn-prev:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-next {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
    }
    .btn-next:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
    }

    /* ---------- Mobile Optimization ---------- */
    @media (max-width: 767.98px) {
        .prog-title {
            font-size: 2.25rem;
        }
        .prog-intro-card {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }
        .stepper-row {
            flex-direction: column;
            gap: 2rem;
            align-items: flex-start;
            padding-left: 2rem;
            margin-bottom: 3rem;
        }
        .stepper-line-bg {
            left: 50px;
            top: 0;
            bottom: 0;
            width: 4px;
            height: 100%;
        }
        .stepper-line-active {
            left: 50px;
            top: 0;
            width: 4px;
            height: 0%;
        }
        .step-node {
            flex-direction: row;
            gap: 1.5rem;
            width: 100%;
        }
        .step-circle {
            width: 56px;
            height: 56px;
            font-size: 1.2rem;
        }
        .step-label {
            margin-top: 0;
            text-align: left;
        }
        .step-panel {
            padding: 2.5rem 1.5rem;
            border-radius: 20px;
        }
        .panel-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .panel-details h3 {
            font-size: 1.5rem;
        }
        .panel-text {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'আমাদের কর্মসূচি',
        'subtitle' => 'শান্তিময় বিশ্বসমাজ ও মানবতাবোধ জাগরণের ঐশ্বরিক রূপরেখা।',
        'badge_text' => 'কর্মসূচি',
        'badge_icon' => 'fas fa-calendar-alt'
    ])

    <section class="prog-section">
        <div class="container">
            
            <div class="prog-header">
                <h1 class="prog-title">হেযবুত তওহীদের <span class="highlight">কর্মসূচি</span></h1>
                <p class="prog-subtitle">
                    আল্লাহর সত্যদীন প্রতিষ্ঠার জন্য রসুলুল্লাহ (সা.) নির্দেশিত শাশ্বত ও পবিত্র ৫ দফা কর্মসূচি।
                </p>
            </div>

            <!-- Introductory Editorial Card -->
            <div class="prog-intro-card">
                <div class="prog-intro-text">
                    <p>
                        বিগত তেরশত বছরে ক্রমাগত বিকৃত হতে হতে সে ইসলাম বর্তমানে ঠিক এর বিপরীত একটি ধর্ম-বিশ্বাসে রূপান্তরিত হয়ে গেছে। বাহ্যিক দিক থেকে দেখতে এই ইসলাম আল্লাহ রসুলের ইসলামের মত হলেও চরিত্রে, আত্মায় ঠিক এর বিপরীত। শেষ রসুল আনীত প্রকৃত ইসলাম মানুষকে দিয়েছিল সর্বরকম মুক্তি ও স্বাধীনতা, নির্মূল করেছিল সমস্ত অন্যায়-অবিচার, সামাজিকভাবে দিয়েছিল পরম নিরাপত্তা, অর্থনৈতিক ক্ষেত্রে দূর করেছিল বৈষম্য। কিন্তু বর্তমানে সে ইসলাম আর আমাদের মাঝে নেই। ধর্ম ব্যবসায়ী ও প-িতদের কবলে পড়ে ইসলাম আজ নামাজ রোজাসর্বস্ব অন্যান্য ধর্মের মত একটি ধর্মে পরিণত হয়ে গেছে। সর্বশেষ ব্রিটিশরা তাদের তৈরি মাদ্রাসা শিক্ষার মাধ্যমে এই জাতির মন মগজে এক মৃত ইসলাম গেড়ে দিয়ে গেছে। এমাতাবস্থায় এই জাতিকে যদি পৃথিবীর বুকে আবার মাথা উঁচু করে দাঁড়াতে হয়, সম্মান নিয়ে বাঁচতে হয়, শান্তি ও সমৃদ্ধ পৃথিবী পেতে হয় এবং মৃত্যুর পরে জান্নাতে যেতে হয় তাহলে আল্লাহ ও রসুলের প্রকৃত ইসলাম প্রতিষ্ঠা ছাড়া তাদের সামনে আর কোন পথ অবশিষ্ট নেই। সেই প্রকৃত ইসলাম যামানার এমাম, এমামুযযামান জনাব মোহাম্মদ বায়াজীদ খান পন্নী মানবজাতির সামনে তুলে ধরেছেন। এখন মানবজাতির কর্তব্য হচ্ছে আল্লাহর দেওয়া সত্য ইসলামটাকে মেনে নেওয়া। এ ছাড়া মানবজাতির সামনে অন্য কোন পথ অবশিষ্ট নেই।
                    </p>
                    <p>
                        এখন একটি গুরুত্বপূর্ণ বিষয় হল, আল্লাহর সত্যদীন প্রতিষ্ঠার জন্য আল্লাহর দেওয়া কর্মসূচি অপরিহার্য। একটি জীবনব্যবস্থা দিলেন আল্লাহ আর সেটি প্রতিষ্ঠার কর্মসূচি তৈরি করবে মানুষ সেটা কখনওই হতে পারে না। আল্লাহর রসুল এবং তাঁর উম্মাহ কি অর্ধ পৃথিবীতে নিজেদের তৈরি কর্মসূচি দিয়ে দীন প্রতিষ্ঠা করেছিলেন? নিশ্চয়ই নয়। তাহলে কোথায় সেই কর্মসূচি? মহান আল্লাহ অসীম করুণা করে, ভালবেসে তাঁর নিজের তৈরি করা সেই পবিত্র কর্মসূচি এ যামানার এমামকে নতুন করে বুঝিয়ে দিয়েছেন।
                    </p>
                    <p>
                        আল্লাহ বলেন, ‘আমি আমার রসুলকে সঠিক পথ প্রর্দশন (হেদায়াহ) এবং সত্যদীন দিয়ে প্রেরণ করলাম এই জন্যে যে তিনি যেন একে (এই হেদায়াহ ও জীবন ব্যবস্থাকে) পৃথিবীর অন্যান্য সমস্ত জীবন ব্যবস্থার উপর বিজয়ী করেন (সুরা ফাতাহ- ২৮, সুরা তওবা- ৩৩ ও সুরা সফ- ৯)। অর্থাৎ পৃথিবীতে, মানব জাতির মধ্যে আল্লাহর রসুলকে প্রেরণের উদ্দেশ্য হচ্ছে রসুলের মাধ্যমে হেদায়াহ, পথ প্রদর্শনসহ দীন পাঠানো এবং সেই হেদায়াহ ও দীনকে সমগ্র মানব জীবনে প্রতিষ্ঠা করা। আল্লাহ তাঁর রসুলকে এই কাজ করার নীতি ও কর্মসূচিও দান করলেন। সেই নীতি হচ্ছে, সংগ্রাম, জেহাদ করে এই সত্যদীন প্রতিষ্ঠা করতে হবে। আল্লাহর রসুল বলেছেন- আমি আদিষ্ট হয়েছি মানব জাতির বিরুদ্ধে সর্বাত্মক সংগ্রাম চালিয়ে যেতে যে পর্যন্ত না সমস্ত মানুষ আল্লাহকে একমাত্র এলাহ এবং আমাকে তাঁর রসুল বলে মেনে নেয় (হাদীস- আবদাল্লাহ বিন ওমর (রা.) থেকে- বোখারী, মেশকাত)।
                    </p>
                    <p>
                        মানবজীবনে শান্তি প্রতিষ্ঠা করতে আল্লাহ যে নীতি রসুলকে দিয়েছেন সেই নীতির উপর ভিত্তি করা একটি ৫ দফা কর্মসূচি আল্লাহ তাঁর রসুলকে দান করলেন। এই ৫ দফা কর্মসূচি তিনি তাঁর উম্মাহর উপর অর্পণ করার সময় বলছেন। এই কর্মসূচি আল্লাহ আমাকে দিয়েছেন, (আমি সারাজীবন এই কর্মসূচি অনুযায়ী সংগ্রাম করেছি) এখন এটা তোমাদের হাতে অর্পণ করে আমি চোলে যাচ্ছি। সেগুলো হল:
                    </p>

                    <!-- 5-Point Statement Callout inside Intro Card -->
                    <div class="statement-callout">
                        <h3>রসুলুল্লাহ (সা.) নির্দেশিত শাশ্বত ৫ দফা কর্মসূচি:</h3>
                        <ul class="statement-list">
                            <li class="statement-item"><i class="fas fa-users text-primary"></i> (১) ঐক্যবদ্ধ হও।</li>
                            <li class="statement-item"><i class="fas fa-volume-up text-primary"></i> (২) (নেতার আদেশ) শোন।</li>
                            <li class="statement-item"><i class="fas fa-check-circle text-primary"></i> (৩) (নেতার ঐ আদেশ) পালন কর।</li>
                            <li class="statement-item"><i class="fas fa-sign-out-alt text-primary"></i> (৪) হিজরত কর।</li>
                            <li class="statement-item"><i class="fas fa-shield-alt text-primary"></i> (৫) (এই দীনুল হক কে পৃথিবীতে প্রতিষ্ঠার জন্য) আল্লাহর রাস্তায় জেহাদ কর।</li>
                        </ul>
                    </div>

                    <p>
                        যে ব্যক্তি (কর্মসূচির) এই ঐক্যবন্ধনী থেকে এক বিঘত পরিমাণও বহির্গত হল, সে নিশ্চয় তার গলা থেকে ইসলামের রজ্জু (বন্ধন) খুলে ফেলল- যদি না সে আবার ফিরে আসে (তওবা করে) এবং যে ব্যক্তি অজ্ঞানতার যুগের (কোনও কিছুর) দিকে আহ্বান করলে, সে নিজেকে মুসলিম বলে বিশ্বাস করলেও, নামায পোড়লেও এবং রোযা রাখলেও নিশ্চয়ই সে জাহান্নামের জ্বালানী পাথর হবে [আল হারিস আল আশয়ারী (রা.) থেকে আহমদ, তিরমিযি, বাব উল এমারাত, মেশকাত]।
                    </p>
                    <p>
                        দুর্ভাগ্যবশতঃ বিশ্বনবীর ওফাতের ৬০/৭০ বৎসর পর এবলিস এই উম্মাহর আকীদায় বিকৃতি ঢুকিয়ে দিতে সমর্থ হল। যার ফলে এই জাতি আল্লাহর রাস্তায় জেহাদ ও ঐ ৫ দফা কর্মসূচি দু’টোই ত্যাগ করে ইসলাম ও উম্মতে মোহাম্মদী দু’টো থেকেই বহি®কৃত হয়ে গেল। সেই থেকে এই কর্মসূচি যে পরিত্যক্ত হয়েছিল এই তেরশ’ বছর ওটা পরিত্যক্ত হয়েই ছিল। হাদীসের বইগুলিতে কর্মসূচির ঐ হাদীসটি যথাস্থানেই আছে। এই তেরশ’ বছরে এটি লক্ষ-কোটিবার পড়া হয়েছে, পড়েছেন লক্ষ লক্ষ আলেম, ফকিহ, মুফাসসের, মোহাদ্দেস, শায়েখ, দরবেশরা, কিন্তু বোঝেন নি যে এটি এই উম্মাহর জন্য স্রষ্টার দেয়া একমাত্র কর্মসূচি-তরিকা। গত কয়েক শতাব্দিতে এই দীনকে পুনরুজ্জীবিত করার প্রচেষ্টায় বিভিন্ন মুসলিম দেশগুলিতে শত শত দল, আন্দোলন, সংগঠন করা হয়েছে, কিন্তু আকীদার বিকৃতির কারণে আল্লাহ তাদেরকে ঐ কর্মসূচি বুঝতে দেন নি। ফলে ঐ সব সংগঠনগুলি বিভিন্ন রকমের ও বিভিন্ন দফার কর্মসূচি নিজেরা তৈরি করে নিয়েছে। কোথাও কেউ সাফল্য লাভ করে নি, পৃথিবীর এক ইঞ্চি জমিতেও তারা ইসলামকে প্রতিষ্ঠা করতে পারেন নি। কারণ সবগুলোরই কর্মসূচি মানুষের মস্তিস্ক প্রসূত, আল্লাহর দেয়া নয়। ফলে তাদের ঐ প্রচেষ্টায় আল্লাহর কোন সাহায্য আসে নি। আর আল্লাহর সাহায্য ছাড়া আল্লাহর রসুলও সত্যদীন প্রতিষ্ঠা করতে পারতেন না।
                    </p>
                </div>
            </div>

            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3" style="color: #0f172a; font-size: 2.2rem;">আল্লাহর দেয়া এই ৫ দফা কমসূচির ব্যাখ্যা:</h2>
                <p class="text-secondary">নিচের ধাপগুলো নির্বাচন করে বিস্তারিত ব্যাখ্যা ও দলিলসমূহ দেখুন</p>
            </div>

            <!-- Stepper Container -->
            <div class="stepper-container">
                
                <!-- Stepper Progress Bar -->
                <div class="stepper-row">
                    <div class="stepper-line-bg"></div>
                    <div class="stepper-line-active" id="progress-line"></div>
                    
                    <div class="step-node active" onclick="goToStep(0)">
                        <div class="step-circle">১</div>
                        <div class="step-label">ঐক্যবদ্ধ হওয়া</div>
                    </div>
                    <div class="step-node" onclick="goToStep(1)">
                        <div class="step-circle">২</div>
                        <div class="step-label">আদেশ শোনা</div>
                    </div>
                    <div class="step-node" onclick="goToStep(2)">
                        <div class="step-circle">৩</div>
                        <div class="step-label">আনুগত্য করা</div>
                    </div>
                    <div class="step-node" onclick="goToStep(3)">
                        <div class="step-circle">৪</div>
                        <div class="step-label">হিজরত করা</div>
                    </div>
                    <div class="step-node" onclick="goToStep(4)">
                        <div class="step-circle">৫</div>
                        <div class="step-label">জেহাদ করা</div>
                    </div>
                </div>

                <!-- Step Panels -->
                <div class="step-panel-wrapper">
                    
                    <!-- Panel 1 -->
                    <div class="step-panel active">
                        <div class="panel-grid">
                            <div class="panel-details">
                                <h3><i class="fas fa-users"></i> প্রথম দায়িত্ব – ঐক্যবদ্ধ হওয়া।</h3>
                                <div class="panel-text">
                                    <p>
                                        কারণ ঐক্য ছাড়া কোন অভীষ্ট, কাক্সিক্ষত লক্ষ্য অর্জন করা সম্ভব নয়। কোন জাতি বা রাষ্ট্র যদি ধনবলে, লোকবলে, সামরিক বলে যতই শক্তিশালী হোক সেটা তার অভীষ্ট কাজে সক্ষম হবে না, তারচেয়ে অনেক দুর্বল ঐক্যবদ্ধ শত্রুর কাছেও পরাজিত হবে। যেমন বর্তমান মুসলিম নামক জনসংখ্যা। এ জন্যই আল্লাহ তাঁর কোর’আনে বারবার সুদৃঢ় ঐক্যের কথা বলেছেন, সুরা আল এমরানের ১০৩ নং আয়াতে বলেছেন- ঐক্যবদ্ধ হয়ে আল্লাহর রজ্জু (হেদায়াহ, দীন) ধরে রাখতে, বিচ্ছিন্ন না হতে; সুরা সফ-এর ৪ নং আয়াতে আবার বলেছেন, ‘আল্লাহ তাদেরকে কতই ভালোবাসেন যারা গলিত সীসার প্রাচীরের মত ঐক্যবদ্ধ হয়ে আল্লাহর রাস্তায় যুদ্ধ করে। যে সব কাজে ও কথায় উম্মাহর ঐক্য নষ্ট হবার সম্ভাবনা আছে যেমন মতভেদ, গীবত ইত্যাদি সে সব কাজকে আল্লাহর রসুল সরাসরি কুফর বলে আখ্যায়িত করেছেন। বর্তমানে ১৫০ কোটির মুসলিম নামধারী জনসংখ্যাটি বহু ভৌগোলিক রাষ্ট্রে, রাজনৈতিকভাবে বহু মতাদর্শে বিভক্ত। ধর্মীয়ভাবে বহু ফেরকা, মাজহাব, আধ্যাত্মিকভাবে শত শত তরীকায় ছিন্নভিন্ন। আজ একমাত্র হেযবুত তওহীদ এই শতধাবিচ্ছিন্ন মানবজাতিকে আল্লাহর সার্বভৌমত্বের ভিত্তিতে ঐক্যবদ্ধ করে একটি মহাজাতিতে পরিণত করার সংগ্রাম চালিয়ে যাচ্ছে।
                                    </p>
                                </div>
                            </div>
                            <div class="panel-illustration">
                                <div class="illustration-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="illustration-quote">
                                    "ঐক্যবদ্ধ হয়ে আল্লাহর রজ্জু ধরে রাখো এবং বিচ্ছিন্ন হয়ো না।"
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel 2 -->
                    <div class="step-panel">
                        <div class="panel-grid">
                            <div class="panel-details">
                                <h3><i class="fas fa-volume-up"></i> দ্বিতীয় দায়িত্ব – (নেতার আদেশ) শোনা।</h3>
                                <div class="panel-text">
                                    <p>
                                        সতর্কতার সাথে কোন বিষয়ে সদা, সর্বদা সচেতন হয়ে থাকা বোঝায়। এখানে এই সচেতনতা হচ্ছে দু’টি বিষয়ে। একটি নেতার আদেশ শোনার প্রতি সদা সর্বদা কান খাড়া করে রাখা, আমিরের কখন কি আদেশ হয় তা তৎক্ষণাৎ পালনের জন্য প্রস্তুত থাকা এবং অপরটি নিজেদের শৃংখলা অটুট রাখা। মানবজাতিকে সত্যিকারভাবে শৃঙ্খলাবদ্ধ করার জন্য সর্বপ্রথম যে জিনিসটি অপরিহার্য তা হল, তাদেরকে একক নেতৃত্বের অধীনে নিয়ে আসা, এতে করে সকলেই একই নিয়ম শৃঙ্খলার মধ্যে জীবন অতিবাহিত করতে সক্ষম হবে। সৃষ্টিজগতে যেমন বিধাতা একজন হওয়ায় কোথাও কোন বিশৃঙ্খলা নেই, তেমনি সমগ্র মানবজাতিতে যখন একজন মাত্র নেতা থাকবেন তখন মানবসমাজেও প্রতিষ্ঠিত হবে বিশ্বপ্রকৃতির ন্যায় অতুলনীয় শৃঙ্খলা, সঙ্গতি ও সমন্বয়।
                                    </p>
                                </div>
                            </div>
                            <div class="panel-illustration">
                                <div class="illustration-icon">
                                    <i class="fas fa-volume-up"></i>
                                </div>
                                <div class="illustration-quote">
                                    "শৃঙ্খলাবদ্ধ হওয়া সৃষ্টিজগতের শৃঙ্খলার মতোই অপরিহার্য।"
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel 3 -->
                    <div class="step-panel">
                        <div class="panel-grid">
                            <div class="panel-details">
                                <h3><i class="fas fa-check-circle"></i> তৃতীয় দায়িত্ব – (নেতার ঐ আদেশের) আনুগত্য করা।</h3>
                                <div class="panel-text">
                                    <p>
                                        কর্মসূচির অন্যতম প্রধান গুরুত্বপূর্ণ বিষয় হচ্ছে আনুগত্য। এই দীনে আনুগত্য হল, আদেশ শোনামাত্র বিন্দুমাত্র ইতঃস্তত না করে সঙ্গে সঙ্গে সে আদেশ পালন করা। আনুগত্য হচ্ছে একটি পরিবার, গোষ্ঠী বা জাতির মেরুদ-, এটা যেখানে দুর্বল সেখানেই অক্ষমতা এবং ব্যর্থতা। এ নির্দেশ শুধু রসুলেরই নয়, এ নির্দেশ স্বয়ং আল্লাহর। তিনি কোর’আনে মো’মেন, উম্মতে মোহাম্মদীকে আদেশ করেছেন- আল্লাহর আনুগত্য কর, তাঁর রসুলের আনুগত্য কর এবং তোমাদের মধ্য থেকে আদেশকারীর (নেতার) আনুগত্য কর (সুরা নেসা ৫৯)।
                                    </p>
                                    <p>
                                        আনুগত্য হচ্ছে শান্তির মূলমন্ত্র, আল্লাহর আনুগত্যই ইসলাম, ইসলাম মানেই শান্তি। আজকের পৃথিবীতে কোন দেশের রাষ্ট্রপ্রধান যখনই তার জাতিকে কোন আদেশ বা বিধান দেন, সঙ্গে সঙ্গে শুরু হয় এর বিরুদ্ধাচারণ ও সমালোচনা। কিন্তু ইসলামের বেলায় আল্লাহর কোন আদেশের ব্যাপারে মতান্তর বা বিরোধিতার প্রশ্ন অবান্তর তেমনি আল্লাহর রসুলের নির্দেশ হচ্ছে, ‘কোন ক্ষুদ্রবুদ্ধি, কান কাটা, নিগ্রো, ক্রীতদাসও যদি তোমাদের নেতা নিয়োজিত হয়, তবে তার কথা বিনা প্রশ্নে, বিনা দ্বিধায় শুনতে ও মানতে হবে।’ কারণ নেতার আদেশ প্রকারান্তরে আল্লাহরই আদেশ।
                                    </p>
                                </div>
                            </div>
                            <div class="panel-illustration">
                                <div class="illustration-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="illustration-quote">
                                    "আনুগত্য হচ্ছে শান্তির মূলমন্ত্র, আল্লাহর আনুগত্যই ইসলাম।"
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel 4 -->
                    <div class="step-panel">
                        <div class="panel-grid">
                            <div class="panel-details">
                                <h3><i class="fas fa-sign-out-alt"></i> четвертый দায়িত্ব – হিজরত</h3>
                                <div class="panel-text">
                                    <p>
                                        হিজরত শব্দের অর্থ শুধু দেশ ত্যাগ করা নয়। হিজরত শব্দের অর্থঃ- “সম্পর্কচ্ছেদ করা, দল বর্জন করা, স্বদেশ পরিত্যাগ করিয়া ভিন্নদেশে গমন করা” (সংক্ষিপ্ত ইসলামী বিশ্বকোষ, দ্বিতীয় খ-, পৃঃ ৫৬০-৬১, ইসলামিক ফাউণ্ডেশন)। আল্লাহয় বিশ্বাসী অথচ মোশরেক আরবদের মধ্যে আবির্ভূত হয়ে বিশ্বনবী যখন প্রকৃত তওহীদের ডাক দিলেন তখন যারা তাঁর সাথে যোগ দিলেন তারা আরবদের ঐ র্শেক ও কুফর থেকে হিজরত করলেন। তারা মোশরেক কাফেরদের সাথে ব্যবসা-বাণিজ্য, ওঠা-বসা সবই করতেন কিন্তু তাদের মধ্যে বাস করেও হৃদয়ের দিক থেকে তাদের সঙ্গে সম্পর্কচ্ছেদ করলেন, তাদের দল বর্জন করলেন, তাদের সাথে এবাদত করা ছেড়ে দিলেন এবং আল্লাহর রসুলকে কেন্দ্র করে তওহীদ ভিত্তিক একটি আলাদা সমাজ, আলাদা ভ্রাতৃত্ব গড়ে তুললেন। ১৩ বছর পর মক্কা থেকে মদীনায় যেয়ে তৃতীয় প্রকার হিজরত করলেন। মক্কা জয়ের পর এই তৃতীয় প্রকারের হিজরতের আর প্রয়োজন রইল না, কিন্তু বাকি দুই প্রকারের হিজরতের প্রয়োজন রয়ে গেল এবং আজও আছে। দীন যখনই বিকৃত হয়ে যাবে, বৃহত্তর জনসংখ্যা যখনই ঐ বিকৃত দীনের ভ্রান্ত পথে চোলবে, তখন আল্লাহ তাঁর অসীম করুণায় যাদের সেরাতুল মুস্তাকীমে হেদায়াত করবেন, তাদের ঐ সংখ্যাগরিষ্ঠ পথভ্রান্তদের সঙ্গে সম্পর্কচ্ছেদ ও তাদের দল বর্জন করতে হবে। চৌদ্দশ’ বছর আগে আল্লাহতে বিশ্বাসী আরবের মোশরেকদের মধ্যে মহানবী ও তাঁর আসহাবদের যে ভূমিকা ছিল, আজ আল্লাহ বিশ্বাসী কিন্তু কার্যতঃ মোশরেক সমাজের মধ্যে হেযবুত তওহীদের সেই ভূমিকা। সেদিন আল্লাহর রসুল যেমন মানুষকে আল্লাহর প্রকৃত তওহীদে, সর্বব্যাপী তওহীদে আহ্বান করেছিলেন, সেই নবীর প্রকৃত উম্মাহ হিসাবে হেযবুত তওহীদ সেই একই আহবান করছে। সেদিন আল্লাহর রসুল ও তাঁর আসহাবগণ যেমন দীনের ব্যাপারে ঐ সমাজ থেকে হিজরত করেছিলেন, তাদের সাথে এবাদত করা ছেড়ে দিয়েছিলেন, আজ ঠিক তেমনিভাবে প্রকৃত উম্মতে মোহাম্মদী হবার প্রচেষ্টারত হেযবুত তওহীদও বর্তমান ‘মুসলিম’ কিন্তু কার্যতঃ কাফের মোশরেক সমাজ থেকে ধর্মীয় সকল এবাদতের ব্যাপারে হিজরত করেছে। রসুলাল্লাহর সময়ে তিনি ও তাঁর আসহাবগণ মক্কা থেকে হিজরত করে মদীনাতে গিয়েছিলেন, সেখানে আল্লাহর সার্বভৌমত্ব প্রতিষ্ঠা করেছিলেন। কিন্তু বর্তমানের প্রেক্ষাপট সে সময় থেকে একটু ভিন্ন। বর্তমানে সারা পৃথিবী দাজ্জালের পদতলে। হিজরত করে কোথাও যাওয়ার উপায় নেই। দাজ্জালের করতলে থেকেই তার বিরুদ্ধে সংগ্রাম চালিয়ে যেতে হচ্ছে হেযবুত তওহীদকে।
                                    </p>
                                </div>
                            </div>
                            <div class="panel-illustration">
                                <div class="illustration-icon">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div class="illustration-quote">
                                    "আদর্শিকভাবে বিকৃত ও ভ্রান্ত পরিবেশ বর্জন করে সত্যের বন্ধনে শামিল হওয়া।"
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel 5 -->
                    <div class="step-panel">
                        <div class="panel-grid">
                            <div class="panel-details">
                                <h3><i class="fas fa-shield-alt"></i> পঞ্চম দায়িত্ব – আল্লাহর রাস্তায় জেহাদ করা।</h3>
                                <div class="panel-text">
                                    <p>
                                        কর্মসূচির প্রথম চারটি দায়িত্বের উদ্দেশ্য ও লক্ষ্যই হল জেহাদ করা। জেহাদ বাদ দিয়ে ঐ চারটি পালন করা অর্থহীন। একটি সংবিধান, সেটি যতো সুন্দর, নিখুঁতই হোক না কেন, সেটা একটা জনসমষ্টি বা জাতির ওপর প্রয়োগ ও কার্যকর না করা হলে যেমন সেটা অর্থহীন, তেমনি তওহীদের ওপর ভিত্তি করা দীনুল হকের সংবিধান “কোর’আনকে” মানব জীবনের সর্বস্তরে, সর্ব অঙ্গনে কার্যকর না করতে পারলে তা অর্থহীন। এই সংবিধানকে প্রয়োগ ও কার্যকর করার নীতি পদ্ধতি আল্লাহ নির্দিষ্ট করে দিয়েছেন- জেহাদ ও কেতাল- সর্বাত্মক সংগ্রাম (সুরা বাকারা- ২১৬ ও অন্যান্য বহু আয়াত)। তাই মো’মেনদের অর্থাৎ প্রকৃত বিশ্বাসীদের সংজ্ঞাকে আল্লাহ শুধু সর্বব্যাপী তওহীদের মধ্যে সীমাবদ্ধ রাখেন নি, ঐ তওহীদকে প্রতিষ্ঠা ও কার্যকর করার জন্য জেহাদকেও অন্তর্ভুক্ত করে দিয়েছেন। বলেছেন- শুধু তারাই সত্যনিষ্ঠ মো’মেন যারা আল্লাহ ও তার রসুলকে বিশ্বাস করেছে, তারপর আর তাতে কখনও সন্দেহ করে নি এবং তাদের জান ও সম্পদ দিয়ে আল্লাহর রাস্তায় জেহাদ করেছে (সুরা হুজরাত- ১৫)।
                                    </p>
                                    <p>
                                        দীর্ঘ তেরশ’ বছর এই উম্মাহকে এই পবিত্র কর্মসূচি থেকে মাহরুম, বঞ্চিত রাখার পর রাহমানুর রহিম আল্লাহ তাঁর অসীম করুণায় তাঁর দেয়া কর্মসূচির পরিচয় মাননীয় এমামুয্যামানকে বোঝার তওফিক দান করেছেন। আল্লাহর এতবড় অনুগ্রহ থেকেই প্রমাণ হয় যে এটাই তাঁর নিজের আন্দোলন।
                                    </p>
                                </div>
                            </div>
                            <div class="panel-illustration">
                                <div class="illustration-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="illustration-quote">
                                    "আল্লাহর পবিত্র সংবিধানকে বাস্তব জীবনে কার্যকর করার সর্বাত্মক সংগ্রাম।"
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Navigation Controls -->
                <div class="stepper-nav-buttons">
                    <button class="btn btn-stepper btn-prev" id="btn-prev" onclick="moveStep(-1)" disabled>
                        <i class="fas fa-arrow-left"></i> পূর্ববর্তী
                    </button>
                    <button class="btn btn-stepper btn-next" id="btn-next" onclick="moveStep(1)">
                        পরবর্তী <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>

        </div>
    </section>

    <!-- Stepper Logic script -->
    <script>
        let currentStep = 0;
        const totalSteps = 5;

        function updateStepperUI() {
            const nodes = document.querySelectorAll('.step-node');
            const panels = document.querySelectorAll('.step-panel');
            const line = document.getElementById('progress-line');

            // Update Nodes status
            nodes.forEach((node, index) => {
                if (index === currentStep) {
                    node.classList.add('active');
                    node.classList.remove('completed');
                } else if (index < currentStep) {
                    node.classList.remove('active');
                    node.classList.add('completed');
                } else {
                    node.classList.remove('active', 'completed');
                }
            });

            // Update Line progress
            let progressPercentage = (currentStep / (totalSteps - 1)) * 100;
            // On mobile, progress goes top to bottom instead
            if (window.innerWidth <= 767.98) {
                line.style.height = `${progressPercentage}%`;
                line.style.width = '4px';
            } else {
                line.style.width = `${progressPercentage}%`;
                line.style.height = '4px';
            }

            // Update Content Panels with smooth fade
            panels.forEach((panel, index) => {
                if (index === currentStep) {
                    panel.style.display = 'block';
                    setTimeout(() => {
                        panel.classList.add('active');
                    }, 50);
                } else {
                    panel.classList.remove('active');
                    setTimeout(() => {
                        if (!panel.classList.contains('active')) {
                            panel.style.display = 'none';
                        }
                    }, 300);
                }
            });

            // Update buttons state
            const prevBtn = document.getElementById('btn-prev');
            const nextBtn = document.getElementById('btn-next');

            if (currentStep === 0) {
                prevBtn.disabled = true;
            } else {
                prevBtn.disabled = false;
            }

            if (currentStep === totalSteps - 1) {
                nextBtn.innerHTML = `সমাপ্ত <i class="fas fa-check"></i>`;
            } else {
                nextBtn.innerHTML = `পরবর্তী <i class="fas fa-arrow-right"></i>`;
            }
        }

        function goToStep(stepIndex) {
            currentStep = stepIndex;
            updateStepperUI();
        }

        function moveStep(direction) {
            let nextStep = currentStep + direction;
            if (nextStep >= 0 && nextStep < totalSteps) {
                currentStep = nextStep;
                updateStepperUI();
            }
        }

        // Initialize on load
        window.addEventListener('DOMContentLoaded', () => {
            updateStepperUI();
        });

        // Recalculate line on resize
        window.addEventListener('resize', () => {
            updateStepperUI();
        });
    </script>

@endsection
