import { PERMISSIONS_USER, PERMISSIONS_PASSWORD } from "../utils/constants";

import PermissionsPage from "../page-objects/PermissionsPage";
import Common from "../page-objects/Common";
import LoginPage from "../page-objects/LoginPage";

const loginPage = new LoginPage();
const permissionsPage = new PermissionsPage();
const common = new Common();

export default class PermissionsBehavior {

  allPermissions(){
    permissionsPage.allPermissions();
    //common.pageUrl().should("contain", "/targets");
  }

  noTargets(){
    permissionsPage.noTargets();
  }

  noTasks(){
    permissionsPage.noTasks();
  }

  noTags(){
    permissionsPage.noTags();
  }

  noDashboard(){
    permissionsPage.noDashboard();
  }

  noReputation(){
    permissionsPage.noReputation();
  }

  noCompliance(){
    permissionsPage.noCompliance();
  }

  dashboardVUC(){
    permissionsPage.dashboardVUC();
  }

  dashboardVU(){
    permissionsPage.dashboardVU();
  }

  dashboardV(){
    permissionsPage.dashboardV();
  }

  libraryVUC(){
    permissionsPage.libraryVUC();
  }

  libraryVU(){
    permissionsPage.libraryVU();
  }

  libraryV(){
    permissionsPage.libraryV();
  }

  companiesVUC(){
    permissionsPage.companiesVUC();
  }

  companiesVU(){
    permissionsPage.companiesVU();
  }

  companiesV(){
    permissionsPage.companiesV();
  }
}