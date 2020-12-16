<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL ADMINISTRATIVA - MUSERPOL </title>
    <link rel="stylesheet" href="{{ public_path("/css/report-print.min.css") }}" media="all"/>
</head>

<body>
    @php ($plural = count($lenders) > 1)
    @php ($n = 1)
    @include('partials.header', $header)

    <div class="block">
        <div class="font-semibold leading-tight text-center m-b-10 text-lg">{{ $title }}</div>
    </div>

    <div class="block">
        <div class="font-semibold leading-tight text-left m-b-10 text-base">{{ $n++ }}. DATOS DEL TRÁMITE</div>
    </div>

    <div class="block">
        <table class="table-info w-100 text-center uppercase my-20">
            <tr class="bg-grey-darker text-sm-1 text-white">
                <td class="w-25">Código Tŕamite</td>
                @if ($loan->parent_loan || $loan->parent_reason)
                <td class="w-25">Trámite origen</td>
                @endif
                <td class="{{ $loan->parent_loan ? 'w-50' : 'w-75' }}" colspan="{{ $loan->parent_loan ? 1 : 2 }}">Modalidad de trámite</td>
            </tr>
            <tr>
                <td class="data-row py-5">{{ $loan->code }}</td>
                @if ($loan->parent_loan)
                <td class="data-row py-5">{{ $loan->parent_loan->code }}</td>
                @endif
                @if ($loan->parent_reason && !$loan->parent_loan_id)
                <td class="data-row py-5">{{ $loan->data_loan->code }}</td>
                @endif
                <td class="data-row py-5" colspan="{{ $loan->parent_loan ? 1 : 2 }}">@if($loan->parent_reason == "REPROGRAMACIÓN") {{$loan->parent_reason}} @endif {{ $loan->modality->name }}</td>
            </tr>
            <tr class="bg-grey-darker text-sm-1 text-white">
                <td>Monto solicitado</td>
                <td>Plazo</td>
                <td>Tipo de Desembolso</td>
            </tr>
            <tr>
                <td class="data-row py-5">{{ Util::money_format($loan->amount_requested) }} <span class="capitalize">Bs.</span></td>
                <td class="data-row py-5">{{ $loan->loan_term }} <span class="capitalize">Meses</span></td>
                <td class="data-row py-5">
                    @if($loan->payment_type->name=='Deposito Bancario')
                        <div class="font-bold">Cuenta Banco Union</div>
                        <div>{{ $loan->number_payment_type }}</div>
                    @else
                        {{ $loan->payment_type->name}}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="block">
        <div class="font-semibold leading-tight text-left m-b-10 text-base">{{ $n++ }}. DATOS DE{{ $plural ? ' LOS' : 'L' }} TITULAR{{ $plural ? 'ES' : ''}}</div>
    </div>

    <div class="block">
        @foreach ($lenders as $lender)
        <table class="table-info w-100 text-center uppercase my-20">
            <tr class="bg-grey-darker text-sm-1 text-white">
                <td class="w-70">Solicitante</td>
                <td class="w-15">CI</td>
                <td class="w-15">Estado</td>
            </tr>
            <tr>
                <td class="data-row py-5">{{ $lender->title }} {{ $lender->full_name }}</td>
                <td class="data-row py-5">{{ $lender->identity_card_ext }}</td>
                @if(!$is_dead)
                <td class="data-row py-5">{{ $lender->affiliate_state->affiliate_state_type->name }}</td>
                @else
                <td class="data-row py-5">no corresponde</td>
                @endif
            </tr>
            <tr class="bg-grey-darker text-sm-1 text-white">
                <td>Domilicio actual</td>
                <td colspan="2">Teléfono(s)</td>
            </tr>
            <tr>
                <td class="data-row py-5">{{ $lender->address ? $lender->address->full_address : '' }}</td>
                <td class="data-row py-5" colspan="2">
                @if ($lender->phone_number != "" && $lender->phone_number != null)
                    <div>{{ $lender->phone_number }}</div>
                @endif
                @if ($lender->cell_phone_number != "" && $lender->cell_phone_number != null)
                    @foreach(explode(',', $lender->cell_phone_number) as $phone)
                        <div>{{ $phone }}</div>
                    @endforeach
                @endif
                </td>
            </tr>
            <tr class="bg-grey-darker text-sm-1 text-white">
                @php ($inactive = $lender->pension_entity)
                <td colspan="{{$inactive ? 1 : 2}}">Unidad</td>
                <td>Categoría</td>
                @if ($inactive)
                    <td>Tipo de Renta</td>
                @endif
            </tr>
            <tr>
                <td class="data-row py-5" colspan="{{$inactive ? 1 : 2}}">{{ $lender->full_unit}}</td>
                <td class="data-row py-5">{{ $lender->category ? $lender->category->name : '' }}</td>
                @if ($inactive)
                    <td class="data-row py-5">{{ $lender->afp ? $lender->pension_entity ? $lender->pension_entity->name : "APS" : "SENASIR"}}</td>
                @endif
            </tr>
        </table>
        @endforeach
    </div>

    @if ($loan->guarantors()->count())
    <div class="block">
        <div class="font-semibold leading-tight text-left m-b-10 text-base">{{ $n++ }}. DATOS DE{{ $plural ? ' LOS' : 'L' }} GARANTE{{ $plural ? 'S' : ''}}</div>
    </div>

    <div class="block ">
        @foreach ($loan->guarantors as $guarantor)
        <table class="table-info w-100 text-center uppercase my-20">
            <tr class="bg-grey-darker text-sm-1 text-white">
                <td class="w-70">Garante</td>
                <td class="w-15">CI</td>
                <td class="w-15">Estado</td>
            </tr>
            <tr>
                <td class="data-row py-5">{{ $guarantor->title }} {{ $guarantor->full_name }}</td>
                <td class="data-row py-5">{{ $guarantor->identity_card_ext }}</td>
                <td class="data-row py-5">{{ $guarantor->affiliate_state->affiliate_state_type->name }}</td>
            </tr>
            <tr class="bg-grey-darker text-sm-1 text-white">
                <td>Domilicio actual</td>
                <td colspan="2">Teléfono(s)</td>
            </tr>
            <tr>
                <td class="data-row py-5">{{ $guarantor->address ? $guarantor->address->full_address : '' }}</td>
                <td class="data-row py-5" colspan="2">
                @if ($guarantor->phone_number != "" && $guarantor->phone_number != null)
                    <div>{{ $guarantor->phone_number }}</div>
                @endif
                @if ($guarantor->cell_phone_number != "" && $guarantor->cell_phone_number != null)
                    @foreach(explode(',', $guarantor->cell_phone_number) as $phone)
                        <div>{{ $phone }}</div>
                    @endforeach
                @endif
                </td>
            </tr>
            <tr class="bg-grey-darker text-sm-1 text-white">
                @php ($inactive = $guarantor->pension_entity)
                <td colspan="{{$inactive ? 1 : 2}}">Unidad</td>
                <td>Categoría</td>
                @if ($inactive)
                    <td>Tipo de Renta</td>
                @endif
            </tr>
            <tr>
                <td class="data-row py-5" colspan="{{$inactive ? 1 : 2}}">{{ $guarantor->full_unit}}</td>
                <td class="data-row py-5">{{ $guarantor->category ? $guarantor->category->name : '' }}</td>
                @if ($inactive)
                    <td class="data-row py-5">{{ $guarantor->afp ? $guarantor->pension_entity ? $guarantor->pension_entity->name : "APS" : "SENASIR"}}</td>
                @endif
            </tr>
        </table>
        @endforeach
    </div>
    @endif

    @if (count($loan->personal_references)>0)
    <div class="block">
        <div class="font-semibold leading-tight text-left m-b-10 text-base">{{ $n++ }}. REFERENCIAS PERSONALES</div>
    </div>

    <div class="block">
    @foreach ($loan->personal_references as $personal_reference)
        <table class="table-info w-100 text-center uppercase my-20">
            <tr class="bg-grey-darker text-sm-1 text-white">
                <td class="w-70">Referencia</td>
                <td class="w-15">Teléfono(s)</td>
            </tr>
            <tr>
                <td class="data-row py-5">{{ $personal_reference->full_name }}</td>
                <td class="data-row py-5">
                @if ($personal_reference->phone_number != "" && $personal_reference->phone_number != null)
                    <div>{{ $personal_reference->phone_number }}</div>
                @endif
                @if ($personal_reference->cell_phone_number != "" && $personal_reference->cell_phone_number != null)
                    @foreach(explode(',', $personal_reference->cell_phone_number) as $phone)
                        <div>{{ $phone }}</div>
                    @endforeach
                @endif
                </td>
            </tr>
        </table>
        @endforeach
    </div>
    @endif

    <div class="block">
        <div class="font-semibold leading-tight text-left m-b-10 text-base">{{ $n++ }}. DOCUMENTOS PRESENTADOS</div>
    </div>

    <div class="block">
        <table class="table-info w-100 text-center uppercase my-20">
            <tr class="bg-grey-darker text-white">
                <td colspan="3">Requisitos</td>
            </tr>
            @foreach ($loan->submitted_documents as $key => $document)
                <tr>
                    <td class="data-row py-5 w-10">{{ $key + 1 }}</td>
                    <td class="data-row py-5 w-85">{{ $document->name }}</td>
                    <td class="data-row py-5 w-5">&#10003;</td>
                </tr>
            @endforeach
            @if ($loan->notes()->count())
            <tr class="bg-grey-darker text-xxs text-white">
                <td colspan="3">Otros</td>
            </tr>
            @foreach ($loan->notes as $key => $note)
                <tr>
                    <td class="data-row py-5">{{ $key + 1 }}</td>
                    <td class="data-row py-5" colspan="2">{{ $note->message }}</td>
                </tr>
               
            @endforeach
            @endif
        </table>
    </div>
    @if($n==6||$key==14)
    <div style="page-break-after: always"></div>
    @endif
    <div class="block  text-justify ">
        <div>   
            La presente solicitud se constituye en una <span class="font-bold">DECLARACIÓN JURADA</span>, consignandose los datos como fidedignos por los interesados.
        </div>
        <br>
        <div>
            El suscrito Asistente de Oficina y/o Responsable Regional y/o Atención al Afiliado de la MUSERPOL, CERTIFICA LA AUTENTICIDAD de la documentación presentada y la firma suscrita por {{ $plural ? 'los' : 'el/la' }} Solicitante{{ $plural ? 's' : '' }}, dando FÉ de que la misma fue estampada en mi presencia y en forma voluntaria con puño y letra {{ $plural ? 'de los' : 'del' }} Solicitante{{ $plural ? 's' : '' }}.
        </div>
    </div>
    <div class="block no-page-break">
    </div>
    <hr class="my-20" style="margin-top: 0; padding-top: 0;">
    <table>
            <tbody>
                @php ($cont = 0)             
                @foreach ($signers->chunk(2) as $chunk)
                <tr class="align-top">
                    @foreach ($chunk as $person)
                        <td width="50%">
                            @include('partials.signature_box', $person)
                        </td>
                        @php ($cont ++)
                    @endforeach
                @endforeach
                @if ($signers->count() % 2 == 0)
                </tr>
                <tr>
                    <td colspan="2" width="100%">
                        @php($user = Auth::user())
                        @include('partials.signature_box', [
                            'full_name' => $user->full_name,
                            'position' => $user->position,
                            'employee' => true
                        ])
                    </td>
                </tr>
                @else
                <td width="50%">
                @php($user = Auth::user())
                @include('partials.signature_box', [
                        'full_name' => $user->full_name,
                        'position' => $user->position,
                        'employee' => true
                    ])
                </td>
                </tr>
                @endif
            </tbody>
        </table>
    <div style="page-break-after: always"></div>
    <div class="block no-page-break">
    </div>
    <div class="block">
        <br>
        <br>
        <div class="text-right">
        <br>
            La Paz, {{ Carbon::now()->isoFormat('LL') }}
        </div>
        <br>
        <br>
        <div class="text-xl ">Señor</div>
        <div class="leading-tight font-bold">
            <div>DIRECTOR GENERAL EJECUTIVO</div>
            <div>Mutual de Servicios al Policía</div>
            <div>Presente.- </div>
        </div>
        <br>
        <br>
        <div class="text-right leading-tight font-bold">
            REF.: {{ $title }}
        </div>
        <br>
        <br>
        <div class="block leading-tight text-justify">
            <div>
                De mi mayor consideración:
            </div >
            <br>
            <div  >
                El objeto de la presente es para solicitar un Préstamo por un monto de Bs. {{ $loan->amount_requested }} (<span class="uppercase">{{ Util::money_format($loan->amount_requested, true) }}</span> Bolivianos) a un plazo de {{$loan->loan_term}} meses, el cual que será aprobado conforme con los procedimientos del Reglamento de Préstamos vigente en la MUSERPOL.
            </div>
            <br>
            <div>
                El destino del préstamo es <span class="lowercase font-bold">{{ $loan->destiny->name }}</span>. A tal efecto, adjunto los requisitos solicitados y declaro que toda la documentación presentada es veraz y fidedigna; en caso de demostrarse cualquier falsedad, distorsión u omisión en la documentación, reconozco que la Unidad de Inversión en Préstamos procederá a la anulación del trámite y podrá efectuar las acciones correspondientes conforme a los Artículo 17 y 18 de del Capítulo II CONSIDERACIONES DEL PRESTATARIO PARA ACCEDER AL PRÉSTAMO del Reglamento de Préstamos vigente.
            </div>
        </div>
    </div>
    <br>
    <div class="block no-page-break">
        <div>
            Sin otro particular, {{ $plural ? 'me despido' : 'nos despedimos'}} de usted con las consideraciones mas distinguidas:
        </div>
        <table>
            <tbody>   
            @if (count($lenders) == 1)                 
            <td width="50%">
                    @include('partials.signature_box', [
                    'full_name' => $lender->full_name,
                    'identity_card' => $lender->identity_card_ext,
                    'position' => 'SOLICITANTE'
                ])
            </td>   
            @endif   
            </tbody>
        </table>
        <table>
            <tbody>   
            @if (count($lenders) == 2)  
            @php ($cont = 0)             
                @foreach ($signers->chunk(2) as $chunk)
                <tr class="align-top">
                    @foreach ($chunk as $person)
                        <td width="50%">
                            @include('partials.signature_box', $person)
                        </td>
                        @php ($cont ++)
                    @endforeach
                @endforeach          
                </tr>  
            @endif
            </tbody>
        </table>
    </div>
</body>
</html>