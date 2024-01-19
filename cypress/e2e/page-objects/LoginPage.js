import { ERROR_MSG_FIELD_EMPTY, ERROR_MSG_INCORRECT_USER } from "../utils/constants";

export default class LoginPage {
    get userInput() {
        return cy.get('#username').should('be.enabled');
    }

    get passwordInput() {
        return cy.get('#password').should('be.enabled');
    }

    get loginBtn() {
        return cy.get('body > div.grid.grid-cols-1.md\\:grid-cols-2 > div.relative > main > div.mx-auto.flex.flex-col.items-center > div.sm\\:mx-auto.sm\\:w-full.sm\\:max-w-md.w-\\[360px\\].md\\:w-\\[360px\\] > div > form > div.mt-5.w-full.text-center > button').should('be.enabled');
    }

    loginCorrectUsernameAndPassword(LOGIN_USERNAME, LOGIN_PASSWORD) {
        this.userInput.type(LOGIN_USERNAME, { force: true });
        this.passwordInput.type(LOGIN_PASSWORD, { force: true });
        this.loginBtn.click();
        cy.once('uncaught:exception', () => false);
    }

    loginIncorrectUsername() {
        this.userInput.type(Cypress.env('LOGIN_USERNAME_INCORRECT'), { force: true });
        this.passwordInput.type(Cypress.env('LOGIN_PASSWORD'), { force: true });
        this.loginBtn.click();
        cy.once('uncaught:exception', () => false);
        cy.verifyLoginError(ERROR_MSG_INCORRECT_USER);
    }

    loginIncorrectPassword() {
        this.userInput.type(Cypress.env('LOGIN_USERNAME'), { force: true });
        this.passwordInput.type(Cypress.env('LOGIN_PASSWORD_INCORRECT'), { force: true });
        this.loginBtn.click();
        cy.once('uncaught:exception', () => false);
        cy.verifyLoginError(ERROR_MSG_INCORRECT_USER);
    }

    loginMissingUsername() {
        this.passwordInput.type(Cypress.env('LOGIN_PASSWORD_INCORRECT'), { force: true });
        this.loginBtn.click();
        cy.once('uncaught:exception', () => false);
        // Assert that the login failed
        cy.on("window:alert", (str) => {
            expect(str).to.equal(ERROR_MSG_FIELD_EMPTY);
        });
    }

    loginMissingPassword() {
        this.userInput.type(Cypress.env('LOGIN_USERNAME'), { force: true });
        this.loginBtn.click();
        cy.once('uncaught:exception', () => false);
        // Assert that the login failed
        cy.on("window:alert", (str) => {
            expect(str).to.equal(ERROR_MSG_FIELD_EMPTY);
        });
    }
}