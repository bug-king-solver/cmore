resource "ibm_code_engine_project" "code-engine-project" {
    name              = "dev-cmore-com"
    resource_group_id = ibm_resource_group.resource-group.id

    lifecycle {
        prevent_destroy = true
    }

    timeouts {}
}

resource "ibm_code_engine_config_map" "app-config-map" {
    data          = {
        "APP_DEBUG"                         = "true"
        "APP_ENV"                           = "dev"
        "APP_NAME"                          = "ESG Maturity"
        "APP_INSTANCE"                      = "esg-maturity"
        "APP_SELF_REGISTRATION"             = "false"
        "APP_URL"                           = "https://dev-cmore.com"
        "APP_VERSION"                       = "22.46.1"
        "AWS_BUCKET"                        = "esg-maturity-dev"
        "AWS_DEFAULT_REGION"                = "eu-de"
        "AWS_ENDPOINT"                      = "https://s3.eu-de.cloud-object-storage.appdomain.cloud"
        "BROADCAST_DRIVER"                  = "log"
        "CACHE_DRIVER"                      = "redis"
        "CASHIER_MODEL"                     = "App\\Models\\Tenant"
        "CENTRAL_DOMAIN"                    = "dev-cmore.com"
        "CSP_ENABLED"                       = "true"
        "DB_CONNECTION"                     = "central"
        "DB_DATABASE"                       = "central"
        "FORCE_HTTPS"                       = "true"
        "LOG_CHANNEL"                       = "sentry"
        "LOG_LEVEL"                         = "debug"
        "MAIL_ENCRYPTION"                   = "tls"
        "MAIL_FROM_ADDRESS"                 = "luis@esg-maturity.com"
        "MAIL_FROM_NAME"                    = "ESG Maturity"
        "MAIL_HOST"                         = "smtp.mailtrap.io"
        "MAIL_MAILER"                       = "smtp"
        "MAIL_PORT"                         = "2525"
        "MYSQL_ATTR_SSL_CA"                 = "/var/www/html/certs/mysql"
        "MYSQL_ATTR_SSL_VERIFY_SERVER_CERT" = "true"
        "QUEUE_CONNECTION"                  = "redis"
        "REDIS_CLIENT"                      = "phpredis"
        "REDIS_CONTEXT"                     = "true"
        "SESSION_DRIVER"                    = "redis"
        "SESSION_LIFETIME"                  = "120"
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
    image_secret                  = "cmore-container-registry"
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
    scale_cpu_limit               = "0.5"
    scale_ephemeral_storage_limit = "500M"
    scale_max_instances           = 1
    scale_memory_limit            = "1G"
    scale_min_instances           = 1
    scale_request_timeout         = 300

    run_env_variables {
        reference = ibm_code_engine_config_map.app-config-map.name
        type      = "config_map_full_reference"
    }
    run_env_variables {
        reference = "app-secrets"
        type      = "secret_full_reference"
    }
    timeouts {}

    lifecycle {
        prevent_destroy = true
    }
}