import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import TargetsBehavior from '../behavior-design/TargetsBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const targetsBehavior = new TargetsBehavior();

describe('Targets Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    cy.on('uncaught:exception', () => false);
    loginBehavior.successfulLogin();
  })

  it("User can create a new target", () => {
    targetsBehavior.createTarget();
  });

  // it("User can edit target", () => {
  //   targetsBehavior.editTarget();
  // });

  // it("User can create a task in a target", () => {
  //   targetsBehavior.createTaskInTarget();
  // });

})
