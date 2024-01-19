import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import ReportBehavior from '../behavior-design/ReportBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const reportBehavior = new ReportBehavior();

describe('Reports Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    loginBehavior.successfulLogin();
  })

  it("Check if all svg's were correctly loaded in the page", () => {
    reportBehavior.verifySVG();
  });

  it("Check if all img's were correctly loaded in the page", () => {
    reportBehavior.verifyIMG();
  });
})
