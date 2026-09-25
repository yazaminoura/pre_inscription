<?php

namespace Database\Seeders;

use App\Models\Etablissement;
use App\Models\Formation;
use Illuminate\Database\Seeder;

/**
 * Traductions anglais / arabe des données de démonstration (établissement + 6 formations).
 * N'écrase jamais une traduction déjà saisie dans l'administration.
 */
class DemoTraductionsSeeder extends Seeder
{
    public function run(): void
    {
        $etablissement = Etablissement::query()->where('nom', 'Université Horizon')->first();
        if ($etablissement && empty($etablissement->traductions)) {
            $etablissement->update(['traductions' => [
                'en' => [
                    'nom' => 'Horizon University',
                    'slogan' => 'Apply online for the ' . date('Y') . ' intake: one programme, one form, one reference.',
                    'presentation' => "Horizon University is a demonstration institution: replace this text from the admin area (Settings > Institution).\n\nDescribe your institution here: its history, departments, laboratories, partnerships and what makes it unique for future students.",
                ],
                'ar' => [
                    'nom' => 'جامعة الأفق',
                    'slogan' => 'أودع تسجيلك القبلي عبر الإنترنت لموسم ' . date('Y') . ': تكوين واحد، استمارة واحدة، مرجع واحد.',
                    'presentation' => "جامعة الأفق مؤسسة تجريبية: عوّض هذا النص من فضاء الإدارة (الإعدادات > المؤسسة).\n\nقدّم هنا مؤسستك: تاريخها وشعبها ومختبراتها وشراكاتها وما يميزها بالنسبة لطلبتك المستقبليين.",
                ],
            ]]);
        }

        $traductions = [
            'Licence Génie Informatique' => [
                'en' => ['titre' => "Bachelor's in Computer Engineering", 'duree' => '3 years (6 semesters)',
                    'description' => 'A solid grounding in programming, databases, networks and software engineering, with team projects every semester and a company internship in the final year.',
                    'conditions_acces' => "Science or technical baccalaureate\nGood marks in mathematics",
                    'modalites_selection' => "Application review\nWritten test in mathematics and logic",
                    'debouches' => "Software developer\nSystems and network administrator\nMaster's studies"],
                'ar' => ['titre' => 'إجازة في هندسة المعلوميات', 'duree' => '3 سنوات (6 فصول)',
                    'description' => 'تكوين متين في البرمجة وقواعد البيانات والشبكات وهندسة البرمجيات، مع مشاريع جماعية كل فصل وتدريب في المقاولة خلال السنة الأخيرة.',
                    'conditions_acces' => "باكالوريا علمية أو تقنية\nمعدل جيد في الرياضيات",
                    'modalites_selection' => "دراسة الملف\nاختبار كتابي في الرياضيات والمنطق",
                    'debouches' => "مطور برمجيات\nمسؤول الأنظمة والشبكات\nمتابعة الدراسة في الماستر"],
            ],
            'Licence Génie Électrique' => [
                'en' => ['titre' => "Bachelor's in Electrical Engineering", 'duree' => '3 years (6 semesters)',
                    'description' => 'Electronics, electrical engineering, automation and industrial computing, with a strong focus on hands-on laboratory work.',
                    'conditions_acces' => 'Baccalaureate in Mathematical Sciences or Physical Sciences',
                    'modalites_selection' => "Application review\nInterview",
                    'debouches' => "Electrical technician\nAutomation specialist\nMaster's studies"],
                'ar' => ['titre' => 'إجازة في الهندسة الكهربائية', 'duree' => '3 سنوات (6 فصول)',
                    'description' => 'الإلكترونيات والكهروتقنية والآلية والمعلوميات الصناعية، مع حصة مهمة من الأشغال التطبيقية في المختبر.',
                    'conditions_acces' => 'باكالوريا العلوم الرياضية أو العلوم الفيزيائية',
                    'modalites_selection' => "دراسة الملف\nمقابلة",
                    'debouches' => "تقني عالٍ في الكهرباء\nمختص في الآلية\nمتابعة الدراسة في الماستر"],
            ],
            'Licence Biotechnologie' => [
                'en' => ['titre' => "Bachelor's in Biotechnology", 'duree' => '3 years (6 semesters)',
                    'description' => 'Molecular biology, microbiology and biochemistry applied to health, the food industry and the environment.',
                    'conditions_acces' => 'Baccalaureate in Life and Earth Sciences or Physical Sciences',
                    'modalites_selection' => 'Application review',
                    'debouches' => "Laboratory technician\nQuality control\nMaster's studies"],
                'ar' => ['titre' => 'إجازة في البيوتكنولوجيا', 'duree' => '3 سنوات (6 فصول)',
                    'description' => 'البيولوجيا الجزيئية والميكروبيولوجيا والكيمياء الحيوية المطبقة في الصحة والصناعات الغذائية والبيئة.',
                    'conditions_acces' => 'باكالوريا علوم الحياة والأرض أو العلوم الفيزيائية',
                    'modalites_selection' => 'دراسة الملف',
                    'debouches' => "تقني مختبر\nمراقبة الجودة\nمتابعة الدراسة في الماستر"],
            ],
            'Master Intelligence Artificielle et Data Science' => [
                'en' => ['titre' => "Master's in Artificial Intelligence and Data Science", 'duree' => '2 years (4 semesters)',
                    'description' => 'Machine learning, deep learning, big data and statistics, with a final-year project in a company or research lab.',
                    'conditions_acces' => "Bachelor's in computer science, mathematics or equivalent\nGood grounding in programming (Python) and statistics",
                    'modalites_selection' => "Shortlisting on application\nWritten test\nInterview",
                    'debouches' => "Data scientist\nMachine learning engineer\nData engineer\nPhD"],
                'ar' => ['titre' => 'ماستر الذكاء الاصطناعي وعلوم البيانات', 'duree' => 'سنتان (4 فصول)',
                    'description' => 'التعلم الآلي والتعلم العميق والبيانات الضخمة والإحصاء، مع مشروع نهاية الدراسة في مقاولة أو مختبر.',
                    'conditions_acces' => "إجازة في المعلوميات أو الرياضيات أو ما يعادلها\nأسس جيدة في البرمجة (Python) والإحصاء",
                    'modalites_selection' => "انتقاء أولي بناءً على الملف\nاختبار كتابي\nمقابلة شفوية",
                    'debouches' => "عالم بيانات\nمهندس تعلم آلي\nمهندس بيانات\nالدكتوراه"],
            ],
            'Master Génie Logiciel' => [
                'en' => ['titre' => "Master's in Software Engineering", 'duree' => '2 years (4 semesters)',
                    'description' => 'Software architecture, agile methods, DevOps, application quality and security.',
                    'conditions_acces' => "Bachelor's in computer science or equivalent",
                    'modalites_selection' => "Shortlisting on application\nInterview",
                    'debouches' => "Software architect\nIT project manager\nDevOps engineer"],
                'ar' => ['titre' => 'ماستر هندسة البرمجيات', 'duree' => 'سنتان (4 فصول)',
                    'description' => 'هندسة البرمجيات والمناهج الرشيقة وDevOps وجودة التطبيقات وأمنها.',
                    'conditions_acces' => 'إجازة في المعلوميات أو ما يعادلها',
                    'modalites_selection' => "انتقاء أولي بناءً على الملف\nمقابلة شفوية",
                    'debouches' => "مهندس معماري للبرمجيات\nمدير مشاريع معلوماتية\nمهندس DevOps"],
            ],
            'Master Énergies Renouvelables' => [
                'en' => ['titre' => "Master's in Renewable Energy", 'duree' => '2 years (4 semesters)',
                    'description' => 'Solar, wind, energy efficiency and smart grid management.',
                    'conditions_acces' => "Bachelor's in physics, electrical or energy engineering",
                    'modalites_selection' => "Shortlisting on application\nWritten test\nInterview",
                    'debouches' => "Renewable energy engineer\nEnergy auditor\nEnergy project manager"],
                'ar' => ['titre' => 'ماستر الطاقات المتجددة', 'duree' => 'سنتان (4 فصول)',
                    'description' => 'الطاقة الشمسية والريحية والنجاعة الطاقية وتدبير الشبكات الكهربائية الذكية.',
                    'conditions_acces' => 'إجازة في الفيزياء أو الكهرباء أو الطاقة',
                    'modalites_selection' => "انتقاء أولي بناءً على الملف\nاختبار كتابي\nمقابلة",
                    'debouches' => "مهندس في الطاقات المتجددة\nمدقق طاقي\nمدير مشاريع الطاقة"],
            ],
        ];

        foreach ($traductions as $titre => $valeurs) {
            $formation = Formation::where('titre', $titre)->first();
            if ($formation && empty($formation->traductions)) {
                $formation->update(['traductions' => $valeurs]);
            }
        }
    }
}
