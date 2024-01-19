import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import TasksBehavior from '../behavior-design/TasksBehavior';
import TargetsBehavior from '../behavior-design/TargetsBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const tasksBehavior = new TasksBehavior();
const targetsBehavior = new TargetsBehavior();

describe('Tasks Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    cy.on('uncaught:exception', () => false);
    loginBehavior.successfulLogin();
  })

  it("User can create a new task", () => {
    tasksBehavior.createTask();
  });

  it("User can edit task", () => {
    tasksBehavior.editTask();
  });

  it("User can view task", () => {
    tasksBehavior.viewTask();
  });

  it("User can delete target", () => {
    targetsBehavior.deleteTarget();
  });
})
