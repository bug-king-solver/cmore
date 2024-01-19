<div class="dashboard">
    @push('body')
        <style nonce="{{ csp_nonce() }}">
        @media print {
            .pagebreak {
                clear: both;
                page-break-after: always;
            }
            div {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .nonavoid {
                break-inside: auto;
            }
            #launcher, #footer {
                visibility: hidden;
            }
            .print {
                page-break-after: avoid;
            }
            /* html, body {
                border: 1px solid white;
                height: 99%;
                page-break-after: avoid;
                page-break-before: avoid;
            } */
        }
        @page {
            size: A4; /* DIN A4 standard, Europe */
            /* margin: 70pt 60pt 70pt; */
        }
        </style>
        <script nonce="{{ csp_nonce() }}">
            var average_levels_satisfaction_platforms = null,
                number_of_complaints = null,
                actionPlanChart = null,
                turnover_total_operating_costs = null,
                total_value_employee_salaries = null,
                total_capital_providers = null,
                total_investment_value = null,
                total_amount_capex_da = null,
                direct_economic_value = null,
                taxonomy_turnover = null,
                percentage_of_workers_by_contractual_regime = null,
                percentage_workers_by_contract = null,
                percentage_of_hires_by_contractual_regime = null,
                percentage_of_hiring_of_workers_by_gender = null,
                sum_of_basic_wages_of_workers_by_gender = null,
                base_salary_of_female_workersby_professional = null,
                base_salary_of_male_workers_by_professional = null,
                salary_of_workers_of_other_gender_by_professional = null,
                remuneration_of_employees_by_gender = null,
                gross_remuneration_of_female_workers_by_professional = null,
                gross_remuneration_of_male_workers_by_professional = null,
                gross_remuneration_of_workers_othergender_by_professional = null,
                earning_more_than_the_national_minimum_wage_by_gender = null,
                number_workers_gender = null,
                percentage_male_workers_professional = null,
                percentage_female_workers_professional = null,
                percentage_othergender_workers_professional = null,
                percentage_workers_agegroup = null,
                //training_hours_by_gender = null,
                avg_hours_training_per_worker = null,
                workers_training_actions = null,
                female_workers_performance_evaluation = null,
                male_workers_performance_evaluation = null,
                workers_initial_parental_leave = null,
                workers_return_towork_after_parental_leave = null,
                workers_return_towork_after_parental_leave_twelve_month = null,
                independent_members_participate_board_of_director = null,
                board_of_directors_by_age_group = null,
                total_amount_of_water_consumed = null,
                emission_value_per_parameter = null,
                electricity_consumed_per_source = null,
                amount_of_non_road_fuel_consumed = null,
                travel_in_vehicles_fuel_consumed = null,
                vehicle_and_distance_travelled = null,
                control_or_operate_fuel_consumed = null,
                type_of_transport_vehicle_distance = null,
                total_amount_of_leak = null,
                total_amount_of_electricity = null,
                waste_produced_in_ton = null,
                waste_placed_in_recycling = null,
                total_amount_of_water_m3 = null,
                type_total_quantity_goods_purchased_ton = null,
                ghg_emissions_and_carbon_sequestration = null,
                air_pollutant = null,
                depletes_the_ozone_layer_in_tons = null,
                scope = null,
                energy_costs = null,
                hazardous_waste = null,
                risks_arising_supply_chain_reporting_units_charts = null,
                average_probability_risk_categories = null,
                average_severity_risk_categories = null,
                modalities_schedules_reporting_units_charts = null,
                conciliation_measures_unit_charts = null,
                established_by_law_charts = null,
                action_status = 0,
                // Pie Charts
                percentage_electricity_consumption = null,
                board_of_directors_by_gender = null,
                reporting_units_wwtp_progress = null,
                ghg_emissions_progress = null,
                waste_production_facilities_progress = null,
                water_on_its_premises_progress = null,
                purchased_goods_during_reporting_period_progress = null,
                telecommuting_workers_progress = null,
                carbon_sequestration_capacity_ghg_progress = null,
                emission_air_pollutants_progress = null,
                deplete_the_ozone_layer_progress = null,
                km_25_km_radius_environmental_protection_area_progress = null,
                participates_local_development_programs_progress = null,
                reduce_consumption_charts = null;

            document.addEventListener('DOMContentLoaded', () => {
                if (@this.chart != null) {
                    charts();
                }
                Livewire.hook('message.processed', (el, component) => {
                    charts();

                    if (@this.printView) {
                        setTimeout(() => {
                            window.print();
                        }, "1000");
                    }
                });
            });

            function charts()
            {
                // Perfil da entidade
                    // average_levels_satisfaction_platforms
                    if(@this.chart.average_levels_satisfaction_platforms) {
                        if (average_levels_satisfaction_platforms !== null) {
                            average_levels_satisfaction_platforms.destroy();
                        }

                        average_levels_satisfaction_platforms = barCharts(
                            @this.chart.average_levels_satisfaction_platforms.label,
                            @this.chart.average_levels_satisfaction_platforms.data,
                            'average_levels_satisfaction_platforms',
                            ["#008131"]
                        );
                    }

                    // number_of_complaints
                    if(@this.chart.number_of_complaints) {
                        if (number_of_complaints !== null) {
                            number_of_complaints.destroy();
                        }

                        number_of_complaints = barCharts(
                            @this.chart.number_of_complaints.label,
                            @this.chart.number_of_complaints.data,
                            'number_of_complaints',
                            ['#ff0000', '#008131'],
                        );
                    }


                // Desempenho económico

                    // turnover_total_operating_costs
                    if(@this.chart.turnover_total_operating_costs) {
                        if (turnover_total_operating_costs !== null) {
                            turnover_total_operating_costs.destroy();
                        }

                        turnover_total_operating_costs = barCharts(
                            @this.chart.turnover_total_operating_costs.label,
                            @this.chart.turnover_total_operating_costs.data,
                            'turnover_total_operating_costs',
                            ['#008131', '#ff0000'],
                        );
                    }

                    //total_value_employee_salaries
                    if(@this.chart.total_value_employee_salaries) {
                        if (total_value_employee_salaries !== null) {
                            total_value_employee_salaries.destroy();
                        }

                        total_value_employee_salaries = barCharts(
                            @this.chart.total_value_employee_salaries.label,
                            @this.chart.total_value_employee_salaries.data,
                            'total_value_employee_salaries',
                            ['#008131'],
                        );
                    }

                    //total_capital_providers
                    if(@this.chart.total_capital_providers) {
                        if (total_capital_providers !== null) {
                            total_capital_providers.destroy();
                        }

                        total_capital_providers = barCharts(
                            @this.chart.total_capital_providers.label,
                            @this.chart.total_capital_providers.data,
                            'total_capital_providers',
                            ['#008131'],
                        );
                    }

                    // total_investment_value
                    if(@this.chart.total_investment_value) {
                        if (total_investment_value !== null) {
                            total_investment_value.destroy();
                        }

                        total_investment_value = barCharts(
                            @this.chart.total_investment_value.label,
                            @this.chart.total_investment_value.data,
                            'total_investment_value',
                            ['#008131'],
                        );
                    }

                    // total_amount_capex_da
                    if(@this.chart.total_amount_capex_da) {
                        if (total_amount_capex_da !== null) {
                            total_amount_capex_da.destroy();
                        }

                        total_amount_capex_da = barCharts(
                            @this.chart.total_amount_capex_da.label,
                            @this.chart.total_amount_capex_da.data,
                            'total_amount_capex_da',
                            ['#008131'],
                        );
                    }

                    // direct_economic_value
                    if(@this.chart.direct_economic_value) {
                        if (direct_economic_value !== null) {
                            direct_economic_value.destroy();
                        }

                        direct_economic_value = barCharts(
                            @this.chart.direct_economic_value.label,
                            @this.chart.direct_economic_value.data,
                            'direct_economic_value',
                            ['#008131'],
                        );
                    }

                    // taxonomy_turnover
                    if(@this.chart.taxonomy_turnover) {
                        if (taxonomy_turnover !== null) {
                            taxonomy_turnover.destroy();
                        }

                        taxonomy_turnover = barCharts(
                            @this.chart.taxonomy_turnover.label,
                            @this.chart.taxonomy_turnover.data,
                            'taxonomy_turnover',
                            ['#008131'],
                        );
                    }



                // Social

                    // percentage_of_workers_by_contractual_regime
                    if(@this.chart.percentage_of_workers_by_contractual_regime) {
                        if (percentage_of_workers_by_contractual_regime !== null) {
                            percentage_of_workers_by_contractual_regime.destroy();
                        }

                        percentage_of_workers_by_contractual_regime = barCharts(
                            @this.chart.percentage_of_workers_by_contractual_regime.label,
                            @this.chart.percentage_of_workers_by_contractual_regime.data,
                            'percentage_of_workers_by_contractual_regime',
                            '{{ color(1) }}'
                        );
                    }

                    // percentage_workers_by_contract
                    if(@this.chart.percentage_workers_by_contract) {
                        if (percentage_workers_by_contract !== null) {
                            percentage_workers_by_contract.destroy();
                        }

                        percentage_workers_by_contract = barCharts(
                            @this.chart.percentage_workers_by_contract.label,
                            @this.chart.percentage_workers_by_contract.data,
                            'percentage_workers_by_contract',
                            '{{ color(1) }}'
                        );
                    }

                    // percentage_of_hires_by_contractual_regime
                    if(@this.chart.percentage_of_hires_by_contractual_regime) {
                        if (percentage_of_hires_by_contractual_regime !== null) {
                            percentage_of_hires_by_contractual_regime.destroy();
                        }

                        percentage_of_hires_by_contractual_regime = barCharts(
                            @this.chart.percentage_of_hires_by_contractual_regime.label,
                            @this.chart.percentage_of_hires_by_contractual_regime.data,
                            'percentage_of_hires_by_contractual_regime',
                            '{{ color(1) }}'
                        );
                    }

                    // percentage_of_hiring_of_workers_by_gender
                    if(@this.chart.percentage_of_hiring_of_workers_by_gender) {
                        if (percentage_of_hiring_of_workers_by_gender !== null) {
                            percentage_of_hiring_of_workers_by_gender.destroy();
                        }

                        percentage_of_hiring_of_workers_by_gender = barCharts(
                            @this.chart.percentage_of_hiring_of_workers_by_gender.label,
                            @this.chart.percentage_of_hiring_of_workers_by_gender.data,
                            'percentage_of_hiring_of_workers_by_gender',
                            '{{ color(1) }}'
                        );
                    }

                    // sum_of_basic_wages_of_workers_by_gender
                    if(@this.chart.sum_of_basic_wages_of_workers_by_gender) {
                        if (sum_of_basic_wages_of_workers_by_gender !== null) {
                            sum_of_basic_wages_of_workers_by_gender.destroy();
                        }

                        sum_of_basic_wages_of_workers_by_gender = barCharts(
                            @this.chart.sum_of_basic_wages_of_workers_by_gender.label,
                            @this.chart.sum_of_basic_wages_of_workers_by_gender.data,
                            'sum_of_basic_wages_of_workers_by_gender',
                            '{{ color(1) }}',
                            '€'
                        );
                    }

                    // base_salary_of_female_workersby_professional
                    if(@this.chart.base_salary_of_female_workersby_professional) {
                        if (base_salary_of_female_workersby_professional !== null) {
                            base_salary_of_female_workersby_professional.destroy();
                        }

                        base_salary_of_female_workersby_professional = barCharts(
                            @this.chart.base_salary_of_female_workersby_professional.label,
                            @this.chart.base_salary_of_female_workersby_professional.data,
                            'base_salary_of_female_workersby_professional',
                            '{{ color(1) }}',
                            '€'
                        );
                    }

                    // base_salary_of_male_workers_by_professional
                    if(@this.chart.base_salary_of_male_workers_by_professional) {
                        if (base_salary_of_male_workers_by_professional !== null) {
                            base_salary_of_male_workers_by_professional.destroy();
                        }

                        base_salary_of_male_workers_by_professional = barCharts(
                            @this.chart.base_salary_of_male_workers_by_professional.label,
                            @this.chart.base_salary_of_male_workers_by_professional.data,
                            'base_salary_of_male_workers_by_professional',
                            '{{ color(1) }}',
                            '€'
                        );
                    }

                    // salary_of_workers_of_other_gender_by_professional
                    if(@this.chart.salary_of_workers_of_other_gender_by_professional) {
                        if (salary_of_workers_of_other_gender_by_professional !== null) {
                            salary_of_workers_of_other_gender_by_professional.destroy();
                        }

                        salary_of_workers_of_other_gender_by_professional = barCharts(
                            @this.chart.salary_of_workers_of_other_gender_by_professional.label,
                            @this.chart.salary_of_workers_of_other_gender_by_professional.data,
                            'salary_of_workers_of_other_gender_by_professional',
                            '{{ color(1) }}',
                            '€'
                        );
                    }

                    // remuneration_of_employees_by_gender
                    if(@this.chart.remuneration_of_employees_by_gender) {
                        if (remuneration_of_employees_by_gender !== null) {
                            remuneration_of_employees_by_gender.destroy();
                        }

                        remuneration_of_employees_by_gender = barCharts(
                            @this.chart.remuneration_of_employees_by_gender.label,
                            @this.chart.remuneration_of_employees_by_gender.data,
                            'remuneration_of_employees_by_gender',
                            '{{ color(1) }}',
                            '€'
                        );
                    }

                    // gross_remuneration_of_female_workers_by_professional
                    if(@this.chart.gross_remuneration_of_female_workers_by_professional) {
                        if (gross_remuneration_of_female_workers_by_professional !== null) {
                            gross_remuneration_of_female_workers_by_professional.destroy();
                        }

                        gross_remuneration_of_female_workers_by_professional = barCharts(
                            @this.chart.gross_remuneration_of_female_workers_by_professional.label,
                            @this.chart.gross_remuneration_of_female_workers_by_professional.data,
                            'gross_remuneration_of_female_workers_by_professional',
                            '{{ color(1) }}',
                            '€'
                        );
                    }

                    // gross_remuneration_of_male_workers_by_professional
                    if(@this.chart.gross_remuneration_of_male_workers_by_professional) {
                        if (gross_remuneration_of_male_workers_by_professional !== null) {
                            gross_remuneration_of_male_workers_by_professional.destroy();
                        }

                        gross_remuneration_of_male_workers_by_professional = barCharts(
                            @this.chart.gross_remuneration_of_male_workers_by_professional.label,
                            @this.chart.gross_remuneration_of_male_workers_by_professional.data,
                            'gross_remuneration_of_male_workers_by_professional',
                            '{{ color(1) }}',
                            '€'
                        );
                    }

                    // gross_remuneration_of_workers_othergender_by_professional
                    if(@this.chart.gross_remuneration_of_workers_othergender_by_professional){
                        if (gross_remuneration_of_workers_othergender_by_professional !== null) {
                            gross_remuneration_of_workers_othergender_by_professional.destroy();
                        }

                        gross_remuneration_of_workers_othergender_by_professional = barCharts(
                            @this.chart.gross_remuneration_of_workers_othergender_by_professional.label,
                            @this.chart.gross_remuneration_of_workers_othergender_by_professional.data,
                            'gross_remuneration_of_workers_othergender_by_professional',
                            '{{ color(1) }}',
                            '€'
                        );
                    }

                    // earning_more_than_the_national_minimum_wage_by_gender
                    if(@this.chart.earning_more_than_the_national_minimum_wage_by_gender) {
                        if (earning_more_than_the_national_minimum_wage_by_gender !== null) {
                            earning_more_than_the_national_minimum_wage_by_gender.destroy();
                        }

                        earning_more_than_the_national_minimum_wage_by_gender = barCharts(
                            @this.chart.earning_more_than_the_national_minimum_wage_by_gender.label,
                            @this.chart.earning_more_than_the_national_minimum_wage_by_gender.data,
                            'earning_more_than_the_national_minimum_wage_by_gender',
                            '{{ color(1) }}',
                            '%'
                        );
                    }

                    // number_workers_gender
                    if(@this.chart.number_workers_gender) {
                        if (number_workers_gender !== null) {
                            number_workers_gender.destroy();
                        }

                        number_workers_gender = barCharts(
                            @this.chart.number_workers_gender.label,
                            @this.chart.number_workers_gender.data,
                            'number_workers_gender',
                            '{{ color(1) }}'
                        );
                    }

                    // percentage_male_workers_professional
                    if(@this.chart.percentage_male_workers_professional) {
                        if (percentage_male_workers_professional !== null) {
                            percentage_male_workers_professional.destroy();
                        }

                        percentage_male_workers_professional = barCharts(
                            @this.chart.percentage_male_workers_professional.label,
                            @this.chart.percentage_male_workers_professional.data,
                            'percentage_male_workers_professional',
                            '{{ color(1) }}',
                            '%'
                        );
                    }

                    // percentage_female_workers_professional
                    if(@this.chart.percentage_female_workers_professional) {
                        if (percentage_female_workers_professional !== null) {
                            percentage_female_workers_professional.destroy();
                        }

                        percentage_female_workers_professional = barCharts(
                            @this.chart.percentage_female_workers_professional.label,
                            @this.chart.percentage_female_workers_professional.data,
                            'percentage_female_workers_professional',
                            '{{ color(1) }}',
                            '%'
                        );
                    }

                    // percentage_othergender_workers_professional
                    if(@this.chart.percentage_othergender_workers_professional) {
                        if (percentage_othergender_workers_professional !== null) {
                            percentage_othergender_workers_professional.destroy();
                        }

                        percentage_othergender_workers_professional = barCharts(
                            @this.chart.percentage_othergender_workers_professional.label,
                            @this.chart.percentage_othergender_workers_professional.data,
                            'percentage_othergender_workers_professional',
                            '{{ color(1) }}',
                            '%'
                        );
                    }

                    // percentage_workers_agegroup
                    if(@this.chart.percentage_workers_agegroup) {
                        if (percentage_workers_agegroup !== null) {
                            percentage_workers_agegroup.destroy();
                        }

                        percentage_workers_agegroup = barCharts(
                            @this.chart.percentage_workers_agegroup.label,
                            @this.chart.percentage_workers_agegroup.data,
                            'percentage_workers_agegroup',
                            '{{ color(1) }}',
                            '%'
                        );
                    }

                    // training_hours_by_gender
                    // if(@this.chart.training_hours_by_gender) {
                    //     if (training_hours_by_gender !== null) {
                    //         training_hours_by_gender.destroy();
                    //     }

                    //     training_hours_by_gender = barCharts(
                    //         @this.chart.training_hours_by_gender.label,
                    //         @this.chart.training_hours_by_gender.data,
                    //         'training_hours_by_gender',
                    //         '{{ color(1) }}'
                    //     );
                    // }

                    // avg_hours_training_per_worker
                    if(@this.chart.avg_hours_training_per_worker) {
                        if (avg_hours_training_per_worker !== null) {
                            avg_hours_training_per_worker.destroy();
                        }

                        avg_hours_training_per_worker = barCharts(
                            @this.chart.avg_hours_training_per_worker.label,
                            @this.chart.avg_hours_training_per_worker.data,
                            'avg_hours_training_per_worker',
                            '{{ color(1) }}'
                        );
                    }

                    // workers_training_actions
                    if(@this.chart.workers_training_actions) {
                        if (workers_training_actions !== null) {
                            workers_training_actions.destroy();
                        }

                        workers_training_actions = barCharts(
                            @this.chart.workers_training_actions.label,
                            @this.chart.workers_training_actions.data,
                            'workers_training_actions',
                            '{{ color(1) }}'
                        );
                    }

                    // female_workers_performance_evaluation
                    if(@this.chart.female_workers_performance_evaluation) {
                        if (female_workers_performance_evaluation !== null) {
                            female_workers_performance_evaluation.destroy();
                        }

                        female_workers_performance_evaluation = barCharts(
                            @this.chart.female_workers_performance_evaluation.label,
                            @this.chart.female_workers_performance_evaluation.data,
                            'female_workers_performance_evaluation',
                            '{{ color(1) }}'
                        );
                    }

                    // male_workers_performance_evaluation
                    if(@this.chart.male_workers_performance_evaluation) {
                        if (male_workers_performance_evaluation !== null) {
                            male_workers_performance_evaluation.destroy();
                        }

                        male_workers_performance_evaluation = barCharts(
                            @this.chart.male_workers_performance_evaluation.label,
                            @this.chart.male_workers_performance_evaluation.data,
                            'male_workers_performance_evaluation',
                            '{{ color(1) }}'
                        );
                    }

                    // workers_initial_parental_leave
                    if(@this.chart.workers_initial_parental_leave) {
                        if (workers_initial_parental_leave !== null) {
                            workers_initial_parental_leave.destroy();
                        }

                        workers_initial_parental_leave = barCharts(
                            @this.chart.workers_initial_parental_leave.label,
                            @this.chart.workers_initial_parental_leave.data,
                            'workers_initial_parental_leave',
                            '{{ color(1) }}'
                        );
                    }

                    // workers_return_towork_after_parental_leave
                    if(@this.chart.workers_return_towork_after_parental_leave) {
                        if (workers_return_towork_after_parental_leave !== null) {
                            workers_return_towork_after_parental_leave.destroy();
                        }

                        workers_return_towork_after_parental_leave = barCharts(
                            @this.chart.workers_return_towork_after_parental_leave.label,
                            @this.chart.workers_return_towork_after_parental_leave.data,
                            'workers_return_towork_after_parental_leave',
                            '{{ color(1) }}'
                        );
                    }

                    // workers_return_towork_after_parental_leave_twelve_month
                    if(@this.chart.workers_return_towork_after_parental_leave_twelve_month) {
                        if (workers_return_towork_after_parental_leave_twelve_month !== null) {
                            workers_return_towork_after_parental_leave_twelve_month.destroy();
                        }

                        workers_return_towork_after_parental_leave_twelve_month = barCharts(
                            @this.chart.workers_return_towork_after_parental_leave_twelve_month.label,
                            @this.chart.workers_return_towork_after_parental_leave_twelve_month.data,
                            'workers_return_towork_after_parental_leave_twelve_month',
                            '{{ color(1) }}'
                        );
                    }

                    // modalities_schedules_reporting_units_charts
                    if(typeof @this.chart.modalities_schedules_reporting_units_charts !== 'undefined' && @this.chart.modalities_schedules_reporting_units_charts) {
                        if (modalities_schedules_reporting_units_charts !== null) {
                            modalities_schedules_reporting_units_charts.destroy();
                        }

                        modalities_schedules_reporting_units_charts = barCharts(
                            @this.chart.modalities_schedules_reporting_units_charts.label,
                            @this.chart.modalities_schedules_reporting_units_charts.data,
                            'modalities_schedules_reporting_units_charts',
                            '{{ color(1) }}'
                        );
                    }

                    // conciliation_measures_unit_charts
                    if(typeof @this.chart.conciliation_measures_unit_charts !== 'undefined' && @this.chart.conciliation_measures_unit_charts) {
                        if (conciliation_measures_unit_charts !== null) {
                            conciliation_measures_unit_charts.destroy();
                        }

                        conciliation_measures_unit_charts = barCharts(
                            @this.chart.conciliation_measures_unit_charts.label,
                            @this.chart.conciliation_measures_unit_charts.data,
                            'conciliation_measures_unit_charts',
                            '{{ color(1) }}'
                        );
                    }

                    // established_by_law_charts
                    if(typeof @this.chart.established_by_law_charts !== 'undefined' && @this.chart.established_by_law_charts) {

                        if (established_by_law_charts !== null) {
                            established_by_law_charts.destroy();
                        }

                        established_by_law_charts = barCharts(
                            @this.chart.established_by_law_charts.label,
                            @this.chart.established_by_law_charts.data,
                            'established_by_law_charts',
                            '{{ color(1) }}'
                        );
                    }

                // Desempenho em governação
                    // Actions plan
                    if(typeof @this.chart.risk_matrix !== 'undefined' && @this.chart.risk_matrix && (@this.chart.risk_matrix.very_high.label.length > 0 || @this.chart.risk_matrix.high.label.length > 0 || @this.chart.risk_matrix.intermediate.label.length > 0 || @this.chart.risk_matrix.low.label.length > 0)) {
                        var plugins = [{
                            beforeDatasetDraw: function(bubbleChart, args) {
                                if(action_status === 0) {
                                    const adjustedMap = [];
                                    const overlappedMap = [];

                                    bubbleChart.data.datasets.forEach(function(dataset, i) {
                                        const datasetMeta= bubbleChart.getDatasetMeta(i);
                                        datasetMeta.data.forEach(function(el, index) {

                                            const overlap = adjustedMap.find(point => point.x === dataset.data[index].x && point.y === dataset.data[index].y);
                                            if (overlap) {

                                                const counts = {};

                                                for (const num of overlappedMap) {
                                                    counts[num] = counts[num] ? counts[num] + 1 : 1;
                                                }

                                                var totaloverlapped = counts[(dataset.data[index].x +"_"+dataset.data[index].y).split(' ').join('_')];

                                                if (totaloverlapped > 0)
                                                    el.x -= 2.5 + (2.5 * totaloverlapped);
                                                else
                                                    el.x -= 2.5;

                                                overlappedMap.push((dataset.data[index].x +"_"+dataset.data[index].y).split(' ').join('_'));
                                            }

                                            adjustedMap.push(dataset.data[index]);
                                        });
                                    });
                                }
                            },
                            afterDatasetsDraw: function(bubbleChart, easing) {
                                var ctx = bubbleChart.ctx;

                                bubbleChart.data.datasets.forEach(function(dataset, i) {
                                    var meta = bubbleChart.getDatasetMeta(i);
                                    if (meta.type == "bubble") {
                                        meta.data.forEach(function(element, index) {
                                            var dataString = dataset.label[index].toString();

                                            ctx.textAlign = 'center';
                                            ctx.textBaseline = 'middle';
                                            ctx.font = "15px bold " + twConfig.theme.fontFamily
                                                .encodesans;
                                            ctx.fillStyle = twConfig.theme.colors.esg4;

                                            var position = element.tooltipPosition();

                                            ctx.fillText(dataString, position.x, position.y);
                                        });
                                    }
                                });
                            },
                            afterInit: function (chart, args, options) {
                                const chartId = chart.canvas.id;
                                const legendId = `actions_plan-legend`;
                                let html = '';

                                chart.data.datasets.forEach((data, i) => {
                                    data.label.forEach((label, i) => {
                                        var label = label,
                                            description = data.description[i].split(' ').map(
                                                (word) => word[0].toUpperCase() + word.substring(1)
                                            ).join(" "),
                                            color = data.backgroundColor;

                                        html += `
                                                <div class="flex mt-3">
                                                    <div class="text-esg5 text-xl">
                                                        <span class="w-4 h-4 relative -top-2 rounded-full inline-block text-xs text-center text-esg4" style="background-color:${color}">
                                                            ${label}
                                                        </span>
                                                    </div>
                                                    <div class="pl-2 inline-block text-xs text-esg8/70">${description}</div>
                                                </div>
                                        `;
                                    });
                                });

                                document.getElementById(legendId).innerHTML = html;
                            },
                            afterRender: function() {
                                action_status = 1;
                            }
                        }];

                        const getOrCreateTooltip = (chart) => {

                            const elements = document.getElementsByClassName("action_tootltip");
                            while(elements.length > 0){
                                elements[0].parentNode.removeChild(elements[0]);
                            }

                            tooltipEl = document.createElement('div');
                            tooltipEl.classList.add("action_tootltip");
                            tooltipEl.style.background = twConfig.theme.colors.esg8;
                            tooltipEl.style.fontFamily = twConfig.theme.fontFamily.encodesans;
                            tooltipEl.style.fontWeight = 500;
                            tooltipEl.style.borderRadius = '8px';
                            tooltipEl.style.color = twConfig.theme.colors.esg4;
                            tooltipEl.style.fontSize = '0.75rem';
                            tooltipEl.style.opacity = 1;
                            tooltipEl.style.textAlign = "center";
                            tooltipEl.style.pointerEvents = 'none';
                            tooltipEl.style.marginTop = "20px";
                            tooltipEl.style.position = 'absolute';
                            tooltipEl.style.transform = 'translate(-50%, 0)';
                            tooltipEl.style.transition = 'all .1s ease';

                            const table = document.createElement('table');
                            table.style.margin = '0px';

                            tooltipEl.appendChild(table);
                            chart.canvas.parentNode.appendChild(tooltipEl);

                            return tooltipEl;
                        };

                        const externalTooltipHandler = (context) => {
                            // Tooltip Element
                            const {chart, tooltip} = context;
                            const tooltipEl = getOrCreateTooltip(chart);

                            // Hide if no tooltip
                            if (tooltip.opacity === 0) {
                                tooltipEl.style.opacity = 0;
                                return;
                            }

                            // Set Text
                            if (tooltip.body) {
                                const titleLines = tooltip.title || [];
                                const bodyLines = tooltip.body.map(b => b.lines[0]);
                                const LabelsArray = bodyLines[0];

                                const tableBody = document.createElement('tbody');
                                const colors = tooltip.labelColors;

                                const span = document.createElement('span');
                                span.style.background = colors.backgroundColor;
                                span.style.fontFamily = twConfig.theme.fontFamily.encodesans;
                                span.style.marginRight = '10px';
                                span.style.height = '10px';
                                span.style.width = '150px';
                                span.style.display = 'inline-block';
                                span.innerHTML = LabelsArray;

                                const tr = document.createElement('tr');
                                tr.style.backgroundColor = 'inherit';
                                tr.style.borderWidth = 0;

                                const td = document.createElement('td');
                                td.style.borderWidth = 0;

                                td.appendChild(span);
                                tr.appendChild(td);
                                tableBody.appendChild(tr);
                                tooltipEl.appendChild(tableBody);
                            }

                            const {offsetLeft: positionX, offsetTop: positionY} = chart.canvas;

                            // Display, position, and set styles for font
                            tooltipEl.style.opacity = 1;
                            tooltipEl.style.left = positionX + tooltip.caretX + 'px';
                            tooltipEl.style.top = positionY + tooltip.caretY + 'px';
                            tooltipEl.style.font = tooltip.options.bodyFont.string;
                            tooltipEl.style.padding = '8px';
                        };

                        var options = {
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: false,
                                },
                                tooltip: {
                                    enabled: false,
                                    position: 'nearest',
                                    external: externalTooltipHandler,
                                    callbacks: {
                                        label: function(context) {
                                            let label = '',
                                                index = context.dataIndex,
                                                currentLabel = context.dataset.description[index];

                                            return currentLabel;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    display: true,
                                    grid: {
                                        borderColor: twConfig.theme.colors.esg7,
                                        borderWidth: 2,
                                        tickLength: 5,
                                        color: twConfig.theme.colors.esg7,
                                        borderDash: [1, 5],
                                    },
                                    ticks: {
                                        display: true,
                                        maxTicksLimit: 10
                                    },
                                    type: 'category',
                                    labels: ["Muito Improvável", "Improvável", "Pouco Provável", "Provável", "Muito Provável"]
                                },
                                y: {
                                    display: true,
                                    grid: {
                                        borderColor: twConfig.theme.colors.esg7,
                                        borderWidth: 2,
                                        tickLength: 0,
                                        color: twConfig.theme.colors.esg7,
                                        borderDash: [1, 5],
                                    },
                                    ticks: {
                                        display: true,
                                        maxTicksLimit: 10
                                    },
                                    type: 'category',
                                    labels: ["Desastroso", "Significativo", "Moderado", "Menor", "Insignificante"]
                                }
                            }
                        };

                        if (actionPlanChart !== null) {
                            actionPlanChart.destroy();
                        }

                        actionPlanChart = new Chart(document.getElementById("actions_plan"), {
                            type: 'bubble',
                            data: {
                                datasets: [
                                    {
                                        label: @this.chart.risk_matrix.very_high.label,
                                        description: @this.chart.risk_matrix.very_high.description,
                                        data: @this.chart.risk_matrix.very_high.data,
                                        borderColor: '#c81e1e',
                                        backgroundColor: '#c81e1e'
                                    },
                                    {
                                        label: @this.chart.risk_matrix.high.label,
                                        description: @this.chart.risk_matrix.high.description,
                                        data: @this.chart.risk_matrix.high.data,
                                        borderColor: twConfig.theme.colors.esg1,
                                        backgroundColor: twConfig.theme.colors.esg1
                                    },
                                    {
                                        label: @this.chart.risk_matrix.intermediate.label,
                                        description: @this.chart.risk_matrix.intermediate.description,
                                        data: @this.chart.risk_matrix.intermediate.data,
                                        borderColor: '#FDD835',
                                        backgroundColor: '#FDD835',
                                    },
                                    {
                                        label: @this.chart.risk_matrix.low.label,
                                        description: @this.chart.risk_matrix.low.description,
                                        data: @this.chart.risk_matrix.low.data,
                                        borderColor: twConfig.theme.colors.esg6,
                                        backgroundColor: twConfig.theme.colors.esg6
                                    },
                                ]
                            },
                            options,
                            plugins
                        });
                    }

                    // board_of_directors_by_gender
                    if(@this.chart.board_of_directors_by_gender) {
                        if (board_of_directors_by_gender !== null) {
                            board_of_directors_by_gender.destroy();
                        }

                        board_of_directors_by_gender = pieCharts(
                            @this.chart.board_of_directors_by_gender.label,
                            @this.chart.board_of_directors_by_gender.data,
                            'board_of_directors_by_gender',
                            ['{{ color(5) }}', '{{ color(1) }}', '{{ color(6) }}']
                        );
                    }

                    // independent_members_participate_board_of_director
                    if(@this.chart.independent_members_participate_board_of_director) {
                        if (independent_members_participate_board_of_director !== null) {
                            independent_members_participate_board_of_director.destroy();
                        }

                        independent_members_participate_board_of_director = barCharts(
                            @this.chart.independent_members_participate_board_of_director.label,
                            @this.chart.independent_members_participate_board_of_director.data,
                            'independent_members_participate_board_of_director',
                            '{{ color(3) }}'
                        );
                    }

                    // board_of_directors_by_age_group
                    if(@this.chart.board_of_directors_by_age_group) {
                        if (board_of_directors_by_age_group !== null) {
                            board_of_directors_by_age_group.destroy();
                        }

                        board_of_directors_by_age_group = barCharts(
                            @this.chart.board_of_directors_by_age_group.label,
                            @this.chart.board_of_directors_by_age_group.data,
                            'board_of_directors_by_age_group',
                            '{{ color(3) }}',
                            '%'
                        );
                    }

                    // risks_arising_supply_chain_reporting_units_charts
                    if(typeof @this.chart.risks_arising_supply_chain_reporting_units_charts !== 'undefined' && @this.chart.risks_arising_supply_chain_reporting_units_charts) {
                        if (risks_arising_supply_chain_reporting_units_charts !== null) {
                            risks_arising_supply_chain_reporting_units_charts.destroy();
                        }

                        risks_arising_supply_chain_reporting_units_charts = barCharts(
                            @this.chart.risks_arising_supply_chain_reporting_units_charts.label,
                            @this.chart.risks_arising_supply_chain_reporting_units_charts.data,
                            'risks_arising_supply_chain_reporting_units_charts',
                            '{{ color(3) }}'
                        );
                    }

                    // average_probability_risk_categories
                    if(typeof @this.chart.average_probability_risk_categories !== 'undefined' && @this.chart.average_probability_risk_categories) {
                        if (average_probability_risk_categories !== null) {
                            average_probability_risk_categories.destroy();
                        }

                        average_probability_risk_categories = barCharts(
                            @this.chart.average_probability_risk_categories.label,
                            @this.chart.average_probability_risk_categories.data,
                            'average_probability_risk_categories',
                            '{{ color(3) }}'
                        );
                    }

                    // average_severity_risk_categories
                    if(typeof @this.chart.average_severity_risk_categories !== 'undefined' && @this.chart.average_severity_risk_categories) {
                        if (average_severity_risk_categories !== null) {
                            average_severity_risk_categories.destroy();
                        }

                        average_severity_risk_categories = barCharts(
                            @this.chart.average_severity_risk_categories.label,
                            @this.chart.average_severity_risk_categories.data,
                            'average_severity_risk_categories',
                            '{{ color(3) }}'
                        );
                    }

                // Desempenho ambiental

                    // reduce_consumption_charts
                    if(typeof @this.chart.reduce_consumption_charts !== 'undefined' && @this.chart.reduce_consumption_charts) {
                        if (reduce_consumption_charts !== null) {
                            reduce_consumption_charts.destroy();
                        }

                        reduce_consumption_charts = barCharts(
                            @this.chart.reduce_consumption_charts.label,
                            @this.chart.reduce_consumption_charts.data,
                            'reduce_consumption_charts',
                            '{{ color(2) }}'
                        );
                    }

                    // total_amount_of_water_consumed
                    if(@this.chart.total_amount_of_water_consumed) {
                        if (total_amount_of_water_consumed !== null) {
                            total_amount_of_water_consumed.destroy();
                        }

                        total_amount_of_water_consumed = barCharts(
                            @this.chart.total_amount_of_water_consumed.label,
                            @this.chart.total_amount_of_water_consumed.data,
                            'total_amount_of_water_consumed',
                            '{{ color(2) }}'
                        );
                    }

                    // emission_value_per_parameter
                    if(@this.chart.emission_value_per_parameter) {
                        if (emission_value_per_parameter !== null) {
                            emission_value_per_parameter.destroy();
                        }

                        emission_value_per_parameter = barCharts(
                            @this.chart.emission_value_per_parameter.label,
                            @this.chart.emission_value_per_parameter.data,
                            'emission_value_per_parameter',
                            '{{ color(2) }}',
                        );
                    }

                    // electricity_consumed_per_source
                    if(@this.chart.electricity_consumed_per_source) {
                        if (electricity_consumed_per_source !== null) {
                            electricity_consumed_per_source.destroy();
                        }

                        electricity_consumed_per_source = barCharts(
                            @this.chart.electricity_consumed_per_source.label,
                            @this.chart.electricity_consumed_per_source.data,
                            'electricity_consumed_per_source',
                            '{{ color(2) }}',
                        );
                    }

                    percentage_electricity_consumption
                    if(@this.chart.percentage_electricity_consumption) {
                        if (percentage_electricity_consumption !== null) {
                            percentage_electricity_consumption.destroy();
                        }

                        percentage_electricity_consumption = pieCharts(
                            @this.chart.percentage_electricity_consumption.label,
                            @this.chart.percentage_electricity_consumption.data,
                            'percentage_electricity_consumption',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    // amount_of_non_road_fuel_consumed
                    if(@this.chart.amount_of_non_road_fuel_consumed) {
                        if (amount_of_non_road_fuel_consumed !== null) {
                            amount_of_non_road_fuel_consumed.destroy();
                        }

                    amount_of_non_road_fuel_consumed = barCharts(
                        @this.chart.amount_of_non_road_fuel_consumed.label,
                        @this.chart.amount_of_non_road_fuel_consumed.data,
                        'amount_of_non_road_fuel_consumed',
                        '{{ color(2) }}'
                    );
                }

                    // travel_in_vehicles_fuel_consumed
                    if(@this.chart.travel_in_vehicles_fuel_consumed) {
                        if (travel_in_vehicles_fuel_consumed !== null) {
                            travel_in_vehicles_fuel_consumed.destroy();
                        }

                        travel_in_vehicles_fuel_consumed = barCharts(
                            @this.chart.travel_in_vehicles_fuel_consumed.label,
                            @this.chart.travel_in_vehicles_fuel_consumed.data,
                            'travel_in_vehicles_fuel_consumed',
                            '{{ color(2) }}'
                        );
                    }

                    // vehicle_and_distance_travelled
                    if(@this.chart.vehicle_and_distance_travelled) {
                        if (vehicle_and_distance_travelled !== null) {
                            vehicle_and_distance_travelled.destroy();
                        }

                        vehicle_and_distance_travelled = barCharts(
                            @this.chart.vehicle_and_distance_travelled.label,
                            @this.chart.vehicle_and_distance_travelled.data,
                            'vehicle_and_distance_travelled',
                            '{{ color(2) }}'
                        );
                    }

                    // control_or_operate_fuel_consumed
                    if(@this.chart.control_or_operate_fuel_consumed) {
                        if (control_or_operate_fuel_consumed !== null) {
                            control_or_operate_fuel_consumed.destroy();
                        }

                        control_or_operate_fuel_consumed = barCharts(
                            @this.chart.control_or_operate_fuel_consumed.label,
                            @this.chart.control_or_operate_fuel_consumed.data,
                            'control_or_operate_fuel_consumed',
                            '{{ color(2) }}'
                        );
                    }

                    // type_of_transport_vehicle_distance
                    if(@this.chart.type_of_transport_vehicle_distance) {
                        if (type_of_transport_vehicle_distance !== null) {
                            type_of_transport_vehicle_distance.destroy();
                        }

                        type_of_transport_vehicle_distance = barCharts(
                            @this.chart.type_of_transport_vehicle_distance.label,
                            @this.chart.type_of_transport_vehicle_distance.data,
                            'type_of_transport_vehicle_distance',
                            '{{ color(2) }}'
                        );
                    }

                    // total_amount_of_leak
                    if(@this.chart.total_amount_of_leak) {
                        if (total_amount_of_leak !== null) {
                            total_amount_of_leak.destroy();
                        }

                        total_amount_of_leak = barCharts(
                            @this.chart.total_amount_of_leak.label,
                            @this.chart.total_amount_of_leak.data,
                            'total_amount_of_leak',
                            '{{ color(2) }}'
                        );
                    }

                    // total_amount_of_electricity
                    if(@this.chart.total_amount_of_electricity) {
                        if (total_amount_of_electricity !== null) {
                            total_amount_of_electricity.destroy();
                        }

                        total_amount_of_electricity = barCharts(
                            @this.chart.total_amount_of_electricity.label,
                            @this.chart.total_amount_of_electricity.data,
                            'total_amount_of_electricity',
                            '{{ color(2) }}'
                        );
                    }

                    // waste_produced_in_ton
                    if(@this.chart.waste_produced_in_ton) {
                        if (waste_produced_in_ton !== null) {
                            waste_produced_in_ton.destroy();
                        }

                        waste_produced_in_ton = barCharts(
                            @this.chart.waste_produced_in_ton.label,
                            @this.chart.waste_produced_in_ton.data,
                            'waste_produced_in_ton',
                            '{{ color(2) }}'
                        );
                    }

                    // waste_placed_in_recycling
                    if(@this.chart.waste_placed_in_recycling) {
                        if (waste_placed_in_recycling !== null) {
                            waste_placed_in_recycling.destroy();
                        }

                        waste_placed_in_recycling = barCharts(
                            @this.chart.waste_placed_in_recycling.label,
                            @this.chart.waste_placed_in_recycling.data,
                            'waste_placed_in_recycling',
                            '{{ color(2) }}',
                            '%'
                        );
                    }

                    // total_amount_of_water_m3
                    if(@this.chart.total_amount_of_water_m3) {
                        if (total_amount_of_water_m3 !== null) {
                            total_amount_of_water_m3.destroy();
                        }

                        total_amount_of_water_m3 = barCharts(
                            @this.chart.total_amount_of_water_m3.label,
                            @this.chart.total_amount_of_water_m3.data,
                            'total_amount_of_water_m3',
                            '{{ color(2) }}'
                        );
                    }

                    // type_total_quantity_goods_purchased_ton
                    if(@this.chart.type_total_quantity_goods_purchased_ton) {
                        if (type_total_quantity_goods_purchased_ton !== null) {
                            type_total_quantity_goods_purchased_ton.destroy();
                        }

                        type_total_quantity_goods_purchased_ton = barCharts(
                            @this.chart.type_total_quantity_goods_purchased_ton.label,
                            @this.chart.type_total_quantity_goods_purchased_ton.data,
                            'type_total_quantity_goods_purchased_ton',
                            '{{ color(2) }}'
                        );
                    }

                    // ghg_emissions_and_carbon_sequestration
                    if(@this.chart.ghg_emissions_and_carbon_sequestration) {
                        if (ghg_emissions_and_carbon_sequestration !== null) {
                            ghg_emissions_and_carbon_sequestration.destroy();
                        }

                        ghg_emissions_and_carbon_sequestration = barCharts(
                            @this.chart.ghg_emissions_and_carbon_sequestration.label,
                            @this.chart.ghg_emissions_and_carbon_sequestration.data,
                            'ghg_emissions_and_carbon_sequestration',
                            '{{ color(2) }}'
                        );
                    }

                    // air_pollutant
                    if(@this.chart.air_pollutant) {
                        if (air_pollutant !== null) {
                            air_pollutant.destroy();
                        }

                        air_pollutant = barCharts(
                            @this.chart.air_pollutant.label,
                            @this.chart.air_pollutant.data,
                            'air_pollutant',
                            '{{ color(2) }}'
                        );
                    }

                    // depletes_the_ozone_layer_in_tons
                    if(@this.chart.depletes_the_ozone_layer_in_tons) {
                        if (depletes_the_ozone_layer_in_tons !== null) {
                            depletes_the_ozone_layer_in_tons.destroy();
                        }

                        depletes_the_ozone_layer_in_tons = barCharts(
                            @this.chart.depletes_the_ozone_layer_in_tons.label,
                            @this.chart.depletes_the_ozone_layer_in_tons.data,
                            'depletes_the_ozone_layer_in_tons',
                            '{{ color(2) }}'
                        );
                    }

                    // Emissions by Scope
                    if(@this.chart.scope) {
                        if (scope !== null) {
                            scope.destroy();
                        }

                        scope = barCharts(
                            @this.chart.scope.label,
                            @this.chart.scope.data,
                            'emissions_scope',
                            ["#008131", "#6AD794", "#98BDA6"]
                        );
                    }

                    // energy_costs
                    if(@this.chart.energy_costs) {
                        if (energy_costs !== null) {
                            energy_costs.destroy();
                        }

                        energy_costs = barCharts(
                            @this.chart.energy_costs.label,
                            @this.chart.energy_costs.data,
                            'energy_costs',
                            '{{ color(2) }}'
                        );
                    }

                    // hazardous_waste
                    if(@this.chart.hazardous_waste) {
                        if (hazardous_waste !== null) {
                            hazardous_waste.destroy();
                        }

                        hazardous_waste = barCharts(
                            @this.chart.hazardous_waste.label,
                            @this.chart.hazardous_waste.data,
                            'hazardous_waste',
                            '{{ color(2) }}'
                        );
                    }

                // reporting_units_wwtp_progress
                    if(@this.chart.reporting_units_wwtp_progress) {
                        if (reporting_units_wwtp_progress !== null) {
                            reporting_units_wwtp_progress.destroy();
                        }

                        reporting_units_wwtp_progress = pieCharts(
                            @this.chart.reporting_units_wwtp_progress.label,
                            @this.chart.reporting_units_wwtp_progress.data,
                            'reporting_units_wwtp_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.ghg_emissions_progress) {
                        if (ghg_emissions_progress !== null) {
                            ghg_emissions_progress.destroy();
                        }

                        ghg_emissions_progress = pieCharts(
                            @this.chart.ghg_emissions_progress.label,
                            @this.chart.ghg_emissions_progress.data,
                            'ghg_emissions_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.waste_production_facilities_progress) {
                        if (waste_production_facilities_progress !== null) {
                            waste_production_facilities_progress.destroy();
                        }

                        waste_production_facilities_progress = pieCharts(
                            @this.chart.waste_production_facilities_progress.label,
                            @this.chart.waste_production_facilities_progress.data,
                            'waste_production_facilities_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.water_on_its_premises_progress) {
                        if (water_on_its_premises_progress !== null) {
                            water_on_its_premises_progress.destroy();
                        }

                        water_on_its_premises_progress = pieCharts(
                            @this.chart.water_on_its_premises_progress.label,
                            @this.chart.water_on_its_premises_progress.data,
                            'water_on_its_premises_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.purchased_goods_during_reporting_period_progress) {
                        if (purchased_goods_during_reporting_period_progress !== null) {
                            purchased_goods_during_reporting_period_progress.destroy();
                        }

                        purchased_goods_during_reporting_period_progress = pieCharts(
                            @this.chart.purchased_goods_during_reporting_period_progress.label,
                            @this.chart.purchased_goods_during_reporting_period_progress.data,
                            'purchased_goods_during_reporting_period_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.telecommuting_workers_progress) {
                        if (telecommuting_workers_progress !== null) {
                            telecommuting_workers_progress.destroy();
                        }

                        telecommuting_workers_progress = pieCharts(
                            @this.chart.telecommuting_workers_progress.label,
                            @this.chart.telecommuting_workers_progress.data,
                            'telecommuting_workers_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.carbon_sequestration_capacity_ghg_progress) {
                        if (carbon_sequestration_capacity_ghg_progress !== null) {
                            carbon_sequestration_capacity_ghg_progress.destroy();
                        }

                        carbon_sequestration_capacity_ghg_progress = pieCharts(
                            @this.chart.carbon_sequestration_capacity_ghg_progress.label,
                            @this.chart.carbon_sequestration_capacity_ghg_progress.data,
                            'carbon_sequestration_capacity_ghg_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.emission_air_pollutants_progress) {
                        if (emission_air_pollutants_progress !== null) {
                            emission_air_pollutants_progress.destroy();
                        }

                        emission_air_pollutants_progress = pieCharts(
                            @this.chart.emission_air_pollutants_progress.label,
                            @this.chart.emission_air_pollutants_progress.data,
                            'emission_air_pollutants_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.deplete_the_ozone_layer_progress) {
                        if (deplete_the_ozone_layer_progress !== null) {
                            deplete_the_ozone_layer_progress.destroy();
                        }

                        deplete_the_ozone_layer_progress = pieCharts(
                            @this.chart.deplete_the_ozone_layer_progress.label,
                            @this.chart.deplete_the_ozone_layer_progress.data,
                            'deplete_the_ozone_layer_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.km_25_km_radius_environmental_protection_area_progress) {
                        if (km_25_km_radius_environmental_protection_area_progress !== null) {
                            km_25_km_radius_environmental_protection_area_progress.destroy();
                        }

                        km_25_km_radius_environmental_protection_area_progress = pieCharts(
                            @this.chart.km_25_km_radius_environmental_protection_area_progress.label,
                            @this.chart.km_25_km_radius_environmental_protection_area_progress.data,
                            'km_25_km_radius_environmental_protection_area_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }

                    if(@this.chart.participates_local_development_programs_progress) {
                        if (participates_local_development_programs_progress !== null) {
                            participates_local_development_programs_progress.destroy();
                        }

                        participates_local_development_programs_progress = pieCharts(
                            @this.chart.participates_local_development_programs_progress.label,
                            @this.chart.participates_local_development_programs_progress.data,
                            'participates_local_development_programs_progress',
                            ['{{ color(5) }}', '{{ color(1) }}']
                        );
                    }
            }

            // Common function for bar charts
            function barCharts(labels, data, id, barColor, unit = '')
            {
                var data = {
                    labels: labels,
                    datasets: [{
                        data: data,
                        lineTension: 0.3,
                        fill: true,
                        backgroundColor: barColor,
                        borderColor: '{{ color(6) }}'
                    }],
                };

                var chartOptions = {
                    layout: {
                        padding: {
                            top: 50
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function (value) {

                                return value + unit;
                            }
                        }
                    },
                    scales: {
                        y: {
                            display: false, // Hide left side of bar numbers
                            ticks: {
                                color: '{{ color(8) }}'
                            },
                            grid: {
                                drawBorder: false,
                                display: true,
                                borderColor: '{{ color(8) }}',
                                borderDash: [10, 2],
                            },
                        },
                        x: {
                            display: true,
                            offset: true,
                            ticks: {
                                display: true,
                                color: '{{ color(8) }}'
                            },
                            grid: {
                                display: false,
                                borderColor: '{{ color(8) }}'
                            },
                        }
                    },
                    // animation: {
                    //     duration: 0
                    // }

                };

                return new Chart(document.getElementById(id).getContext("2d"), {
                    type: 'bar',
                    data: data,
                    options: chartOptions,
                    plugins: [ChartDataLabels]
                });
            }

            // Common function for pie charts
            function pieCharts(labels, data, id, barColor)
            {
                var options = {
                        plugins: {
                            legend: {
                                display: false,
                            },
                            title: {
                                display: true,
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 22,
                                    weight: twConfig.theme.fontWeight.bold,
                                },
                                padding: {
                                    bottom: 30
                                },
                                color: twConfig.theme.colors.esg27
                            },
                            datalabels: {
                                color: twConfig.theme.colors.esg27,
                                formatter: function (value) {
                                    var total = data;
                                    const sum = total.reduce((accumulator, current) => accumulator + current);

                                    return Math.round(value * 100 / sum) + '%';
                                },
                                font: {
                                    weight: 'bold',
                                    size: 15,
                                }
                            }
                        },
                        cutout: '70%',
                        animation: {
                            duration: 0
                        }
                    };

                    var config = {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: barColor,
                                borderRadius: 8,
                                borderWidth: 0,
                                spacing: 0,
                                hoverOffset: 1
                            }]
                        },
                        options: options,
                        plugins: [ChartDataLabels]
                    };

                return new Chart(document.getElementById(id), config);
            }

            function handlePrintClick(e) {
                // Now you can access the event object (e) directly
                printview = true;
            }

            document.addEventListener('alpine:init', () => {
                Alpine.bind('handlePrintClick', () => ({
                    type: 'button',

                    '@click'() {
                        printview = true;
                        setTimeout(() => {
                            window.print();
                        }, "1000");
                    },
                }))
            });
        </script>
    @endpush
    <div class="print:hidden bg-esg6 h-44 flex left-[50%] -ml-[50vw] w-screen relative items-center justify-center -mt-16">
        <div class="w-full mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 absolute z-90">
            <div class="grid text-esg4 pt-14 text-2xl font-bold">
                {{ __('Dashboard') }}
            </div>
        </div>
    </div>

    <div class="px-4 lg:px-0 print:p-10" >
        <div class="max-w-7xl mx-auto">
            <div class="mt-5 print:hidden">
                <div class="flex items-center justify-between pb-5 mb-3 border-b border-b-esg8/20">
                    <div class="text-esg8 text-lg font-semibold pt-3 flex gap-2">
                        <div class="bg-esg8/10 text-esg8 text-base font-semibold p-2 rounded-md flex items-center">
                            @include('icons.plan', ['class' => 'mr-2'])
                            TDP
                        </div>

                        <div class="bg-esg8/10 text-esg8 text-base font-semibold p-2 rounded-md flex items-center">
                            @include('icons.calender', ['class' => 'mr-2'])
                            {{ $period }}
                        </div>

                        <div class="">
                            @php $data = json_encode(["questionnaire_type" => $this->dashboardType]); @endphp
                            <x-buttons.btn-alt text="{{ __('Editar') }}" modal="dashboard.modals.filter" :data="$data" class="!h-10 text-center !border-esg8/50 !text-esg8/70 !block !px-3 !py-3 cursor-pointer"/>
                        </div>
                    </div>


                    @if($charts != null)
                        <div>
                            @php $data = json_encode(["questionnaireIds" => $this->search['questionnaire']]); @endphp
                            <x-buttons.btn-icon-text class="bg-esg6 text-esg4 {{ $printView === 0 ? '' : 'hidden' }}" modal="dashboard.modals.report" :data="$data">
                                <x-slot name="buttonicon">
                                    @include(tenant()->views .'icons.download', ['class' => 'inline'])
                                </x-slot>
                                <span class="ml-2">Download relatório</span>
                            </x-buttons.btn-icon-text>

                            <x-buttons.btn-icon-text class="bg-esg6 text-esg4 {{ $printView === 0 ? 'hidden' : '' }} print:hidden" wire:click="abortPrint()">
                                <x-slot name="buttonicon">
                                    @include(tenant()->views .'icons.download', ['class' => 'inline'])
                                </x-slot>
                                <span class="ml-2">Retornar</span>
                            </x-buttons.btn-icon-text>
                        </div>
                    @endif
                </div>

                <div class="">
                    <div class="text-esg8 text-lg font-semibold pt-3">
                        <div class="flex gap-5 items-center">
                            <div class="w-full">
                                <x-inputs.tomselect
                                    :wire_ignore="false"
                                    wire:model.defer="search.questionnaire"
                                    :options="$questionnaireList"
                                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']"
                                    :items="$search['questionnaire']"
                                    class="min-w-[150px]"
                                    multiple
                                />

                                {{-- <x-inputs.tomselect
                                    :wire_ignore="false"
                                    wire:model.defer="search.questionnaire"
                                    remote="{{$questionnaireListURL}}"
                                    :items="$search['questionnaire']"
                                    class="min-w-[150px]"
                                    multiple
                                /> --}}
                            </div>
                            <div>
                                <x-buttons.a-alt wire:click="filter()" text="{{ __('Mostrar') }}" class="!h-10 text-center !block !px-3 !py-3 cursor-pointer"/>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($charts != null)
        <div class="px-4 lg:px-0 print:p-10">
            <div class="max-w-7xl mx-auto">
                <div class="mt-5">
                    {{-- Print text --}}
                    <div class="pagebreak print:-mt-[150px] {{ $printView === 0 ? 'hidden' : '' }}" >
                        <div class="pagebreak print:h-full">
                            <img src="{{ global_asset('images/customizations/tenant88bd07c3-05a9-4a30-b732-c865d7ccbce1/01.jpg')}}" class="">
                            <div class="absolute w-[31.8%] h-56 text-esg27 -mt-[44%] text-center grid place-content-center text-3xl font-bold print:w-[90.5%] print:-mt-[180%] print:h-full">
                                <p>{{ __('RELATÓRIO DE SUSTENTABILIDADE') }}</p>
                                <p class="text-xl">{{ __('ESG | ') }} {{ (isset($charts['report']['company'])) ? date('Y', strtotime($charts['report']['company'][0]['from'])) : '-' }}</p>
                            </div>

                            <div class="absolute z-40 w-[31.8%] h-56 bg-esg7 -mt-[28%] text-center grid place-content-center text-3xl font-bold print:w-[90%] print:-mt-[80%]">
                                @if($charts['report']['company'])
                                    {{ $charts['report']['company'][0]['company']['name'] }}
                                @endif
                            </div>
                        </div>

                        <div class="pagebreak print:mt-20">
                            <p class="font-encodesans text-base font-bold leading-10 text-[#008131]"> {{ __('Índice') }} </p>

                            <div class="flex justify-between">
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4"> 1 {{ __('Mensagem da Gestão') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4">  </p>
                            </div>

                            <div class="flex justify-between">
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4"> 2 {{ __('Perfil da Organização') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4">  </p>
                            </div>

                            <div class="flex justify-between">
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4"> 3 {{ __('Estratégia') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4">  </p>
                            </div>

                            <div class="flex justify-between pl-10">
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4"> 3.1 {{ __('Principais impactes, riscos e oportunidades') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4">  </p>
                            </div>

                            <div class="flex justify-between pl-10">
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4"> 3.2 {{ __('Partes interessadas') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4">  </p>
                            </div>

                            <div class="flex justify-between pl-10">
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4"> 3.3 {{ __('Materialidade') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4">  </p>
                            </div>

                            <div class="flex justify-between pl-10">
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4"> 3.4
                                    @if(isset($charts['report']['company'][0]['company']['size']))
                                        @if($charts['report']['company'][0]['company']['size'] == 1 || $charts['report']['company'][0]['company']['size'] == 2)
                                            {{ __('Compromissos e metas de curto e médio prazo') }}
                                        @else
                                            {{ __('Metas de curto, médio e longo prazo') }}
                                        @endif
                                    @endif
                                </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4">  </p>
                            </div>

                            <div class="flex justify-between">
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4"> 4 {{ __('Contributo para a Agenda 2030 e para os Objetivos de Desenvolvimento Sustentável') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4"> </p>
                            </div>

                            <div class="flex justify-between">
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4"> 5 {{ __('Desempenho Económico') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4">  </p>
                            </div>

                            <div class="flex justify-between">
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4"> 6 {{ __('Desempenho ESG') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4">  </p>
                            </div>

                            <div class="flex justify-between pl-10">
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4"> 6.1 {{ __('Ambiental') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4">  </p>
                            </div>

                            <div class="flex justify-between pl-10">
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4"> 6.2 {{ __('Social') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4">  </p>
                            </div>

                            <div class="flex justify-between pl-10">
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4"> 6.3 {{ __('Governação') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-esg8 mt-4">  </p>
                            </div>

                            <div class="flex justify-between">
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4"> 7 {{ __('Declaração de Responsabilidade') }} </p>
                                <p class="font-encodesans text-base font-normal leading-10 text-[#008131] mt-4">  </p>
                            </div>

                        </div>

                        <div class="pagebreak">
                            <div class="flex items-center gap-5 mt-50 print:mt-20">
                                <div class="w-12 h-3 bg-red-700"> </div>
                                <div>
                                    <h1 class="font-encodesans text-base font-bold leading-10 text-[#008131]">  1. {{__('Mensagem da Gestão')}}</h1>
                                </div>
                            </div>

                            <div class="grid grid-cols1 md:gap-10 mt-4 pb-4">
                                <div class="">
                                    <p class="text-esg8 text-xs font-normal">{{ $charts['report']['mensagem'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="pagebreak">
                            <div class="flex items-center gap-5 mt-50 print:mt-20">
                                <div class="w-12 h-3 bg-red-700"> </div>
                                <div>
                                    <h1 class="font-encodesans text-base font-bold leading-10 text-[#008131]"> 2. {{__('Perfil da Organização')}}</h1>
                                </div>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Nome') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">
                                    @if($charts['report']['company'])
                                        {{ $charts['report']['company'][0]['company']['name'] }}
                                    @endif
                                </p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Atividades, marcas, produtos e serviços') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['products_services'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Localização da sede') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">
                                    @if($charts['report']['vat_country'])
                                        @foreach ($charts['report']['vat_country'] as $row)
                                            {{ $row['name'] }}
                                        @endforeach
                                    @endif
                                </p>
                            </div>

                            <div class="mt-4 pb-4 pagebreak">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Tipo e natureza jurídica da propriedade') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['nature_ownership'] }}</p>
                            </div>

                            <div class="mt-4 pb-4 print:mt-20">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Mercados abrangidos') }} </p>
                                @if($charts['report']['mercados'])
                                    @foreach ($charts['report']['mercados'] as $row)
                                        <p class="text-esg8 text-xs font-normal mt-4"> {{ $row['name'] }} </p>
                                    @endforeach
                                @endif
                            </div>

                            @if(isset($charts['report']['company'][0]['company']['size']))
                                <div class="mt-4 pb-4">
                                    <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Dimensão') }} </p>
                                    <p class="text-esg8 text-xs font-normal mt-4">
                                        @if($charts['report']['company'][0]['company']['size'] == 2)
                                            {{ __('Pequena') }}
                                        @elseif($charts['report']['company'][0]['company']['size'] == 3)
                                            {{ __('Média') }}
                                        @elseif($charts['report']['company'][0]['company']['size'] == 4)
                                            {{ __('Grande') }}
                                        @endif

                                    </p>
                                </div>
                            @endif

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Número de trabalhadores') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['employees'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Número de unidades de negócio') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['business_units'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Relação Com o Cliente') }} </p>
                                <p class="font-normal text-xs text-[#008131] mt-4"> {{ __('Caracterização do perfil do cliente') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['characterization'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Níveis de satisfação nas plataformas digitais') }} </p>

                                <div class="flex gap-5 items-center">
                                    @if(isset($charts['report']['customer_satisfaction'][901]))
                                        <div class="items-center">
                                            <p class="text-esg8 text-xs font-normal mt-4 text-center"> <span class="text-2xl font-extrabold">{{ $charts['report']['customer_satisfaction'][901] }}</span> / 10 </p>
                                            <div class="flex">
                                                @for($i=1; $i <= 10; $i++)
                                                    @if($i <= $charts['report']['customer_satisfaction'][901])
                                                        @include(tenant()->views .'icons.reports.star')
                                                    @else
                                                        @include(tenant()->views .'icons.reports.star', ['color' => color(7)])
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="flex place-content-center mt-2">
                                                @include(tenant()->views .'icons.reports.booking')
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($charts['report']['customer_satisfaction'][902]))
                                        <div>
                                            <p class="text-esg8 text-xs font-normal mt-4 text-center"> <span class="text-2xl font-extrabold">{{ $charts['report']['customer_satisfaction'][902] }}</span> / 5 </p>
                                            <div class="flex">
                                                @for($i=1; $i <= 5; $i++)
                                                    @if($i <= $charts['report']['customer_satisfaction'][902])
                                                        @include(tenant()->views .'icons.reports.star')
                                                    @else
                                                        @include(tenant()->views .'icons.reports.star', ['color' => color(7)])
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="flex place-content-center mt-2">
                                                @include(tenant()->views .'icons.reports.airbnb')
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($charts['report']['customer_satisfaction'][903]))
                                        <div>
                                            <p class="text-esg8 text-xs font-normal mt-4 text-center"> <span class="text-2xl font-extrabold">{{ $charts['report']['customer_satisfaction'][903] }}</span> / 5 </p>
                                            <div class="flex">
                                                @for($i=1; $i <= 5; $i++)
                                                    @if($i <= $charts['report']['customer_satisfaction'][903])
                                                        @include(tenant()->views .'icons.reports.star')
                                                    @else
                                                        @include(tenant()->views .'icons.reports.star', ['color' => color(7)])
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="flex place-content-center mt-2">
                                                @include(tenant()->views .'icons.reports.zomato')
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($charts['report']['customer_satisfaction'][904]))
                                        <div>
                                            <p class="text-esg8 text-xs font-normal mt-4 text-center"> <span class="text-2xl font-extrabold">{{ $charts['report']['customer_satisfaction'][904] }}</span> / 10 </p>
                                            <div class="flex">
                                                @for($i=1; $i <= 10; $i++)
                                                    @if($i <= $charts['report']['customer_satisfaction'][904])
                                                        @include(tenant()->views .'icons.reports.star')
                                                    @else
                                                        @include(tenant()->views .'icons.reports.star', ['color' => color(7)])
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="flex place-content-center mt-2">
                                                @include(tenant()->views .'icons.reports.fork')
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Número de reclamações') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['claims'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Número de elogios') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['elogios'] }}</p>
                            </div>

                            <div class="mt-4 pb-4 pagebreak">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Número médio de meses de operação') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['number_months'] }}</p>
                            </div>

                            <div class="mt-4 pb-4 print:mt-20">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Cadeia de fornecedores') }} </p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Número de fornecedores') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['numero_fornecedores'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Tipos de fornecedores (principais marcas, produtos e serviços)') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['tipos_de_fornecedores'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Fornecedores de primeiro nível ou diretos') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['fornecedores_de_primeiro'] }}</p>
                            </div>

                            <div class="mt-4 pb-4 print:mt-20">
                                <p class="font-bold text-xs text-[#008131] uppercase"> {{ __('Cadeia de fornecedores (Cont.)') }} </p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Localização geográfica') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['localização_geografica'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Número de fornecedores que apresentam risco de incidentes com escravatura moderna') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['moderna'] }}</p>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-normal text-xs text-[#008131]"> {{ __('Número de fornecedores que apresentam risco de incidentes com trabalho e exploração infantil') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['infantil'] }}</p>
                            </div>

                        </div>

                        <div class="pagebreak">
                            <div class="flex items-center gap-5 mt-50 print:mt-20">
                                <div class="w-12 h-3 bg-red-700"> </div>
                                <div>
                                    <h1 class="font-encodesans text-base font-bold leading-10 text-[#008131]"> 3. {{__('Estratégia')}}</h1>
                                </div>
                            </div>

                            <div class="mt-4 pb-4">
                                <p class="font-bold text-xs text-[#008131] uppercase"> 3.1 {{ __('Principais impactes, riscos e oportunidades') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['oportunidades'] }}</p>
                            </div>
                        </div>

                        <div class="pagebreak print:mt-20">
                            <div class="mt-4 pb-4 pagebreak">
                                <p class="font-bold text-xs text-[#008131] uppercase"> 3.2 {{ __('Partes interessadas') }} </p>

                                <p class="font-normal text-xs text-[#008131] mt-4">{{ __('Identificação das partes interessadas') }}</p>
                                <p class="text-esg8 text-xs font-normal mt-3">{{ $charts['report']['interessadas_1'] }}</p>

                                <p class="font-normal text-xs    text-[#008131] mt-4">{{ __('Abordagem ao envolvimento das partes interessadas') }}</p>
                                <p class="text-esg8 text-xs font-normal mt-3">{{ $charts['report']['interessadas_2'] }}</p>
                            </div>

                            <div class="mt-4 pb-4 print:mt-20">
                                <p class="font-bold text-xs text-[#008131] uppercase"> 3.3 {{ __('materialidade') }} </p>
                            </div>

                            <div class="mt-2 pb-2">
                                <p class="font-normal text-xs text-[#008131] uppercase"> {{ __('temas materiais mais relevantes para o negócio') }} </p>
                            </div>

                            @if(isset($charts['materiality_matrix']))
                                @if($charts['materiality_matrix']['environmental'] && $charts['materiality_matrix']['social'] && $charts['materiality_matrix']['governance'])
                                    <div class="pagebreak mt-4 pb-4">
                                        <p class="font-normal text-xs text-[#008131]"> {{ __('Ambientais') }} </p>
                                        <div class="grid grid-cols-2 gap-3">
                                            @if (in_array(924, $charts['materiality_matrix']['environmental_options'], false))
                                                <div class="w-full mt-5 flex gap-5 items-center ">
                                                    <div> @include(tenant()->views .'icons.consumo', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][924]) ? color(2) : color(7)])</div>
                                                    <div class="text-xs font-bold text-esg8">
                                                        Consumo de água
                                                    </div>
                                                </div>
                                            @endif

                                            @if (in_array(925, $charts['materiality_matrix']['environmental_options'], false))
                                                <div class="w-full mt-5 flex gap-5 items-center">
                                                    <div> @include(tenant()->views .'icons.gestao_energia', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][925]) ? color(2) : color(7)])</div>
                                                    <div class="text-xs font-bold text-esg8">
                                                        Gestão de Energia
                                                    </div>
                                                </div>
                                            @endif

                                            @if (in_array(926, $charts['materiality_matrix']['environmental_options'], false))
                                                <div class="w-full mt-5 flex gap-5 items-center">
                                                    <div> @include(tenant()->views .'icons.emissoes_gee', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][926]) ? color(2) : color(7)])</div>
                                                    <div class="text-xs font-bold text-esg8">
                                                        Emissões de GEE
                                                    </div>
                                                </div>
                                            @endif

                                            @if (in_array(927, $charts['materiality_matrix']['environmental_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.biodiversidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][927]) ? color(2) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Pressão sobre a biodiversidade
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(928, $charts['materiality_matrix']['environmental_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.climaticos', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][928]) ? color(2) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Riscos climáticos por geolocalização
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(929, $charts['materiality_matrix']['environmental_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.residuos', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][929]) ? color(2) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Gestão de resíduos
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(930, $charts['materiality_matrix']['environmental_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.economia', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][930]) ? color(2) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Economia Circular
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <p class="font-normal text-xs text-[#008131] mt-3"> {{ __('Sociais') }} </p>
                                        <div class="grid grid-cols-2 gap-3">
                                            @if (in_array(931, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.contratacao', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][931]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Modelo de contratação
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(932, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.salarial', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][932]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Igualdade Salarial
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(933, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.diversidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][933]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Diversidade da força de trabalho
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(934, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.qualification', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][934]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Qualificação dos trabalhadores
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(935, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.segurance', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][935]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Saúde e Segurança no Trabalho
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(936, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.conciliacao', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][936]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Conciliação entre a vida profissional, pessoal e familiar
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(937, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.parcerias', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][937]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Parcerias locais
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(938, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.comparas', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][938]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Compras Locais
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(939, $charts['materiality_matrix']['social_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.produtos', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][939]) ? color(1) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Produtos Locais
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <p class="font-normal text-xs text-[#008131] mt-3"> {{ __('Governação') }} </p>
                                        <div class="grid grid-cols-2 gap-3">
                                            @if (in_array(940, $charts['materiality_matrix']['governance_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.conformidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][940]) ? color(3) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Conformidade Legal
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(941, $charts['materiality_matrix']['governance_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.etica', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][941]) ? color(3) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Ética
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(942, $charts['materiality_matrix']['governance_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.transparencia', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][942]) ? color(3) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Transparência
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(943, $charts['materiality_matrix']['governance_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.gov_divesidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][943]) ? color(3) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Diversidade no órgão de administração
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(944, $charts['materiality_matrix']['governance_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.cadeia', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][944]) ? color(3) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Diligência devida na cadeia de abastecimento
                                                </div>
                                            </div>
                                            @endif

                                            @if (in_array(945, $charts['materiality_matrix']['governance_options'], false))
                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                <div> @include(tenant()->views .'icons.gov_gestao', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][945]) ? color(3) : color(7)])</div>
                                                <div class="text-xs font-bold text-esg8">
                                                    Gestão de risco
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="print:mt-20 mt-50 pb-4">
                                <p class="font-normal text-xs text-[#008131] uppercase">
                                    @if(isset($charts['report']['company'][0]['company']['size']))
                                        @if($charts['report']['company'][0]['company']['size'] == 1 || $charts['report']['company'][0]['company']['size'] == 2)
                                            3.4 {{ __('Compromissos e metas de curto e médio prazo') }}
                                        @else
                                            3.4 {{ __('Metas de curto, médio e longo prazo') }}
                                        @endif
                                    @else
                                        3.4 {{ __('Compromissos e metas de curto e médio prazo') }}
                                    @endif
                                </p>
                                @if(isset($charts['report']['company'][0]['company']['size']))
                                    @if($charts['report']['company'][0]['company']['size'] == 1 || $charts['report']['company'][0]['company']['size'] == 2)
                                        <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['prazo'] }}</p>
                                    @else
                                        <p class="text-esg8 text-xs font-normal mt-4">{{ $charts['report']['prazo_long'] }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="pagebreak">
                            <div class="flex items-center gap-5 mt-50 print:mt-20">
                                <div class="w-12 h-3 bg-red-700"> </div>
                                <div>
                                    <h1 class="font-encodesans text-base font-bold leading-10 text-[#008131]"> 4. {{__('Contributo para a Agenda 2030 e para os Objetivos de Desenvolvimento Sustentável')}}</h1>
                                </div>
                            </div>

                            @if(isset($charts['sustainable_development_goals']))
                                    <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                                        <div class="grid grid-cols-6">
                                            @if (array_key_exists(376, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.1', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(377, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.2', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(378, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.3', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(379, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.4', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(380, $charts['sustainable_development_goals']))

                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.5', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(381, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.6', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(382, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.7', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(383, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.8', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(384, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.9', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(385, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.10', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(386, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.11', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(387, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.12', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(388, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.13', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(389, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.14', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(390, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.15', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(391, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.16', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                            @if (array_key_exists(392, $charts['sustainable_development_goals']))
                                                <div class="w-full mt-4">
                                                    @include(tenant()->views .'icons.goals.17', ['class' => 'inline-block'])
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                            @endif

                            <p class="font-normal text-xs text-esg8">{{ $charts['report']['ods_text'] }} </p>
                        </div>
                    </div>

                    {{-- Perfil da entidade  --}}
                    <div class="mt-10 md:mt-5 pagebreak print:mt-20 print:hidden">
                        <div class="flex">
                            <div class="pt-2.5 mr-2">@include('icons.dashboard')</div>
                            <div>
                                <h1 class="font-encodesans text-LG font-bold leading-10 text-esg8">Perfil da entidade</h1>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 nonavoid">
                            @if ($charts['total_workers'])
                                @php $text = json_encode([__('Número de trabalhadores')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['total_workers'] }}">
                                        @include(tenant()->views .'icons.workers', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['number_of_business_units'])
                                @php $text = json_encode([__('Número de unidades de negócio')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['number_of_business_units'] }}">
                                        @include(tenant()->views .'icons.building', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['average_months_activity'])
                                @php $text = json_encode( count($search['questionnaire']) > 1 ? [__('Número médio de meses de operação')] : [__('Número de meses de operação')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['average_months_activity'] }}">
                                        @include(tenant()->views .'icons.calender', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['markets_reporting_units_operate'])
                                @php $text = json_encode( count($search['questionnaire']) > 1 ? [__('Mercados em que as unidades de reporte operam')] : [__('Mercados em que a unidade de reporte opera')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.country-list list="{{ json_encode($charts['markets_reporting_units_operate']) }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['average_levels_satisfaction_platforms'])
                                @php
                                    $text = json_encode([__('Níveis médios de satisfação nas plataformas digitais')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.bar id="average_levels_satisfaction_platforms" class="m-auto relative !h-full !w-full" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['number_of_complaints'])
                                @php
                                    $subpoint = json_encode([ [ 'color' => 'bg-[#008131]', 'text' => __('Elogios') ], [ 'color' => 'bg-red-700', 'text' => __('Reclamações') ] ]);
                                    $text = json_encode([__('Número de reclamações e de elogios')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                                    <x-charts.bar id="number_of_complaints" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['total_suppliers'])
                                @php $text = json_encode([__('Número de fornecedores')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['total_suppliers'] }}">
                                        @include(tenant()->views .'icons.supplier', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['total_reporting_unit_suppliers'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Número total de fornecedores das unidades de reporte que apresentam'), __('risco de incidentes com escravatura moderna')]
                                        : [__('Número total de fornecedores da unidade de reporte que apresentam'), __('risco de incidentes com escravatura moderna')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['total_reporting_unit_suppliers'] }}">
                                        @include(tenant()->views .'icons.supply_warning', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['total_reporting_unit_suppliers_child_labor'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Número total de fornecedores das unidades de reporte que apresentam'), __('risco de incidentes com trabalho e exploração infantil')]
                                        : [__('Número total de fornecedores da unidade de reporte que apresentam'), __('risco de incidentes com trabalho e exploração infantil')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['total_reporting_unit_suppliers_child_labor'] }}">
                                        @include(tenant()->views .'icons.supply_warning', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif
                        </div>
                    </div>

                    {{-- Desempenho económico  --}}
                    <div class="mt-10 md:mt-5 pagebreak print:mt-20">
                        <div class="{{ $printView === 0 ? 'hidden' : '' }}">
                            <div class="flex items-center gap-5 mt-50 print:mt-20">
                                <div class="w-12 h-3 bg-red-700"> </div>
                                <div>
                                    <h1 class="font-encodesans text-base font-bold leading-10 text-[#008131]"> 5. {{__('Desempenho Económico')}}</h1>
                                </div>

                            </div>
                        </div>

                        <div class="flex print:hidden">
                            <div class="pt-2.5 mr-2">@include('icons.dashboard')</div>
                            <div>
                                <h1 class="font-encodesans text-LG font-bold leading-10 text-esg8">Desempenho económico</h1>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 nonavoid">

                            @if ($charts['turnover_value'])
                                @php $text = json_encode([__('Valor económico direto gerado')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['turnover_value'] }}" unit="€">
                                        @include(tenant()->views .'icons.income_money', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['direct_economic_value'])
                                @php
                                    $text = json_encode([__('Valor económico direto distribuído')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="direct_economic_value" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['withheld_economic_value'])
                                @php $text = json_encode([__('Valor económico direto retido')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['withheld_economic_value'] }}" unit="€">
                                        @include(tenant()->views .'icons.income_money', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['turnover_total_operating_costs'])
                                @php
                                    $text = json_encode([__('Volume de negócio e custos operacionais totais em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.bar id="turnover_total_operating_costs" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_value_employee_salaries'])
                                @php
                                    $text = json_encode([__('Valor total de salários e benefícios de empregados em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.bar id="total_value_employee_salaries" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_capital_providers'])
                                @php
                                    $text = json_encode([__('Valor total de pagamentos a fornecedores de capital e ao estado em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.bar id="total_capital_providers" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_investment_value'])
                                @php
                                    $text = json_encode([__('Valor total de investimentos em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="total_investment_value" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['total_financial_support_received'])
                                @php $text = json_encode([__('Valor total de apoios financeiros recebidos do estado')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['total_financial_support_received'] }}" unit="€">
                                        @include(tenant()->views .'icons.receive_money', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['turnover_resulting_from_products'])
                                @php $text = json_encode([__('Volume de negócios resultante de produtos ou serviços associados'), __('a atividades económicas que são qualificadas como sustentáveis'), __('do ponto de vista ambiental')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.icon-number value="{{ $charts['turnover_resulting_from_products'] }}" unit="€">
                                        @include(tenant()->views .'icons.product_env', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_amount_capex_da'])
                                @php
                                    $text = json_encode([__('Valor total de CapEx e D&A em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.bar id="total_amount_capex_da" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['taxonomy_turnover'])
                                @php
                                    $text = json_encode([__('Taxonomia - Volume de negócios, CapEx e OpEx em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="taxonomy_turnover" />
                                </x-cards.card-dashboard-version1>
                            @endif
                        </div>
                    </div>

                    {{-- Ambiental --}}
                    <div class="pagebreak md:mt-24 ">
                        <div class="{{ $printView === 0 ? 'hidden' : '' }}">
                            <div class="flex items-center gap-5 mt-50 print:mt-20">
                                <div class="w-12 h-3 bg-red-700"> </div>
                                <div>
                                    <h1 class="font-encodesans text-base font-bold leading-10 text-[#008131]"> 6. {{__('Desempenho ESG')}}</h1>
                                </div>
                            </div>
                        </div>

                        <div class="flex print:hidden">
                            <div class="pt-2 mr-2">@include('icons.categories.1')</div>
                            <div>
                                <h1 class="font-encodesans text-LG font-bold leading-10 text-esg8"> Desempenho Ambiental</h1>
                            </div>
                        </div>

                        <p class="font-bold text-xs text-[#008131] uppercase mt-4 {{ $printView === 0 ? 'hidden' : '' }}">6.1  {{ __('Ambiental') }}</p>
                        <p class="text-esg8 text-xs font-normal mt-2 {{ $printView === 0 ? 'hidden' : '' }}"> {{ $charts['report']['ambiental_text'] }} </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 mt-6 nonavoid">
                            @if($charts['total_amount_of_water_consumed'])
                                @php
                                    $text = json_encode([__('Consumo de água: Quantidade total de água consumida em Megalitros por fonte')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="total_amount_of_water_consumed" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['water_consumption_customer'])
                                @php $text = json_encode([__('Consumo de água: Consumo de água por cliente')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ number_format($charts['water_consumption_customer'], 2) }}" unit="m3">
                                        @include(tenant()->views .'icons.agua', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['reduce_consumption_charts']))
                                @php
                                    $text = json_encode([__('Consumo de água: Medidas implementadas para redução do consumo')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="reduce_consumption_charts" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['emission_value_per_parameter'])
                                @php
                                    $text = json_encode([__('Consumo de água: ETAR - Valor de emissões total por parâmetro em mg/l')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="emission_value_per_parameter" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['electricity_consumed_per_source'])
                                @php
                                    $text = json_encode([__('Gestão de energia: Energia elétrica consumida, por fonte (kWh)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="electricity_consumed_per_source" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_electricity_consumption'])
                                @php
                                    $text = json_encode([__('Gestão de energia: Percentagem do consumo de energia elétrica de fontes renováveis e não renováveis')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Fonte renovável') ], ['color' => 'bg-esg1', 'text' => __('Fonte não renovável') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}">
                                    <x-charts.donut id="percentage_electricity_consumption" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['percentage_electricity_consumption']['label']) }}" data="{{ json_encode($charts['percentage_electricity_consumption']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['energy_consumption_value'])
                                @php $text = json_encode([__('Gestão de energia: Consumo de energia relativo às melhorias. (kWh)')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ number_format($charts['energy_consumption_value'], 2) }}">
                                        @include(tenant()->views .'icons.gestao_energia', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['energy_costs'])
                                @php
                                    $text = json_encode([__('Gestão de energia: Gastos de energia (combustível e eletricidade) em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="energy_costs" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['energy_intensity'])
                                @php $text = json_encode([__('Gestão de energia: Intensidade energética em KWh/euros')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['energy_intensity'] }}" unit="KWh/€">
                                        @include(tenant()->views .'icons.energy_intensity', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['energy_consumption'])
                                @php $text = json_encode([__('Gestão de energia: Consumo específico de energia')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['energy_consumption'] }}" unit="KWh/€">
                                        @include(tenant()->views .'icons.gestao_energia', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['energy_efficiency'])
                                @php $text = json_encode([__('Gestão de energia: Eficiência energética')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['energy_efficiency'] }}" unit="%">
                                        @include(tenant()->views .'icons.energy_efficiency', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['ghg_emissions_and_carbon_sequestration'])
                                @php
                                    $text = json_encode([__('Emissões de gases com efeito de estufa: Emissões de GEE e sequestro de carbono calculados pela unidade de reporte (tCO2eq)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="ghg_emissions_and_carbon_sequestration" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['scope'])
                                @php
                                    $text = json_encode([__('Emissões de gases com efeito de estufa: Emissões de GEE, por âmbito')]);
                                    $subpoint = json_encode([ ['color' => 'bg-[#008131]', 'text' => __('Âmbito 1') ], ['color' => 'bg-[#6AD794]', 'text' => __('Âmbito 2') ], ['color' => 'bg-[#98BDA6]', 'text' => __('Âmbito 3') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                                    <x-charts.bar id="emissions_scope" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['emissions'])
                                @php
                                    $text = json_encode([__('Emissões de gases com efeito de estufa: Balanço das Emissões GEE')]);
                                    $subpoint = json_encode([ ['text' => __('Emissões totais') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                                    <x-charts.icon-number value="{{ $charts['emissions'] }}" unit="Ton CO₂ Eq">
                                        @include(tenant()->views .'icons.emissoes_gee', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['air_pollutant'])
                                @php
                                    $text = json_encode([__('Emissões de gases com efeito de estufa: Quantidade total de cada poluente atmosférico em ton')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="air_pollutant" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['depletes_the_ozone_layer_in_tons'])
                                @php
                                    $text = json_encode([__('Emissões de gases com efeito de estufa: Quantidade total de cada substância que empobrecem a camada de ozono em ton')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="depletes_the_ozone_layer_in_tons" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['carbon_intensity'])
                                @php $text = json_encode([__('Emissões de gases com efeito de estufa: Intensidade carbónica')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ number_format($charts['carbon_intensity'], 2) }}">
                                        @include(tenant()->views .'icons.emissoes_gee', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['25_km_radius_environmental_protection_area']))
                                @php
                                    $text = json_encode([__('Pressão sobre a biodiversidade: A unidade de reporte fica num raio de 25 km de uma área de proteção ambiental ou área de alto valor de biodiversidade')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte fica num raio de 25 km de uma área de proteção ambiental ou área de alto valor de biodiversidade'), 'color' => 2, 'check' => ($charts['25_km_radius_environmental_protection_area']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['biodiversity_map'])
                                <div class="bg-esg4 border border-esg8/20 rounded h-[440px] p-4 mt-5 md:mt-0 print:mt-20">
                                    <div class="absolute">
                                        <div class="text-esg8 font-encodesans flex pb-5 h-20 text-base font-bold">
                                            <span class="pl-2">{{ __('Pressão sobre a biodiversidade: Mapa de Biodiversidade') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-esg25 font-encodesans text-5xl font-bold h-full pt-10">
                                        <div class="flex w-full h-full gap-4">
                                            <div class="w-64 h-full justify-center bg-no-repeat bg-cover bg-[url('/images/customizations/tenant88bd07c3-05a9-4a30-b732-c865d7ccbce1/map.png')]"></div>
                                            <div class="grow h-full overflow-x-auto">
                                                <x-tables.white.table>
                                                    <x-slot name="thead" class="border-b bg-esg4">
                                                        <x-tables.white.th class="bg-esg4 text-esg6">{{ __('Tipo de área') }}</x-tables.th>
                                                        <x-tables.white.th class="bg-esg4 text-esg6">{{ __('Posição') }}</x-tables.th>
                                                        <x-tables.white.th class="bg-esg4 text-esg6">{{ __('Dimensão') }}</x-tables.th>
                                                    </x-slot>

                                                    @foreach ($charts['biodiversity_map'] as $zone)
                                                        <x-tables.white.tr class="border-b border-b-esg8/20">
                                                            <x-tables.white.td>{{ $zone['type'] }}</x-tables.td>
                                                            <x-tables.white.td>{{ $zone['position'] }}</x-tables.td>
                                                            <x-tables.white.td>{{ $zone['size'] }} km²</x-tables.td>
                                                        </x-tables.white.tr>
                                                    @endforeach
                                                </x-tables.white.table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(isset($charts['environmental_impact_studies']))
                                @php
                                    $text = json_encode([__('Pressão sobre a biodiversidade: Estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['id' => 'studies', 'text' => __('A unidade de reporte realizou estudos de impacto ambiental'), 'color' => 2, 'check' => ($charts['environmental_impact_studies']['studies'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'network', 'text' => __('A unidade de reporte identificou espécies na lista de proteção, como a lista vermelha da União Internacional para a Conservação da Natureza ou legislação nacional, como rede natura 2000'), 'color' => 2, 'check' => ($charts['environmental_impact_studies']['network'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'species_habitats', 'text' => __('A unidade de reporte detetou impactos que afetam as espécies/habitats'), 'color' => 2, 'check' => ($charts['environmental_impact_studies']['species_habitats'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'mitigation_measures', 'text' => __('A unidade de reporte detetou impactos que afetam as espécies/habitats e estão a implementar medidas de mitigação'), 'color' => 2, 'check' => ($charts['environmental_impact_studies']['mitigation_measures'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'adaptation_measures', 'text' => __('A unidade de reporte detetou impactos que afetam as espécies/habitats e estão a implementar medidas de adaptação'), 'color' => 2, 'check' => ($charts['environmental_impact_studies']['adaptation_measures'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['species_affected'])
                                @php $text = json_encode(count($search['questionnaire']) > 1
                                    ? [__('Unidades de reporte que detetaram impactos que afetam as espécies/habitats - número de espécies afetadas')]
                                    : [__('Pressão sobre a biodiversidade: A unidade de reporte detetou impactos que afetam as espécies/habitats - número de espécies afetadas')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['species_affected'] }}">
                                        @include(tenant()->views .'icons.species', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['environmental_impact_studies1']))
                                @php
                                    $text = json_encode([__('Pressão sobre a biodiversidade: Estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['id' => 'reversible', 'text' => __('A unidade de reporte detetou impactos que afetam as espécies/habitats que são reversíveisl'), 'color' => 2, 'check' => ($charts['environmental_impact_studies']['reversible'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'restoration_measures', 'text' => __('A unidade de reporte detetou impactos que afetam as espécies/habitats e têm medidas para restaurar áreas de habitats diferentes daquelas que supervisionaram e implementaram medidas de restauração'), 'color' => 2, 'check' => ($charts['environmental_impact_studies1']['restoration_measures'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'monitoring_measures', 'text' => __('A unidade de reporte tem medidas de monitorização'), 'color' => 2, 'check' => ($charts['environmental_impact_studies1']['monitoring_measures'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'number_of_species', 'text' => __('A unidade de reporte tem medidas de monitorização e verificou uma redução do número de espécies'), 'color' => 2, 'check' => ($charts['environmental_impact_studies1']['number_of_species'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'biodiversity', 'text' => __('A unidade de reporte promove projetos de promoção da biodiversidade'), 'color' => 2, 'check' => ($charts['environmental_impact_studies1']['biodiversity'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['habitats_outside_the_studies']))
                                @php
                                    $text = json_encode([__('Pressão sobre a biodiversidade: Estudos de impacto ambiental - Impacto detetado em espécies/habitats fora dos estudos')]);
                                    $data = json_encode([
                                        ['id' => 'impact_studies', 'text' => __('A unidade de reporte detetou impactos que afetam espécies/habitats fora de estudos de impacto ambiental'), 'color' => 2, 'check' => ($charts['habitats_outside_the_studies']['impact_studies'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'adaptation_measures', 'text' => __('A unidade de reporte está a implementar medidas de adaptação'), 'color' => 2, 'check' => ($charts['habitats_outside_the_studies']['adaptation_measures'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'reversible', 'text' => __('Os impactos nas espécies/habitats são reversíveis'), 'color' => 2, 'check' => ($charts['habitats_outside_the_studies']['reversible'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'biodiversity', 'text' => __('A unidade de reporte promove projetos de promoção da biodiversidade'), 'color' => 2, 'check' => ($charts['habitats_outside_the_studies']['biodiversity'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['physical_risks'])
                                <div class="bg-esg4 border border-esg8/20 rounded h-[440px] p-4 mt-5 md:mt-0 print:mt-20">
                                    <div class="absolute">
                                        <div class="text-esg8 font-encodesans flex text-base font-bold">
                                            <span >{{ __('Riscos climáticos por geolocalização: Riscos físicos') }}</span>
                                        </div>

                                        <div class="flex gap-5">
                                            <div class="flex">
                                                <div class="text-esg5 text-xl">
                                                    <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-[{{ \App\Models\Enums\Risk::HIGH->color() }}] text-red-700"></span>
                                                </div>
                                                <div class="pl-2 inline-block text-xs text-esg8/70">{{ __('Alto') }}</div>
                                            </div>

                                            <div class="flex">
                                                <div class="text-esg5 text-xl">
                                                    <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-[{{ \App\Models\Enums\Risk::MEDIUM->color() }}] text-esg1"></span>
                                                </div>
                                                <div class="pl-2 inline-block text-xs text-esg8/70">{{ __('Médio') }}</div>
                                            </div>

                                            <div class="flex">
                                                <div class="text-esg5 text-xl">
                                                    <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-[{{ \App\Models\Enums\Risk::VERY_LOW->color() }}] text-esg6"></span>
                                                </div>
                                                <div class="pl-2 inline-block text-xs text-esg8/70">{{ __('Baixo') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-esg25 font-encodesans text-5xl font-bold h-full grid place-content-center">
                                        <div class="grid grid-cols-4 gap-5">
                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-1" > @include(tenant()->views .'icons.coastal_flood', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::COASTAL_FLOOD->value]])</span>
                                                <div id="tooltip-physical-hazards-1" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Inundações Costeiras
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>

                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-2" > @include(tenant()->views .'icons.urban_flood', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::URBAN_FLOOD->value]])</span>
                                                <div id="tooltip-physical-hazards-2" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Inundações Urbanas
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>

                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-3" > @include(tenant()->views .'icons.river_flood', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::RIVER_FLOOD->value]])</span>
                                                <div id="tooltip-physical-hazards-3" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Inundações de Rio
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-4 gap-5">
                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-4" > @include(tenant()->views .'icons.earthquake', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::EARTHQUAKE->value]])</span>
                                                <div id="tooltip-physical-hazards-4" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Sismos
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>

                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-5" > @include(tenant()->views .'icons.landslide', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::LANDSLIDE->value]])</span>
                                                <div id="tooltip-physical-hazards-5" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Deslizamentos de terra
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>

                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-6" > @include(tenant()->views .'icons.tsunami', ['class' => 'inline-block', 'color' =>$charts['physical_risks'][\App\Models\Enums\NaturalHazard::TSUNAMI->value]])</span>
                                                <div id="tooltip-physical-hazards-6" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Tsunâmis
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>

                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-7" > @include(tenant()->views .'icons.volcano', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::VOLCANO->value]])</span>
                                                <div id="tooltip-physical-hazards-7" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Vulcões
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-4 gap-5">
                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-8" > @include(tenant()->views .'icons.cyclone', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::CYCLONE->value]])</span>
                                                <div id="tooltip-physical-hazards-8" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Ciclones
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>

                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-9" > @include(tenant()->views .'icons.water_scarcity', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::WATER_SCARCITY->value]])</span>
                                                <div id="tooltip-physical-hazards-9" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Escassez de água
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>
                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-10" > @include(tenant()->views .'icons.extreme_heat', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::EXTREME_HEAT->value]])</span>
                                                <div id="tooltip-physical-hazards-10" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Calor extremo
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>

                                            <div class="w-full mt-10 text-center">
                                                <span data-tooltip-target="tooltip-physical-hazards-11" > @include(tenant()->views .'icons.wildfire', ['class' => 'inline-block', 'color' => $charts['physical_risks'][\App\Models\Enums\NaturalHazard::WILDFIRE->value]])</span>
                                                <div id="tooltip-physical-hazards-11" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                    Incêndios
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif

                            @if($charts['expenditure_on_innovation'])
                                @php
                                    $subpoint = null;
                                    $text = json_encode([__('Riscos climáticos por geolocalização: Investimento em I & D - Despesas em inovação')]);
                                    if(count($search['questionnaire']) > 1) {
                                        $subpoint = json_encode([ ['text' => __('Emissões totais') ]]);
                                    }
                                @endphp

                                <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                                    <x-charts.icon-number value="{{ $charts['expenditure_on_innovation'] }}" unit="€">
                                        @include(tenant()->views .'icons.r&d', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['waste_management']))
                                @php
                                    $text = json_encode([__('Gestão de resíduos: Estratégia e monitorização')]);
                                    $data = json_encode([
                                        ['id' => 'reduction_strategy', 'text' => __('A unidade de reporte tem uma estratégia de tratamento e/ou de redução de resíduos'), 'color' => 2, 'check' => ($charts['waste_management']['reduction_strategy'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'waste_production', 'text' => __('A unidade de reporte monitoriza a produção de resíduos'), 'color' => 2, 'check' => ($charts['waste_management']['waste_production'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['total_waste_generated'])
                                @php $text = json_encode([__('Gestão de resíduos: Quantidade total de resíduos gerados em ton')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['total_waste_generated'] }}" unit="ton">
                                        @include(tenant()->views .'icons.gestao_residuos', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['radioactive_waste']))
                                @php
                                    $text = json_encode([__('Gestão de resíduos: Resíduos perigosos e/ou radioativos')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte monitoriza a produção de resíduos perigosos e/ou radioativos'), 'color' => 2, 'check' => ($charts['radioactive_waste']['status'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['hazardous_waste'])
                                @php
                                    $text = json_encode([__('Gestão de resíduos: Quantidade total de resíduos perigosos e radioativos em ton')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="hazardous_waste" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['reused_materials'])
                                @php $text = json_encode([__('Economia circular: Quantidade total de materiais reutilizados em ton')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['reused_materials'] }}" unit="ton">
                                        @include(tenant()->views .'icons.recicle_residue', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['wasted_food'])
                                @php $text = json_encode([__('Economia circular: Quantidade total de alimentos e refeições desperdiçadas em ton')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['wasted_food'] }}" unit="ton">
                                        @include(tenant()->views .'icons.gestao_residuos', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['meals_prepared'])
                                @php $text = json_encode([__('Economia circular: Número total de alimentos e refeições confecionadas em ton')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['meals_prepared'] }}" unit="ton">
                                        @include(tenant()->views .'icons.meal', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['cooking_oils_recycled'])
                                @php $text = json_encode([__('Economia circular: Quantidade total de óleos alimentares colocados na reciclagem em L')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['cooking_oils_recycled'] }}" unit="L">
                                        @include(tenant()->views .'icons.recicle_residue', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['environmental_policies_progress']))
                                @php
                                    $text = json_encode([__('Políticas ambientais')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_policies_progress'][0], 'text' => __('Unidades de reporte com Política Ambiental'), 'color' => 2],
                                        ['percentage' => $charts['environmental_policies_progress'][1], 'text' => __('Unidades de reporte com Política de Redução de Emissões'), 'color' => 2],
                                        ['percentage' => $charts['environmental_policies_progress'][2], 'text' => __('Unidades de reporte que avaliam os processos internos de forma a identificar formas de reduzir as emissões'), 'color' => 2],
                                        ['percentage' => $charts['environmental_policies_progress'][3], 'text' => __('Unidades de reporte com Política de Proteção de Biodiversidade'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['environmental_policies']))
                                @php
                                    $text = json_encode([__('Políticas ambientais')]);
                                    $data = json_encode([
                                        ['id' => 'environmental_policy', 'text' => __('A unidade de reporte tem uma Política Ambiental'), 'color' => 2, 'check' => ($charts['environmental_policies']['environmental_policy'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'emissions_reduction_policy', 'text' => __('A unidade de reporte tem uma Política de Redução de Emissões'), 'color' => 2, 'check' => ($charts['environmental_policies']['emissions_reduction_policy'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'reduce_emissions', 'text' => __('A unidade de reporte avalia os processos internos de forma a identificar formas de reduzir as emissões'), 'color' => 2, 'check' => ($charts['environmental_policies']['reduce_emissions'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'biodiversity_protection_policy', 'text' => __('A unidade de reporte tem uma Política de Proteção de Biodiversidade'), 'color' => 2, 'check' => ($charts['environmental_policies']['biodiversity_protection_policy'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif












                            @if(isset($charts['reduce_consumption']))
                                @php
                                    $text = json_encode([__('Medidas implementadas para redução do consumo')]);
                                    $data = json_encode([
                                        ['id' => 'temporizadores', 'text' => __('Temporizadores'), 'color' => 2, 'check' => ($charts['reduce_consumption']['temporizadores'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'redutores_de_caudal', 'text' => __('Redutores de caudal'), 'color' => 2, 'check' => ($charts['reduce_consumption']['redutores_de_caudal'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'alteração_de_pressão', 'text' => __('Alteração de pressão'), 'color' => 2, 'check' => ($charts['reduce_consumption']['alteração_de_pressão'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'alteração_dos_autoclismos', 'text' => __('Alteração dos autoclismos'), 'color' => 2, 'check' => ($charts['reduce_consumption']['alteração_dos_autoclismos'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'outro', 'text' => __('Outro'), 'color' => 2, 'check' => ($charts['reduce_consumption']['outro'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['reporting_units_wwtp_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte com ETAR')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}">
                                    <x-charts.donut id="reporting_units_wwtp_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['reporting_units_wwtp_progress']['label']) }}" data="{{ json_encode($charts['reporting_units_wwtp_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_gross_production_value'])
                                @php $text = json_encode([__('Valor bruto de produção total')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.icon-number value="{{ $charts['total_gross_production_value'] }}" unit="€">
                                        @include(tenant()->views .'icons.production_value', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_indirect_costs'])
                                @php $text = json_encode([__('Custos indiretos totais')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.icon-number value="{{ $charts['total_indirect_costs'] }}" unit="€">
                                        @include(tenant()->views .'icons.cost', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['gross_added_value_company'])
                                @php $text = json_encode([__('Valor Acrescentado Bruto das atividades da empresa')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.icon-number value="{{ $charts['gross_added_value_company'] }}" unit="€">
                                        @include(tenant()->views .'icons.unit_value', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_production_volume'])
                                @php $text = json_encode([__('Volume de produção total')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.icon-number value="{{ $charts['total_production_volume'] }}" unit="">
                                        @include(tenant()->views .'icons.production', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            <div class="mt-20 {{ $printView === 0 ? 'hidden' : '' }}">
                                <p class="text-xs font-normal text-[#008131] mt-4 {{ $printView === 0 ? 'hidden' : '' }}"> {{ __('Medidas de promoção de eficiência energética') }} </p>
                                <p class="text-esg8 text-xs font-normal mt-2 {{ $printView === 0 ? 'hidden' : '' }}">{{ $charts['report']['measures_promote_energy_efficiency'] }} </p>
                            </div>

                            @if(isset($charts['ghg_emissions']))
                                @php
                                    $text = json_encode([__('A unidade de reporte  efetua o cálculo das suas emissões GEE')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte  efetua o cálculo das suas emissões GEE'), 'color' => 2, 'check' => ($charts['ghg_emissions']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['ghg_emissions_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que efetuam o cálculo das suas emissões GEE')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}">
                                    <x-charts.donut id="ghg_emissions_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['ghg_emissions_progress']['label']) }}" data="{{ json_encode($charts['ghg_emissions_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['amount_of_non_road_fuel_consumed'])
                                @php
                                    $text = json_encode([__('Tipo e quantidade de combustível não rodoviário consumido'), __('nas infraestruturas em Litros')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="amount_of_non_road_fuel_consumed" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['travel_in_vehicles_owned']))
                                @php
                                    $text = json_encode([__('Deslocações em veículos de que a unidade de reporte é proprietária,'), __('controla ou opera')]);
                                    $data = json_encode([
                                        ['id' => 'displacements_were_made', 'text' => __('Foram efetuadas deslocações na unidade de reporte'), 'color' => 2, 'check' => ($charts['travel_in_vehicles_owned']['displacements_were_made'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'fuel_consumed', 'text' => __('A Unidade de reporte conhece a quantidade de combustível consumido'), 'color' => 2, 'check' => ($charts['travel_in_vehicles_owned']['fuel_consumed'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'distances_traveled', 'text' => __('A Unidade de reporte conhece as distâncias percorridas'), 'color' => 2, 'check' => ($charts['travel_in_vehicles_owned']['distances_traveled'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['travel_in_vehicles_owned_progress']))
                                @php
                                    $text = json_encode([__('Deslocações em veículos de que as unidades de reporte são proprietárias,'), __('controla ou opera')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['travel_in_vehicles_owned_progress'][0], 'text' => __('Unidades de reporte em que foram efetuadas deslocações'), 'color' => 2],
                                        ['percentage' => $charts['travel_in_vehicles_owned_progress'][1], 'text' => __('Unidades de reporte que conhecem a quantidade de combustível consumido'), 'color' => 2],
                                        ['percentage' => $charts['travel_in_vehicles_owned_progress'][2], 'text' => __('Unidades de reporte que conhecem as distâncias percorridas'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['travel_in_vehicles_fuel_consumed'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Deslocações em veículos de que a unidade de reporte é proprietária,'), __('controla ou opera - Combustível consumido')]
                                        : [__('Deslocações em veículos de que a unidade de reporte é proprietária,'), __('controla ou opera - Combustível consumido em Litros')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="travel_in_vehicles_fuel_consumed" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['vehicle_and_distance_travelled'])
                                @php
                                    $text = json_encode([__('Deslocações em veículos de que a unidade de reporte é proprietária,'), __('controla ou opera - Tipo transporte/veículo e distância percorrida (Km)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="vehicle_and_distance_travelled" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['travel_vehicles_reporting_units_donot_own']))
                                @php
                                    $text = json_encode(count($search['questionnaire']) > 1
                                        ? [__('Deslocações em veículos de que as unidades de reporte não são'), __('proprietárias, não controlam ou operam')]
                                        : [__('Deslocações em veículos de que a unidade de reporte não é'), __('proprietária, não controla ou opera')]
                                    );
                                    $data = json_encode([
                                        ['id' => 'displacements_were_made', 'text' => __('Foram efetuadas deslocações'), 'color' => 2, 'check' => ($charts['travel_vehicles_reporting_units_donot_own']['displacements_were_made'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'fuel_consumed', 'text' => (count($search['questionnaire']) > 1
                                            ? __('Unidades de reporte que conhecem a quantidade de combustível consumido')
                                            : __('A Unidade de reporte conhece a quantidade de combustível consumido')), 'color' => 2, 'check' => ($charts['travel_vehicles_reporting_units_donot_own']['fuel_consumed'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'distances_traveled', 'text' => __('A unidade de reporte conhece as distâncias percorridas'), 'color' => 2, 'check' => ($charts['travel_vehicles_reporting_units_donot_own']['distances_traveled'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['travel_vehicles_reporting_units_donot_own_progress']))
                                @php
                                    $text = json_encode([__('Deslocações em veículos de que as unidades de reporte não são'), __('proprietárias, não controlam ou operam')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['travel_vehicles_reporting_units_donot_own_progress'][0], 'text' => __('Foram efetuadas deslocações'), 'color' => 2],
                                        ['percentage' => $charts['travel_vehicles_reporting_units_donot_own_progress'][1], 'text' => __('Unidades de reporte que conhecem a quantidade de combustível consumido'), 'color' => 2],
                                        ['percentage' => $charts['travel_vehicles_reporting_units_donot_own_progress'][2], 'text' => __('Unidades de reporte que conhecem as distâncias percorridas'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['control_or_operate_fuel_consumed'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Deslocações em veículos de que as unidades de reporte não são'), __('proprietárias, não controlam ou operam - Combustível consumido')]
                                        : [__('Deslocações em veículos de que a unidade de reporte não é'), __('proprietária, não controla ou opera - Combustível consumido em Litros')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="control_or_operate_fuel_consumed" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['type_of_transport_vehicle_distance'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Deslocações em veículos de que as unidades de reporte não são'), __('proprietárias, não controlam ou operam - Tipo transporte/veículo e'), __('distância percorrida. (Km)')]
                                        : [__('Deslocações em veículos de que a unidade de reporte não é'), __('proprietária, não controla ou opera - Tipo transporte/veículo e'), __('distância percorrida. (Km)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="type_of_transport_vehicle_distance" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['equipment_containing_greebhouse_gas']))
                                @php
                                    $text = json_encode([__('Equipamentos que contêm gases fluorados com efeito de estufa')]);
                                    $data = json_encode([
                                        [
                                            'id' => 'greenhouse_effect',
                                            'text' => __('A unidade de reporte detém equipamentos de refrigeração fixos, equipamentos de ar condicionado fixos, bombas de calor fixas, equipamentos fixos de proteção contra incêndios, unidades de refrigeração de camiões e reboques refrigerados, comutadores elétricos e/ou ciclos orgânicos de Rankine que contenham gases fluorados com efeito de estufa'),
                                            'color' => 2, 'check' => ($charts['equipment_containing_greebhouse_gas']['greenhouse_effect'] == 'yes' ? 'checked' : '')
                                        ],
                                        ['id' => 'equipment_leaks', 'text' => __('Unidades de reporte que, durante o período de reporte, realizaram ação de verificação de fugas'), 'color' => 2, 'check' => ($charts['equipment_containing_greebhouse_gas']['equipment_leaks'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'leakage', 'text' => __('Unidades de reporte em que foi verificada a ocorrência de fugas'), 'color' => 2, 'check' => ($charts['equipment_containing_greebhouse_gas']['leakage'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['equipment_containing_greebhouse_gas_progress']))
                                @php
                                    $text = json_encode([__('Equipamentos que contêm gases fluorados com efeito de estufa')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['equipment_containing_greebhouse_gas_progress'][0], 'text' => __('Unidades de reporte que detêm equipamentos fixos que contêm gases fluorados com efeito de estufa'), 'color' => 2],
                                        ['percentage' => $charts['equipment_containing_greebhouse_gas_progress'][1], 'text' => __('Unidades de reporte que, durante o período de reporte, realizaram ação de verificação de fugas'), 'color' => 2],
                                        ['percentage' => $charts['equipment_containing_greebhouse_gas_progress'][2], 'text' => __('Unidades de reporte em que foi verificada a ocorrência de fugas'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_amount_of_leak'])
                                @php
                                    $text = json_encode([__('Quantidade total da fuga (quantidade de gás fluorado libertada/emitida'), __('para a atmosfera) em Kg')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="total_amount_of_leak" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_amount_of_electricity'])
                                @php
                                    $text = json_encode([__('Quantidade de eletricidade (ou vapor) consumida (kWh)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="total_amount_of_electricity" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['waste_production_facilities']))
                                @php
                                    $text = json_encode([__('A unidade de reporte tem produção de resíduos nas instalações')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte tem produção de resíduos nas instalações'), 'color' => 2, 'check' => ($charts['waste_production_facilities']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['waste_production_facilities_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que têm produção de resíduos nas instalações')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}" class="print:hidden">
                                    <x-charts.donut id="waste_production_facilities_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['waste_production_facilities_progress']['label']) }}" data="{{ json_encode($charts['waste_production_facilities_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['waste_produced_in_ton'])
                                @php
                                    $text = json_encode([__('Tipo, quantidade e destinos de resíduos produzidos (t)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="waste_produced_in_ton"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['waste_placed_in_recycling'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Resíduos colocados na reciclagem (%)')]
                                        : [__('Resíduos colocados na reciclagem (%)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="waste_placed_in_recycling" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['water_on_its_premises']))
                                @php
                                    $text = json_encode([__('A unidade de reporte utiliza água nas suas instalações')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte utiliza água nas suas instalações'), 'color' => 2, 'check' => ($charts['water_on_its_premises']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['water_on_its_premises_progress']))
                                @php
                                    $text = json_encode([__('Proporção de unidades de reporte que utilizam água nas instalações (%)')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}" class="print:hidden">
                                    <x-charts.donut id="water_on_its_premises_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['water_on_its_premises_progress']['label']) }}" data="{{ json_encode($charts['water_on_its_premises_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_amount_of_water_m3'])
                                @php
                                    $text = json_encode([__('Tipo de utilização e quantidade total de água em m3')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="total_amount_of_water_m3" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['purchased_goods_during_reporting_period']))
                                @php
                                    $text = json_encode([__('A unidade de reporte que adquiriu bens durante o período de reporte')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte que adquiriu bens durante o período de reporte'), 'color' => 2, 'check' => ($charts['purchased_goods_during_reporting_period']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['purchased_goods_during_reporting_period_progress']))
                                @php
                                    $text = json_encode([__('Proporção de unidades de reporte que adquiriram bens (%)')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}" class="print:hidden">
                                    <x-charts.donut id="purchased_goods_during_reporting_period_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['purchased_goods_during_reporting_period_progress']['label']) }}" data="{{ json_encode($charts['purchased_goods_during_reporting_period_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['type_total_quantity_goods_purchased_ton'])
                                @php
                                    $text = json_encode([__('Tipo e quantidade total de bens adquiridos em ton')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="type_total_quantity_goods_purchased_ton" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['telecommuting_workers']))
                                @php
                                    $text = json_encode([__('A unidade de reporte tem trabalhadores em regime de teletrabalho')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte tem trabalhadores em regime de teletrabalho'), 'color' => 2, 'check' => ($charts['telecommuting_workers']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['telecommuting_workers_progress']))
                                @php
                                    $text = json_encode([__('Proporção de unidades de reporte com colaboradores em regime de teletrabalho (%)')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}" class="print:hidden">
                                    <x-charts.donut id="telecommuting_workers_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['telecommuting_workers_progress']['label']) }}" data="{{ json_encode($charts['telecommuting_workers_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['hours_worked_in_telecommuting'])
                                @php $text = json_encode( count($search['questionnaire']) > 1 ? [__('Horas totais trabalhadas em teletrabalho')] : [__('Horas totais trabalhadas em teletrabalho')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['hours_worked_in_telecommuting'] }}" unit="h">
                                        @include(tenant()->views .'icons.remote_work', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50, 'color' => color(2)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['carbon_sequestration_capacity_ghg']))
                                @php
                                    $text = json_encode([__('A unidade de reporte tem capacidade de sequestro de carbono'), __('e/ou dados das suas emissões de GEE')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte tem capacidade de sequestro de carbono e/ou dados das suas emissões de GEE'), 'color' => 2, 'check' => ($charts['carbon_sequestration_capacity_ghg']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['carbon_sequestration_capacity_ghg_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que têm capacidade de sequestro de carbono'), __('e/ou dados das suas emissões de GEE')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}">
                                    <x-charts.donut id="carbon_sequestration_capacity_ghg_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['carbon_sequestration_capacity_ghg_progress']['label']) }}" data="{{ json_encode($charts['carbon_sequestration_capacity_ghg_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['emission_air_pollutants']))
                                @php
                                    $text = json_encode([__('A unidade de reporte monitoriza a emissão de poluentes atmosféricos')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte monitoriza a emissão de poluentes atmosféricos'), 'color' => 2, 'check' => ($charts['emission_air_pollutants']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['emission_air_pollutants_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que monitorizam a emissão de poluentes atmosféricos')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}" class="print:hidden">
                                    <x-charts.donut id="emission_air_pollutants_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['emission_air_pollutants_progress']['label']) }}" data="{{ json_encode($charts['emission_air_pollutants_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['deplete_the_ozone_layer']))
                                @php
                                    $text = json_encode([__('A unidade de reporte monitoriza a utilização de equipamentos com'), __('substâncias que empobrecem a camada de ozono')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('A unidade de reporte monitoriza a utilização de equipamentos com substâncias que empobrecem a camada de ozono'), 'color' => 2, 'check' => ($charts['deplete_the_ozone_layer']['status'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:hidden">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['deplete_the_ozone_layer_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que monitorizam a utilização de equipamentos com'), __('substâncias que empobrecem a camada de ozono')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}" class="print:hidden">
                                    <x-charts.donut id="deplete_the_ozone_layer_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['deplete_the_ozone_layer_progress']['label']) }}" data="{{ json_encode($charts['deplete_the_ozone_layer_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['km_25_km_radius_environmental_protection_area_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que ficam num raio de 25 km de uma área'), __('de proteção ambiental ou área de alto valor de biodiversidade')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}">
                                    <x-charts.donut id="km_25_km_radius_environmental_protection_area_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['km_25_km_radius_environmental_protection_area_progress']['label']) }}" data="{{ json_encode($charts['km_25_km_radius_environmental_protection_area_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['environmental_impact_studies_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies_progress'][1078], 'text' => __('A unidade de reporte realizou estudos de impacto ambiental'), 'color' => 2],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies_progress'][1079], 'text' => __('Unidades de reporte que identificaram espécies na lista de proteção, como a lista vermelha da União Internacional para a Conservação da Natureza ou legislação nacional, como rede natura 2000'), 'color' => 2],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies_progress'][1080], 'text' => __('Unidades de reporte que detetaram impactos que afetam as espécies/habitats'), 'color' => 2],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies_progress'][1081], 'text' => __('Unidades de reporte que detetaram impactos que afetam as espécies/habitats e estão a implementar medidas de mitigação'), 'color' => 2],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies_progress'][1083], 'text' => __('Unidades de reporte que detetaram impactos que afetam as espécies/habitats e estão a implementar medidas de adaptação'), 'color' => 2],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies_progress'][1085], 'text' => __('Unidades de reporte que detetaram impactos que afetam as espécies/habitats que são reversíveis'), 'color' => 2],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['environmental_impact_studies1_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies1_progress'][1087], 'text' => __('Unidades de reporte que detetaram impactos que afetam as espécies/habitats e têm medidas para restaurar áreas de habitats diferentes daquelas que supervisionaram e implementaram medidas de restauração'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies1_progress'][1089], 'text' => __('Unidades de reporte com medidas de monitorização'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies1_progress'][1090], 'text' => __('Unidades de reporte com medidas de monitorização e que verificaram uma redução do número de espécies'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que fazem estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['environmental_impact_studies1_progress'][1092], 'text' => __('Unidades de reporte que promovem projetos de promoção da biodiversidade'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['habitats_outside_the_studies_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que detetaram impactos que afetam as'), __('espécies/habitats fora de estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['habitats_outside_the_studies_progress'][1093], 'text' => __('Unidades de reporte que detetaram impactos que afetam as espécies/habitats e têm medidas para restaurar áreas de habitats diferentes daquelas que supervisionaram e implementaram medidas de restauração'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que detetaram impactos que afetam as'), __('espécies/habitats fora de estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['habitats_outside_the_studies_progress'][1083], 'text' => __('Unidades de reporte que estão a implementar medidas de adaptação'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que detetaram impactos que afetam as'), __('espécies/habitats fora de estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['habitats_outside_the_studies_progress'][1085], 'text' => __('Unidades de reporte cujos impactos nas espécies/habitats são reversíveis'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>

                                @php
                                    $text = json_encode([__('Unidades de reporte que detetaram impactos que afetam as'), __('espécies/habitats fora de estudos de impacto ambiental')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['habitats_outside_the_studies_progress'][1094], 'text' => __('Unidades de reporte que promovem projetos de promoção da biodiversidade'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['waste_management_progress']))
                                @php
                                    $text = json_encode([__('Gestão de resíduos')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['waste_management_progress'][0], 'text' => __('Unidades de reporte que têm uma estratégia de tratamento e/ou de redução de resíduos'), 'color' => 2],
                                        ['percentage' => $charts['waste_management_progress'][1], 'text' => __('Unidade de reporte que monitorizam a produção de resíduos'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['radioactive_waste_progress']))
                                @php
                                    $text = json_encode([__('Resíduos perigosos e/ou radioativos')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['radioactive_waste_progress'], 'text' => __('Unidades de reporte que monitorizam a produção de resíduos perigosos e/ou radioativos'), 'color' => 2]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif
                        </div>
                    </div>

                    {{-- Social --}}
                    <div class="pagebreak mt-24">
                        <div class="flex print:hidden">
                            <div class="pt-2 mr-2">@include('icons.categories.2')</div>
                            <div>
                                <h1 class="font-encodesans text-LG font-bold leading-10 text-esg8">Desempenho Social</h1>
                            </div>
                        </div>

                        <p class="font-bold text-xs text-[#008131] mt-4 uppercase {{ $printView === 0 ? 'hidden' : '' }}">6.2 {{ __('Social') }}</p>
                        <p class="text-esg8 text-xs font-normal mt-2 {{ $printView === 0 ? 'hidden' : '' }}"> {{ $charts['report']['social_text'] }}</p>

                        @if(isset($charts['report']['desenvolvimento']) && !empty($charts['report']['desenvolvimento']))
                            <p class="font-normal text-xs text-[#008131] mt-4 {{ $printView === 0 ? 'hidden' : '' }}"> {{ __('Programas de desenvolvimento local ') }}</p>
                            <p class="text-esg8 text-xs font-normal mt-2 mb-5 {{ $printView === 0 ? 'hidden' : '' }}"> {{ $charts['report']['desenvolvimento'] }}</p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 nonavoid">

                            @if($charts['percentage_of_workers_by_contractual_regime'])
                                @php
                                    $text = json_encode([__('Modelo de contratação: Percentagem de trabalhadores por regime contratual')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="percentage_of_workers_by_contractual_regime" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_workers_by_contract'])
                                @php
                                    $text = json_encode([__('Modelo de contratação: Percentagem de trabalhadores por tipo de contrato')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="percentage_workers_by_contract" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['total_number_of_hires'])
                                @php $text = json_encode([__('Modelo de contratação: Número total de contratações')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['total_number_of_hires'] }}">
                                        @include(tenant()->views .'icons.employment', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_of_hires_by_contractual_regime'])
                                @php
                                    $text = json_encode([__('Modelo de contratação: Percentagem de contratações por regime contratual')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="percentage_of_hires_by_contractual_regime" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_of_hiring_of_workers_by_gender'])
                                @php
                                    $text = json_encode([__('Modelo de contratação: Percentagem de contratações de trabalhadores por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="percentage_of_hiring_of_workers_by_gender" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_of_workers_residing_locally'])
                                @php $text = json_encode([__('Modelo de contratação: Percentagem de trabalhadores que residem localmente')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['percentage_of_workers_residing_locally'] }}" unit="%">
                                        @include(tenant()->views .'icons.worker_location', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['job_creation'])
                                @php $text = json_encode([__('Modelo de contratação: Criação líquida de postos de trabalho')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['job_creation'] }}">
                                        @include(tenant()->views .'icons.employment', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['average_turnover'])
                                @php $text = json_encode([__('Modelo de contratação: Criação líquida de postos de trabalho')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['average_turnover'] }}">
                                        @include(tenant()->views .'icons.employyes_turnover', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['average_turnover_rate'])
                                @php $text = json_encode([__('Modelo de contratação: Taxa média de rotatividade')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['average_turnover_rate'] }}" unit="%">
                                        @include(tenant()->views .'icons.employyes_turnover', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['absenteeism_rate'])
                                @php $text = json_encode([__('Modelo de contratação: Taxa de absentismo')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['absenteeism_rate'] }}" unit="%">
                                        @include(tenant()->views .'icons.absenteeism', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['sum_of_basic_wages_of_workers_by_gender'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Soma dos salários base dos trabalhadores por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="sum_of_basic_wages_of_workers_by_gender" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['base_salary_of_female_workersby_professional'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Soma do salário base dos trabalhadores do género feminino por categoria profissional em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="base_salary_of_female_workersby_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['base_salary_of_male_workers_by_professional'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Soma do salário base dos trabalhadores do género masculino por categoria profissional em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="base_salary_of_male_workers_by_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['salary_of_workers_of_other_gender_by_professional'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Soma do salário base dos trabalhadores de outro género por categoria profissional em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="salary_of_workers_of_other_gender_by_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['remuneration_of_employees_by_gender'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Soma da remuneração bruta dos trabalhadores por género em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="remuneration_of_employees_by_gender" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['gross_remuneration_of_female_workers_by_professional'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Soma da remuneração bruta dos trabalhadores do género feminino por categoria profissional em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="gross_remuneration_of_female_workers_by_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['gross_remuneration_of_male_workers_by_professional'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Soma da remuneração bruta dos trabalhadores do género masculino por categoria profissional em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="gross_remuneration_of_male_workers_by_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['gross_remuneration_of_workers_othergender_by_professional'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Soma da remuneração bruta dos trabalhadores de outro género por categoria profissional em €')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="gross_remuneration_of_workers_othergender_by_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['earning_more_than_the_national_minimum_wage_by_gender'])
                                @php
                                    $text = json_encode([__('Igualdade salarial: Percentagem de trabalhadores com salário superior ao salário mínimo nacional por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="earning_more_than_the_national_minimum_wage_by_gender" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['number_workers_gender'])
                                @php
                                    $text = json_encode([__('Diversidade da força de trabalho: Número de trabalhadores, por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="number_workers_gender" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_male_workers_professional'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Percentagem de trabalhadores do sexo masculino por categoria profissional')]
                                        : [__('Diversidade da força de trabalho: Percentagem de trabalhadores do género masculino por categoria profissional')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="percentage_male_workers_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_female_workers_professional'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Percentagem de trabalhadores do sexo feminino por categoria profissional')]
                                        : [__('Diversidade da força de trabalho: Percentagem de trabalhadores do género feminino por categoria profissional')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="percentage_female_workers_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_othergender_workers_professional'])
                                @php
                                    $text = json_encode([__('Diversidade da força de trabalho: Percentagem de trabalhadores de outro género por categoria profissional')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="percentage_othergender_workers_professional" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_workers_foreign_nationality'])
                                @php $text = json_encode([__('Diversidade da força de trabalho: Percentagem de trabalhadores de nacionalidade estrangeira')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['percentage_workers_foreign_nationality'] }}" unit="%">
                                        @include(tenant()->views .'icons.employees_nationality', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['percentage_workers_agegroup'])
                                @php
                                    $text = json_encode([__('Diversidade da força de trabalho: Percentagem de trabalhadores por faixa etária')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="percentage_workers_agegroup" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['number_training_hours'])
                                @php $text = json_encode([__('Qualificação dos trabalhadores: Número de horas de capacitação')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['number_training_hours'] }}">
                                        @include(tenant()->views .'icons.qualification', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['avg_hours_training_per_worker'])
                                @php
                                    $text = json_encode([__('Qualificação dos trabalhadores: Média de horas de capacitação por trabalhador, por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="avg_hours_training_per_worker" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['workers_training_actions'])
                                @php
                                    $text = json_encode([__('Qualificação dos trabalhadores: Número de trabalhadores que tiveram ações de capacitação, por categoria profissional')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="workers_training_actions" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['female_workers_performance_evaluation'])
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Número de trabalhadores do sexo feminino que tiveram avaliação de desempenho e desenvolvimento de carreira por categoria profissional')]
                                        : [__('Qualificação dos trabalhadores: Número de trabalhadores do género feminino que tiveram avaliação de desempenho e desenvolvimento de carreira por categoria profissional')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="female_workers_performance_evaluation" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['male_workers_performance_evaluation'])
                                @php
                                    $text = json_encode([__('Qualificação dos trabalhadores: Número de trabalhadores do sexo masculino que tiveram avaliação de desempenho e desenvolvimento de carreira por categoria profissional')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="male_workers_performance_evaluation" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['workers_another_gender_performance_evaluation'])
                                @php $text = json_encode([__('Qualificação dos trabalhadores: Número de trabalhadores de outro género que tiveram avaliação de desempenho e desenvolvimento de carreira')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['workers_another_gender_performance_evaluation'] }}">
                                        @include(tenant()->views .'icons.employees_evalution', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['hours_training_occupational_health_safety'])
                                @php $text = json_encode([__('Saúde e segurança no trabalho: Número de horas de formação em Saúde e Segurança no Trabalho')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['hours_training_occupational_health_safety'] }}" unit="h">
                                        @include(tenant()->views .'icons.health_sefty', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['received_training_occupational_health_safety'])
                                @php $text = json_encode([__('Saúde e segurança no trabalho: Número de trabalhadores que tiveram formação em Saúde e Segurança no Trabalho')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['received_training_occupational_health_safety'] }}">
                                        @include(tenant()->views .'icons.health_sefty', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['accidents_at_work'])
                                @php $text = json_encode([__('Saúde e segurança no trabalho: Número de acidentes de trabalho')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['accidents_at_work'] }}">
                                        @include(tenant()->views .'icons.health_sefty', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['workdays_lost_due_to_accidents'])
                                @php $text = json_encode([__('Saúde e segurança no trabalho: Número de dias de trabalho perdidos devido a acidentes de trabalho, lesões, fatalidades e/ou doença profissional')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['workdays_lost_due_to_accidents'] }}" unit="d">
                                        @include(tenant()->views .'icons.health_sefty', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['number_of_disabled_days'])
                                @php $text = json_encode([__('Saúde e segurança no trabalho: Número de dias debilitados')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['number_of_disabled_days'] }}" unit="d">
                                        @include(tenant()->views .'icons.health_sefty', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['modalities_schedules_reporting_units']))
                                @php
                                    $text = json_encode([__('Conciliação entre a vida profissional, pessoal e familiar: Modalidades de horários nas unidades de reporte')]);
                                    $data = json_encode([
                                        ['id' => 'horas_extras', 'text' => __('Horas extras'), 'color' => 1, 'check' => ($charts['modalities_schedules_reporting_units']['horas_extras'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'banco_de_horas', 'text' => __('Banco de horas'), 'color' => 1, 'check' => ($charts['modalities_schedules_reporting_units']['banco_de_horas'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'horários_repartidos', 'text' => __('Horários repartidos'), 'color' => 1, 'check' => ($charts['modalities_schedules_reporting_units']['horários_repartidos'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'turnos_rotativos', 'text' => __('Turnos rotativos'), 'color' => 1, 'check' => ($charts['modalities_schedules_reporting_units']['turnos_rotativos'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'outro', 'text' => __('Outro'), 'color' => 1, 'check' => ($charts['modalities_schedules_reporting_units']['outro'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['modalities_schedules_reporting_units_charts']))
                                @php
                                    $text = json_encode([__('Modalidades de horários nas unidades de reporte')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="modalities_schedules_reporting_units_charts" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['conciliation_measures_unit']))
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Medidas de conciliação nas unidades de reporte.')]
                                        : [__('Conciliação entre a vida profissional, pessoal e familiar: Medidas de conciliação na unidade de reporte')]);
                                    $data = json_encode([
                                        ['id' => 'banco_de_horas', 'text' => __('Banco de horas'), 'color' => 1, 'check' => ($charts['conciliation_measures_unit']['banco_de_horas'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'flexibilidade_de_horário', 'text' => __('Flexibilidade de horário'), 'color' => 1, 'check' => ($charts['conciliation_measures_unit']['flexibilidade_de_horário'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'dias_de_férias_adicionais', 'text' => __('Dias de férias adicionais'), 'color' => 1, 'check' => ($charts['conciliation_measures_unit']['dias_de_férias_adicionais'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'horário_compactado_num_número_reduzido_de_dias_por_semana', 'text' => __('Horário compactado num número reduzido de dias por semana'), 'color' => 1, 'check' => ($charts['conciliation_measures_unit']['horário_compactado_num_número_reduzido_de_dias_por_semana'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'trabalho_a_partir_de_casa_escritório_móvel', 'text' => __('Trabalho a partir de casa/Escritório móvel'), 'color' => 1, 'check' => ($charts['conciliation_measures_unit']['trabalho_a_partir_de_casa_escritório_móvel'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'teletrabalho', 'text' => __('Teletrabalho'), 'color' => 1, 'check' => ($charts['conciliation_measures_unit']['teletrabalho'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'outro', 'text' => __('Outro'), 'color' => 1, 'check' => ($charts['conciliation_measures_unit']['outro'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['conciliation_measures_unit_charts']))
                                @php
                                    $text = json_encode( count($search['questionnaire']) > 1
                                        ? [__('Medidas de conciliação nas unidades de reporte.')]
                                        : [__('Medidas de conciliação na unidade de reporte')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="conciliation_measures_unit_charts" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['workers_initial_parental_leave'])
                                @php
                                    $text = json_encode([__('Conciliação entre a vida profissional, pessoal e familiar: Número de trabalhadores que gozaram licença parental inicial, por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="workers_initial_parental_leave" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['workers_return_towork_after_parental_leave'])
                                @php
                                    $text = json_encode([__('Conciliação entre a vida profissional, pessoal e familiar: Número de trabalhadores que retornaram ao trabalho depois do término da licença parental, por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="workers_return_towork_after_parental_leave" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['return_to_work_rate'])
                                @php $text = json_encode([__('Conciliação entre a vida profissional, pessoal e familiar: Taxa de retorno após licença parental')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['return_to_work_rate'] }}" unit="%">
                                        @include(tenant()->views .'icons.employees_backtowork', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['workers_return_towork_after_parental_leave_twelve_month'])
                                @php
                                    $text = json_encode([__('Conciliação entre a vida profissional, pessoal e familiar: Número total de trabalhadores que retornaram ao trabalho depois do término da licença parental e continuaram trabalhadores doze meses após seu retorno ao trabalho, por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="workers_return_towork_after_parental_leave_twelve_month" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['worker_expect_toreturn_after_leave']))
                                @php $text = json_encode([__('Conciliação entre a vida profissional, pessoal e familiar: Número de trabalhadores que deviam retornar ao trabalho após o término da licença parental')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['worker_expect_toreturn_after_leave'] }}">
                                        @include(tenant()->views .'icons.employees_backtowork', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['established_by_law']))
                                @php
                                    $text = json_encode([__('Conciliação entre a vida profissional, pessoal e familiar: Medidas relacionadas com a parentalidade para além do instituído na lei')]);
                                    $data = json_encode([
                                        ['id' => 'flexibilização_do_tempo_e_de_formas_de_trabalho', 'text' => __('Flexibilização do tempo e de formas de trabalho'), 'color' => 1, 'check' => ($charts['established_by_law']['flexibilização_do_tempo_e_de_formas_de_trabalho'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'ajuste_de_função', 'text' => __('Ajuste de função'), 'color' => 1, 'check' => ($charts['established_by_law']['ajuste_de_função'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'apoios_sociais', 'text' => __('Apoios sociais'), 'color' => 1, 'check' => ($charts['established_by_law']['apoios_sociais'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'dias_de_licença_adicionais', 'text' => __('Dias de licença adicionais'), 'color' => 1, 'check' => ($charts['established_by_law']['dias_de_licença_adicionais'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'outro', 'text' => __('Outro'), 'color' => 1, 'check' => ($charts['established_by_law']['outro'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['established_by_law_charts']))
                                @php
                                    $text = json_encode([__('Medidas relacionadas com a parentalidade para além do instituído na lei.')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="established_by_law_charts" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['participates_local_development_programs']))
                                @php
                                    $text = json_encode([__('Parcerias locais: Unidade de reporte participa em programas de desenvolvimento local')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('Unidade de reporte participa em programas de desenvolvimento local'), 'color' => 1, 'check' => ($charts['participates_local_development_programs']['status'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['participates_local_development_programs_progress']))
                                @php
                                    $text = json_encode([__('Unidades de reporte que participam em programas de'), __('desenvolvimento local')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Sim') ], ['color' => 'bg-esg1', 'text' => __('Não') ]]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" type="flex" subpoint="{{ $subpoint }}">
                                    <x-charts.donut id="participates_local_development_programs_progress" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['participates_local_development_programs_progress']['label']) }}" data="{{ json_encode($charts['participates_local_development_programs_progress']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['local_development_programs'])
                                @php $text = json_encode([__('Parcerias locais: Número total de programas de desenvolvimento local')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['local_development_programs'] }}" >
                                        @include(tenant()->views .'icons.community_program', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['monetary_amount_spent_local_products'])
                                @php $text = json_encode([__('Produtos locais: Valor monetário despendido em produtos locais')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['monetary_amount_spent_local_products'] }}" unit="€">
                                        @include(tenant()->views .'icons.community_program', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['percentage_local_purchases'])
                                @php $text = json_encode([__('Percentagem de compras locais')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ number_format($charts['percentage_local_purchases'], 2) }}" unit="%">
                                        @include(tenant()->views .'icons.comparas', ['class' => 'inline-block ml-2', 'width' => 60, 'height' => 50, 'color' => color(1)])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['policies']))
                                @php
                                    $text = json_encode([__('Políticas sociais')]);
                                    $data = json_encode([
                                        ['id' => 'política_de_direitos_humanos', 'text' => __('Política de Direitos Humanos'), 'color' => 1, 'check' => ($charts['policies']['política_de_direitos_humanos'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'política_de_fornecedores', 'text' => __('Política de Fornecedores'), 'color' => 1, 'check' => ($charts['policies']['política_de_fornecedores'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'política_de_remuneração', 'text' => __('Política de Remuneração'), 'color' => 1, 'check' => ($charts['policies']['política_de_remuneração'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['policies_progress_social']))
                                @php
                                    $text = json_encode([__('Políticas')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['policies_progress_social'][0], 'text' => __('Direitos Humanos'), 'color' => 1],
                                        ['percentage' => $charts['policies_progress_social'][1], 'text' => __('Fornecedores'), 'color' => 1],
                                        ['percentage' => $charts['policies_progress_social'][2], 'text' => __('Remuneração'), 'color' => 1]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif
                        </div>
                    </div>

                    {{-- Governação --}}
                    <div class="pagebreak mt-24">
                        <div class="flex print:hidden">
                            <div class="pt-2 mr-2">@include('icons.categories.3')</div>
                            <div>
                                <h1 class="font-encodesans text-LG font-bold leading-10 text-esg8">Desempenho em Governação</h1>
                            </div>
                        </div>

                        <p class="font-bold text-xs text-[#008131] mt-4 uppercase {{ $printView === 0 ? 'hidden' : '' }}">6.3 {{ __('Governação') }}</p>
                        <p class="text-esg8 text-xs font-normal mt-4 {{ $printView === 0 ? 'hidden' : '' }}"> {{ $charts['report']['governação_text'] }}</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 nonavoid">

                            @if (isset($charts['legal_requirements_applicable_progress']))
                                @php
                                    $text = json_encode([__('Requesitos legais e Ética')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['legal_requirements_applicable_progress'][0], 'text' => __('Unidades de reporte que cumprem os requisitos legais aplicáveis à sua atividade'), 'color' => 3],
                                        ['percentage' => $charts['legal_requirements_applicable_progress'][1], 'text' => __('Unidades de reporte que possuem um Código de Ética'), 'color' => 3],
                                        ['percentage' => $charts['legal_requirements_applicable_progress'][2], 'text' => __('Unidades de reporte que subscreveram o Código de Ética Mundial do Turismo'), 'color' => 3]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if (isset($charts['legal_requirements_applicable']))
                                @php
                                    $text = json_encode([__('Conformidade legal: Unidade de reporte cumpre os requisitos legais aplicáveis à sua atividade')]);
                                    $data = json_encode([
                                        ['id' => 'status', 'text' => __('Unidade de reporte cumpre os requisitos legais aplicáveis à sua atividade'), 'color' => 3, 'check' => ($charts['legal_requirements_applicable']['status'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['ethic']))
                                @php
                                    $text = json_encode([__('Ética: Códigos de Ética')]);
                                    $data = json_encode([
                                        ['id' => 'reporting_unit_code_ethics', 'text' => __('Unidade de reporte possui um Código de Ética'), 'color' => 3, 'check' => ($charts['ethic']['reporting_unit_code_ethics'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'world_tourism_code_of_ethics', 'text' => __('Unidade de reporte subscreve o Código de Ética Mundial do Turismo'), 'color' => 3, 'check' => ($charts['ethic']['world_tourism_code_of_ethics'] == 'yes' ? 'checked' : '')]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if ($charts['number_of_hours_of_ethics_training'])
                                @php $text = json_encode([__('Ética: Número de horas de formação em ética')]); @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.icon-number value="{{ $charts['number_of_hours_of_ethics_training'] }}" unit="h">
                                        @include(tenant()->views .'icons.formacao_etica', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                    </x-charts.icon-number>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['board_of_directors_by_gender'])
                                @php
                                    $text = json_encode([__('Diversidade no órgão de administração: Percentagem de indivíduos que compõem o Órgão de Administração por género')]);
                                    $subpoint = json_encode([ ['color' => 'bg-esg5', 'text' => __('Masculino')], ['color' => 'bg-esg1', 'text' => __('Feminino')], ['color' => 'bg-esg6', 'text' => __('Outro')] ])
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}" type="flex">
                                    <x-charts.donut id="board_of_directors_by_gender" class="m-auto !h-[250px] !w-[250px]" labels="{{ json_encode($charts['board_of_directors_by_gender']['label']) }}" data="{{ json_encode($charts['board_of_directors_by_gender']['data']) }}" color="{{ json_encode([color(5), color(1),color(6)]) }}"/>
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['independent_members_participate_board_of_director'])
                                @php
                                    $text = json_encode([__('Diversidade no órgão de administração: Número de elementos independentes que participam no Órgão de Administração por género')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="independent_members_participate_board_of_director" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if($charts['board_of_directors_by_age_group'])
                                @php
                                    $text = json_encode([__('Diversidade no órgão de administração: Percentagem de indivíduos no Órgão de Administração por faixa etária')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="board_of_directors_by_age_group" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['risks_arising_supply_chain_reporting_units']))
                                @php
                                    $text = json_encode(count($search['questionnaire']) > 1
                                        ? [__('Riscos que advêm da cadeia de abastecimento das unidades de reporte')]
                                        : [__('Diligência devida na cadeia de abastecimento: Riscos que advêm da cadeia de abastecimento da unidade de reporte')]);
                                    $data = json_encode([
                                        ['id' => 'child_labor', 'text' => __('Utilização de trabalho infantil e outras situações de abuso de direitos humanos'), 'color' => 3, 'check' => ($charts['risks_arising_supply_chain_reporting_units']['child_labor'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'unsafe_working_conditions', 'text' => __('Condições inseguras de trabalho'), 'color' => 3, 'check' => ($charts['risks_arising_supply_chain_reporting_units']['unsafe_working_conditions'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'labor_laws', 'text' => __('Desrespeito pela legislação laboral'), 'color' => 3, 'check' => ($charts['risks_arising_supply_chain_reporting_units']['labor_laws'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'environmental_legislation', 'text' => __('Incumprimento da legislação ambiental'), 'color' => 3, 'check' => ($charts['risks_arising_supply_chain_reporting_units']['environmental_legislation'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'hazardous_substances', 'text' => __('Uso de substâncias perigosas'), 'color' => 3, 'check' => ($charts['risks_arising_supply_chain_reporting_units']['hazardous_substances'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'corruption_situations', 'text' => __('Situações de suborno e corrupção'), 'color' => 3, 'check' => ($charts['risks_arising_supply_chain_reporting_units']['corruption_situations'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'competition_laws', 'text' => __('Incumprimento das leis da concorrência'), 'color' => 3, 'check' => ($charts['risks_arising_supply_chain_reporting_units']['competition_laws'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'outro', 'text' => __('Outro'), 'color' => 3, 'check' => ($charts['risks_arising_supply_chain_reporting_units']['outro'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['risks_arising_supply_chain_reporting_units_charts']))
                                @php
                                    $text = json_encode([__('Riscos que advêm da cadeia de abastecimento das unidades de reporte')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="risks_arising_supply_chain_reporting_units_charts" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['average_probability_risk_categories']))
                                @php
                                    $text = json_encode([__('Probabilidade média de ocorrência das categorias de risco'), __('(1 - muito improvável a 5 - muito provável)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="average_probability_risk_categories" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['average_severity_risk_categories']))
                                @php
                                    $text = json_encode([__('Severidade média do impacto das categorias de risco'), __('(1 - insignificante a 5 - desastroso)')]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.bar id="average_severity_risk_categories" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['risk_matrix']))
                                @if($charts['risk_matrix']['very_high']['label'] || $charts['risk_matrix']['high']['label'] || $charts['risk_matrix']['intermediate']['label'] || $charts['risk_matrix']['low']['label'])
                                    @php
                                        $text = json_encode([__('Gestão de risco: Matriz de risco')]);
                                        $subpoint = json_encode([
                                            ['color' => 'bg-red-700', 'text' => __('Muito alto')],
                                            ['color' => 'bg-esg1', 'text' => __('Alto')],
                                            ['color' => 'bg-[#FDD835]', 'text' => __('Intermediário')],
                                            ['color' => 'bg-esg6', 'text' => __('Baixo')],
                                            ['color' => 'bg-esg7', 'text' => __('Insignificante')]
                                        ]);
                                    @endphp
                                    <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                                        <div class="flex">
                                            <div class="h-[350px] w-[350px] mt-10">
                                                <x-charts.bar id="actions_plan" class="m-auto relative !h-full !w-full" />
                                            </div>
                                            <div id="actions_plan-legend" class="w-44 mt-24 pl-4 h-64 overflow-y-scroll"></div>
                                        </div>
                                    </x-cards.card-dashboard-version1>
                                @endif
                            @endif

                            @if(isset($charts['governance_policies']))
                                @php
                                    $text = json_encode([__('Políticas de governação')]);
                                    $data = json_encode([
                                        ['id' => 'anti_corruption', 'text' => __('Política anticorrupção e fraude'), 'color' => 3, 'check' => ($charts['governance_policies']['anti_corruption'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'conflicts_interest', 'text' => __('Política para prevenir e tratar situações de conflitos de interesse'), 'color' => 3, 'check' => ($charts['governance_policies']['conflicts_interest'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'code_of_ethics_and_conduct_from_suppliers', 'text' => __('A unidade de reporte solicita o Código de Ética e Conduta aos seus Fornecedores'), 'color' => 3, 'check' => ($charts['governance_policies']['code_of_ethics_and_conduct_from_suppliers'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'reporting_channel', 'text' => __('Canal de denúncia para colaboradores'), 'color' => 3, 'check' => ($charts['governance_policies']['reporting_channel'] == 'yes' ? 'checked' : '')],
                                        ['id' => 'data_privacy_policy', 'text' => __('Política de Privacidade de dados'), 'color' => 3, 'check' => ($charts['governance_policies']['data_privacy_policy'] == 'yes' ? 'checked' : '')],
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.switch data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif

                            @if(isset($charts['policies_progress']))
                                @php
                                    $text = json_encode([__('Políticas')]);
                                    $data = json_encode([
                                        ['percentage' => $charts['policies_progress'][0], 'text' => __('Unidades de reporte com Política anticorrupção e fraude'), 'color' => 3],
                                        ['percentage' => $charts['policies_progress'][1], 'text' => __('Unidades de reporte com Política para prevenir e tratar situações de conflitos de interesse'), 'color' => 3],
                                        ['percentage' => $charts['policies_progress'][2], 'text' => __('Unidades de reporte que solicitam o Código de Ética e Conduta aos seus Fornecedores'), 'color' => 3],
                                        ['percentage' => $charts['policies_progress'][3], 'text' => __('Unidades de reporte com canal de denúncia para colaboradores'), 'color' => 3]
                                    ]);
                                @endphp
                                <x-cards.card-dashboard-version1 text="{{ $text }}">
                                    <x-charts.progress data="{{ $data }}" />
                                </x-cards.card-dashboard-version1>
                            @endif
                        </div>
                    </div>

                    {{-- Relatório OLDDDD NOT USED  --}}
                    @if(isset($charts['materiality_matrix_old']))
                        @if($charts['materiality_matrix']['environmental'] && $charts['materiality_matrix']['social'] && $charts['materiality_matrix']['governance'] || $charts['sustainable_development_goals'])
                            <div class="mt-10 md:mt-5  print:mt-20">
                                <div class="flex">
                                    <div class="pt-2.5 mr-2">@include('icons.dashboard')</div>
                                    <div>
                                        <h1 class="font-encodesans text-LG font-bold leading-10 text-esg8">Relatório</h1>
                                    </div>
                                </div>
                                @if($charts['materiality_matrix']['environmental'] && $charts['materiality_matrix']['social'] && $charts['materiality_matrix']['governance'])
                                    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 mt-6">
                                        <div class="bg-esg4 border border-esg8/20 rounded p-4 mt-5 md:mt-0 print:mt-20 print:mt-20">
                                            <div class="text-esg8 font-encodesans flex text-base font-bold">
                                                <span >{{ __('Matriz de Materialidade') }}</span>
                                            </div>

                                            <div class="flex gap-5">
                                                <div class="flex">
                                                    <div class="text-esg5 text-xl">
                                                        <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-esg2 text-esg2"></span>
                                                    </div>
                                                    <div class="pl-2 inline-block text-xs text-esg8/70">Ambiental</div>
                                                </div>

                                                <div class="flex">
                                                    <div class="text-esg5 text-xl">
                                                        <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                    </div>
                                                    <div class="pl-2 inline-block text-xs text-esg8/70">Social</div>
                                                </div>

                                                <div class="flex">
                                                    <div class="text-esg5 text-xl">
                                                        <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-esg3 text-esg3"></span>
                                                    </div>
                                                    <div class="pl-2 inline-block text-xs text-esg8/70">{{ __('Governação') }}</div>
                                                </div>
                                            </div>

                                            <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                                                {{-- Ambiental --}}
                                                <div class="grid grid-cols-9">
                                                    @if (in_array(924, $charts['materiality_matrix']['environmental_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-924" > @include(tenant()->views .'icons.consumo', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][924]) ? color(2) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-924" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Consumo de água
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(925, $charts['materiality_matrix']['environmental_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-925" > @include(tenant()->views .'icons.gestao_energia', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['environmental'][925]) ? color(2) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-925" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Gestão de Energia
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(926, $charts['materiality_matrix']['environmental_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-926" > @include(tenant()->views .'icons.emissoes_gee', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['environmental'][926]) ? color(2) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-926" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Emissões de GEE
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(927, $charts['materiality_matrix']['environmental_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-927" > @include(tenant()->views .'icons.biodiversidade', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['environmental'][927]) ? color(2) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-927" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Pressão sobre a biodiversidade
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(928, $charts['materiality_matrix']['environmental_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-928" > @include(tenant()->views .'icons.climaticos', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['environmental'][928]) ? color(2) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-928" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Riscos climáticos por geolocalização
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(929, $charts['materiality_matrix']['environmental_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-929" > @include(tenant()->views .'icons.residuos', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['environmental'][929]) ? color(2) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-929" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Gestão de resíduos
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(930, $charts['materiality_matrix']['environmental_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-930" > @include(tenant()->views .'icons.economia', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['environmental'][930]) ? color(2) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-930" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Economia Circular
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>

                                                {{-- Social --}}
                                                <div class="grid grid-cols-9 mt-5">
                                                    @if (in_array(931, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-931" > @include(tenant()->views .'icons.contratacao', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][931]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-931" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Modelo de contratação
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(932, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-932" > @include(tenant()->views .'icons.salarial', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['social'][932]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-932" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Igualdade Salarial
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(933, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-933" > @include(tenant()->views .'icons.diversidade', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['social'][933]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-933" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Diversidade da força de trabalho
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(934, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-934" > @include(tenant()->views .'icons.qualification', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['social'][934]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-934" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Qualificação dos trabalhadores
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(935, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-934" > @include(tenant()->views .'icons.segurance', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['social'][935]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-934" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Saúde e Segurança no Trabalho
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(936, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-935" > @include(tenant()->views .'icons.conciliacao', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['social'][936]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-935" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Conciliação entre a vida profissional, pessoal e familiar
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(937, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-936" > @include(tenant()->views .'icons.parcerias', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['social'][937]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-936" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Parcerias locais
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(938, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-937" > @include(tenant()->views .'icons.comparas', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['social'][938]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-937" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Compras Locais
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(939, $charts['materiality_matrix']['social_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-938" > @include(tenant()->views .'icons.produtos', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['social'][939]) ? color(1) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-938" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Produtos Locais
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>

                                                {{-- Governação --}}
                                                <div class="grid grid-cols-9 mt-5">
                                                    @if (in_array(940, $charts['materiality_matrix']['governance_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-940" > @include(tenant()->views .'icons.conformidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][940]) ? color(3) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-940" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Conformidade Legal
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(941, $charts['materiality_matrix']['governance_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-941" > @include(tenant()->views .'icons.etica', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['governance'][941]) ? color(3) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-941" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Ética
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(942, $charts['materiality_matrix']['governance_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-942" > @include(tenant()->views .'icons.transparencia', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['governance'][942]) ? color(3) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-942" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Transparência
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(943, $charts['materiality_matrix']['governance_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-943" > @include(tenant()->views .'icons.gov_divesidade', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['governance'][943]) ? color(3) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-943" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Diversidade no órgão de administração
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(944, $charts['materiality_matrix']['governance_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-944" > @include(tenant()->views .'icons.cadeia', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['governance'][944]) ? color(3) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-944" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Diligência devida na cadeia de abastecimento
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if (in_array(945, $charts['materiality_matrix']['governance_options'], false))
                                                    <div class="w-full mt-10 text-center">
                                                        <span data-tooltip-target="tooltip-materiality-matrix-945" > @include(tenant()->views .'icons.gov_gestao', ['class' => 'inline-block ml-2', 'color' => isset($charts['materiality_matrix']['governance'][945]) ? color(3) : color(7)])</span>
                                                        <div id="tooltip-materiality-matrix-945" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                            Gestão de risco
                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if(!empty($charts['sustainable_development_goals']))
                                            <div class="bg-esg4 border border-esg8/20 rounded p-4 mt-5 md:mt-0 print:mt-20">
                                                <div class="text-esg8 font-encodesans flex text-base font-bold">
                                                    <span >{{ __('Objetivos de Desenvolvimento Sustentável') }}</span>
                                                </div>

                                                <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                                                    <div class="grid grid-cols-6">
                                                        @if (array_key_exists(376, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.1', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(377, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.2', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(378, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.3', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(379, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.4', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(380, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.5', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(381, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.6', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(382, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.7', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(383, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.8', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(384, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.9', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(385, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.10', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(386, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.11', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(387, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.12', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(388, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.13', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(389, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.14', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(390, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.15', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(391, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.16', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                        @if (array_key_exists(392, $charts['sustainable_development_goals']))
                                                            <div class="w-full mt-4">
                                                                @include(tenant()->views .'icons.goals.17', ['class' => 'inline-block'])
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif

                    @if((isset($charts['materiality_matrix']) && $charts['materiality_matrix']['environmental'] && $charts['materiality_matrix']['social'] && $charts['materiality_matrix']['governance']) || (isset($charts['sustainable_development_goals']) && !empty($charts['sustainable_development_goals'])))
                        <div class="mt-10 md:mt-5  print:mt-20 print:hidden">
                            <div class="flex">
                                <div class="pt-2.5 mr-2">@include('icons.dashboard')</div>
                                <div>
                                    <h1 class="font-encodesans text-LG font-bold leading-10 text-esg8">Relatório</h1>
                                </div>
                            </div>
                            @if(isset($charts['materiality_matrix']) && $charts['materiality_matrix']['environmental'] && $charts['materiality_matrix']['social'] && $charts['materiality_matrix']['governance'])
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 mt-6 print:hidden">
                                    <div class="bg-esg4 border border-esg8/20 rounded p-4 mt-5 md:mt-0 print:mt-20 print:mt-20">
                                        <div class="text-esg8 font-encodesans flex text-base font-bold">
                                            <span >{{ __('Matriz de Materialidade') }}</span>
                                        </div>

                                        <div class="flex gap-5">
                                            <div class="flex">
                                                <div class="text-esg5 text-xl">
                                                    <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-esg2 text-esg2"></span>
                                                </div>
                                                <div class="pl-2 inline-block text-xs text-esg8/70">Ambiental</div>
                                            </div>

                                            <div class="flex">
                                                <div class="text-esg5 text-xl">
                                                    <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-2 inline-block text-xs text-esg8/70">Social</div>
                                            </div>

                                            <div class="flex">
                                                <div class="text-esg5 text-xl">
                                                    <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-esg3 text-esg3"></span>
                                                </div>
                                                <div class="pl-2 inline-block text-xs text-esg8/70">{{ __('Governação') }}</div>
                                            </div>
                                        </div>

                                        <div class="text-esg25 font-encodesans text-5xl font-bold pb-10" x-data="{visible: 'esg_category_1'}">
                                            <div class="flex items-center gap-10">
                                                <div class="flex flex-col">
                                                    @foreach ([1, 2, 3] as $category)
                                                        <button :class="{'text-esg28': visible === 'esg_category_{{ $category }}'}" @click="visible = 'esg_category_{{ $category }}'" class="block mt-5">
                                                            <template x-if="visible === 'esg_category_{{ $category }}'">
                                                                @include('icons.categories.' . $category, ['width' => $category == 1 ? 30 : 25, 'height' => $category == 1 ? 30 : 25])
                                                            </template>

                                                            <template x-if="visible !== 'esg_category_{{ $category }}'">
                                                                @include('icons.categories.' . $category, ['color' => color(7), 'width' => $category == 1 ? 30 : 25, 'height' => $category == 1 ? 30 : 25])
                                                            </template>
                                                        </button>
                                                    @endforeach
                                                </div>
                                                <div>
                                                    {{-- Ambiental --}}
                                                    <div class="grid grid-cols-2 gap-5" x-show="visible === 'esg_category_1'">
                                                        @if (in_array(924, $charts['materiality_matrix']['environmental_options'], false))
                                                            <div class="w-full mt-5 flex gap-5 items-center ">
                                                                <div> @include(tenant()->views .'icons.consumo', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][924]) ? color(2) : color(7)])</div>
                                                                <div class="text-xs font-bold text-esg8">
                                                                    Consumo de água
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if (in_array(925, $charts['materiality_matrix']['environmental_options'], false))
                                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                                <div> @include(tenant()->views .'icons.gestao_energia', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][925]) ? color(2) : color(7)])</div>
                                                                <div class="text-xs font-bold text-esg8">
                                                                    Gestão de Energia
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if (in_array(926, $charts['materiality_matrix']['environmental_options'], false))
                                                            <div class="w-full mt-5 flex gap-5 items-center">
                                                                <div> @include(tenant()->views .'icons.emissoes_gee', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][926]) ? color(2) : color(7)])</div>
                                                                <div class="text-xs font-bold text-esg8">
                                                                    Emissões de GEE
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if (in_array(927, $charts['materiality_matrix']['environmental_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.biodiversidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][927]) ? color(2) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Pressão sobre a biodiversidade
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(928, $charts['materiality_matrix']['environmental_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.climaticos', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][928]) ? color(2) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Riscos climáticos por geolocalização
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(929, $charts['materiality_matrix']['environmental_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.residuos', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][929]) ? color(2) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Gestão de resíduos
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(930, $charts['materiality_matrix']['environmental_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.economia', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['environmental'][930]) ? color(2) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Economia Circular
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    {{-- Social --}}
                                                    <div class="grid grid-cols-2 gap-5" x-show="visible === 'esg_category_2'">
                                                        @if (in_array(931, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.contratacao', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][931]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Modelo de contratação
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(932, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.salarial', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][932]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Igualdade Salarial
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(933, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.diversidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][933]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Diversidade da força de trabalho
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(934, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.qualification', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][934]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Qualificação dos trabalhadores
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(935, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.segurance', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][935]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Saúde e Segurança no Trabalho
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(936, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.conciliacao', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][936]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Conciliação entre a vida profissional, pessoal e familiar
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(937, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.parcerias', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][937]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Parcerias locais
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(938, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.comparas', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][938]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Compras Locais
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(939, $charts['materiality_matrix']['social_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.produtos', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['social'][939]) ? color(1) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Produtos Locais
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    {{-- Governação --}}
                                                    <div class="grid grid-cols-2 gap-5" x-show="visible === 'esg_category_3'">
                                                        @if (in_array(940, $charts['materiality_matrix']['governance_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.conformidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][940]) ? color(3) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Conformidade Legal
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(941, $charts['materiality_matrix']['governance_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.etica', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][941]) ? color(3) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Ética
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(942, $charts['materiality_matrix']['governance_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.transparencia', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][942]) ? color(3) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Transparência
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(943, $charts['materiality_matrix']['governance_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.gov_divesidade', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][943]) ? color(3) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Diversidade no órgão de administração
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(944, $charts['materiality_matrix']['governance_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.cadeia', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][944]) ? color(3) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Diligência devida na cadeia de abastecimento
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if (in_array(945, $charts['materiality_matrix']['governance_options'], false))
                                                        <div class="w-full mt-5 flex gap-5 items-center">
                                                            <div> @include(tenant()->views .'icons.gov_gestao', ['class' => 'inline-block', 'color' => isset($charts['materiality_matrix']['governance'][945]) ? color(3) : color(7)])</div>
                                                            <div class="text-xs font-bold text-esg8">
                                                                Gestão de risco
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            @endif

                            @if(isset($charts['sustainable_development_goals']) && !empty($charts['sustainable_development_goals']))
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 mt-6 print:hidden">
                                    <div class="bg-esg4 border border-esg8/20 rounded p-4 mt-5 md:mt-0">
                                        <div class="text-esg8 font-encodesans flex text-base font-bold">
                                            <span >{{ __('Objetivos de Desenvolvimento Sustentável') }}</span>
                                        </div>

                                        <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                                            <div class="grid grid-cols-6">
                                                @if (array_key_exists(376, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.1', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(377, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.2', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(378, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.3', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(379, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.4', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(380, $charts['sustainable_development_goals']))

                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.5', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(381, $charts['sustainable_development_goals']))

                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.6', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(382, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.7', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(383, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.8', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(384, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.9', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(385, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.10', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(386, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.11', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(387, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.12', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(388, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.13', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(389, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.14', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(390, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.15', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(391, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.16', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                                @if (array_key_exists(392, $charts['sustainable_development_goals']))
                                                    <div class="w-full mt-4">
                                                        @include(tenant()->views .'icons.goals.17', ['class' => 'inline-block'])
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="{{ $printView === 0 ? 'hidden' : '' }}">
                    <div class="pagebreak">
                        <div class="flex items-center gap-5 mt-50 print:mt-20">
                            <div class="w-12 h-3 bg-red-700"> </div>
                            <div>
                                <h1 class="font-encodesans text-base font-bold leading-10 text-[#008131]"> 7. {{__('Declaração de Responsabilidade')}}</h1>
                            </div>
                        </div>

                        <div class="grid grid-cols1 md:gap-10 mt-4 pb-4">
                            <div class="">
                                <p class="text-esg8 text-xs font-normal">{{ $charts['report']['responsibility'] }}</p>
                            </div>

                            <p class="text-esg8 text-xs font-bold mt-2">{{ auth()->user()->name }} </p>
                            <p class="text-esg8 text-xs font-normal mt-2">{{ __('Submetido em') }} {{ (isset($charts['report']['company'])) ? date('d/m/Y', strtotime($charts['report']['company'][0]['submitted_at'])) : '-' }} </p>
                        </div>
                    </div>
                </div>

                <div class="{{ $printView === 0 ? 'hidden' : '' }}">
                    <div class="print:h-full">
                        <img src="{{ global_asset('images/customizations/tenant88bd07c3-05a9-4a30-b732-c865d7ccbce1/01.jpg')}}" class="">

                        <div class="absolute z-40 w-[31.8%] h-56 bg-esg7 -mt-[12%] text-center grid place-content-center text-3xl font-bold print:w-[90%] print:-mt-[28%]">
                            <div class="flex gap-5">
                                @if($charts['report']['logo'])
                                    <img src="{{ $charts['report']['logo'] }}">
                                @endif

                                @include(tenant()->views .'icons.logo')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
