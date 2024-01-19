import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import DashboardsBehavior from '../behavior-design/DashboardsBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const dashboardsBehavior = new DashboardsBehavior();

describe('Dashboards Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    loginBehavior.successfulLogin();
  })

  it("Validate dashboards", () => {
    dashboardsBehavior.validateDashboard();
  });

  it("Verify if all svg items are displayed", () => {
    dashboardsBehavior.verifyCharts();
  });
})
