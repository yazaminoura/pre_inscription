<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de l'inscription</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            border-left: 4px solid #3498db;
            padding-left: 10px;
            margin-top: 30px;
        }
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
            min-width: 150px;
        }
        .info-value {
            color: #555;
            flex: 1;
        }
        .formation-info {
            background-color: #e8f4fd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .diploma-item, .stage-item, .experience-item, .attestation-item {
            background-color: #f8f9fa;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 3px solid #3498db;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue {{ $candidatName }} !</h1>
        
        <div class="success-message">
            <p><strong>Félicitations !</strong> Votre inscription à la FST de l'Université Sidi Mohamed Ben Abdellah de Fès a été confirmée.</p>
            <p>Votre inscription a été effectuée avec succès dans notre système le {{ now()->format('d/m/Y') }} à {{ now()->format('H:i') }}</p>
        </div>

        <h2>Informations Personnelles</h2>
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Nom et Prénom :</span>
                <span class="info-value">{{ $personalInfo['nom'] ?? '' }} {{ $personalInfo['prenom'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">CNE :</span>
                <span class="info-value">{{ $personalInfo['CNE'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">CIN :</span>
                <span class="info-value">{{ $personalInfo['CIN'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email :</span>
                <span class="info-value">{{ $personalInfo['email'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone :</span>
                <span class="info-value">{{ $personalInfo['telephone'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date de naissance :</span>
                <span class="info-value">{{ $personalInfo['date_naissance'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ville de naissance :</span>
                <span class="info-value">{{ $personalInfo['ville_naissance'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nationalité :</span>
                <span class="info-value">{{ $personalInfo['nationalite'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Sexe :</span>
                <span class="info-value">{{ $personalInfo['sex'] == 'M' ? 'Masculin' : ($personalInfo['sex'] == 'F' ? 'Féminin' : $personalInfo['sex']) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Adresse :</span>
                <span class="info-value">{{ $personalInfo['adresse'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ville :</span>
                <span class="info-value">{{ $personalInfo['ville'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pays :</span>
                <span class="info-value">{{ $personalInfo['pays'] ?? '' }}</span>
            </div>
        </div>

        @if($formation)
        <h2>Formation Choisie</h2>
        <div class="formation-info">
            <div class="info-row">
                <span class="info-label">Type de formation :</span>
                <span class="info-value">{{ $formation['type_formation'] ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Titre :</span>
                <span class="info-value">{{ $formation['titre'] ?? '' }}</span>
            </div>
        </div>
        @endif

        @if(!empty($diplomas))
        <h2>Diplômes</h2>
        @foreach($diplomas as $diploma)
        <div class="diploma-item">
            <div class="info-row">
                <span class="info-label">Type :</span>
                <span class="info-value">{{ $diploma['type'] ?? '' }}</span>
            </div>
            @if(isset($diploma['filiere']))
            <div class="info-row">
                <span class="info-label">Filière :</span>
                <span class="info-value">{{ $diploma['filiere'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($diploma['annee']))
            <div class="info-row">
                <span class="info-label">Année :</span>
                <span class="info-value">{{ $diploma['annee'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($diploma['etablissement']))
            <div class="info-row">
                <span class="info-label">Établissement :</span>
                <span class="info-value">{{ $diploma['etablissement'] ?? '' }}</span>
            </div>
            @endif
        </div>
        @endforeach
        @endif

        @if(!empty($stages))
        <h2>Stages</h2>
        @foreach($stages as $stage)
        <div class="stage-item">
            @if(isset($stage['fonction']))
            <div class="info-row">
                <span class="info-label">Fonction :</span>
                <span class="info-value">{{ $stage['fonction'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($stage['periode']))
            <div class="info-row">
                <span class="info-label">Période :</span>
                <span class="info-value">{{ $stage['periode'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($stage['etablissement']))
            <div class="info-row">
                <span class="info-label">Établissement :</span>
                <span class="info-value">{{ $stage['etablissement'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($stage['secteur_activite']))
            <div class="info-row">
                <span class="info-label">Secteur d'activité :</span>
                <span class="info-value">{{ $stage['secteur_activite'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($stage['description']))
            <div class="info-row">
                <span class="info-label">Description :</span>
                <span class="info-value">{{ $stage['description'] ?? '' }}</span>
            </div>
            @endif
        </div>
        @endforeach
        @endif

        @if(!empty($experiences))
        <h2>Expériences Professionnelles</h2>
        @foreach($experiences as $experience)
        <div class="experience-item">
            @if(isset($experience['fonction']))
            <div class="info-row">
                <span class="info-label">Fonction :</span>
                <span class="info-value">{{ $experience['fonction'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($experience['periode']))
            <div class="info-row">
                <span class="info-label">Période :</span>
                <span class="info-value">{{ $experience['periode'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($experience['etablissement']))
            <div class="info-row">
                <span class="info-label">Établissement :</span>
                <span class="info-value">{{ $experience['etablissement'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($experience['secteur_activite']))
            <div class="info-row">
                <span class="info-label">Secteur d'activité :</span>
                <span class="info-value">{{ $experience['secteur_activite'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($experience['description']))
            <div class="info-row">
                <span class="info-label">Description :</span>
                <span class="info-value">{{ $experience['description'] ?? '' }}</span>
            </div>
            @endif
        </div>
        @endforeach
        @endif

        @if(!empty($attestations))
        <h2>Attestations</h2>
        @foreach($attestations as $attestation)
        <div class="attestation-item">
            @if(isset($attestation['type_attestation']))
            <div class="info-row">
                <span class="info-label">Type :</span>
                <span class="info-value">{{ $attestation['type_attestation'] ?? '' }}</span>
            </div>
            @endif
            @if(isset($attestation['description']))
            <div class="info-row">
                <span class="info-label">Description :</span>
                <span class="info-value">{{ $attestation['description'] ?? '' }}</span>
            </div>
            @endif
        </div>
        @endforeach
        @endif

        <div class="footer">
            <p><strong>Merci de votre confiance !</strong></p>
            <p>Pour plus d'informations, consultez notre site : <a href="https://fst-usmba.ac.ma/">https://fst-usmba.ac.ma/</a></p>
            <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
        </div>
    </div>
</body>
</html>
