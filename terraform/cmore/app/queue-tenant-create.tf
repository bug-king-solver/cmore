resource "ibm_code_engine_job" "queue-tenant-create" {
    count = 0

    image_reference               = var.docker_image
    image_secret                  = "cmore-container-registry"
    name                          = "app-worker-tenant-create"
    project_id                    = ibm_code_engine_project.code-engine-project.id
    run_arguments                 = [
        "-c",
        "/php-worker-custom.sh",
    ]
    run_commands                  = [
        "/bin/bash",
    ]

    run_mode                      = "daemon"

    run_env_variables {
        reference = ibm_code_engine_config_map.app-config-map.name
        type      = "config_map_full_reference"
    }
    run_env_variables {
        reference = "app-secrets"
        type      = "secret_full_reference"
    }

    run_env_variables {
        name  = "NAME"
        value = "CreateTenant"
    }

    run_env_variables {
        name  = "QUEUE"
        value = "tenant_create"
    }

    # lifecycle {
    #     prevent_destroy = true
    # }
}