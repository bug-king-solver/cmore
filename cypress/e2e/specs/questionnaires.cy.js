import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import QuestionnairesBehavior from '../behavior-design/QuestionnairesBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const questionnairesBehavior = new QuestionnairesBehavior();

describe('Questionnaires Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    loginBehavior.successfulLogin();
  })

  it("User delete a questionnaire previously created", () => {
    questionnairesBehavior.deleteQuestionnaire();
  });

  it("User create a questionnaire successfully", () => {
    questionnairesBehavior.createQuestionnaire();
  });

  it("User verify only radio button check negative ", () => {
    questionnairesBehavior.radioTestNegative();
  });

  it("User verify only radio button check positive ", () => {
    questionnairesBehavior.radioTestPositive();
  });

  it("User verify all Add Comments ", () => {
    questionnairesBehavior.addComment();
  });
})