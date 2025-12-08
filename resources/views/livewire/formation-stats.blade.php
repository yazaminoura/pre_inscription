<div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="border-radius-lg pt-4 pb-3" style="background: #1a4b8c; background: linear-gradient(195deg, #1a4b8c 0%, #12315f 100%);">
                            <h6 class="text-white text-capitalize ps-3">Statistiques des inscriptions</h6>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        @foreach($formations as $formation)
                            @php
                                $annee_debut = \Carbon\Carbon::parse($formation->date_debut)->format('Y');
                                $annee_fin = \Carbon\Carbon::parse($formation->date_fin)->format('Y');
                            @endphp

                            <div class="card shadow-sm mb-4" style="border-left: 4px solid #1a4b8c;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1" style="color: #1a4b8c;">
                                                {{ $annee_debut }}/{{ $annee_fin }} {{ $formation->type_formation }}
                                            </h5>
                                            <h6 class="mb-3">{{ $formation->titre }}</h6>
                                            <p class="mb-0">Nombre de candidats inscrits: 
                                                <span class="badge bg-gradient-info">{{ $formation->inscriptions_count }}</span>
                                            </p>
                                        </div>
                                        <button wire:click="exportCandidats({{ $formation->id }})" 
                                                class="btn btn-sm text-white" 
                                                style="background-color: #1a4b8c;">
                                            <i class="material-symbols-rounded me-1">download</i> Exporter
                                        </button>
                                         
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>