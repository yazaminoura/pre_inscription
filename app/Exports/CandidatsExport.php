<?php

namespace App\Exports;

use App\Models\Candidat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class CandidatsExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithColumnFormatting
{
    protected $formationId;
    protected $baseUrl;

    public function __construct($formationId)
    {
        $this->formationId = $formationId;
        $this->baseUrl = (strpos(config('app.url'), 'localhost') !== false)
            ? 'http://127.0.0.1:8000/storage/'
            : 'https://fc.fst-usmba.ac.ma//storage/';
    }

    public function collection()
    {
        return Candidat::whereHas('inscriptions', function ($query) {
            $query->where('formation_id', $this->formationId);
        })
        ->with([
            'stages',
            'diplomes',
            'attestations',
            'experiences',
            'inscriptions.formation',
        ])
        ->get();
    }

   public function map($candidat): array
{
    $makeLink = function ($path, $displayText) {
        if (!empty($path) && $path !== '0') {
            return '=HYPERLINK("' . $this->baseUrl . $path . '", "' . $displayText . '")';
        }
        return '';
    };

    return [
        // Personal Information
        $candidat->id,
        $candidat->inscriptions->first()->formation->type_formation .' (' . $candidat->inscriptions->first()->formation->titre .')',
        $candidat->nom,
        $candidat->prenom,
        $candidat->nom_ar,
        $candidat->prenom_ar,
        $candidat->CNE,
        $candidat->CIN,
        // Modified email field to open Gmail compose window
        !empty($candidat->email) ? '=HYPERLINK("https://mail.google.com/mail/?view=cm&to=' . urlencode($candidat->email) . '", "' . $candidat->email . '")' : 'Email not provided',
        $candidat->date_naissance,
        $candidat->ville_naissance,
        $candidat->ville_naissance_ar,
        $candidat->province,
        $candidat->pay_naissance,
        $candidat->nationalite,
        $sexe = $candidat->sexe === 'M' ? 'Masculin' : ($candidat->sexe === 'F' ? 'Féminin' : $candidat->sexe),      
        !empty($candidat->telephone_mob) ? '=HYPERLINK("tel:' . urlencode($candidat->telephone_mob) . '", "' . $candidat->telephone_mob . '")' : 'Phone not provided',
        !empty($candidat->telephone_fix) ? '=HYPERLINK("tel:' . urlencode($candidat->telephone_fix) . '", "' . $candidat->telephone_fix . '")' : 'Phone not provided',

       
        $candidat->adresse,
        $candidat->ville,
        $candidat->pays,

        // Main Documents
        $makeLink($candidat->CV, 'Voir CV'),
        $makeLink($candidat->demande, 'Voir Demande'),
        $makeLink($candidat->scan_cartid, 'Voir CIN'),
        $makeLink($candidat->photo, 'Voir Photo'),

        // Baccalaureate
        $candidat->serie_bac,
        $candidat->annee_bac,
        $makeLink($candidat->scan_bac, 'Bac'),

        // Bac+2
        $candidat->diplomes->pluck('type_diplome_bac_2')->first() ?? '',
        $candidat->diplomes->pluck('annee_diplome_bac_2')->first() ?? '',
        $candidat->diplomes->pluck('filiere_diplome_bac_2')->first() ?? '',
        $candidat->diplomes->pluck('etablissement_bac_2')->first() ?? '',
        $makeLink($candidat->diplomes->pluck('scan_bac_2')->first(), 'Bac2'),

        // Bac3
        $candidat->diplomes->pluck('type_diplome_bac_3')->first() ?? '',
        $candidat->diplomes->pluck('annee_diplome_bac_3')->first() ?? '',
        $candidat->diplomes->pluck('filiere_diplome_bac_3')->first() ?? '',
        $candidat->diplomes->pluck('etablissement_bac_3')->first() ?? '',
        $makeLink($candidat->diplomes->pluck('scan_bac_3')->first(), 'Bac3'),

        // Stages (Internships)
        ...$this->getStageData($candidat->stages),

        // Work Experiences
        ...$this->getExperienceData($candidat->experiences),

        // Attestations
        ...$this->getAttestationData($candidat->attestations),
    ];
}    protected function getStageData($stages)
    {
        $stageData = [];
        
        foreach ($stages as $stage) {
            $stageData[] = $stage->fonction ?? '';
            $stageData[] = $stage->secteur_activite ?? '';
            $stageData[] = $stage->periode ?? '';
            $stageData[] = $stage->etablissement ?? '';
            $stageData[] = $stage->attestation 
                ? '=HYPERLINK("' . $this->baseUrl . $stage->attestation . '", "Stage Attestation")' 
                : '';
            $stageData[] = $stage->description ?? '';
        }
        
        return array_pad($stageData, 18, '');
    }

    protected function getExperienceData($experiences)
    {
        $experienceData = [];
        
        foreach ($experiences as $experience) {
            $experienceData[] = $experience->fonction ?? '';
            $experienceData[] = $experience->secteur_activite ?? '';
            $experienceData[] = $experience->periode ?? '';
            $experienceData[] = $experience->etablissement ?? '';
            $experienceData[] = $experience->attestation 
                ? '=HYPERLINK("' . $this->baseUrl . $experience->attestation . '", "Work Attestation")' 
                : '';
            $experienceData[] = $experience->description ?? '';
        }
        
        return array_pad($experienceData, 18, '');
    }

    protected function getAttestationData($attestations)
    {
        $attestationData = [];
        
        foreach ($attestations as $attestation) {
            $attestationData[] = $attestation->type_attestation ?? '';
            $attestationData[] = $attestation->attestation 
                ? '=HYPERLINK("' . $this->baseUrl . $attestation->attestation . '", "Certificate")' 
                : '';
            $attestationData[] = $attestation->description ?? '';
        }
        
        return array_pad($attestationData, 9, '');
    }

    public function headings(): array
{
        $sector = "Secteur d'activité";
        return [
            // Personal Information Headers
            'ID', 'Type De Formation', 'Nom', 'Prénom', 'الاسم العائلي', 'الاسم الشخصي', 'CNE', 'CIN', 'Email', 'Date de naissance',
            'Ville naissance', 'مدينة الولادة', 'Province', 'Pays naissance', 'Nationalité', 'Sexe', 'Téléphone mobile',
            'Téléphone fixe', 'Adresse', 'Ville', 'Pays',

            // Documents Headers
            'CV', 'Demande', 'Carte Identité', 'Photo',

            // Bac Headers
            'Bac Type', 'Bac Year', 'Bac Scan',

            // Bac+2 Headers
            'Type Bac2', 'Year Bac2', 'Filière Bac2', 'Establishment Bac2', 'Bac2 Scan',

            // Bac3 Headers
            'Type Bac3', 'Year Bac3', 'Filière Bac3', 'Establishment Bac3', 'Bac3 Scan',

            // Stage Headers (3 stages)
            'Stage 1 Function', 'Stage 1 '.$sector, 'Stage 1 Period', 'Stage 1 Establishment', 'Stage 1 Attestation', 'Stage 1 Description',
            'Stage 2 Function', 'Stage 2 '.$sector, 'Stage 2 Period', 'Stage 2 Establishment', 'Stage 2 Attestation', 'Stage 2 Description',
            'Stage 3 Function', 'Stage 3 '.$sector, 'Stage 3 Period', 'Stage 3 Establishment', 'Stage 3 Attestation', 'Stage 3 Description',

            // Experience Headers (3 experiences)
            'Experience 1 Function', 'Experience 1 '.$sector, 'Experience 1 Period', 'Experience 1 Establishment', 'Experience 1 Attestation', 'Experience 1 Description',
            'Experience 2 Function', 'Experience 2 '.$sector, 'Experience 2 Period', 'Experience 2 Establishment', 'Experience 2 Attestation', 'Experience 2 Description',
            'Experience 3 Function', 'Experience 3 '.$sector, 'Experience 3 Period', 'Experience 3 Establishment', 'Experience 3 Attestation', 'Experience 3 Description',

            // Attestation Headers (3 attestations)
            'Attestation 1 Type', 'Attestation 1 Document', 'Attestation 1 Description',
            'Attestation 2 Type', 'Attestation 2 Document', 'Attestation 2 Description',
            'Attestation 3 Type', 'Attestation 3 Document', 'Attestation 3 Description',
        ];
    }

    public function styles($sheet)
    {
        // Header styling
        $sheet->getStyle('A1:CE1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => Color::COLOR_BLUE],
            ],
        ]);
    
        // Cell borders
        $sheet->getStyle('A1:CE' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);
        $columns = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 
            'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 
            'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 
            'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 
            'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE'
        ];
    
        // Auto-size the columns to fit their content
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    public function columnFormats(): array
    {
        return [
            'J' => 'yyyy-mm-dd', // Date format for birth date
        ];
    }
}