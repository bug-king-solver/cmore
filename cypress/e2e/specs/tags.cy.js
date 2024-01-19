import CommonBehavior from '../behavior-design/CommonBehavior';
import LoginBehavior from '../behavior-design/LoginBehavior';
import TagsBehavior from '../behavior-design/TagsBehavior';

const commonBehavior = new CommonBehavior();
const loginBehavior = new LoginBehavior();
const tagsBehavior = new TagsBehavior();

describe('Tags Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    loginBehavior.successfulLogin();
  })

// CAB: # User can create a new tag
// CAB: # User can assign a tag to a company
// CAB: # User can assign a tag to a questionnaire

  it("User can create a new tag", () => {
    tagsBehavior.createTag();
  });

  it("User can edit tag", () => {
    tagsBehavior.editTag();
  });

  it("User can delete tag", () => {
    tagsBehavior.deleteTag();
  });

  it("User can restore a tag", () => {
    tagsBehavior.restoreTag();
  });

})
