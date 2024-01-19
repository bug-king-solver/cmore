import { DASHBOARDS_PAGE_URL } from "../utils/constants";
import DashboardsPage from "../page-objects/DashboardsPage";
import Common from "../page-objects/Common";

const dashboardsPage = new DashboardsPage();
const common = new Common();

export default class UsersBehavior {
  validateDashboard(){
    dashboardsPage.validateDashboard();
    common.pageUrl().should("contain", DASHBOARDS_PAGE_URL);
  }

  verifyCharts(){
    dashboardsPage.verifyCharts();
    common.pageUrl().should("contain", DASHBOARDS_PAGE_URL);
  }
}