import { PW_RESET_NON_EXISTENT_EMAIL, PW_RESET_INVALID_EMAIL, PW_RESET_FILL_OUT_THIS_FIELD, PW_RESET_CANT_FIND_USER } from "../utils/constants";

export default class PasswordResetPage {
    get forgotPassword(){
        return cy.get("[data-test=forgot-pw]").should('be.visible');
    }

    get emailInput(){
        return cy.get("#email").should('be.enabled');
    }

    get submitBtn(){
        return cy.contains("Submit").should('be.visible');
    }

    get checkEmail(){
        return cy.contains("Check your email").should('be.visible');
    }

    get cantFindEmail(){
        return cy.contains(PW_RESET_CANT_FIND_USER).should('be.visible');
    }
    
    successfulPasswordRecovery(){
        this.forgotPassword.click({ force: true });
        cy.wait(1500);
        this.emailInput.type(Cypress.env('LOGIN_USERNAME'));
        this.submitBtn.click({ force: true });
        cy.wait(1500);
        this.checkEmail.should("be.visible");
    }

    passwordRecoveryNonExistentEmail(){
        this.forgotPassword.click({ force: true });
        cy.wait(1500);
        this.emailInput.type(PW_RESET_NON_EXISTENT_EMAIL);
        this.submitBtn.click({ force: true });
        cy.wait(1500);
        this.cantFindEmail;
    }

    passwordRecoveryMissingEmail(){
        this.forgotPassword.click({ force: true });
        cy.wait(1500);
        this.submitBtn.click({ force: true });
        cy.wait(1500);
        cy.on('window:message', (event) => {
            expect(event.data).to.equal(PW_RESET_FILL_OUT_THIS_FIELD);
        });   
    }

    passwordRecoveryInvalidEmail(){
        this.forgotPassword.click({ force: true });
        cy.wait(1500);
        this.emailInput.type(PW_RESET_INVALID_EMAIL);
        this.submitBtn.click({ force: true });
        cy.wait(1500);
        this.cantFindEmail;
    }
}