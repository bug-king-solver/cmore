resource "ibm_resource_instance" "Cloud-Object-Production" {
  service                 = "cloud-object-storage"
  location                = "global"
  name                    = "esg-maturity-com"
  plan                    = "standard"
  resource_group_id       = ibm_resource_group.resource-group.id
  tags                    = [
    "env:prod",
    "version-1"
  ]

    lifecycle {
      prevent_destroy = true
    }
}