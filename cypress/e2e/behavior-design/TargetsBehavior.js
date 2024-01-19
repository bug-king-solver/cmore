import TargetsPage from "../page-objects/TargetsPage";
import Common from "../page-objects/Common";
import { TARGETS_PAGE_URL, TASKS_PAGE_URL } from "../utils/constants";

const targetsPage = new TargetsPage();
const common = new Common();

export default class TargetsBehavior {
  createTarget(){
    targetsPage.createTarget();
    common.pageUrl().should("contain", TARGETS_PAGE_URL);
  }

  editTarget(){
    targetsPage.editTarget();
    common.pageUrl().should("contain", TARGETS_PAGE_URL);
  }

  deleteTarget(){
    targetsPage.deleteTarget();
    common.pageUrl().should("contain", TARGETS_PAGE_URL);
  }

  createTaskInTarget(){
    targetsPage.createTaskInTarget();
    common.pageUrl().should("contain", "/user"+TASKS_PAGE_URL);
  }
}