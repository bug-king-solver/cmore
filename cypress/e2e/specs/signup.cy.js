import {
  BASE_URL
} from '../utils/constants';

import CommonBehavior from '../behavior-design/CommonBehavior';
import SignUpBehavior from '../behavior-design/SignUpBehavior';

const commonBehavior = new CommonBehavior();
const signUpBehavior = new SignUpBehavior();

describe('SignUp Page', () => {
  beforeEach(() => {
    commonBehavior.setupPage();
    cy.on('uncaught:exception', () => false);
  })

  it("Registration with missing required fields", () => {
    signUpBehavior.registrationMissingRequiredFields();
  });

  it("Registration with invalid email address", () => {
    signUpBehavior.registrationInvalidEmailAddress();
  });

  it("Registration with password that doesn't meet requirements", () => {
    signUpBehavior.registrationPasswordNotMeetRequirements();
  });

  it("Registration with existing email address", () => {
    signUpBehavior.registrationExistingEmail();
  });

  // it("Successful registration", () => {
  //   signUpBehavior.successfulRegistration();
  // });


})
