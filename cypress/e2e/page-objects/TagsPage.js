import Common from "../page-objects/Common";
import { TAGS_PAGE_URL } from "../utils/constants";

const common = new Common();

export default class TagsPage {
    get tagsMenu() {
        return cy.get('[data-test=tags-menu]');
    }

    get createTagBtn() {
        return cy.get('[data-test=add-tags-btn]').should('be.visible');
    }

    get tagChangeColor() {
        return cy.get("#color").should('be.visible');
    }

    get tagColorPicker() {
        return cy.get("#modal-container > div > div > div.bg-esg4.px-4.pt-5.pb-5.sm\\:p-6.sm\\:pb-4 > div > div > div:nth-child(2) > div.mt-1.w-2\\/3 > div > div > div.picker_wrapper.layout_default.no_cancel.popup.popup_right > div.picker_done > button").should('be.visible');
    }

    get editTagBtn() {
        return cy.get("#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(1) > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div > button.cursor-pointer.inline.px-2.py-1.text-esg28.uppercase.font-inter.font-bold.text-xs.cursor-pointer").should('be.visible');
    }

    get deleteTagBtn() {
        return cy.get("#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div:nth-child(1) > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > div > button.cursor-pointer.inline.py-1.px-2.text-sm.cursor-pointer").should('be.visible');
    }

    get restoreTagBtn() {
        return cy.get("#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div > div.border-t.border-esg7\\/25.w-full.flex.flex-col.justify-center.bottom-0 > div > div > button").should('be.visible');
    }

    get tagNameInput() {
        return cy.get("#name").should('be.visible');
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

    get tagLabel() {
        return cy.get("#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div > div.text-esg29.font-encodesans.w-full.h-auto > div > div:nth-child(3) > div.text-esg16.text-xs.font-medium").should('be.visible');
    }

    get tagName() {
        return cy.get("#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9 > div > div.text-esg29.font-encodesans.flex.h-auto.text-lg.font-bold.border-b.border-b-esg7\\/25 > span > div > a > span").should('be.visible')
    }

    get confirmActionBtn()  {
        return cy.get('#modal-container > div > div > div > div > div.mt-4.flex.justify-center.space-x-4.pb-5 > button.cursor-pointer.text-esg27.bg-esg5.ml-10.p-1\\.5.rounded.leading-3.text-uppercase.font-inter.font-bold.text-xs').should('be.visible');
    }
    
    checkTask() {
        cy.contains("#app > main > div > div > div.grid.grid-cols-1.md\\:grid-cols-3.gap-9").should('be.visible').contains('testetitle');
    }

    fillCreateTagFields() {
        this.tagNameInput.type('testetitle');
        this.tagChangeColor.click({ force: true });
        this.tagColorPicker.click({ force: true });
    }

    navToTagsPage() {
        this.tagsMenu.click({ force: true, multiple: true });
    }

    clickCreateTagBtn() {
        this.createTagBtn.click();
    }

    clickEditTagBtn() {
        this.editTagBtn.click({ force: true });
    }

    clickDeleteTagBtn() {
        this.deleteTagBtn.click();
    }

    clickRestoreTagBtn() {
        this.restoreTagBtn.click();
    }

    confirmAction() {
        this.confirmActionBtn.click();
    }
    

    createTag() {
        cy.wait(1200);
        this.navToTagsPage();
        this.clickCreateTagBtn();
        common.pageUrl().should("contain", TAGS_PAGE_URL);
        this.fillCreateTagFields();
        cy.contains("Save").should('be.visible').click();
        this.tagName.contains('testetitle');
        this.tagLabel.contains('Active');
    }

    editTag()  {
        cy.wait(1200);
        this.navToTagsPage();
        this.tagName.contains('testetitle');
        this.tagLabel.contains('Active');
        this.clickEditTagBtn();
        this.tagNameInput.clear({ force: true });
        this.tagNameInput.type('testetitle2', { force: true });
        cy.contains("Save").should('be.visible').click();
        this.tagName.contains('testetitle2');
        this.tagLabel.contains('Active');
    }

    deleteTag()  {
        cy.wait(1200);
        this.navToTagsPage();
        this.tagName.contains('testetitle2');
        this.tagLabel.contains('Active');
        this.clickDeleteTagBtn();
        this.confirmAction();
        this.tagLabel.contains('Deleted');
    }

    restoreTag()  {
        cy.wait(1200);
        this.navToTagsPage();
        this.tagName.contains('testetitle2');
        this.tagLabel.contains('Deleted');
        this.clickRestoreTagBtn();
        this.confirmAction();
        this.tagLabel.contains('Active');
    }
}