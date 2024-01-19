//urls
export const COMPANIES_PAGE_URL = Cypress.env('COMPANIES_PAGE_URL');
export const DASHBOARDS_PAGE_URL = Cypress.env('DASHBOARDS_PAGE_URL');
export const HOME_PAGE_URL = Cypress.env('HOME_PAGE_URL');
export const LOGIN_PAGE_URL = Cypress.env('LOGIN_PAGE_URL');
export const PW_RESET_PAGE_URL = Cypress.env('PW_RESET_PAGE_URL');
export const TARGETS_PAGE_URL = Cypress.env('TARGETS_PAGE_URL');
export const USERS_PAGE_URL = Cypress.env('USERS_PAGE_URL');
export const DYNAMICDASHBOARDS_PAGE_URL = Cypress.env('DYNAMICDASHBOARDS_PAGE_URL');
export const QUESTIONNAIRES_PAGE_URL = Cypress.env('QUESTIONNAIRES_PAGE_URL');
export const REGISTER_PAGE_URL = Cypress.env('REGISTER_PAGE_URL');
export const TAGS_PAGE_URL = Cypress.env('TAGS_PAGE_URL');
export const TASKS_PAGE_URL = Cypress.env('TASKS_PAGE_URL');
export const ROLES_PAGE_URL = Cypress.env('ROLES_PAGE_URL');

//common
export const BASE_URL = Cypress.env('BASE_URL');
export const BASE_URL_PROD = Cypress.env('BASE_URL_PROD');

export const ERROR_MSG_INCORRECT_USER = Cypress.env('ERROR_MSG_INCORRECT_USER');
export const ERROR_MSG_FIELD_EMPTY = 'Please fill out this field.';

export const VIEWPORT_WIDTH = 1280;
export const VIEWPORT_HEIGHT = 720;

//companies
export const NEW_COMPANY_NAME = 'New Company';
export const NEW_COMPANY_NAME_EDIT = 'New Company2';
export const NEW_COMPANY_VAT_NR = Cypress.env('NEW_COMPANY_VAT_NR');
export const NEW_COMPANY_FOUNDED_AT = '2009-12-12';


//pw reset
export const PW_RESET_NON_EXISTENT_EMAIL = Cypress.env('PW_RESET_NON_EXISTENT_EMAIL');
export const PW_RESET_INVALID_EMAIL = Cypress.env('PW_RESET_INVALID_EMAIL');
export const PW_RESET_FILL_OUT_THIS_FIELD = 'Please fill out this field.';
export const PW_RESET_CANT_FIND_USER = Cypress.env('PW_RESET_CANT_FIND_USER');

//sign up
export const SIGN_UP_FILL_OUT_THIS_FIELD = 'Please check this box if you want to proceed.';
export const SIGN_UP_INVALID_EMAIL_ERROR = "Please include an '@' in the email address. 'teste1.teste.com' is missing an '@'.";
export const SIGN_UP_EXISTING_EMAIL = Cypress.env('SIGN_UP_EXISTING_EMAIL')
export const SIGN_UP_INVALID_EMAIL = Cypress.env('SIGN_UP_INVALID_EMAIL')
export const SIGN_UP_INVALID_PASSWORD = Cypress.env('SIGN_UP_INVALID_PASSWORD')
export const SIGN_UP_PASSWORD_NOT_MEET_REQUIREMENTS_ERROR = Cypress.env('SIGN_UP_PASSWORD_NOT_MEET_REQUIREMENTS_ERROR');
export const SIGN_UP_EMAIL_ALREADY_EXISTS_ERROR = 'The email has already been taken.';

//dashboards
export const ACHIEVEMENTS_HEADER = ' Achievements ';
export const PRIORITY_MATRIX_HEADER = ' Action Plans - Prority Matrix ';
export const ACTION_PLANS_HEADER = ' Action Plans ';
export const DOCUMENTATION_HEADER = ' Documentation ';
export const STRATEGIC_HEADER = ' Strategic Sustainable Development Goals ';
export const GENDER_EMPLOYEES_HEADER = ' Gender Equality - Employees ';
export const GENDER_OUTSOURCED_HEADER = ' Gender Equality - Outsourced workers ';
export const GENDER_EXECUTIVES_HEADER = ' Gender Equality - Executives ';
export const GENDER_LEADERSHIP_HEADER = ' Gender Equality - Leadership ';
export const LAYOFFS_HEADER = ' Number of layoffs in last the 12 months ';
export const ANNUAL_REPORTING_HEADER = ' Annual reporting ';

//permissions
export const PERMISSIONS_USER_ALL = Cypress.env('PERMISSIONS_USER_ALL');
export const PERMISSIONS_PASSWORD_ALL = Cypress.env('PERMISSIONS_PASSWORD_ALL');
export const PERMISSIONS_USER_TARGETS_TASKS_TAGS = Cypress.env('PERMISSIONS_USER_TARGETS_TASKS_TAGS');
export const PERMISSIONS_PASSWORD_TARGETS_TASKS_TAGS = Cypress.env('PERMISSIONS_PASSWORD_TARGETS_TASKS_TAGS');

export const TARGETS_URL = Cypress.env('TARGET_URL');
export const TASKS_URL = Cypress.env('TASK_URL');
export const TAGS_URL = Cypress.env('TAG_URL');
export const DYNAMICDASHBOARD_URL = Cypress.env('DYNAMICDASHBOARD_URL');
export const REPUTATION_URL = Cypress.env('REPUTATION_URL');
export const COMPLIANCE_URL = Cypress.env('COMPLIANCE_URL');
export const USERS_URL = Cypress.env('USERS_URL');
export const LIBRARY_URL = Cypress.env('LIBRARY_URL');
export const QUESTIONNAIRES_URL = Cypress.env('QUESTIONNAIRES_URL');
export const COMPANIES_URL = Cypress.env('COMPANIES_URL');


export const DASHBOARDS_PAGE_HEADER = 'Dashboards';
export const DASHBOARDS_CREATE_URL = Cypress.env('DASHBOARDS_CREATE_URL');
export const DASHBOARDS_CREATE_PAGE_HEADER = 'Dashboards';

export const LIBRARY_PAGE_HEADER = 'Library';
export const LIBRARY_CREATE_URL = Cypress.env('LIBRARY_CREATE_URL');
export const LIBRARY_CREATE_PAGE_HEADER = 'Attachments';

export const USERS_PAGE_HEADER = Cypress.env('USERS_PAGE_HEADER');
export const USERS_SETTINGS_PAGE_HEADER = Cypress.env('USERS_SETTINGS_PAGE_HEADER');
export const USERS_CREATE_PAGE_HEADER = Cypress.env('USERS_CREATE_PAGE_HEADER');
export const USERS_SETTINGS_URL = Cypress.env('USERS_SETTINGS_URL');
export const USERS_CREATE_URL = Cypress.env('USERS_CREATE_URL');
