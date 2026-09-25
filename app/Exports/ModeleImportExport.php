<?php

namespace App\Exports;

use App\Imports\CandidatsImport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/** Fichier modèle pour l'import : les titres de colonnes reconnus, sans données. */
class ModeleImportExport implements FromArray, WithHeadings, WithStyles
{
    // Obligatoires pour chaque ligne
    private const OBLIGATOIRES = ['Nom', 'Prénom', 'CNE', 'CIN', 'Email', 'Date de naissance', 'Sexe'];

    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return array_keys(CandidatsImport::COLONNES);
    }

    public function styles(Worksheet $sheet)
    {
        foreach ($this->headings() as $i => $titre) {
            $colonne = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->getColumnDimension($colonne)->setAutoSize(true);
            $sheet->getStyle($colonne . '1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => in_array($titre, self::OBLIGATOIRES, true) ? 'FF096A9B' : 'FF64748B']],
            ]);
        }
        $sheet->getComment('A1')->getText()->createTextRun(
            "Colonnes bleues : obligatoires. Grises : facultatives.\nFormation : l'intitulé exact (ex. Master Génie Logiciel), utile si vous ne choisissez pas la formation à l'import.\nDate : JJ/MM/AAAA. Sexe : M ou F.\nStatut : En attente, En cours d'étude, Dossier incomplet, Liste d'attente, Acceptée ou Refusée."
        );
        $sheet->getComment('A1')->setWidth('340pt')->setHeight('120pt');
    }
}
