<?php

namespace App\Http\Controllers\Tenant;

class DownloadInvoiceController
{
    public function __invoke($id)
    {
        return redirect(tenant()->findInvoice($id)->asStripeInvoice()->invoice_pdf);
    }
}
