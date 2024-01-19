import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();

describe('Login Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    cy.on('uncaught:exception', () => false);
  })

  it("Login with incorrect username", () => {
    loginBehavior.loginIncorrectUsername();
  });

  it("Login with incorrect password", () => {
    loginBehavior.loginIncorrectPassword();
  });

  it("Login with missing username", () => {
    loginBehavior.loginMissingUsername();
  });

  it("Login with missing password", () => {
    loginBehavior.loginMissingPassword();
  });

  it("Successful Login", () => {
    loginBehavior.successfulLogin();
  });
})
