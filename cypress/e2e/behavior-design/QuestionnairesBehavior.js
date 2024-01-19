import QuestionnairesPage from "../page-objects/QuestionnairesPage";
import Common from "../page-objects/Common";
import { QUESTIONNAIRES_PAGE_URL } from "../utils/constants";

const questionnairesPage = new QuestionnairesPage();
const common = new Common();

export default class QuestionnairesBehavior {
  deleteQuestionnaire(){
    questionnairesPage.deleteQuestionnaire();
    common.pageUrl().should("contain", QUESTIONNAIRES_PAGE_URL);
  }

  createQuestionnaire(){
    questionnairesPage.createQuestionnaire();
    common.pageUrl().should("contain", QUESTIONNAIRES_PAGE_URL+"/welcome");
  }

  replyQuestionnaireYes(){
    questionnairesPage.replyQuestionnaireYes();
  }

  radioTestNegative(){
    questionnairesPage.radioTestNegative();
  }

  radioTestPositive(){
    questionnairesPage.radioTestPositive();
  }

  addComment(){
    questionnairesPage.addComment();
  }

  attachFile(){
    questionnairesPage.attachFile();
  }

  assignUser(){
    questionnairesPage.assignUser();
  }

  assignValidator(){
    questionnairesPage.assignValidator();
  }

  completeQuestionnaireNegative(){
    questionnairesPage.completeQuestionnaireNegative();
  }

  completeQuestionnairePositive(){
    questionnairesPage.completeQuestionnairePositive();
  }

  createScreenQuestionnaire1(){
    questionnairesPage.createScreenQuestionnaire1();
  }

  createScreenQuestionnaire2(){
    questionnairesPage.createScreenQuestionnaire2();
  }

  createScreenQuestionnaire3(){
    questionnairesPage.createScreenQuestionnaire3();
  }

}


