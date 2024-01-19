export default class ReportPage {
    get questionnairesMenu() {
        return cy.get('[data-test=questionnaires-menu]');
    }

    get dashboardBtn() {
        return cy.get('[data-test=result-questionnaire-btn]');
    }

    navToQuestionnairesPage() {
        this.questionnairesMenu.click({ force: true, multiple: true });
    }

    navToDashboardPage() {
        this.dashboardBtn.click({ force: true, multiple: true });
    }

    navToReportsPage() {
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.navToDashboardPage();

        //go to report
        cy.get(":nth-child(2) > .inline-flex").click({ force: true });
    }

    verifySVG() {
        cy.wait(1200);
        this.navToReportsPage();

        cy.get("svg").each(($svg) => {
            cy.wrap($svg).should("have.attr", "data-testid");
        });
    }

    verifyIMG() {
        cy.wait(1200);
        this.navToReportsPage();

        cy.get("img").each(($img) => {
            cy.wrap($img)
                .should("be.visible")
                .and(($img) => {
                    expect($img[0].naturalWidth).to.be.greaterThan(0);
                });
        });
    }
}