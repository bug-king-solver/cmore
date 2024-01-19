import UserPage from "../page-objects/UserPage";
import Common from "../page-objects/Common";
import { USERS_PAGE_URL, ROLES_PAGE_URL } from "../utils/constants";

const userPage = new UserPage();
const common = new Common();

export default class UsersBehavior {
  editUser(){
    userPage.editUser();
    common.pageUrl().should("contain", USERS_PAGE_URL);
  }

  createUser(){
    userPage.createUser();
    common.pageUrl().should("contain", USERS_PAGE_URL);
  }

  deleteUser(){
    userPage.deleteUser();
    common.pageUrl().should("contain", USERS_PAGE_URL);
  }

  createRole(){
    userPage.createRole();
    common.pageUrl().should("contain", ROLES_PAGE_URL);
  }

  editRole(){
    userPage.editRole();
    common.pageUrl().should("contain", ROLES_PAGE_URL);
  }

  deleteRole(){
    userPage.deleteRole();
    common.pageUrl().should("contain", ROLES_PAGE_URL);
  }
}