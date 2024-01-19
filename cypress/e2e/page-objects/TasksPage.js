import Common from "../page-objects/Common";
import { TASKS_PAGE_URL, USERS_PAGE_URL } from "../utils/constants";
const common = new Common();

export default class TasksPage {
    get tasksMenu() {
        return cy.get('[data-test=tasks-menu]');
    }

    get createTaskBtn() {
        return cy.get('[data-test=add-data-btn]').should('be.visible');
    }

    get editTaskBtn() {
        return cy.get('#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(1) > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div.flex.flex-row.gap-2.items-end > div > a.cursor-pointer.inline.px-2.py-1.text-esg28.uppercase.font-inter.font-bold.text-xs').should('be.visible');
    }

    get viewTaskBtn() {
        return cy.get('#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(3) > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div.flex.flex-row.gap-2.items-end > div > a.cursor-pointer.inline.py-1.px-2.text-sm.cursor-pointer').should('be.visible');
    }

    get taskNameInput() {
        return cy.get("[data-test=task-name]").should('be.visible');
    }

    get taskDescriptionInput() {
        return cy.get("#app > main > div > div > div > div.bg-esg4.pb-5 > div > div > div.mt-4.w-full > div > div.h-64.min-h-\\[150px\\].\\!border-0.\\!bg-esg7\\/10.h-12.\\!text-esg8.ql-container.ql-snow > div.ql-editor.ql-blank").should('be.visible');
    }

    get taskWeightInput() { 
        return cy.get("#weight").should('be.visible');
    }

    get taskDueDateInput() {
        return cy.get("[data-test=task-due-date]").should('be.visible');
    }

    get editTaskTitleInput() {
        return cy.get('#name').should('be.visible');
    }

    get taskName() {
        return cy.get("#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(1) > div.text-esg29.font-encodesans.flex.h-auto.text-lg.font-bold.border-b.border-b-esg7\\/25 > span > div > a > span").should('be.visible')
    }

    checkTask() {
        cy.contains("#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9").should('be.visible').contains('testetitle');
    }


    fillCreateTaskFields() {
        this.taskNameInput.type('testetitle');
        this.taskDescriptionInput.type('testedesc');
        this.taskWeightInput.type('1');
        this.taskDueDateInput.type('2023-10-04');
        cy.get("#tomselect-1-ts-control").click();
        cy.get("#tomselect-1-opt-2").click();
        cy.get("#tomselect-5-ts-control").click();
        cy.get("#tomselect-5-opt-1").click();
        cy.contains("Save").should('be.visible').click();
    }

    navToTasksPage() {
        this.tasksMenu.click({ force: true, multiple: true });
    }

    clickCreateTaskBtn() {
        this.createTaskBtn.click();
    }

    clickEditTaskBtn() {
        this.editTaskBtn.click();
    }

    clickViewTaskBtn() {
        this.viewTaskBtn.click();
    }

    createTask() {
        cy.wait(1200);
        this.navToTasksPage();
        this.clickCreateTaskBtn();
        common.pageUrl().should("contain", TASKS_PAGE_URL+"/form");
        this.fillCreateTaskFields();
        this.taskName.contains('testetitle');
    }

    editTask()  {
        cy.wait(1200);
        this.navToTasksPage();
        this.clickEditTaskBtn();
        cy.get("#app > main > div > div > div > div.bg-esg4.pb-5 > div > div > div:nth-child(7) > div > div.mt-2.w-full > div > div > div.ts-control").click();
        cy.get("#tomselect-1-opt-1").click();
        cy.get("#tomselect-5-ts-control").click();
        cy.get("#tomselect-5-opt-1").click();
        this.editTaskTitleInput.clear({ force: true });
        this.editTaskTitleInput.type('testetitle2', { force: true });
        cy.contains("Save").should('be.visible').click();
    }

    viewTask()  {
        cy.wait(1200);
        this.navToTasksPage();
        this.clickViewTaskBtn();
        cy.get("#app > main > div > div > div > div.flex.pt-6.item-center > div.w-full.text-esg5.text-base.font-bold").should('be.visible').contains('testetitle2');
    }

    assignTaskToTarget()  {
        cy.wait(1200);
        this.navToTasksPage();
        this.taskName.contains('testetitle2');
        this.clickEditTaskBtn();
        common.pageUrl().should("contain", USERS_PAGE_URL+"/tasks/form/3");
        cy.get("#app > main > div > div > div > div.bg-esg4.pb-5 > div > div > div:nth-child(7) > div > div.mt-2.w-full > div > div > div.ts-control").click();
        cy.get("#tomselect-1-opt-1").click();
        cy.get("#tomselect-5-ts-control").click();
        cy.get("#tomselect-5-opt-1").click();
        cy.contains("Save").should('be.visible').click();
        this.taskName.contains('testetitle2');
    }
}