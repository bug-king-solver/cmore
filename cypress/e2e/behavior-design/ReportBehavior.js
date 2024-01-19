import ReportPage from "../page-objects/ReportPage";
import Common from "../page-objects/Common";

const reportPage = new ReportPage();
const common = new Common();

export default class ReportBehavior {
  verifySVG(){
    reportPage.verifySVG();
  }

  verifyIMG(){
    reportPage.verifyIMG();
  }
}