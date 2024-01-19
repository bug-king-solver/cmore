import TagsPage from "../page-objects/TagsPage";
import Common from "../page-objects/Common";
import { TAGS_PAGE_URL } from "../utils/constants";

const tagsPage = new TagsPage();
const common = new Common();

export default class TagsBehavior {
  createTag(){
    tagsPage.createTag();
    common.pageUrl().should("contain", TAGS_PAGE_URL);
  }

  editTag(){
    tagsPage.editTag();
    common.pageUrl().should("contain", TAGS_PAGE_URL);
  }

  deleteTag(){
    tagsPage.deleteTag();
    common.pageUrl().should("contain", TAGS_PAGE_URL);
  }

  restoreTag(){
    tagsPage.restoreTag();
    common.pageUrl().should("contain", TAGS_PAGE_URL);
  }
}