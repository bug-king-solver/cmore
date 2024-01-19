<?php

namespace App\Services\Currency;

use Cartalyst\Converter\Exchangers\ExchangerInterface;
use Exception;
use Illuminate\Support\Facades\Http;

class Exchanger
{
    /**
     * Holds the currency rates.
     *
     * @var object
     */
    protected $rates;

    /**
     * Holds the OpenExchangeRates.org api url.
     *
     * @var string
     */
    protected $url = 'https://openexchangerates.org/api/historical/';

    /**
     * Holds the application id.
     *
     * @var array
     */
    protected $appId;

    /**
     * From
     *
     * @var string
     */
    protected $from;

    /**
     * To
     *
     * @var string
     */
    protected $to;

    /**
     * Year
     *
     * @var int
     */
    protected $year;

    /**
     * Year
     *
     * @var float
     */
    protected $value;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(?int $year = null)
    {
        $this->appId = env('OPENEXCHANGERATES_API_ID', '8792a2ccc36b4587891deb618da13d53');
        $this->year = $year ?? date('Y') . '-01-01';
    }

    /**
     * Return the exchange rate for the provided currency code.
     *
     * @param string $code
     *
     * @throws \Exception
     *
     * @return float
     */
    public function convert(string $from, string $to, float $value, ?string $year = null): self
    {
        $this->year = $year ?? date('Y') . '-01-01';

        $this->from = strtoupper($from);
        $this->to = strtoupper($to);

        $rates = $this->getRates();

        if (! isset($rates[$this->to])) {
            throw new Exception('Currency not found.');
        }

        $this->value = $rates[$this->to] * $value;

        return $this;
    }

    /**
     * Return the currencies rates.
     *
     * @return object
     */

    public function get()
    {
        return $this->value;
    }

    /**
     * Return the currencies rates.
     *
     * @return object
     */

    public function format()
    {
        return round($this->value, 2);
    }

    /**
     * Return the currencies rates.
     *
     * @return object
     */
    public function getRates()
    {
        $this->setRates();

        return $this->rates;
    }

    /**
     * Downloads the latest exchange rates file from openexchangerates.org.
     *
     * @return object
     */
    public function setRates()
    {
        return $this->rates = \Cache::rememberForever("currencies.{$this->from}.{$this->year}", function () {
            $data = Http::acceptJson()->get("{$this->url }{$this->year}.json?app_id={$this->appId}&base={$this->from}")->json();

            return $data['rates'];
        });
    }
}
