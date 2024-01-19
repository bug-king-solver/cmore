<?php

namespace App\Http\Livewire\Charts;

use App\Models\Tenant\Analysis\TermsFrequency;
use App\Models\Tenant\Company;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class SentimentalAnalysis extends Component
{
    protected $listeners = [
        'companiesChanged' => '$refresh',
    ];

    public string $from;

    public string $to;

    public function mount()
    {
        $this->from = '2022-12-01';
        $this->to = '2022-12-31';
    }

    public function render(): View
    {
        return view(
            'livewire.tenant.charts.sentimental-analysis',
            [
                // 'avg_sentiment' => $this->averageSentiment(),
                // 'frequency_words' => $this->allWordsFrequency(),
            ]
        )
        ->layoutData(
            [
                'mainBgColor' => 'bg-esg6',
            ]
        );
    }

    public function sentimentOverTime($period = 'day')
    {
        if (strtolower($period) == 'day') {
            $file = storage_path('temp/sentiment_daily.json');

            $data = file_get_contents($file);
            $data = json_decode($data, false);

            if ($data) {
                return [
                    'label' => array_column($data, 'extracted_at'),
                    'positive' => array_column($data, 'positive_count'),
                    'negative' => array_column($data, 'negative_count'),
                    'neutral' => array_column($data, 'neutral_count'),
                ];
            }

            return [];
        }

        if (strtolower($period) == 'week') {
            $file = storage_path('temp/sentiment_weekly.json');

            $data = file_get_contents($file);
            $data = json_decode($data, true);

            if ($data) {
                $dates = array_map(function ($ar) {
                    return $ar['year_week'][0] . ' Week-' . $ar['year_week'][1];
                }, array_column($data, '_id'));

                return [
                    'label' => $dates,
                    'positive' => array_column($data, 'total_positive_count'),
                    'negative' => array_column($data, 'total_negative_count'),
                    'neutral' => array_column($data, 'total_neutral_count'),
                ];
            }

            return [];
        }

        if (strtolower($period) == 'month') {
            $file = storage_path('temp/sentiment_monthly.json');

            $data = file_get_contents($file);
            $data = json_decode($data, true);

            if ($data) {
                $dates = array_map(function ($ar) {
                    return $ar['year_month'][0] . ' ' . $ar['year_month'][1];
                }, array_column($data, '_id'));

                return [
                    'label' => $dates,
                    'positive' => array_column($data, 'total_positive_count'),
                    'negative' => array_column($data, 'total_negative_count'),
                    'neutral' => array_column($data, 'total_neutral_count'),
                ];
            }

            return [];
        }

        return [];
    }

    public function averageSentiment()
    {
        $file = storage_path('temp/sentiment_weekly.json');

        $data = file_get_contents($file);
        $data = json_decode($data, true);

        if ($data) {
            $data = end($data);

            $finalData = [
                'positive' => $data['total_positive_count'],
                'negative' => $data['total_negative_count'],
                'neutral' => $data['total_neutral_count'],
            ];

            return array_search(max($finalData), $finalData);
        }

        return [];
    }

    public function emotionsOverTime($period = 'day')
    {
        if (strtolower($period) == 'day') {
            $file = storage_path('temp/emotions_daily.json');

            $data = file_get_contents($file);
            $data = json_decode($data, false);

            if ($data) {
                return [
                    'label' => array_column($data, 'extracted_at'),
                    'happy' => array_column($data, 'happy_count'),
                    'unhappy' => array_column($data, 'angry_count'),
                    'sad' => array_column($data, 'sad_count'),
                ];
            }

            return [];
        }

        if (strtolower($period) == 'week') {
            $file = storage_path('temp/emotions_weekly.json');

            $data = file_get_contents($file);
            $data = json_decode($data, true);

            if ($data) {
                $dates = array_map(function ($ar) {
                    return $ar['year_week'][0] . ' Week-' . $ar['year_week'][1];
                }, array_column($data, '_id'));

                return [
                    'label' => $dates,
                    'happy' => array_column($data, 'total_happy_count'),
                    'unhappy' => array_column($data, 'total_angry_count'),
                    'sad' => array_column($data, 'total_sad_count'),
                ];
            }

            return [];
        }

        if (strtolower($period) == 'month') {
            $file = storage_path('temp/emotions_monthly.json');

            $data = file_get_contents($file);
            $data = json_decode($data, true);

            if ($data) {
                $dates = array_map(function ($ar) {
                    return $ar['year_month'][0] . ' ' . $ar['year_month'][1];
                }, array_column($data, '_id'));

                return [
                    'label' => $dates,
                    'happy' => array_column($data, 'total_happy_count'),
                    'unhappy' => array_column($data, 'total_angry_count'),
                    'sad' => array_column($data, 'total_sad_count'),
                ];
            }

            return [];
        }

        return [];
    }

    public function averageEmotion()
    {
        $file = storage_path('temp/emotions_weekly.json');

        $data = file_get_contents($file);
        $data = json_decode($data, true);

        if ($data) {
            $data = end($data);

            $values = [
                $data['total_amused_count'],
                $data['total_afraid_count'],
                $data['total_angry_count'],
                $data['total_annoyed_count'],
                $data['total_dontcare_count'],
                $data['total_happy_count'],
                $data['total_inspired_count'],
                $data['total_sad_count'],
            ];

            return [
                'label' => ['amused', 'afraid', 'angry', 'annoyed', 'dontcare', 'happy', 'inspired', 'sad'],
                'data' => $values,
                'max' => max($values),
                'min' => min($values),
            ];
        }

        return [];
    }

    public function allWordsFrequency()
    {
        $file = storage_path('temp/kw_daily.json');

        $data = file_get_contents($file);
        $data = json_decode($data, true)[0];

        if ($data) {
            $words = array_keys($data['kw_weights']);

            return  array_combine($words, $words);
        }

        return [];
    }

    public function wordFrequency($period = 'day', $word = '')
    {
        if (strtolower($period) == 'day') {
            $file = storage_path('temp/kw_daily.json');

            $data = file_get_contents($file);
            $data = json_decode($data, true);

            if ($data) {
                $kw_weights = array_column($data, 'kw_weights');

                return [
                    'label' => array_column($data, 'extracted_at'),
                    'data' => array_column($kw_weights, $word),
                ];
            }

            return [];
        }

        if (strtolower($period) == 'week') {
            $file = storage_path('temp/kw_weekly.json');

            $data = file_get_contents($file);
            $data = json_decode($data, true);

            if ($data) {
                $dates = array_map(function ($ar) {
                    return $ar['year_week'][0] . ' Week-' . $ar['year_week'][1];
                }, array_column($data, '_id'));

                $kw_weights = array_column($data, 'kw_weights');

                return [
                    'label' => $dates,
                    'data' => array_column($kw_weights, $word),
                ];
            }

            return [];
        }

        if (strtolower($period) == 'month') {
            $file = storage_path('temp/kw_monthly.json');

            $data = file_get_contents($file);
            $data = json_decode($data, true);

            if ($data) {
                $dates = array_map(function ($ar) {
                    return $ar['year_month'][0] . ' ' . $ar['year_month'][1];
                }, array_column($data, '_id'));

                $kw_weights = array_column($data, 'kw_weights');

                return [
                    'label' => $dates,
                    'data' => array_column($kw_weights, $word),
                ];
            }

            return [];
        }

        return [];
    }
}
