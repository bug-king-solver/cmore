resource "ibm_database" "mysql" {
    location                     = "eu-de"
    name                         = "staging-cmore.com"
    plan                         = "standard"
    resource_group_id            = ibm_resource_group.resource-group.id
    service                      = "databases-for-mysql"
    service_endpoints            = "public-and-private"
    tags                         = [
        "env:dev",
        "version-1",
    ]
    version                      = "8.0"

    group {
      group_id = "member"

      memory {
        allocation_mb = 2048 # 2 GB
      }

      disk {
        allocation_mb = 122880 # 120 GB
      }

      cpu {
        allocation_count = 0
      }
    }

    lifecycle {
      prevent_destroy = true
    }

    timeouts {}
}

resource "ibm_database" "redis" {
    location                     = "eu-de"
    name                         = "staging-cmore.com"
    plan                         = "standard"
    resource_group_id            = ibm_resource_group.resource-group.id
    service                      = "databases-for-redis"
    service_endpoints            = "public-and-private"
    tags                         = [
        "env:dev",
        "version-1",
    ]
    version                      = "6"

    group {
      group_id = "member"

      memory {
        allocation_mb = 1024 # 1 GB
      }

      disk {
        allocation_mb = 20480 # 20 GB
      }

      cpu {
        allocation_count = 0
      }
    }

    lifecycle {
      prevent_destroy = true
    }


    timeouts {}
}

data "ibm_database_connection" "mysql-connection" {
    deployment_id = ibm_database.mysql.id
    endpoint_type = "private"
    user_id = "admin"
    user_type = "database"
}