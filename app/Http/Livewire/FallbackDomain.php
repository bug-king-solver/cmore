<?php

namespace App\Http\Livewire;

use App\Models\Domain;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;

class FallbackDomain extends Component
{
    public $domain = '';

    public function mount()
    {
        $this->domain = tenant()->fallbackDomain->domain;
    }

    public function save()
    {
        $this->validate([
            'domain' => [
                'required',
                'string',
                Rule::unique('central.domains')->ignoreModel(tenant()->fallbackDomain), 'regex:/^[A-Za-z0-9-]+$/',
            ],
        ]);

        if ($this->domain === tenant()->fallbackDomain->domain) {
            return; // No action
        }

        $oldFallback = tenant()->fallbackDomain;

        $newFallback = tenant()->createDomain($this->domain)->makeFallback();

        // We'll be deleting the old fallback domain. So if it was
        // the primary domain, we'll make the one one primary
        if ($oldFallback->is_primary) {
            $newFallback->makePrimary();
        }

        // In our setup, we only allow tenants to have one subdomain.
        // We don't want them squatting multiple subdomains.
        $oldFallback->delete();

        $this->emit('domainsUpdated');

        $this->domain = '';

        // If we were visiting the old fallback, which was deleted,
        // we'll redirect the user to the new fallback domain.
        if ($oldFallback->is(DomainTenantResolver::$currentDomain)) {
            redirect(tenant_route(Domain::domainFromSubdomain($newFallback->domain), 'tenant.settings.application'));
        }
    }

    public function render()
    {
        return view('livewire.tenant.fallback-domain');
    }
}
