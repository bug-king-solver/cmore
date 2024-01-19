module.exports = {
    watchForFileChanges: false,
    e2e: {
        setupNodeEvents(on, config) {
            // implement node event listeners here

        },
    },
    env: {
        COMPANIES_PAGE_URL: "/companies",
        DASHBOARDS_PAGE_URL: "/dashboards",
        HOME_PAGE_URL: "/home",
        LOGIN_PAGE_URL: "/login",
        PW_RESET_PAGE_URL: "/password/reset",
        TARGETS_PAGE_URL: "/targets",
        USERS_PAGE_URL: "/users",
        DYNAMICDASHBOARDS_PAGE_URL: "/dynamic-dashboard",
        QUESTIONNAIRES_PAGE_URL: "/questionnaires",
        REGISTER_PAGE_URL: "/register",
        TAGS_PAGE_URL: "/tags",
        TASKS_PAGE_URL: "/tasks",
        ROLES_PAGE_URL: "/roles",
        LOGIN_PAGE_URL: "/login",
        LOGIN_USERNAME: "testadmin@test.com",
        LOGIN_USERNAME_PROD: "joaopequeno1996@gmail.com",
        LOGIN_USERNAME_INCORRECT: "testadmin@test.comm",
        LOGIN_PASSWORD: "hgzCwUtCZj!XAt7",
        LOGIN_PASSWORD_PROD: "JoaoPESG111$$,",
        LOGIN_PASSWORD_INCORRECT: "M3zHCU575z6Qq",

        NEW_USER_NAME: "e2e test user",
        NEW_USER_EMAIL: "e2etest@email.com",
        NEW_USER_PASSWORD: "Teste123@",

        SIGN_UP_VALID_USERNAME: "teste",
        SIGN_UP_VALID_EMAIL: "teste2@teste.com",
        SIGN_UP_EXISTING_EMAIL: "testadmin@test.com",
        SIGN_UP_INVALID_EMAIL: "teste1.teste.com",
        SIGN_UP_VALID_PASSWORD: "Teste123@@@",
        SIGN_UP_INVALID_PASSWORD: "test",

        SIGN_UP_PASSWORD_NOT_MEET_REQUIREMENTS_ERROR: "The password should be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.",

        PERMISSIONS_USER_ALL: "teste1@teste.com",
        PERMISSIONS_PASSWORD_ALL: "Teste123@@@",
        PERMISSIONS_USER_TARGETS_TASKS_TAGS: "teste5@teste.com",
        PERMISSIONS_PASSWORD_TARGETS_TASKS_TAGS: "Teste123@@@",

        NEW_COMPANY_VAT_NR: "123123123",

        PW_RESET_NON_EXISTENT_EMAIL: "nonexistingmail@test.com",
        PW_RESET_INVALID_EMAIL: "testttt@test.com",

        SIGN_UP_EXISTING_EMAIL: "testadmin@test.com",
        SIGN_UP_INVALID_EMAIL: "teste1.teste.com",

        TARGET_URL: "https://qatest.staging-cmore.com/targets",
        TASK_URL: "https://qatest.staging-cmore.com/user/tasks",
        TAG_URL: "https://qatest.staging-cmore.com/tags",
        DYNAMICDASHBOARD_URL: "https://qatest.staging-cmore.com/dynamic-dashboard",
        REPUTATION_URL: "https://qatest.staging-cmore.com/reputation",
        COMPLIANCE_URL: "https://qatest.staging-cmore.com/compliance/document_analysis",
        USERS_URL: "https://qatest.staging-cmore.com/users",
        LIBRARY_URL: "https://qatest.staging-cmore.com/library",
        QUESTIONNAIRES_URL: "https://qatest.staging-cmore.com/questionnaires/panel",
        COMPANIES_URL: "https://qatest.staging-cmore.com/companies",

        DASHBOARDS_CREATE_URL: "https://qatest.staging-cmore.com/dynamic-dashboard/create",
        LIBRARY_CREATE_URL: "https://qatest.staging-cmore.com/library/internal/attachments",
        USERS_SETTINGS_URL: "https://qatest.staging-cmore.com/users/form/4",
        USERS_CREATE_URL: "https://qatest.staging-cmore.com/users/form",

        USERS_SETTINGS_PAGE_HEADER: "User",
        USERS_CREATE_PAGE_HEADER: "User",
        USERS_PAGE_HEADER: "List of Users"


        PW_RESET_CANT_FIND_USER: "We can't find a user with that email address.",
        ERROR_MSG_INCORRECT_USER: "These credentials do not match our records.",
    },
};
