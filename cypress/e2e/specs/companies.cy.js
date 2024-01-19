import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import CompaniesBehavior from '../behavior-design/CompaniesBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const companiesBehavior = new CompaniesBehavior();

describe('Companies Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    cy.on('uncaught:exception', () => false);
    loginBehavior.successfulLogin();
  })

  it("User create a company successfully", () => {
    companiesBehavior.createCompany();
  });

  it("User edits a company successfully", () => {
    companiesBehavior.editCompany();
  });

  it("User views details of a created company", () => {
    companiesBehavior.viewCompany();
  });

})
