import SignUpPage from "../page-objects/SignUpPage";
import Common from "../page-objects/Common";
import { REGISTER_PAGE_URL, HOME_PAGE_URL } from "../utils/constants";

const signUpPage = new SignUpPage();
const common = new Common();

export default class SignUpBehavior {
  registrationMissingRequiredFields() {
    cy.wait(1200);
    signUpPage.registrationMissingRequiredFields();
    common.pageUrl().should("contain", REGISTER_PAGE_URL);
  }


  registrationInvalidEmailAddress() {
      cy.wait(1200);
      signUpPage.registrationInvalidEmailAddress();
      common.pageUrl().should("contain", REGISTER_PAGE_URL);
  }

  registrationPasswordNotMeetRequirements() {
      cy.wait(1200);
      signUpPage.registrationPasswordNotMeetRequirements();
      common.pageUrl().should("contain", REGISTER_PAGE_URL);
  }

  registrationExistingEmail() {
      cy.wait(1200);
      signUpPage.registrationExistingEmail();
      common.pageUrl().should("contain", REGISTER_PAGE_URL);
  }

  successfulRegistration() {
      cy.wait(1200);
      signUpPage.successfulRegistration();
      common.pageUrl().should("contain", HOME_PAGE_URL);
  }

}