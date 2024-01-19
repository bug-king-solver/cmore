<?php

use App\Models\Tenant\Questionnaire;
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

        /** New column */
        Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
            $table->unsignedBigInteger('company_address_id')->nullable();
            $table->foreign('company_address_id')->references('id')->on('company_addresses')->onDelete('cascade');
        });

        //change  , if exists  , company_addresses latitude and longitude to nullable
        if (Schema::hasColumn('company_addresses', 'latitude')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->string('latitude')->nullable()->change();
            });
        }
        if (Schema::hasColumn('company_addresses', 'longitude')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->string('longitude')->nullable()->change();
            });
        }

        /**
         * Fatch all physical risk data from table where type id 12,
         * Store data in company_address from physical risk
         */
        if (!Schema::hasColumn('questionnaires', 'deleted_at')) {
            return;
        }

        foreach (Questionnaire::where('questionnaire_type_id', 12)->get() as $questionnaire) {
            if ($questionnaire->exists) {
                $company = $questionnaire->company;

                foreach ($questionnaire->physicalRisks()->get() as $physicalrisk) {
                    if ($physicalrisk->exists) {
                        $currentAddresses = $company->addresses()->get();
                        // If address not found in company address table for the company, Will add it.
                        if (empty($currentAddresses->toArray())) {
                            $address = $company->addresses()->create([
                                'name' => $company->name,
                                'headquarters' => 0,
                                'country' => $physicalrisk->country_code,
                                'region' => $physicalrisk->region_code,
                                'city' => $physicalrisk->city_code,
                                'latitude' => null,
                                'longitude' => null,
                            ]);

                            // update address id
                            $physicalrisk->update([
                                'company_address_id' => $address->id
                            ]);
                        } else {
                            $status = 0;
                            // If address already exits, skipe it.
                            foreach ($currentAddresses as $address) {
                                if (
                                    $physicalrisk->country_code == $address->country &&
                                    $physicalrisk->region_code == $address->region &&
                                    $physicalrisk->city_code == $address->city
                                ) {
                                    $status = 1;

                                    // update address id
                                    $physicalrisk->update([
                                        'company_address_id' => $address->id
                                    ]);
                                }
                            }

                            if ($status != 1) {
                                $address = $company->addresses()->create([
                                    'name' => $company->name,
                                    'headquarters' => 0,
                                    'country' => $physicalrisk->country_code,
                                    'region' => $physicalrisk->region_code,
                                    'city' => $physicalrisk->city_code,
                                    'latitude' => null,
                                    'longitude' => null,
                                ]);

                                // update address id
                                $physicalrisk->update([
                                    'company_address_id' => $address->id
                                ]);
                            }
                        }
                    }
                }
            }
        }

        /** Drop Columns */
        Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
            $table->dropColumn(['country_iso', 'country_code', 'region_code', 'city_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
            $table->string('country_iso')->nullable();
            $table->string('country_code')->nullable();
            $table->string('region_code')->nullable();
            $table->string('city_code')->nullable();
        });

        //remove the company_address_id from physical risk
        Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
            $table->dropForeign(['company_address_id']);
            $table->dropColumn(['company_address_id']);
        });
    }
};
