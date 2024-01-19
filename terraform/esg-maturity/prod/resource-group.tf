resource "ibm_resource_group" "resource-group" {
    name       = "production"

    lifecycle {
      prevent_destroy = true
    }
}

