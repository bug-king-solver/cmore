resource "ibm_code_engine_job" "queue-default" {
    count = 0

    image_reference               = var.docker_image
    image_secret                  = "gitlab-container-registry"
    name                          = "app-worker-default"
    project_id                    = ibm_code_engine_project.code-engine-project.id
    run_arguments                 = [
        "-c",
        "/php-worker-default.sh",
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

    # lifecycle {
    #     prevent_destroy = true
    # }
}