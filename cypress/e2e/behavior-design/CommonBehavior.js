import {
  BASE_URL,
  VIEWPORT_WIDTH,
  VIEWPORT_HEIGHT,
  BASE_URL_PROD,
} from '../utils/constants';

import Common from "../page-objects/Common";

const common = new Common();

export default class CommonBehavior {
  setupPage() {
    cy.visit(BASE_URL);
    cy.viewport(VIEWPORT_WIDTH, VIEWPORT_HEIGHT);
  }

  setupPageProd() {
    cy.visit(BASE_URL_PROD);
    cy.viewport(VIEWPORT_WIDTH, VIEWPORT_HEIGHT);
  }
}