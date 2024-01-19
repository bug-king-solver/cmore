import { SIGN_UP_FILL_OUT_THIS_FIELD, SIGN_UP_INVALID_EMAIL, SIGN_UP_INVALID_PASSWORD, SIGN_UP_PASSWORD_NOT_MEET_REQUIREMENTS_ERROR, SIGN_UP_EXISTING_EMAIL, SIGN_UP_EMAIL_ALREADY_EXISTS_ERROR } from "../utils/constants";

export default class SignUpPage {

    get nameInput () { 
        return cy.get("#name").should('be.visible');
    }

    get emailInput () { 
        return cy.get("#email").should('be.visible');
    }

    get passwordInput () { 
        return cy.get("#password").should('be.visible');
    }

    get passwordConfirmationInput () { 
        return cy.get("#password_confirmation").should('be.visible');
    }

    get createAccBtn () { 
        return cy.contains("Create account").should('be.visible');
    }

    get confirmTermsAndConditions() { 
        return cy.get('[id="terms"]').should('be.visible');
    }

    get passwordNotMeetRequirementsLabel() { 
        return cy.get(
            "body > div.grid.grid-cols-1.md\\:grid-cols-2 > div.relative > main > div.mx-auto.flex.flex-col.items-center > div.sm\\:mx-auto.sm\\:w-full.sm\\:max-w-md.w-\\[360px\\].lg\\:w-\\[360px\\].md\\:w-\\[320px\\] > form > div:nth-child(4) > div.relative.z-0.w-full.group > p"
        ).should('be.visible').contains(SIGN_UP_PASSWORD_NOT_MEET_REQUIREMENTS_ERROR);
    }

    get existentEmailLabel() { 
        return cy.get(
            "body > div.grid.grid-cols-1.md\\:grid-cols-2 > div.relative > main > div.mx-auto.flex.flex-col.items-center > div.sm\\:mx-auto.sm\\:w-full.sm\\:max-w-md.w-\\[360px\\].lg\\:w-\\[360px\\].md\\:w-\\[320px\\] > form > div:nth-child(3) > div.relative.z-0.w-full.group > p"
        ).should('be.visible').contains(SIGN_UP_EMAIL_ALREADY_EXISTS_ERROR);
    }

    navToRegisterPage() { 
        return cy.get('[data-test="register-btn"]').should('be.visible').click({ force: true });
    }


    registrationMissingRequiredFields() {
        this.navToRegisterPage();
        cy.once('uncaught:exception', () => false);
        this.nameInput.type(Cypress.env('SIGN_UP_VALID_USERNAME'));
        this.emailInput.type(Cypress.env('SIGN_UP_VALID_EMAIL'));
        this.passwordInput.type(Cypress.env('SIGN_UP_VALID_PASSWORD'));
        this.passwordConfirmationInput.type(Cypress.env('SIGN_UP_VALID_PASSWORD'));
        //cy.get("#terms").check({ force: true });
      
        this.createAccBtn.click({ force: true });
      
        cy.on("window:alert", (str) => { 
          expect(str).to.equal(SIGN_UP_FILL_OUT_THIS_FIELD);
        });
    }

    registrationInvalidEmailAddress() {
        this.navToRegisterPage();
        cy.once('uncaught:exception', () => false);
        this.nameInput.type(Cypress.env('SIGN_UP_VALID_USERNAME'));
        this.emailInput.type(Cypress.env('SIGN_UP_INVALID_EMAIL'));
        this.passwordInput.type(Cypress.env('SIGN_UP_VALID_PASSWORD'));
        this.passwordConfirmationInput.type(Cypress.env('SIGN_UP_VALID_PASSWORD'));
        this.confirmTermsAndConditions.check();
        this.createAccBtn.click({ force: true });
      
        cy.on("window:alert", (str) => { 
          expect(str).to.equal(SIGN_UP_FILL_OUT_THIS_FIELD);
        });
    }

    registrationPasswordNotMeetRequirements() {
        this.navToRegisterPage();
        cy.once('uncaught:exception', () => false);
        this.nameInput.type(Cypress.env('SIGN_UP_VALID_USERNAME'));
        this.emailInput.type(Cypress.env('SIGN_UP_VALID_EMAIL'));
        this.passwordInput.type(Cypress.env('SIGN_UP_INVALID_PASSWORD'));
        this.passwordConfirmationInput.type(Cypress.env('SIGN_UP_INVALID_PASSWORD'));
        this.confirmTermsAndConditions.check();
        this.createAccBtn.click({ force: true });
        this.passwordNotMeetRequirementsLabel;
    }

    registrationExistingEmail(){
        this.navToRegisterPage();
        cy.once('uncaught:exception', () => false);
        this.nameInput.type(Cypress.env('SIGN_UP_VALID_USERNAME'));
        this.emailInput.type(SIGN_UP_EXISTING_EMAIL);
        this.passwordInput.type(Cypress.env('SIGN_UP_VALID_PASSWORD'));
        this.passwordConfirmationInput.type(Cypress.env('SIGN_UP_VALID_PASSWORD'));
        this.confirmTermsAndConditions.check();
        this.createAccBtn.click({ force: true });
        this.existentEmailLabel;
    }

    successfulRegistration(){
        this.navToRegisterPage();
        cy.once('uncaught:exception', () => false);
        this.nameInput.type(SIGN_UP_VALID_USERNAME);
        this.emailInput.type(SIGN_UP_VALID_EMAIL);
        this.passwordInput.type(SIGN_UP_VALID_PASSWORD);
        this.passwordConfirmationInput.type(SIGN_UP_VALID_PASSWORD);
        this.confirmTermsAndConditions.check();
        this.createAccBtn.click({ force: true });
    }
}