import Common from "../page-objects/Common";
import { TARGETS_PAGE_URL, TASKS_PAGE_URL } from "../utils/constants";

const common = new Common();

export default class TargetsPage {
    get targetsMenu() {
        return cy.get('[data-test=targets-menu]');
    }

    get createTargetBtn() {
        return cy.get('[data-test=add-targets-btn]').should('be.visible');
    }

    get createTaskBtn() {
        return cy.get('#app > main > div > div > div.bg-esg4.border.border-esg7\\/70.rounded-md.p-4.w-full.mt-10 > div.text-esg8.font-encodesans.flex.justify-between.items-center.pb-5.text-lg.font-normal > div:nth-child(2) > a').should('be.visible');
    }
    get editTargetBtn() {
        return cy.get('#app > main > div > div > div.flex.flex-col.mx-auto.max-w-7xl.leading-normal.pb-10.mt-10 > div:nth-child(5) > div > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div.flex.flex-row.gap-2.items-end > div > a.cursor-pointer.inline.px-2.py-1.text-esg28.uppercase.font-inter.font-bold.text-xs').should('be.visible');
    }

    get deleteTargetBtn() {
        return cy.get('#app > main > div > div > div.flex.flex-col.mx-auto.max-w-7xl.leading-normal.pb-10.mt-10 > div:nth-child(5) > div > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div.flex.flex-row.gap-2.items-end > div > button').should('be.visible');
    }

    get targetTitleInput() {
        return cy.get("#title").should('be.visible');
    }

    get userDescriptionInput() {
        return cy.get("#app > main > div > div > div > div.bg-esg4.pb-5 > div > div > div.mt-4.w-full > div > div.h-64.min-h-\\[150px\\].\\!border-0.\\!bg-esg7\\/10.h-12.\\!text-esg8.ql-container.ql-snow > div.ql-editor.ql-blank").should('be.visible');
    }

    get targetCompanyInput() {
        return cy.get("#tomselect-1-ts-control").should('be.visible');
    }

    get targetGoalInput() {
        return cy.get("[data-test=target-goal]").should('be.visible');
    }

    get targetIndicatorInput() {
        return cy.get("#indicator").should('be.visible');
    }

    get targetDueDateInput() {
        return cy.get("#due_date").should('be.visible');
    }

    get editTargetTitleInput() {
        return cy.get('#title').should('be.visible');
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

    checkTarget() {
        cy.get('#app > main > div > div > div.flex.flex-col.mx-auto.max-w-7xl.leading-normal.pb-10.mt-10 > div:nth-child(5) > div > div.text-esg29.font-encodesans.flex.h-auto.text-lg.font-bold.border-b.border-b-esg7\\/25 > span > div > a > span').should('be.visible').contains('testetitle');
    }

    checkEditedTarget() {
        cy.get('#app > main > div > div > div.flex.flex-col.mx-auto.max-w-7xl.leading-normal.pb-10.mt-10 > div:nth-child(5) > div > div.text-esg29.font-encodesans.flex.h-auto.text-lg.font-bold.border-b.border-b-esg7\\/25 > span > div > a > span').should('be.visible').contains('testetitle2');
    }

    fillCreateTargetFields() {
        this.targetTitleInput.type('testetitle');
        this.targetCompanyInput.click();
        cy.get("#tomselect-1-opt-1").click();
        this.targetIndicatorInput.select("A organização monitoriza o consumo e utilização de água");
        this.userDescriptionInput.type('testedesc');
        this.targetGoalInput.type('testegoal');
        this.targetDueDateInput.type('2023-10-04');
        cy.contains("Save").should('be.visible').click();
    }

    fillCreateTaskFields() {
        this.taskNameInput.type('testetitle');
        this.taskDescriptionInput.type('testedesc');
        this.taskWeightInput.type('1');
        this.taskDueDateInput.type('2023-10-04');
        cy.get("#app > main > div > div > div > div.bg-esg4.pb-5 > div > div > div:nth-child(7) > div > div.mt-2.w-full > div > div > div.ts-control > div > a").click();
        cy.get("#tomselect-1-ts-control").click();
        cy.get("#tomselect-1-opt-1").click();
        cy.get("#tomselect-6-ts-control").click();
        cy.get("#tomselect-6-opt-1").click();
        cy.contains("Save").should('be.visible').click();
    }

    navToTargetsPage() {
        this.targetsMenu.click({ force: true, multiple: true });
    }

    clickCreateTargetBtn() {
        this.createTargetBtn.click();
        cy.get('#dropdown > ul > div:nth-child(1) > li > a > div').click();
    }

    clickCreateTaskBtn() {
        this.createTaskBtn.click();
    }

    clickEditTargetBtn() {
        this.editTargetBtn.click({ force: true });
    }

    clickDeleteTargetBtn() {
        this.deleteTargetBtn.click();
        cy.contains('Delete target').should('be.visible');
    }

    confirmAction() {
        cy.get('#modal-container > div > div > div > div > div.mt-4.flex.justify-center.space-x-4.pb-5 > button.cursor-pointer.text-esg27.bg-esg5.ml-10.p-1\\.5.rounded.leading-3.text-uppercase.font-inter.font-bold.text-xs').should('be.visible').click();
    }

    createTarget() {
        cy.wait(1200);
        this.navToTargetsPage();
        this.clickCreateTargetBtn();
        common.pageUrl().should("contain", TARGETS_PAGE_URL+"/form");
        this.fillCreateTargetFields();
        this.checkTarget();
    }

    editTarget()  {
        cy.wait(1200);
        this.navToTargetsPage();
        this.checkTarget();
        this.clickEditTargetBtn();
        common.pageUrl().should("contain", TARGETS_PAGE_URL+"/form");
        this.editTargetTitleInput.clear({ force: true });
        this.editTargetTitleInput.type('testetitle2', { force: true });
        cy.contains("Save").should('be.visible').click();
        this.checkEditedTarget();
    }

    deleteTarget()  {
        cy.wait(1200);
        this.navToTargetsPage();
        this.checkEditedTarget();
        this.clickDeleteTargetBtn();
        this.confirmAction();
        cy.get('#app > main > div > div > div.flex.flex-col.mx-auto.max-w-7xl.leading-normal.pb-10.mt-10 > div:nth-child(4)').should('not.exist');
    }

    createTaskInTarget() {
        cy.wait(1200);
        this.navToTargetsPage();
        this.clickCreateTaskBtn();
        common.pageUrl().should("contain", TASKS_PAGE_URL+"/form?entity=targets");
        this.fillCreateTaskFields();
    }
}