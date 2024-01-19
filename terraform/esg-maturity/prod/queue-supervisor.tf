resource "ibm_code_engine_job" "queue-supervisor" {
    image_reference               = var.docker_image
    image_secret                  = "gitlab-container-registry"
    name                          = "app-worker-supervisor"
    project_id                    = ibm_code_engine_project.code-engine-project.id
    run_arguments                 = [
        "-c",
        "/php-worker-entrypoint.dev.sh",
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

    lifecycle {
        prevent_destroy = true
    }
}

resource "null_resource" "submit-queue-supervisor" {
    depends_on = [ ibm_code_engine_job.queue-supervisor ]

    triggers = {
        IBMCLOUD_API_KEY         = var.ibmcloud_api_key
        REGION                   = var.region
        docker_image             = var.docker_image
    }
 
    provisioner "local-exec" {
        command = "cd '${path.module}' && bash submit-queue.sh"
    
        environment = {
            IBMCLOUD_API_KEY         = self.triggers.IBMCLOUD_API_KEY
            IBMCLOUD_REGION          = self.triggers.REGION
            IBMCLOUD_GROUP           = ibm_resource_group.resource-group.name
            IBMCLOUD_PROJECT         = ibm_code_engine_project.code-engine-project.name
            JOB_NAME                 = ibm_code_engine_job.queue-supervisor.name
        }
    }
}