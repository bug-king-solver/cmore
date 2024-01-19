import TasksPage from "../page-objects/TasksPage";
import Common from "../page-objects/Common";
import { TASKS_PAGE_URL } from "../utils/constants";

const tasksPage = new TasksPage();
const common = new Common();

export default class TasksBehavior {
  createTask(){
    tasksPage.createTask();
    common.pageUrl().should("contain", TASKS_PAGE_URL);
  }

  editTask(){
    tasksPage.editTask();
    common.pageUrl().should("contain", TASKS_PAGE_URL);
  }

  viewTask(){
    tasksPage.viewTask();
    common.pageUrl().should("contain", "/user/my-todo-list/3");
  }

  assignTaskToTarget(){
    tasksPage.assignTaskToTarget();
    common.pageUrl().should("contain", TASKS_PAGE_URL);
  }
}