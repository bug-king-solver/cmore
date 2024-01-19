import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import UsersBehavior from '../behavior-design/UsersBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const usersBehavior = new UsersBehavior();

describe('Users Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    cy.on('uncaught:exception', () => false);
    loginBehavior.successfulLogin();
  })

  it("User create a user successfully", () => {
    usersBehavior.createUser();
  });

  it("User edits a user successfully", () => {
    usersBehavior.editUser();
  });

  it("User delete a user successfully", () => {
    usersBehavior.deleteUser();
  });

  it("User can create a new role", () => {
    usersBehavior.createRole();
  });

  it("User can edit a role", () => {
    usersBehavior.editRole();
  });

  it("User can delete a role", () => {
    usersBehavior.deleteRole();
  });
})
