// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Create a custom command in Cypress
Cypress.Commands.add('verifyLoginError', (errorMsg) => {
    cy.get('body > div.grid.grid-cols-1.md\\:grid-cols-2 > div.relative > main > div.mx-auto.flex.flex-col.items-center > div.sm\\:mx-auto.sm\\:w-full.sm\\:max-w-md.w-\\[360px\\].md\\:w-\\[360px\\] > div > form > div:nth-child(2) > div.relative.z-0.mb-2.w-full.group > p').should('be.visible').should('contain.text', errorMsg);
});

Cypress.Commands.add('logout', () => {
    cy.get('#app > nav > div > div > div:nth-child(4) > div > div.relative.z-10.md\\:ml-14 > div.flex.items-center > button').click({ force: true, multiple: true });
    cy.get('#app > nav > div > div > div:nth-child(4) > div > div.relative.z-10.md\\:ml-14 > div.absolute.-right-4.mt-2.rounded-md.shadow-lg.text-right.w-\\[100vw\\].md\\:w-max.box-border > div > a:nth-child(3)').click({ force: true, multiple: true });
});



