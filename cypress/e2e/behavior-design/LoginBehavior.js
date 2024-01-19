import LoginPage from "../page-objects/LoginPage";
import Common from "../page-objects/Common";
import { HOME_PAGE_URL, LOGIN_PAGE_URL, PERMISSIONS_USER_ALL, PERMISSIONS_PASSWORD_ALL, PERMISSIONS_USER_TARGETS_TASKS_TAGS, PERMISSIONS_PASSWORD_TARGETS_TASKS_TAGS } from "../utils/constants";

const loginPage = new LoginPage();
const common = new Common();

export default class LoginBehavior {

  successfulLoginPermUserALL() {
    cy.wait(1200);
    loginPage.loginCorrectUsernameAndPassword(PERMISSIONS_USER_ALL, PERMISSIONS_PASSWORD_ALL);
    common.pageUrl().should("contain", Cypress.env('HOME_PAGE_URL'));
  }

  successfulLoginPermUserno_TargetsTasksTags() {
    cy.wait(1200);
    loginPage.loginCorrectUsernameAndPassword(PERMISSIONS_USER_TARGETS_TASKS_TAGS, PERMISSIONS_PASSWORD_TARGETS_TASKS_TAGS);
    common.pageUrl().should("contain", Cypress.env('HOME_PAGE_URL'));
  }

  successfulLogin() {
    cy.wait(1200);
    loginPage.loginCorrectUsernameAndPassword(Cypress.env('LOGIN_USERNAME'), Cypress.env('LOGIN_PASSWORD'));
    common.pageUrl().should("contain", Cypress.env('HOME_PAGE_URL'));
  }

  successfulLoginProd() {
    cy.wait(1200);
    loginPage.loginCorrectUsernameAndPassword(Cypress.env('LOGIN_USERNAME_PROD'), Cypress.env('LOGIN_PASSWORD_PROD'));
    common.pageUrl().should("contain", Cypress.env('HOME_PAGE_URL'));
  }

  loginIncorrectUsername() {
    cy.wait(1200);
    loginPage.loginIncorrectUsername();
    common.pageUrl().should("contain", Cypress.env('LOGIN_PAGE_URL'));
  }

  loginIncorrectPassword() {
      cy.wait(1200);
      loginPage.loginIncorrectPassword();
      common.pageUrl().should("contain", Cypress.env('LOGIN_PAGE_URL'));
  }

  loginMissingUsername() {
      cy.wait(1200);
      loginPage.loginMissingUsername();
      common.pageUrl().should("contain", Cypress.env('LOGIN_PAGE_URL'));
  }

  loginMissingPassword() {
      cy.wait(1200);
      loginPage.loginMissingPassword();
      common.pageUrl().should("contain", Cypress.env('LOGIN_PAGE_URL'));
  }
}