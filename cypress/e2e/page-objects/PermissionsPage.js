import { TARGETS_URL, TASKS_URL, TAGS_URL, DYNAMICDASHBOARD_URL, REPUTATION_URL, QUESTIONNAIRES_URL, COMPANIES_URL,  USERS_URL, LIBRARY_URL 
} from "../utils/constants";

export default class PermissionsPage {
    navToPageByMenu(datatest) {
        cy.get("[data-test="+datatest+"]").click({ force: true, multiple: true });
    }

    navToPageByURL(url) {
        cy.visit(url);
    }

    successPage(title) {
        cy.get('#app > div > header > div > div > div > h1').should("contain", title);
    }

    allPermissions() {
        cy.wait(1200);
        this.navToPageByMenu('targets-menu');
        this.successPage('Targets');
        this.navToPageByURL(TARGETS_URL);
        this.successPage('Targets');

        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');

        this.navToPageByMenu('tags-menu');
        this.successPage('List of Tags');
        this.navToPageByURL(TAGS_URL);
        this.successPage('List of Tags');

        this.navToPageByMenu('dashboards-menu');
        // this.successPage('Dashboards');
        this.navToPageByURL(DYNAMICDASHBOARD_URL);
        // this.successPage('Dashboards');

        this.navToPageByMenu('reputation-menu');
        this.successPage('Reputational Analysis');
        this.navToPageByURL(REPUTATION_URL);
        this.successPage('Reputational Analysis');

        this.navToPageByMenu('users-menu');
        this.successPage('List of Users');
        this.navToPageByURL(USERS_URL);
        this.successPage('List of Users');

        this.navToPageByMenu('library-menu');
        this.successPage('Library');
        this.navToPageByURL(LIBRARY_URL);
        this.successPage('Library');

        this.navToPageByMenu('questionnaires-menu');
        this.successPage('Questionnaires');
        this.navToPageByURL(QUESTIONNAIRES_URL);
        this.successPage('Questionnaires');

        this.navToPageByMenu('companies-menu');
        this.successPage('List of Companies');
        this.navToPageByURL(COMPANIES_URL);
        this.successPage('List of Companies');
    }

    noTargets(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
    }

    noTasks(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');

    }

    noTags(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
    }

    noDashboard() {
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
    }

    noReputation() {
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
    }

    noCompliance() {
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
    }

    dashboardVUC(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('companies-menu');
        this.successPage('List of Companies');
        this.navToPageByURL(COMPANIES_URL);
        this.successPage('List of Companies');
    }

    dashboardVU(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('companies-menu');
        this.successPage('List of Companies');
        this.navToPageByURL(COMPANIES_URL);
        this.successPage('List of Companies');
    }

    dashboardV(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('companies-menu');
        this.successPage('List of Companies');
        this.navToPageByURL(COMPANIES_URL);
        this.successPage('List of Companies');
    }

    libraryVUC(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('library-menu');
        this.successPage('Library');
        this.navToPageByURL(LIBRARY_URL);
        this.successPage('Library');
    }

    libraryVU(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('library-menu');
        this.successPage('Library');
        this.navToPageByURL(LIBRARY_URL);
        this.successPage('Library');
    }

    libraryV(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('library-menu');
        this.successPage('Library');
        this.navToPageByURL(LIBRARY_URL);
        this.successPage('Library');
    }

    companiesVUC(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('companies-menu');
        this.successPage('List of Companies');
        this.navToPageByURL(COMPANIES_URL);
        this.successPage('List of Companies');
    }

    companiesVU(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('companies-menu');
        this.successPage('List of Companies');
        this.navToPageByURL(COMPANIES_URL);
        this.successPage('List of Companies');
    }

    companiesV(){
        this.navToPageByMenu('tasks-menu');
        this.successPage('To-Do List');
        this.navToPageByURL(TASKS_URL);
        this.successPage('To-Do List');
        this.navToPageByMenu('companies-menu');
        this.successPage('List of Companies');
        this.navToPageByURL(COMPANIES_URL);
        this.successPage('List of Companies');
    }
}