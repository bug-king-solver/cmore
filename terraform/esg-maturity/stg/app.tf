resource "ibm_code_engine_project" "code-engine-project" {
    name              = "staging-cmore-com"
    resource_group_id = ibm_resource_group.resource-group.id

    lifecycle {
      prevent_destroy = true
    }

    timeouts {}
}

resource "ibm_code_engine_config_map" "app-config-map" {
    data          = {
        "APP_DEBUG"                         = "true"
        "APP_ENV"                           = "staging"
        "APP_INSTANCE"                      = "esg-maturity"
        "APP_NAME"                          = "ESG Maturity"
        "APP_SELF_REGISTRATION"             = "false"
        "APP_URL"                           = "https://staging-cmore.com"
        "APP_VERSION"                       = "22.46.1"
        "AWS_ACCESS_KEY_ID"                 = "c7855e77e4e344b38b75b0e361091f76"
        "AWS_BUCKET"                        = "central-staging"
        "AWS_DEFAULT_REGION"                = "eu-de"
        "AWS_ENDPOINT"                      = "https://s3.eu-de.cloud-object-storage.appdomain.cloud"
        "BROADCAST_DRIVER"                  = "log"
        "CACHE_DRIVER"                      = "redis"
        "CASHIER_MODEL"                     = "App\\Models\\Tenant"
        "CENTRAL_DOMAIN"                    = "staging-cmore.com"
        "CSP_ENABLED"                       = "true"
        "DB_CONNECTION"                     = "central"
        "DB_DATABASE"                       = "central"
        "DB_HOST"                           = data.ibm_database_connection.mysql-connection.mysql[0].hosts[0].hostname
        "DB_PORT"                           = data.ibm_database_connection.mysql-connection.mysql[0].hosts[0].port
        "DB_USERNAME"                       = data.ibm_database_connection.mysql-connection.mysql[0].authentication[0].username
        "FORCE_HTTPS"                       = "true"
        "LOG_CHANNEL"                       = "sentry"
        "LOG_LEVEL"                         = "debug"
        "MAIL_ENCRYPTION"                   = "tls"
        "MAIL_FROM_ADDRESS"                 = "luis@esg-maturity.com"
        "MAIL_FROM_NAME"                    = "ESG Maturity"
        "MAIL_HOST"                         = "smtp.mailtrap.io"
        "MAIL_MAILER"                       = "smtp"
        "MAIL_PORT"                         = "2525"
        "MAIL_USERNAME"                     = "4ff6b260f9e6c4"
        "MYSQL_ATTR_SSL_CA"                 = "/var/www/html/certs/mysql"
        "MYSQL_ATTR_SSL_VERIFY_SERVER_CERT" = "true"
        "QUEUE_CONNECTION"                  = "redis"
        "REDIS_CLIENT"                      = "phpredis"
        "REDIS_CONTEXT"                     = "true"
        "SENTRY_LARAVEL_DSN"                = "https://f612c0499f8c4839ae5d878c003f6250@o4504017868881920.ingest.sentry.io/4504017877729280"
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
    name                          = "app-eu-de"
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

    lifecycle {
        prevent_destroy = true
    }

    run_env_variables {
        reference = ibm_code_engine_config_map.app-config-map.name
        type      = "config_map_full_reference"
    }
    run_env_variables {
        reference = "app-secrets"
        type      = "secret_full_reference"
    }
    run_env_variables {
        name  = "CE_SUBDOMAIN"
        type  = "literal"
        value = "t39jcajv8pm"
    }
    run_env_variables {
        name  = "CE_APP"
        type  = "literal"
        value = "app-eu-de"
    }
    run_env_variables {
        name  = "CE_DOMAIN"
        type  = "literal"
        value = "eu-de.codeengine.appdomain.cloud"
    }

    timeouts {}
}