import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import PermissionsBehavior from '../behavior-design/PermissionsBehavior';
import CompaniesBehavior from '../behavior-design/CompaniesBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const permissionsBehavior = new PermissionsBehavior();
const companiesBehavior = new CompaniesBehavior();

describe('Permissions', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    cy.on('uncaught:exception', () => false);
  })

  it("User with all permisions", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.allPermissions();
  });

  it("Tenants  without targets feature - access by url and menu", () => {
    loginBehavior.successfulLoginPermUserno_TargetsTasksTags();
    permissionsBehavior.noTargets();
  });

  it("Tenants  without tasks feature - access by url and menu", () => {
    loginBehavior.successfulLoginPermUserno_TargetsTasksTags();
    permissionsBehavior.noTasks();
  });

  it("Tenants  without tags feature - access by url and menu", () => {
    loginBehavior.successfulLoginPermUserno_TargetsTasksTags();
    permissionsBehavior.noTags();
  });

  it("Tenants  without dynamic dashboard - access by url and menu", () => {
    loginBehavior.successfulLoginPermUserno_TargetsTasksTags();
    permissionsBehavior.noDashboard();
  });

  it("Tenants  without reputation - access by url and menu", () => {
    loginBehavior.successfulLoginPermUserno_TargetsTasksTags();
    permissionsBehavior.noReputation();
  });

  it("Tenants  without compliance - access by url and menu", () => {
    loginBehavior.successfulLoginPermUserno_TargetsTasksTags();
    permissionsBehavior.noCompliance();
  });

  it("User with Dashboard > All permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.allPermissions();
  });

  it("User with Dashboard > Only view, update and create permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.dashboardVUC();
  });

  it("User with Dashboard > Only view and update permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.dashboardVU();
  });

  it("User with Dashboard > Only view permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.dashboardV();
  });

  it("User with Library > All permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.allPermissions();
  });

  it("User with Library > Only view, update and create permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.libraryVUC();
  });

  it("User with Library > Only view and update permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.libraryVU();
  });

  it("User with Library > Only view permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.libraryV();
  });

  it("User with Companies > All permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.allPermissions();
  });

  it("User with Companies > Only view, update and create permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.companiesVUC();
  });

  it("User with Companies > Only view and update permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.companiesVU();
  });

  it("User with Companies > Only view permission", () => {
    loginBehavior.successfulLoginPermUserALL();
    permissionsBehavior.companiesV();
  });

  it("User delete a company successfully", () => {
    companiesBehavior.deleteCompany();
  });
})
