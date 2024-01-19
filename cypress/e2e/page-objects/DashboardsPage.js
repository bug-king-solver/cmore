import {
    ACHIEVEMENTS_HEADER, PRIORITY_MATRIX_HEADER, ACTION_PLANS_HEADER, DOCUMENTATION_HEADER,
    STRATEGIC_HEADER, GENDER_EMPLOYEES_HEADER, GENDER_OUTSOURCED_HEADER, GENDER_EXECUTIVES_HEADER,
    GENDER_LEADERSHIP_HEADER, LAYOFFS_HEADER, ANNUAL_REPORTING_HEADER
} from "../utils/constants";

export default class DashboardsPage {
    get questionnairesMenu() {
        return cy.get('[data-test=questionnaires-menu]');
    }

    get dashboardBtn() {
        return cy.get('[data-test=result-questionnaire-btn]');
    }

    get achievementsHeader() {
        return cy.get(
            "#app > main > div > div > div > div.mt-5.pagebreak > div:nth-child(1) > div > div.absolute.w-full.pl-4.pr-4.-ml-4 > div > p"
        ).scrollIntoView()
            .should('be.visible').contains(ACHIEVEMENTS_HEADER);
    }

    get priorityMatrixHeader() {
        return cy.get( 
            "#app > main > div > div > div > div.mt-5.pagebreak > div.grid.grid-cols-1.lg\\:grid-cols-2.mt-10.gap-5 > div:nth-child(1) > div.absolute.w-full.pl-4.pr-4.-ml-4 > div > p"
        ).scrollIntoView()
            .should('be.visible').contains(PRIORITY_MATRIX_HEADER);
    }

    get actionPlansHeader() {
        return cy.get(
            "#app > main > div > div > div > div.mt-5.pagebreak > div.grid.grid-cols-1.lg\\:grid-cols-2.mt-10.gap-5 > div:nth-child(2) > div.absolute.w-full.pl-4.pr-4.-ml-4 > div > p"
        ).scrollIntoView()
            .should('be.visible').contains(ACTION_PLANS_HEADER);
    }

    get documentationHeader() {
        return cy.get(
            "#app > main > div > div > div > div:nth-child(3) > div > div.absolute.w-full.pl-4.pr-4.-ml-4 > div > p"
        ).scrollIntoView()
            .should('be.visible').contains(DOCUMENTATION_HEADER);
    }

    get strategicHeader() {
        return cy.get(
            "#app > main > div > div > div > div:nth-child(4) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div > div.absolute.w-full.pl-4.pr-4.-ml-4 > div > p"
        ).scrollIntoView()
            .should('be.visible').contains(STRATEGIC_HEADER);
    }

    get genderEmployeesHeader() {
        return cy.get(
            "#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(1) > div.absolute.w-full.pl-4.pr-4.-ml-4 > div.text-esg8.font-encodesans.flex.flex-col.text-base.font-bold > p"
        ).scrollIntoView()
            .should('be.visible').contains(GENDER_EMPLOYEES_HEADER);
    }

    get genderOutsourcedHeader() {
        return cy.get(
            "#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(2) > div.absolute.w-full.pl-4.pr-4.-ml-4 > div.text-esg8.font-encodesans.flex.flex-col.text-base.font-bold > p"
        ).scrollIntoView()
            .should('be.visible').contains(GENDER_OUTSOURCED_HEADER);
    }

    get genderExecutivesHeader() {
        return cy.get(
            "#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(3) > div.absolute.w-full.pl-4.pr-4.-ml-4 > div.text-esg8.font-encodesans.flex.flex-col.text-base.font-bold > p"
        ).scrollIntoView()
            .should('be.visible').contains(GENDER_EXECUTIVES_HEADER);
    }

    get genderLeadershipHeader() {
        return cy.get(
            "#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(4) > div.absolute.w-full.pl-4.pr-4.-ml-4 > div.text-esg8.font-encodesans.flex.flex-col.text-base.font-bold > p"
        ).scrollIntoView()
            .should('be.visible').contains(GENDER_LEADERSHIP_HEADER);
    }

    get layoffsHeader() {
        return cy.get(
            "#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(5) > div.absolute.w-full.pl-4.pr-4.-ml-4 > div > p"
        ).scrollIntoView()
            .should('be.visible').contains(LAYOFFS_HEADER);
    }

    get annualReportingHeader() {
        return cy.get(
            "#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div:nth-child(1) > div.absolute.w-full.pl-4.pr-4.-ml-4 > div > p"
        ).scrollIntoView()
            .should('be.visible').contains(ANNUAL_REPORTING_HEADER);
    }

    navToQuestionnairesPage() {
        this.questionnairesMenu.click({ force: true, multiple: true });
    }

    navToDashboardPage() {
        this.dashboardBtn.click({ force: true, multiple: true });
    }

    validateDashboard() {
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.navToDashboardPage();

        this.achievementsHeader;
        this.priorityMatrixHeader;
        this.actionPlansHeader;
        this.documentationHeader;
        this.strategicHeader;
        this.genderEmployeesHeader;
        this.genderOutsourcedHeader;
        this.genderExecutivesHeader;
        this.genderLeadershipHeader;
        this.layoffsHeader;
        this.annualReportingHeader;
    }

    verifyCharts() {
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.navToDashboardPage();
        cy.get("svg").each(($svg) => {
            cy.wrap($svg)
                .invoke("text")
                .then((text) => {
                    expect(text).not.to.include("No data available");
                });
        });
    }
}
