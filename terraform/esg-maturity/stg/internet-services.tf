## Blocks associated with staging-cmore.com
resource "ibm_cis" "internet-services" {
    location                = "global"
    name                    = "staging-cmore.com"
    parameters              = {}
    plan                    = "enterprise-usage"
    resource_group_id       = ibm_resource_group.resource-group.id
    tags                    = [
        "env:dev",
        "version-1",
    ]

    timeouts {}

    lifecycle {
      prevent_destroy = true
    }
}

resource "ibm_cis_domain" "staging-cmore-com" {
    cis_id = ibm_cis.internet-services.id
    domain                = "staging-cmore.com"
    type                  = "full"

    lifecycle {
      prevent_destroy = true
    }
}

resource "ibm_cis_tls_settings" "tls_settings" {
    cis_id = ibm_cis.internet-services.id
    domain_id = ibm_cis_domain.staging-cmore-com.domain_id
    min_tls_version = "1.3"
    tls_1_3 = "on"
    universal_ssl = true

    lifecycle {
        prevent_destroy = true
    }
}

resource "ibm_cis_edge_functions_trigger" "staging-cmore-com" {
    action_name             = ibm_cis_edge_functions_action.app-staging.action_name
    cis_id                  = ibm_cis.internet-services.id
    domain_id               = ibm_cis_domain.staging-cmore-com.domain_id
    pattern_url             = "staging-cmore.com/*"

    lifecycle {
      prevent_destroy = true
    }
}

# *.staging-cmore.com
resource "ibm_cis_edge_functions_trigger" "wildcard-staging-cmore-com" {
    action_name             = ibm_cis_edge_functions_action.app-staging.action_name
    cis_id                  = ibm_cis.internet-services.id
    domain_id               = ibm_cis_domain.staging-cmore-com.domain_id
    pattern_url             = "*.staging-cmore.com/*"

    lifecycle {
      prevent_destroy = true
    }
}

resource "ibm_cis_edge_functions_action" "app-staging" {
    action_name = "app"
    cis_id      = ibm_cis.internet-services.id
    domain_id   = ibm_cis_domain.staging-cmore-com.domain_id
    script      = <<-EOT
        addEventListener('fetch', (event) => {
            const mutable_request = new Request(event.request);
            event.respondWith(redirectAndLog(mutable_request));
        });
        
        async function redirectAndLog(request) {
            const response = await redirectOrPass(request);
            return response;
        }
        
        async function getSite(request, site) {
            const url = new URL(request.url);
            // let our servers know what origin the request came from
            // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Forwarded-Host
            request.headers.set('X-Forwarded-Host', url.hostname);
            request.headers.set('host', site);
            
            url.hostname = site;
            url.protocol = "https:";
            
            response = await fetch(url.toString(), request);
            response = new Response(response.body, response);
            //response.headers.set('Gitlab-On-Demand-DAST', '1c4e5df5-49b1-4628-a381-49cc205fcefb');
            console.log('Got getSite Request to ' + site, response);
            return response;
        }
        
        async function redirectOrPass(request) {
            const urlObject = new URL(request.url);
            const hostname = urlObject.hostname;
            const subdomainList = hostname.split('.');
            const subdomain = subdomainList[0];
        
            let response = null;
            
            switch (subdomain) {
                default:
                    destination = '${replace(ibm_code_engine_app.app.endpoint, "https://", "")}';
            }
        
            try {
                console.log('Got MAIN request', request);
        
                response = await getSite(request, destination);
        
                console.log('Got MAIN response', response.status);
        
                if (response.status >= 500) {
                    // do failover only in case of a server-side problem (5xx, such as 502 bad gateway)
                    //console.log('Got FALLBACK request', response);
                    //response = await getSite(request, destination);
                    //console.log('Got Inside ', response);
                } /*  else, the following return statement returns the original response for all OK responses (2xx),
                      redirect responses (3xx) and request error responses (4xx) */
        
                return response;
            } catch (error) {
                // if no action found, play the regular request
                console.log('Got Error', error);
                return await fetch(request);
            }
        }
    EOT

    lifecycle {
      prevent_destroy = true
    }
}