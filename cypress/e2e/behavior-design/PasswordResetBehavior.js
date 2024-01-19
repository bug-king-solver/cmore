import PasswordResetPage from "../page-objects/PasswordResetPage";
import Common from "../page-objects/Common";

const passwordResetPage = new PasswordResetPage();
const common = new Common();

export default class PasswordResetBehavior {
  successfulPasswordRecovery() {
      cy.wait(1200);
      passwordResetPage.successfulPasswordRecovery();
      common.pageUrl().should("contain", "/password/reset");
  }

  passwordRecoveryNonExistentEmail() {
    cy.wait(1200);
    passwordResetPage.passwordRecoveryNonExistentEmail();
    common.pageUrl().should("contain", "/password/reset");
  }

  passwordRecoveryMissingEmail() {
    cy.wait(1200);
    passwordResetPage.passwordRecoveryMissingEmail();
    common.pageUrl().should("contain", "/password/reset");
  }

  passwordRecoveryInvalidEmail() {
    cy.wait(1200);
    passwordResetPage.passwordRecoveryInvalidEmail();
    common.pageUrl().should("contain", "/password/reset");
  }
}