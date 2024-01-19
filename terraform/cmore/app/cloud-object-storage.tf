resource "ibm_resource_instance" "resource_instance" {
    service                 = "cloud-object-storage"
    location                = "global"
    name                    = "dev-cmore.com"
    plan                    = "standard"
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

resource "ibm_cos_bucket" "bucket" {
    allowed_ip           = []
    bucket_name          = "esg-maturity-dev"
    endpoint_type        = "public"
    hard_quota           = 0
    region_location      = "eu-de"
    resource_instance_id = ibm_resource_instance.resource_instance.id
    storage_class        = "smart"

    timeouts {}

    lifecycle {
      prevent_destroy = true
    }
}