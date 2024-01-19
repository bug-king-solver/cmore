resource "ibm_resource_instance" "resource_instance" {
    service                 = "cloud-object-storage"
    location                = "global"
    name                    = "staging-cmore.com"
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

## buckets can now be created in the resource instance
## follow example from dev environment