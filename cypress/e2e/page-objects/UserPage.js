import { ROLES_PAGE_URL } from "../utils/constants";

import Common from "../page-objects/Common";

const common = new Common();

export default class UserPage {
    get usersMenu() {
        return cy.get('[data-test=users-menu]');
    }

    get rolesMenu() {
        return cy.get('#app > main > div > div > div.mx-auto.max-w-7xl.sm\\:px-6.px-4.lg\\:px-0.leading-normal > div.mb-10.flex.w-full.border-b.border-\\[\\#8A8A8A\\].text-\\[\\#8A8A8A\\] > a.px-2.pb-2.text-lg.focus\\:outline-none.focus\\:border-b-2.focus\\:border-esg5.focus\\:text-esg8.hover\\:text-esg8.rounded-t-sm.active.text-esg8.border-b-2.border-esg5');
    }

    get editUserBtn() {
        return cy.get('#app > main > div > div > div.mx-auto.max-w-7xl.sm\\:px-6.px-4.lg\\:px-0.leading-normal > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(2) > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div > a.cursor-pointer.inline.px-2.py-1.text-esg28.uppercase.font-inter.font-bold.text-xs').should('be.visible');
    }

    get editUserNameInput() {
        return cy.get('#name').should('be.visible');
    }

    get createUserBtn() {
        return cy.get('[data-test=add-data-btn]').should('be.visible');
    }

    get deleteUserBtn() {
        return cy.get('#app > main > div > div > div.mx-auto.max-w-7xl.sm\\:px-6.px-4.lg\\:px-0.leading-normal > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(2) > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div > button').should('be.visible');
    }

    get editUserSaveBtn() {
        return cy.contains("Save").scrollIntoView().should('be.visible');
    }

    get createCompanySaveBtn() {
        return cy.contains("Save").should('be.visible');
    }

    get userNameInput() {
        return cy.get("#name").should('be.visible');
    }

    get userPasswordInput() {
        return cy.get("#password").should('be.visible');
    }

    get userEmailInput() {
        return cy.get("#email").should('be.visible');
    }

    get userLocaleInput() {
        return cy.get("#locale").should('be.visible');
    }

    get editRoleBtn() {
        return cy.get("#app > main > div > div > div.mx-auto.max-w-7xl.sm\\:px-6.px-4.lg\\:px-0.leading-normal > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(2) > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div.flex.flex-row.gap-1.items-center > a.cursor-pointer.inline.px-2.py-1.text-esg28.uppercase.font-inter.font-bold.text-xs").should('be.visible')
    }

    get deleteRoleBtn() {
        return cy.get("#app > main > div > div > div.mx-auto.max-w-7xl.sm\\:px-6.px-4.lg\\:px-0.leading-normal > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(2) > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div.flex.flex-row.gap-1.items-center > button").should('be.visible')
        
    }

    fillCreateUserFields() {
        this.userNameInput.type(Cypress.env('NEW_USER_NAME'));
        this.userEmailInput.type(Cypress.env('NEW_USER_EMAIL'));
        this.userPasswordInput.type(Cypress.env('NEW_USER_PASSWORD'))
        this.userLocaleInput.select("en");
        cy.contains("Save").should('be.visible').click();
    }

    navToUsersPage() {
        this.usersMenu.click({ force: true, multiple: true });
    }

    navToRolesPage() {
        this.rolesMenu.click({ force: true, multiple: true });
    }

    clickEditUserBtn() {
        this.editUserBtn.eq(0).click();
    }

    clickEditRoleBtn() {
        this.editRoleBtn.click();
    }

    clickDeleteRoleBtn() {
        this.deleteRoleBtn.click();
    }

    clickCreateUserBtn() {
        this.createUserBtn.click();
    }

    clickDeleteUserBtn() {
        this.deleteUserBtn.click();
        cy.get("#modal-container > div > div > div > div > div.mt-4.flex.justify-center.space-x-4.pb-5 > button.cursor-pointer.text-esg27.bg-esg5.ml-10.p-1\\.5.rounded.leading-3.text-uppercase.font-inter.font-bold.text-xs").click();
    }

    checkRole(rolename) {
        cy.get("#app > main > div > div > div.mx-auto.max-w-7xl.sm\\:px-6.px-4.lg\\:px-0.leading-normal > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(2) > div.text-esg29.font-encodesans.flex.h-auto.text-lg.font-bold.border-b.border-b-esg7\\/25 > span > div > a > span").should('be.visible').contains(rolename);
    }

    confirmAction() {
        cy.get("#modal-container > div > div > div > div > div.mt-4.flex.justify-center.space-x-4.pb-5 > button.cursor-pointer.text-esg27.bg-esg5.ml-10.p-1\\.5.rounded.leading-3.text-uppercase.font-inter.font-bold.text-xs").click();
    }

    editUser() {
        cy.wait(1200);
        this.navToUsersPage();
        cy.once('uncaught:exception', () => false);
        this.clickEditUserBtn();
        this.editUserNameInput.clear({ force: true });
        this.editUserNameInput.type(Cypress.env('NEW_USER_NAME'), { force: true });
        this.editUserSaveBtn.click({ force: true });
    }

    createUser() {
        cy.wait(1200);
        this.navToUsersPage();
        cy.once('uncaught:exception', () => false);
        this.clickCreateUserBtn();
        this.fillCreateUserFields();
    }

    deleteUser() {
        cy.wait(1200);
        this.navToUsersPage();
        cy.once('uncaught:exception', () => false);
        this.clickDeleteUserBtn();
        cy.get("#app > main > div > div > div.mx-auto.max-w-7xl.sm\\:px-6.px-4.lg\\:px-0.leading-normal > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(2) > div.text-esg29.font-encodesans.flex.h-auto.text-lg.font-bold.border-b.border-b-esg7\\/25 > span > div > a > span").should('not.exist');
    }

    createRole() {
        cy.wait(1200);
        this.navToUsersPage();
        cy.once('uncaught:exception', () => false);
        cy.visit('https://qatest.staging-cmore.com/roles');
        common.pageUrl().should("contain", ROLES_PAGE_URL);
        cy.get("#app > main > div > div > div.float-right > a").click();
        common.pageUrl().should("contain", ROLES_PAGE_URL+"/form");
        cy.get("#name").type('roletest');
        cy.contains("Save").should('be.visible').click();
        this.checkRole('roletest');
    }

    editRole() {
        cy.wait(1200);
        this.navToUsersPage();
        cy.once('uncaught:exception', () => false);
        cy.visit('https://qatest.staging-cmore.com/roles');
        common.pageUrl().should("contain", ROLES_PAGE_URL);
        this.clickEditRoleBtn();
        common.pageUrl().should("contain", ROLES_PAGE_URL+"/form/");
        cy.get("#name").clear({ force: true });
        cy.get("#name").type('roletest2');
        cy.contains("Save").should('be.visible').click();
        this.checkRole('roletest2');
    }

    deleteRole() {
        cy.wait(1200);
        this.navToUsersPage();
        cy.once('uncaught:exception', () => false);
        cy.visit('https://qatest.staging-cmore.com/roles');
        common.pageUrl().should("contain", ROLES_PAGE_URL);
        this.clickDeleteRoleBtn();
        cy.contains('Delete role').should('be.visible');
        this.confirmAction();
        cy.get("#app > main > div > div > div.mx-auto.max-w-7xl.sm\\:px-6.px-4.lg\\:px-0.leading-normal > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(2) > div.text-esg29.font-encodesans.flex.h-auto.text-lg.font-bold.border-b.border-b-esg7\\/25 > span > div > a > span").should('not.exist');
    }
}