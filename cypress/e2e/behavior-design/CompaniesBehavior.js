import { COMPANIES_PAGE_URL } from "../utils/constants";
import CompanyPage from "../page-objects/CompanyPage";
import Common from "../page-objects/Common";

const companyPage = new CompanyPage();
const common = new Common();

export default class CompaniesBehavior {
  editCompany() {
    companyPage.editCompany();
    common.pageUrl().should("contain", COMPANIES_PAGE_URL);
  }

  createCompany() {
    companyPage.createCompany();
    common.pageUrl().should("contain", COMPANIES_PAGE_URL);
  }

  deleteCompany() {
    companyPage.deleteCompany();
    common.pageUrl().should("contain", COMPANIES_PAGE_URL);
  }

  viewCompany() {
    companyPage.viewCompany();
    common.pageUrl().should("contain", COMPANIES_PAGE_URL);
  }
}