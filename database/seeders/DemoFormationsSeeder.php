<?php

namespace Database\Seeders;

use App\Models\Formation;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Formations de démonstration des autres types (DUT, BTS, Licence pro, cycle ingénieur, Master spécialisé,
 * doctorat, formation continue), avec niveaux d'accès variés, voies alternatives et traductions EN / AR.
 * Ne crée que celles qui n'existent pas encore (par intitulé).
 */
class DemoFormationsSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->value('id');
        if (!$adminId) {
            return;
        }

        foreach ($this->formations() as $f) {
            if (Formation::where('titre', $f['titre'])->exists()) {
                continue;
            }
            Formation::create($f + ['user_id' => $adminId]);
        }
    }

    private function formations(): array
    {
        $annee = (int) date('Y');

        return [
            [
                'type_formation' => 'DUT', 'titre' => 'DUT Génie Informatique',
                'niveau_acces' => 'bac', 'duree' => '2 ans (4 semestres)', 'places' => 48,
                'date_debut' => "$annee-09-01", 'date_fin' => "$annee-10-31",
                'description' => "Un diplôme court et professionnalisant : programmation, bases de données, réseaux et développement web, avec un stage de 10 semaines en entreprise.",
                'conditions_acces' => "Baccalauréat scientifique, technique ou économique\nBonne moyenne en mathématiques",
                'modalites_selection' => "Présélection sur la moyenne du bac\nTest écrit",
                'debouches' => "Technicien développeur\nTechnicien réseaux\nLicence professionnelle\nÉcole d'ingénieurs",
                'traductions' => [
                    'en' => ['titre' => 'Two-year technical diploma in Computer Science', 'duree' => '2 years (4 semesters)',
                        'description' => 'A short, career-focused diploma: programming, databases, networks and web development, with a 10-week company internship.',
                        'conditions_acces' => "Science, technical or economics baccalaureate\nGood marks in mathematics",
                        'modalites_selection' => "Shortlisting on baccalaureate average\nWritten test",
                        'debouches' => "Software technician\nNetwork technician\nProfessional bachelor's\nEngineering school"],
                    'ar' => ['titre' => 'دبلوم جامعي للتكنولوجيا في هندسة المعلوميات', 'duree' => 'سنتان (4 فصول)',
                        'description' => 'دبلوم قصير ومهني: البرمجة وقواعد البيانات والشبكات وتطوير الويب، مع تدريب لمدة 10 أسابيع في المقاولة.',
                        'conditions_acces' => "باكالوريا علمية أو تقنية أو اقتصادية\nمعدل جيد في الرياضيات",
                        'modalites_selection' => "انتقاء أولي حسب معدل الباكالوريا\nاختبار كتابي",
                        'debouches' => "تقني مطور\nتقني شبكات\nإجازة مهنية\nمدرسة المهندسين"],
                ],
            ],
            [
                'type_formation' => 'BTS', 'titre' => "BTS Développement des Systèmes d'Information",
                'niveau_acces' => 'bac', 'duree' => '2 ans', 'places' => 30,
                'date_debut' => "$annee-09-01", 'date_fin' => date('Y-m-d', strtotime('+6 days')),
                'description' => "Formation pratique au développement d'applications de gestion et au support informatique, en petits groupes, avec deux stages.",
                'conditions_acces' => 'Baccalauréat, toutes séries scientifiques et techniques',
                'modalites_selection' => "Étude du dossier\nEntretien de motivation",
                'debouches' => "Développeur d'applications\nTechnicien support\nAdministrateur de bases de données junior",
                'traductions' => [
                    'en' => ['titre' => 'Higher technician diploma in Information Systems Development', 'duree' => '2 years',
                        'description' => 'Hands-on training in business application development and IT support, in small groups, with two internships.',
                        'conditions_acces' => 'Baccalaureate in any science or technical stream',
                        'modalites_selection' => "Application review\nMotivation interview",
                        'debouches' => "Application developer\nIT support technician\nJunior database administrator"],
                    'ar' => ['titre' => 'شهادة التقني العالي في تطوير أنظمة المعلومات', 'duree' => 'سنتان',
                        'description' => 'تكوين تطبيقي في تطوير تطبيقات التسيير والدعم المعلوماتي، ضمن مجموعات صغيرة، مع تدريبين.',
                        'conditions_acces' => 'باكالوريا جميع الشعب العلمية والتقنية',
                        'modalites_selection' => "دراسة الملف\nمقابلة التحفيز",
                        'debouches' => "مطور تطبيقات\nتقني الدعم المعلوماتي\nمسؤول قواعد بيانات مبتدئ"],
                ],
            ],
            [
                'type_formation' => 'Licence professionnelle', 'titre' => 'Licence professionnelle Développement Web et Mobile',
                'niveau_acces' => 'bac2', 'alternatif_niveau' => 'bac', 'alternatif_experience' => 3,
                'duree' => '1 an (2 semestres)', 'places' => 30,
                'date_debut' => "$annee-09-01", 'date_fin' => "$annee-11-15",
                'description' => "Une année pour devenir développeur web et mobile : frameworks modernes, API, applications Android/iOS et projet tuteuré en entreprise.",
                'conditions_acces' => "Diplôme Bac+2 en informatique (DUT, BTS, DEUST, DEUG…)\nOu baccalauréat avec 3 ans d'expérience en développement",
                'modalites_selection' => "Étude du dossier\nEntretien technique",
                'debouches' => "Développeur web full-stack\nDéveloppeur mobile\nIntégrateur",
                'traductions' => [
                    'en' => ['titre' => "Professional bachelor's in Web and Mobile Development", 'duree' => '1 year (2 semesters)',
                        'description' => 'One year to become a web and mobile developer: modern frameworks, APIs, Android/iOS apps and a supervised company project.',
                        'conditions_acces' => "Two-year diploma in computing (DUT, BTS, DEUST, DEUG…)\nOr baccalaureate with 3 years of development experience",
                        'modalites_selection' => "Application review\nTechnical interview",
                        'debouches' => "Full-stack web developer\nMobile developer\nFront-end integrator"],
                    'ar' => ['titre' => 'إجازة مهنية في تطوير الويب والتطبيقات المحمولة', 'duree' => 'سنة واحدة (فصلان)',
                        'description' => 'سنة لتصبح مطور ويب وتطبيقات محمولة: أطر عمل حديثة، واجهات برمجية، تطبيقات أندرويد وiOS ومشروع مؤطر في المقاولة.',
                        'conditions_acces' => "شهادة باك+2 في المعلوميات (DUT، BTS، DEUST، DEUG…)\nأو باكالوريا مع 3 سنوات من الخبرة في التطوير",
                        'modalites_selection' => "دراسة الملف\nمقابلة تقنية",
                        'debouches' => "مطور ويب متكامل\nمطور تطبيقات محمولة\nمدمج واجهات"],
                ],
            ],
            [
                'type_formation' => 'Cycle ingénieur', 'titre' => "Cycle d'ingénieur Génie Industriel",
                'niveau_acces' => 'bac2', 'duree' => '3 ans (6 semestres)', 'places' => 40,
                'date_debut' => "$annee-09-01", 'date_fin' => "$annee-12-15",
                'description' => "Former des ingénieurs capables de concevoir, piloter et améliorer les systèmes de production : logistique, qualité, maintenance et industrie 4.0.",
                'conditions_acces' => "Classes préparatoires (CPGE) ou diplôme Bac+2 scientifique (DEUST, DUT, DEUG)\nExcellent dossier en mathématiques et physique",
                'modalites_selection' => "Présélection sur dossier\nConcours écrit\nEntretien",
                'debouches' => "Ingénieur production\nIngénieur qualité\nIngénieur logistique\nConsultant industrie 4.0",
                'traductions' => [
                    'en' => ['titre' => 'Engineering degree in Industrial Engineering', 'duree' => '3 years (6 semesters)',
                        'description' => 'Training engineers to design, run and improve production systems: logistics, quality, maintenance and Industry 4.0.',
                        'conditions_acces' => "Preparatory classes (CPGE) or a two-year science diploma (DEUST, DUT, DEUG)\nExcellent record in mathematics and physics",
                        'modalites_selection' => "Shortlisting on application\nWritten entrance exam\nInterview",
                        'debouches' => "Production engineer\nQuality engineer\nLogistics engineer\nIndustry 4.0 consultant"],
                    'ar' => ['titre' => 'سلك المهندس في الهندسة الصناعية', 'duree' => '3 سنوات (6 فصول)',
                        'description' => 'تكوين مهندسين قادرين على تصميم أنظمة الإنتاج وقيادتها وتحسينها: اللوجستيك والجودة والصيانة والصناعة 4.0.',
                        'conditions_acces' => "الأقسام التحضيرية أو شهادة باك+2 علمية (DEUST، DUT، DEUG)\nملف ممتاز في الرياضيات والفيزياء",
                        'modalites_selection' => "انتقاء أولي بناءً على الملف\nمباراة كتابية\nمقابلة",
                        'debouches' => "مهندس إنتاج\nمهندس جودة\nمهندس لوجستيك\nمستشار في الصناعة 4.0"],
                ],
            ],
            [
                'type_formation' => 'Master spécialisé', 'titre' => 'Master spécialisé Management de Projets',
                'niveau_acces' => 'bac3', 'alternatif_niveau' => 'bac2', 'alternatif_experience' => 5,
                'duree' => '2 ans, en horaires aménagés', 'places' => 25,
                'date_debut' => "$annee-09-01", 'date_fin' => "$annee-11-30",
                'description' => "Pour les professionnels qui pilotent des projets : planification, budget, gestion des risques, méthodes agiles et leadership. Cours le soir et le samedi.",
                'conditions_acces' => "Licence ou diplôme Bac+3\nOu Bac+2 avec au moins 5 ans d'expérience professionnelle",
                'modalites_selection' => "Étude du dossier\nEntretien avec le jury",
                'debouches' => "Chef de projet\nResponsable PMO\nConsultant en organisation",
                'traductions' => [
                    'en' => ['titre' => "Specialised master's in Project Management", 'duree' => '2 years, evening and weekend classes',
                        'description' => 'For professionals who lead projects: planning, budgeting, risk management, agile methods and leadership. Evening and Saturday classes.',
                        'conditions_acces' => "Bachelor's or Bac+3 diploma\nOr Bac+2 with at least 5 years of work experience",
                        'modalites_selection' => "Application review\nInterview with the panel",
                        'debouches' => "Project manager\nPMO manager\nOrganisation consultant"],
                    'ar' => ['titre' => 'ماستر متخصص في تدبير المشاريع', 'duree' => 'سنتان، بتوقيت ملائم',
                        'description' => 'للمهنيين الذين يقودون المشاريع: التخطيط والميزانية وتدبير المخاطر والمناهج الرشيقة والقيادة. دروس مسائية وأيام السبت.',
                        'conditions_acces' => "إجازة أو شهادة باك+3\nأو باك+2 مع 5 سنوات على الأقل من الخبرة المهنية",
                        'modalites_selection' => "دراسة الملف\nمقابلة مع اللجنة",
                        'debouches' => "رئيس مشروع\nمسؤول مكتب المشاريع\nمستشار في التنظيم"],
                ],
            ],
            [
                'type_formation' => 'Doctorat', 'titre' => "Doctorat Sciences et Techniques de l'Ingénieur",
                'niveau_acces' => 'bac5', 'duree' => '3 ans', 'places' => 12,
                'date_debut' => "$annee-09-01", 'date_fin' => "$annee-10-20",
                'description' => "Préparer une thèse dans l'un des laboratoires de l'établissement : énergie, matériaux, informatique ou systèmes intelligents.",
                'conditions_acces' => "Master ou diplôme d'ingénieur (Bac+5)\nProjet de thèse validé par un directeur de recherche",
                'modalites_selection' => "Étude du dossier\nAudition devant la commission doctorale",
                'debouches' => "Enseignant-chercheur\nIngénieur R&D\nChercheur en entreprise",
                'traductions' => [
                    'en' => ['titre' => 'PhD in Engineering Sciences', 'duree' => '3 years',
                        'description' => "Prepare a thesis in one of the institution's laboratories: energy, materials, computer science or intelligent systems.",
                        'conditions_acces' => "Master's or engineering degree (Bac+5)\nThesis project approved by a research supervisor",
                        'modalites_selection' => "Application review\nHearing before the doctoral committee",
                        'debouches' => "Lecturer-researcher\nR&D engineer\nIndustrial researcher"],
                    'ar' => ['titre' => 'الدكتوراه في علوم وتقنيات المهندس', 'duree' => '3 سنوات',
                        'description' => 'إعداد أطروحة في أحد مختبرات المؤسسة: الطاقة أو المواد أو المعلوميات أو الأنظمة الذكية.',
                        'conditions_acces' => "ماستر أو دبلوم مهندس (باك+5)\nمشروع أطروحة مصادق عليه من طرف مشرف",
                        'modalites_selection' => "دراسة الملف\nمقابلة أمام لجنة الدكتوراه",
                        'debouches' => "أستاذ باحث\nمهندس بحث وتطوير\nباحث في المقاولة"],
                ],
            ],
            [
                'type_formation' => 'Formation continue', 'titre' => 'Certificat Data Analyst (formation continue)',
                'niveau_acces' => 'bac', 'duree' => '6 mois, le samedi', 'places' => 25,
                'date_debut' => "$annee-09-01", 'date_fin' => "$annee-12-31",
                'description' => "Une formation certifiante pour les salariés et demandeurs d'emploi : Excel avancé, SQL, Power BI et Python pour l'analyse de données.",
                'conditions_acces' => "Baccalauréat\nÀ l'aise avec l'outil informatique",
                'modalites_selection' => 'Entretien de motivation',
                'debouches' => "Data analyst\nChargé de reporting\nContrôleur de gestion",
                'traductions' => [
                    'en' => ['titre' => 'Data Analyst Certificate (continuing education)', 'duree' => '6 months, on Saturdays',
                        'description' => 'A certified programme for employees and job seekers: advanced Excel, SQL, Power BI and Python for data analysis.',
                        'conditions_acces' => "Baccalaureate\nComfortable using a computer",
                        'modalites_selection' => 'Motivation interview',
                        'debouches' => "Data analyst\nReporting officer\nManagement controller"],
                    'ar' => ['titre' => 'شهادة محلل البيانات (تكوين مستمر)', 'duree' => '6 أشهر، يوم السبت',
                        'description' => 'تكوين إشهادي للأجراء والباحثين عن عمل: إكسل متقدم وSQL وPower BI وبايثون لتحليل البيانات.',
                        'conditions_acces' => "الباكالوريا\nالتمكن من استعمال الحاسوب",
                        'modalites_selection' => 'مقابلة التحفيز',
                        'debouches' => "محلل بيانات\nمكلف بالتقارير\nمراقب التسيير"],
                ],
            ],
            [
                'type_formation' => 'Licence professionnelle', 'titre' => 'Licence professionnelle Cybersécurité',
                'niveau_acces' => 'bac2', 'duree' => '1 an (2 semestres)', 'places' => 24,
                'date_debut' => date('Y-m-d', strtotime('+20 days')), 'date_fin' => ($annee + 1) . '-01-31',
                'description' => "Sécurité des réseaux et des systèmes, tests d'intrusion, gestion des incidents et conformité. Ouverture prochaine des préinscriptions.",
                'conditions_acces' => 'Diplôme Bac+2 en informatique ou réseaux',
                'modalites_selection' => "Étude du dossier\nTest technique",
                'debouches' => "Analyste SOC\nTechnicien sécurité\nAuditeur junior",
                'traductions' => [
                    'en' => ['titre' => "Professional bachelor's in Cybersecurity", 'duree' => '1 year (2 semesters)',
                        'description' => 'Network and system security, penetration testing, incident management and compliance. Pre-registration opens soon.',
                        'conditions_acces' => 'Two-year diploma in computing or networks',
                        'modalites_selection' => "Application review\nTechnical test",
                        'debouches' => "SOC analyst\nSecurity technician\nJunior auditor"],
                    'ar' => ['titre' => 'إجازة مهنية في الأمن السيبراني', 'duree' => 'سنة واحدة (فصلان)',
                        'description' => 'أمن الشبكات والأنظمة، اختبارات الاختراق، تدبير الحوادث والمطابقة. يفتح التسجيل القبلي قريبًا.',
                        'conditions_acces' => 'شهادة باك+2 في المعلوميات أو الشبكات',
                        'modalites_selection' => "دراسة الملف\nاختبار تقني",
                        'debouches' => "محلل مركز العمليات الأمنية\nتقني أمن\nمدقق مبتدئ"],
                ],
            ],
        ];
    }
}
