<?php

namespace App\Models\Enums\Territory;

use App\Models\Traits\EnumToArray;

/**
 * 1 chars » Continent
 * 3 chars » Country
 * 3 chars » District
 */
enum District: string
{
    use EnumToArray;

    case AVEIRO = '3179001';
    case BEJA = '3179002';
    case BRAGA = '3179003';
    case BRAGANCA = '3179004';
    case CASTELO_BRANCO = '3179005';
    case COIMBRA = '3179006';
    case EVORA = '3179007';
    case FARO = '3179008';
    case GUARDA = '3179009';
    case LEIRIA = '3179010';
    case LISBOA = '3179011';
    case PORTALEGRE = '3179012';
    case PORTO = '3179013';
    case SANTAREM = '3179014';
    case SETUBAL = '3179015';
    case VIANA_DO_CASTELO = '3179016';
    case VILA_REAL = '3179017';
    case VISEU = '3179018';
    case ILHA_DA_MADEIRA = '3179031';
    case ILHA_DE_PORTO_SANTO = '3179032';
    case ILHA_DE_SANTA_MARIA = '3179041';
    case ILHA_DE_SAO_MIGUEL = '3179042';
    case ILHA_TERCEIRA = '3179043';
    case ILHA_GRACIOSA = '3179044';
    case ILHA_DE_SAO_JORGE = '3179045';
    case ILHA_DO_PICO = '3179046';
    case ILHA_DO_FAIAL = '3179047';
    case ILHA_DAS_FLORES = '3179048';
    case ILHA_DO_CORVO = '3179049';

    public function name(): string
    {
        return match ($this) {
            District::AVEIRO => 'AVEIRO',
            District::BEJA => 'BEJA',
            District::BRAGA => 'BRAGA',
            District::BRAGANCA => 'BRAGANÇA',
            District::CASTELO_BRANCO => 'CASTELO BRANCO',
            District::COIMBRA => 'COIMBRA',
            District::EVORA => 'ÉVORA',
            District::FARO => 'FARO',
            District::GUARDA => 'GUARDA',
            District::LEIRIA => 'LEIRIA',
            District::LISBOA => 'LISBOA',
            District::PORTALEGRE => 'PORTALEGRE',
            District::PORTO => 'PORTO',
            District::SANTAREM => 'SANTARÉM',
            District::SETUBAL => 'SETÚBAL',
            District::VIANA_DO_CASTELO => 'VIANA DO CASTELO',
            District::VILA_REAL => 'VILA REAL',
            District::VISEU => 'VISEU',
            District::ILHA_DA_MADEIRA => 'ILHA DA MADEIRA',
            District::ILHA_DE_PORTO_SANTO => 'ILHA DE PORTO SANTO',
            District::ILHA_DE_SANTA_MARIA => 'ILHA DE SANTA MARIA',
            District::ILHA_DE_SAO_MIGUEL => 'ILHA DE SÃO MIGUEL',
            District::ILHA_TERCEIRA => 'ILHA TERCEIRA',
            District::ILHA_GRACIOSA => 'ILHA GRACIOSA',
            District::ILHA_DE_SAO_JORGE => 'ILHA DE SÃO JORGE',
            District::ILHA_DO_PICO => 'ILHA DO PICO',
            District::ILHA_DO_FAIAL => 'ILHA DO FAIAL',
            District::ILHA_DAS_FLORES => 'ILHA DAS FLORES',
            District::ILHA_DO_CORVO => 'ILHA DO CORVO',
        };
    }

    /**
     * Get the Continent of a Country
     */
    public function continent(): Continent
    {
        $continentId = substr($this->value, 0, 1);

        return Continent::from($continentId);
    }

    /**
     * Get the Country of a District
     */
    public function country(): Country
    {
        $continentId = substr($this->value, 0, 4);

        return Country::from($continentId);
    }

    /**
     * Get all Districts of a Country
     */
    public function counties(): array
    {
        return array_filter(
            County::cases(),
            fn (County $enum) => substr($enum->value, 0, 7) === $this->value
        );
    }
}
