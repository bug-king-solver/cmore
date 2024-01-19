resource "ibm_resource_group" "resource-group" {
    name       = "staging"
  
    lifecycle {
      prevent_destroy = true
    }
}