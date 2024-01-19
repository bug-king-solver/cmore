resource "ibm_resource_group" "resource-group" {
    name       = "dev"

    lifecycle {
      prevent_destroy = true
    }
}