import { NEW_COMPANY_NAME, NEW_COMPANY_NAME_EDIT, NEW_COMPANY_VAT_NR, NEW_COMPANY_FOUNDED_AT } from "../utils/constants";

export default class CompanyPage {
    get companiesMenu() {
        return cy.get('[data-test=companies-menu]');
    }

    get editCompanyBtn() {
        return cy.get('#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div > a.cursor-pointer.inline.px-2.py-1.text-esg28.uppercase.font-inter.font-bold.text-xs').should('be.visible');
    }

    get createCompanyBtn() {
        return cy.get('[data-test=add-data-btn]').should('be.visible');
    }

    get viewCompanyBtn() {
        return cy.get('#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div > a.cursor-pointer.inline.py-1.px-2.text-sm.cursor-pointer.view').should('be.visible');
    }

    get editCompanySaveBtn() {
        return cy.contains("Save").scrollIntoView().should('be.visible');
    }

    get deleteCompanyConfirmBtn() {
        return cy.contains("Confirm").should('be.visible');
    }

    get createCompanySaveBtn() {
        return cy.contains("Save").should('be.visible');
    }

    get companyCommercialNameInput() {
        return cy.get("#commercial_name").should('be.visible');
    }

    get companyNameInput() {
        return cy.get("#name").should('be.visible');
    }

    get businessSectorInput() {
        return cy.get("#tomselect-3-ts-control").should('be.visible');
    }

    get companyCountry() {
        return cy.get("#tomselect-4-ts-control").should('be.visible');
    }

    get companyVATCountry() {
        return cy.get("#tomselect-5-ts-control").should('be.visible');
    }

    get companyVATNumber() {
        return cy.get("#vat_number").should('be.visible');
    }

    get companySize() {
        return cy.get("#tomselect-6-ts-control").should('be.visible');
    }

    get companyFoundedAt() {
        return cy.get("#founded_at").should('be.visible');
    }

    get createCompanyChangeColor() {
        return cy.get("#modal-container > div > div > div.bg-esg4.px-4.pt-5.pb-5.sm\\:p-6.sm\\:pb-4 > div > div > div:nth-child(13) > div.mt-4.w-2\\/3 > div > button").should('be.visible');
    }

    get createCompanyColorPicker() {
        return cy.get("#modal-container > div > div > div.bg-esg4.px-4.pt-5.pb-5.sm\\:p-6.sm\\:pb-4 > div > div > div:nth-child(13) > div.mt-4.w-2\\/3 > div > button > div").should('be.visible');
    }

    get companyCommercialName() {
        return cy.get('#app > main > div > div > div.text-esg8.px-4.lg\\:px-0 > div > table > tbody > tr:nth-child(2) > td:nth-child(1)').scrollIntoView().should('be.visible');
    }

    get companyToDelete() {
        return cy.get('#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-4.gap-9 > div > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div > button').should('be.visible');
    }

    fillCreateCompanyFields() {
        this.companyNameInput.clear({ force: true });
        this.companyNameInput.type(NEW_COMPANY_NAME, { force: true });
        this.companyCommercialNameInput.clear({ force: true });
        this.companyCommercialNameInput.type(NEW_COMPANY_NAME, { force: true });
        this.businessSectorInput.click({ force: true, multiple: true });
        cy.get("#tomselect-3-opt-1").click();
        this.companyCountry.click({ force: true, multiple: true });
        cy.get("#tomselect-4-opt-1").click();
        this.companyVATCountry.click({ force: true, multiple: true });
        cy.get("#tomselect-5-opt-1").click();
        this.companyVATNumber.type(NEW_COMPANY_VAT_NR);
        this.companySize.click({ force: true, multiple: true });
        cy.get("#tomselect-6-opt-1").click();
        this.companyFoundedAt.type(NEW_COMPANY_FOUNDED_AT);
        //this.createCompanyChangeColor.click({ force: true });
        //this.createCompanyColorPicker.type('{enter}');
        //this.companySize.select("Micro");
    }

    checkCompany() {
        cy.contains(NEW_COMPANY_NAME_EDIT).should('be.visible');
        cy.contains(NEW_COMPANY_VAT_NR).should('be.visible');
    }

    navToCompaniesPage() {
        this.companiesMenu.click({ force: true, multiple: true });
    }

    clickEditCompanyBtn() {
        this.editCompanyBtn.click();
    }

    clickCreateCompanyBtn() {
        this.createCompanyBtn.click();
    }

    viewCompanyEye() {
        this.viewCompanyBtn.click({ force: true });
    }

    editCompany() {
        cy.wait(1200);
        this.navToCompaniesPage();
        this.clickEditCompanyBtn();
        this.companyCommercialNameInput.clear({ force: true });
        this.companyCommercialNameInput.type(NEW_COMPANY_NAME_EDIT, { force: true });
        this.editCompanySaveBtn.click({ force: true });
    }

    createCompany() {
        cy.wait(1200);
        this.navToCompaniesPage();
        this.clickCreateCompanyBtn();
        this.fillCreateCompanyFields();
        this.createCompanySaveBtn.click({ force: true });
        cy.wait(1200);
        //this.checkCompany();
    }

    deleteCompany() {
        cy.wait(1200);
        this.navToCompaniesPage();
        this.companyToDelete.click({ force: true });
        this.deleteCompanyConfirmBtn.click({ force: true });
    }

    viewCompany() {
        cy.wait(1200);
        this.navToCompaniesPage();
        this.viewCompanyEye();
        cy.get("#app > main > div > div > div.flex.justify-between.items-center.mt-2 > div.text-lg.font-bold.text-esg5").should('be.visible').contains(NEW_COMPANY_NAME)
        //this.checkCompany();
    }
}