import CommonBehavior from '../behavior-design/CommonBehavior';
import PasswordResetBehavior from '../behavior-design/PasswordResetBehavior';

const commonBehavior = new CommonBehavior();
const passwordResetBehavior = new PasswordResetBehavior();

describe('Password Reset Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
  })

  it("Successful password recovery", () => {
    passwordResetBehavior.successfulPasswordRecovery();
  });

  it("Password recovery with non-existent email address", () => {
    passwordResetBehavior.passwordRecoveryNonExistentEmail();
  });

  it("Password recovery with missing email address", () => {
    passwordResetBehavior.passwordRecoveryMissingEmail();
  });

  it("Password recovery with invalid email address", () => {
    passwordResetBehavior.passwordRecoveryInvalidEmail();
  });

})
