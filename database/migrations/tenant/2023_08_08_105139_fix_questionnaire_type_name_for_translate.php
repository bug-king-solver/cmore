<?php

use App\Models\Tenant\QuestionnaireType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $questionnaireTypes = QuestionnaireType::withoutGlobalScopes()->get();
        foreach ($questionnaireTypes as $questionnaireType) {
            $arr = $questionnaireType->toArray();

            if (preg_match('/"en":/', $arr['name'])) {
                $name = json_decode($arr['name'], true);
                $arr['name'] = $name['en'];
            }

            QuestionnaireType::withoutGlobalScopes()->where('id', $questionnaireType->id)
                ->update([
                    'name' => [
                        'en' => $arr['name'],
                        'fr' => $arr['name'],
                        'pt-BR' => $arr['name'],
                        'es' => $arr['name'],
                        'pt-PT' => $arr['name'],
                    ],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
