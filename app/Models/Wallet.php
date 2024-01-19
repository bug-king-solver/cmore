<?php

namespace App\Models;

use Bavix\Wallet\Models\Wallet as ModelsWallet;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Wallet extends ModelsWallet
{

    use CentralConnection;

}
