<?php

namespace App\Http\Livewire\Wallet;

use App\Http\Livewire\Components\FilterBarComponent;
use App\Models\Transaction;
use Livewire\WithPagination;

class Index extends FilterBarComponent
{
    use WithPagination;

    protected $listeners = [
        'balanceChanged' => '$refresh',
    ];

    public $perPageTransactions;
    public $model;
    public $search;

    public function __construct()
    {
        $this->perPageTransactions = config('app.paginate.per_page');
    }

    public function mount()
    {
        $this->model = new Transaction();
        $this->model->wallet = tenant()->wallet;
        $this->fetchAvailableFilters($this->model->getMorphClass());
    }

    public function render()
    {
        $tenant = tenant();

        $transactions = $this->search($this->model->list())->paginate($this->perPageTransactions);
        $balance = $tenant->balanceFloat;
        $totalDeposited90Days = $this->model->total(
            $this->model->wallet->holder_type,
            $this->model->wallet->holder_id,
            Transaction::TYPE_DEPOSIT,
            90
        );
        $totalWithdrawn90Days = $this->model->total(
            $this->model->wallet->holder_type,
            $this->model->wallet->holder_id,
            Transaction::TYPE_WITHDRAW,
            90
        );
        $credit = $tenant->walletLimitCredit;

        return view('livewire.tenant.wallet.index', [
            'balance' => $balance,
            'totalDeposited90Days' => $totalDeposited90Days,
            'totalWithdrawn90Days' => $totalWithdrawn90Days,
            'credit' => $credit,
            'transactions' => $transactions
        ]);
    }

    public function loadMoreTransactions()
    {
        $this->perPageTransactions += config('app.paginate.per_page');
    }
}
