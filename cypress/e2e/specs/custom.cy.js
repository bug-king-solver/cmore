import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import QuestionnairesBehavior from '../behavior-design/QuestionnairesBehavior';
import CompaniesBehavior from '../behavior-design/CompaniesBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const questionnairesBehavior = new QuestionnairesBehavior();
const companiesBehavior = new CompaniesBehavior();

describe('Custom tests', () => {
  beforeEach(() => {
    commonBehavior.setupPageProd();
    loginBehavior.successfulLoginProd();
  })

  
  it("User create a custom questionnaire and check report results - test case 1", () => {
    questionnairesBehavior.createScreenQuestionnaire1();
  });

  it("User create a custom questionnaire and check report results - test case 2", () => {
    questionnairesBehavior.createScreenQuestionnaire2();
  });

  it("User create a custom questionnaire and check report results - test case 3", () => {
    questionnairesBehavior.createScreenQuestionnaire3();
  });

  it.skip("User delete a company successfully", () => {
    companiesBehavior.deleteCompany();
  });

})