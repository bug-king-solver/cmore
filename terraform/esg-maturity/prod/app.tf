resource "ibm_code_engine_project" "code-engine-project" {
    name              = "esg-maturity-com"
    resource_group_id = ibm_resource_group.resource-group.id

    timeouts {}

    lifecycle {
      prevent_destroy = true
    }
}

resource "ibm_code_engine_config_map" "app-config-map" {
    data          = {
        "APP_DEBUG"             = "false"
        "APP_ENV"               = "production"
        "APP_INSTANCE"          = "esg-maturity"
        "APP_NAME"              = "ESG Maturity"
        "APP_SELF_REGISTRATION" = "false"
        "APP_URL"               = "https://esg-maturity.com"
        "AWS_BUCKET"            = "esg-maturity-prod"
        "AWS_DEFAULT_REGION"    = "eu-de"
        "AWS_ENDPOINT"          = "https://s3.eu-de.cloud-object-storage.appdomain.cloud"
        "CACHE_DRIVER"          = "redis"
        "CASHIER_MODEL"         = "App\\Models\\Tenant"
        "CENTRAL_DOMAIN"        = "esg-maturity.com"
        "CSP_ENABLED"           = "false"
        "DB_CONNECTION"         = "central"
        "DB_DATABASE"           = "central"
        "FORCE_HTTPS"           = "true"
        "LOG_CHANNEL"           = "sentry"
        "LOG_LEVEL"             = "notice"
        "MAIL_ENCRYPTION"       = "tls"
        "MAIL_FROM_ADDRESS"     = "no-reply@esg-maturity.com"
        "MAIL_FROM_NAME"        = "ESG Maturity"
        "QUEUE_CONNECTION"      = "redis"
        "REDIS_CLIENT"          = "phpredis"
        "SESSION_DRIVER"        = "redis"
        "SESSION_LIFETIME"      = "120"
    }
    name          = "app"
    project_id    = ibm_code_engine_project.code-engine-project.id

    lifecycle {
      prevent_destroy = true
    }
}

resource "ibm_code_engine_app" "app" {
    image_port                    = 8080
    image_reference               = var.docker_image
    image_secret                  = "gitlab-container-registry"
    managed_domain_mappings       = "local_public"
    name                          = "app"
    project_id                    = ibm_code_engine_project.code-engine-project.id
    run_arguments                 = [
        "-c",
        "/php-apache-entrypoint.sh",
    ]
    run_commands                  = [
        "/bin/bash",
    ]
    scale_concurrency             = 100
    scale_cpu_limit               = "4"
    scale_ephemeral_storage_limit = "1G"
    scale_max_instances           = 10
    scale_memory_limit            = "8G"
    scale_min_instances           = 1
    scale_request_timeout         = 300

    lifecycle {
      prevent_destroy = true
    }

    run_env_variables {
        reference = "app-secrets"
        type      = "secret_full_reference"
    }
    run_env_variables {
        reference = "app"
        type      = "config_map_full_reference"
    }
    run_env_variables {
        name  = "CE_SUBDOMAIN"
        type  = "literal"
        value = "13riuca1k93f"
    }
    run_env_variables {
        name  = "CE_APP"
        type  = "literal"
        value = "app"
    }
    run_env_variables {
        name  = "CE_DOMAIN"
        type  = "literal"
        value = "eu-de.codeengine.appdomain.cloud"
    }

    timeouts {}
}