export default class QuestionnairesPage {
    get reportMenu(){
        return cy.get('[data-test=questionnaires-menu]');
    }

    get questionnairesMenu(){
        return cy.get('[data-test=questionnaires-menu]');
    }

    get createQuestionnaireBtn(){
        return cy.get('[data-test=add-questionnaires-btn]').should('be.visible');
    }

    get continueQuestionnaireBtn(){
        return cy.get('[data-test=continue-questionnaire-btn]').should('be.visible');
    }

    get questionnaireToDelete() {
        return cy.get('[data-test=delete-questionnaire-btn]').should('be.visible');
    }

    get deleteQuestionnaireConfirmBtn(){
        return cy.contains("Confirm").should('be.visible');
    }

    get createQuestionnaireSaveBtn(){
        return cy.contains("Save").should('be.visible');
    }

    navToQuestionnairesPage() {
        this.reportMenu.click({ force: true ,multiple: true });
        this.questionnairesMenu.click({ force: true, multiple: true });
    }

    clickCreateQuestionnaireBtn() {
        this.createQuestionnaireBtn.click();
    }

    fillCreateQuestionnaireFields() {
        this.createQuestionnaireSaveBtn.click({ force: true });
    }

    partQuestionnaire() {
        cy.visit("https://test.esg-maturity.com/questionnaires/108");
        cy.wait(1000);
    }

    deleteQuestionnaire(){
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.questionnaireToDelete.eq(1).click({ force: true });
        this.deleteQuestionnaireConfirmBtn.click({ force: true });
    }

    createQuestionnaire(){
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.clickCreateQuestionnaireBtn();
        this.fillCreateQuestionnaireFields();
    }

    replyQuestionnaireYes(){
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.continueQuestionnaireBtn.eq(0).click({ force: true });
        this.replyYesToAllQuestions();
    }

    radioTestNegative(){
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.partQuestionnaire();
        cy.get("input[type=radio][value=no]").click({ force: true, multiple: true });
    }

    radioTestPositive(){
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.partQuestionnaire();
        cy.get("input[type=radio][value=yes]").click({ force: true, multiple: true });
    }

    addComment() {
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.partQuestionnaire();

        for (let i = 1; i <= 124; i++) {
          const selector = `.px-4:nth-child(${i}) .relative:nth-child(1) > .flex`;
          cy.get(selector).click({ force: true, multiple: true });
          cy.get(".CodeMirror textarea").type("test", {
            force: true,
            multiple: true,
          });
          cy.get(".py-1\\.5").click({ force: true, multiple: true });
          cy.get(".comments-form-inner").submit({ force: true, multiple: true });
        }
      }

    createScreenQuestionnaire1(){
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.clickCreateQuestionnaireBtn();
        cy.get('#type').select("Screen");
        cy.get('[data-test=save-btn]').click();
        cy.wait(10000);

        cy.get("#app > main > div > div > div > div.flex-1.p-4 > div.w-full.justify-between.grid.grid-cols-1.gap-20.md\\:grid-cols-3 > div:nth-child(1) > h2 > a").click();

        cy.get("#question-32170 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32171 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32172 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32173 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2001')
        cy.get("#question-32174 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32175 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32175 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('25')
        cy.get("#question-32175 > div > div:nth-child(2) > div > div > div:nth-child(4) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32175 > div > div:nth-child(2) > div > div > div:nth-child(5) > input").type('42')
        cy.get("#question-32176 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32177 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('45')
        cy.get("#question-32178 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('72')
        cy.get("#question-32179 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('51')
        cy.get("#question-32180 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32181 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32182 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2008')
        cy.get("#question-32183 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1502')
        cy.get("#question-32184 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('220')
        cy.get("#question-32185 > div > div:nth-child(2) > div > textarea").type('These are the non-renewable sources used')
        cy.get("#question-32186 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32187 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1722')
        cy.get("#question-32188 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('154')
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(4) > label > input").check();
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(5) > input").type('154');
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(6) > label > input").check();
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(7) > input").type('0');
        cy.get("#question-32190 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32191 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.get("#question-32191 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('23')
        cy.get("#question-32191 > div > div:nth-child(2) > div > div > div:nth-child(4) > label > input").check();
        cy.get("#question-32191 > div > div:nth-child(2) > div > div > div:nth-child(5) > input").type('0');
        cy.get("#question-32192 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32193 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2010')
        cy.get("#question-32194 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32195 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2437')
        cy.get("#question-32196 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32197 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1922')
        cy.get("#question-32198 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32199 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('22')
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(10) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(11) > input").type('43')
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(15) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(16) > input").type('9')
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(25) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(26) > input").type('1')
        cy.get("#question-32201 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32204 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32207 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32212 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32214 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32215 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32216 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32217 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32225 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32226 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32231 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32232 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32235 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32236 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32240 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32241 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32241 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('145')
        cy.get("#question-32241 > div > div:nth-child(2) > div > div > div:nth-child(4) > label > input").check();
        cy.get("#question-32241 > div > div:nth-child(2) > div > div > div:nth-child(5) > input").type('78')
        cy.wait(2000)

        //next
        cy.get("#app > main > div > div > div > div.md\\:ml-4.md\\:pl-20.sm\\:col-span-3 > div.border-t-esg6.text-esg29.mb-8.grid.grid-cols-2.border-t-\\[1px\\].pt-7.px-4.lg\\:px-0 > div.text-right > a").click();

        cy.get("#question-32165 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32166 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('20')
        cy.get("#question-32166 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('10')
        cy.get("#question-32166 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('1')
        cy.get("#question-32167 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32168 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('52');
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('11');
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('2');
        cy.wait(2000)
        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('2');
        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('3');
        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('0');
        cy.wait(2000)
        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('3');
        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('3');
        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('1');
        cy.wait(2000)
        cy.get("#question-32224 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('15');
        cy.get("#question-32243 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32244 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1522')
        cy.get("#question-32245 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1601')
        cy.wait(2000)
        cy.get("#question-32248 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32249 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('35697')
        cy.get("#question-32250 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('23475')
        cy.wait(2000)
        cy.get("#question-32251 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32252 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('1')
        cy.get("#question-32252 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('2')
        cy.get("#question-32252 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('0')
        cy.wait(2000)
        cy.get("#question-32255 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32256 > div > div:nth-child(2) > div > p:nth-child(2) > label > input").check();
        cy.get("#question-32256 > div > div:nth-child(2) > div > p:nth-child(4) > label > input").check();
        cy.get("#question-32257 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32258 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();

        //next
        cy.get("#app > main > div > div > div > div.md\\:ml-4.md\\:pl-20.sm\\:col-span-3 > div.border-t-esg6.text-esg29.mb-8.grid.grid-cols-2.border-t-\\[1px\\].pt-7.px-4.lg\\:px-0 > div.text-right > a").click();

        cy.get("#question-32169 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32203 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32205 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32208 > div > div:nth-child(2) > div > label:nth-child(6) > input").check();
        cy.get("#question-32209 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('2')
        cy.get("#question-32209 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('1')
        cy.get("#question-32209 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('0')
        cy.get("#question-32210 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32213 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32219 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32220 > div > div:nth-child(2) > div > p:nth-child(2) > label > input").check();
        cy.get("#question-32220 > div > div:nth-child(2) > div > p:nth-child(7) > label > input").check();
        cy.get("#question-32221 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32222 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2')
        cy.get("#question-32223 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('4')
        cy.wait(2000)
        cy.get("#question-32227 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32228 > div > div:nth-child(2) > div > p:nth-child(4) > label > input").check();
        cy.get("#question-32228 > div > div:nth-child(2) > div > p:nth-child(9) > label > input").check();
        cy.get("#question-32229 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32230 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32234 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32238 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32239 > div > div:nth-child(2) > div > p:nth-child(4) > label > input").check();
        cy.get("#question-32239 > div > div:nth-child(2) > div > p:nth-child(8) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32246 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32247 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32253 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32254 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32259 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32260 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32261 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32262 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2256.2')
        cy.wait(2000)
        cy.get("#question-32263 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32264 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32265 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32266 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('5')
        cy.wait(2000)
        cy.get("#question-32267 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32268 > div > div:nth-child(2) > div > p:nth-child(2) > label > input").check();
        cy.get("#question-32268 > div > div:nth-child(2) > div > p:nth-child(6) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32269 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32271 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32272 > div > div:nth-child(2) > div > p:nth-child(2) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32273 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32274 > div > div:nth-child(2) > div > p:nth-child(2) > label > input").check();
        cy.get("#question-32274 > div > div:nth-child(2) > div > p:nth-child(4) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32278 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1000586');
        cy.get("#question-32279 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('100425');
        cy.get("#question-32280 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('256667');
        cy.get("#question-32281 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('5007687');
        cy.get("#question-32282 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('20458');
        cy.get("#question-32283 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('10325');
        cy.get("#question-32284 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('87085');
        cy.get("#question-32285 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('105647');
        cy.get("#question-32286 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('111255');
        cy.get("#question-32287 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('552325');
        cy.get("#question-32288 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('125423');
        cy.get("#question-32289 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('52878');
        cy.get("#question-32290 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1545578');
        cy.get("#question-32291 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1212546');
        cy.wait(2000)
        cy.get("#question-32292 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32293 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('42.3')
        cy.get("#question-32294 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('188576')
        cy.get("#question-32295 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > div.relative > input").type('10.98')
        cy.get("#question-32296 > div > div:nth-child(2) > div > p > label > input").check();

        // nav again to questionnaires page
        cy.wait(5000);
        this.navToQuestionnairesPage();

        //search by all questionnaires - go to last questionnaire and submit
        cy.get("#filterBarComponent > div.flex.flex-row.flex-wrap.gap-5.justify-items-center.mb-5 > div.flex.flex-row.items-center.h-\\[40px\\].w-fit > div > div.w-full.h-full.flex.flex-row.items-center.justify-between.ml-1 > a").click();
        cy.get("#tomselect-1-opt-1 > input[type=checkbox]").check();
        cy.get("#app > main > div > div > div:nth-child(4) > div > nav > div.hidden.sm\\:flex.sm\\:flex-1.sm\\:items-center.sm\\:justify-between > div:nth-child(2) > span > span:nth-child(5) > button").click();
        cy.get("#app > main > div > div > div:nth-child(4) > div > nav > div.hidden.sm\\:flex.sm\\:flex-1.sm\\:items-center.sm\\:justify-between > div:nth-child(2) > span > span:nth-child(5) > button").click();
        cy.contains("Submeter").should('be.visible').click();
        cy.get("#modal-container > div > div > div > div.mt-4.flex.justify-center.space-x-4.pb-5 > button.cursor-pointer.text-esg27.bg-esg5.ml-10.p-1\\.5.rounded.leading-3.text-uppercase.font-inter.font-bold.text-xs").click();
        cy.wait(3000);

        // //validate report
        cy.visit('https://test.esg-maturity.com/dashboards/342');

        // Get the SVG element -documentation
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(1) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(3) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(5) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(7) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(9) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(11) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(13) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(2) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });
        
        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(4) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(6) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(8) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(10) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(12) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(14) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // //check canvas graphs
        
        //energy
        cy.get("#app > main > div > div > div > div:nth-child(4) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(2) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('1722');
        //residuos
        cy.get("#app > main > div > div > div > div:nth-child(4) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(3) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('154');
        //waste
        cy.get("#app > main > div > div > div > div:nth-child(4) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(4) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('154');
        //fired
        cy.get("#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(5) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('15');
        //lesoes
        cy.get("#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(6) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('4');
        
        // //anual reports
        // Get the SVG element
        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(1) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasBlueColor).to.be.true;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(2) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(3) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasBlueColor).to.be.true;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(4) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(5) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(6) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });
    }

    createScreenQuestionnaire2(){
        document.querySelector("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(3) > input")
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.clickCreateQuestionnaireBtn();
        cy.get('#type').select("Screen");
        cy.get('[data-test=save-btn]').click();
        cy.wait(10000);
        
        cy.get("#app > main > div > div > div > div.flex-1.p-4 > div.w-full.justify-between.grid.grid-cols-1.gap-20.md\\:grid-cols-3 > div:nth-child(1) > h2 > a").click();

        cy.get("#question-32170 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32171 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32172 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32173 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2002')
        cy.get("#question-32174 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32175 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32175 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('25')
        cy.get("#question-32175 > div > div:nth-child(2) > div > div > div:nth-child(4) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32175 > div > div:nth-child(2) > div > div > div:nth-child(5) > input").type('43')
        cy.get("#question-32176 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32177 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2')
        cy.get("#question-32178 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('75')
        cy.get("#question-32179 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('64')
        cy.get("#question-32180 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32181 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32182 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1999')
        cy.get("#question-32183 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('50')
        cy.get("#question-32184 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('4250')
        cy.get("#question-32185 > div > div:nth-child(2) > div > textarea").type('These are the non-renewable sources used')
        cy.get("#question-32186 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32187 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('4300')
        cy.get("#question-32188 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('180')
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(4) > label > input").check();
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(5) > input").type('150');
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(6) > label > input").check();
        cy.get("#question-32189 > div > div:nth-child(2) > div > div > div:nth-child(7) > input").type('30');
        cy.get("#question-32190 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32191 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.get("#question-32191 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('50')
        cy.get("#question-32191 > div > div:nth-child(2) > div > div > div:nth-child(4) > label > input").check();
        cy.get("#question-32191 > div > div:nth-child(2) > div > div > div:nth-child(5) > input").type('5');
        cy.get("#question-32192 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32193 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2015')
        cy.get("#question-32194 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32195 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('225')
        cy.get("#question-32196 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32197 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('21')
        cy.get("#question-32198 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32199 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(10) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(11) > input").type('12')
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(13) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(14) > input").type('1')
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(23) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(24) > input").type('15')
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(34) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32200 > div > div:nth-child(2) > div > div > div:nth-child(35) > input").type('5')
        cy.get("#question-32201 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32204 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32207 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32212 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32214 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32215 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32216 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32217 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32225 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32226 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32231 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32232 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32235 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32236 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32240 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32241 > div > div:nth-child(2) > div > div > div:nth-child(2) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32241 > div > div:nth-child(2) > div > div > div:nth-child(3) > input").type('275')
        cy.get("#question-32241 > div > div:nth-child(2) > div > div > div:nth-child(4) > label > input").check();
        cy.get("#question-32241 > div > div:nth-child(2) > div > div > div:nth-child(5) > input").type('75')
        cy.wait(2000)

        //next
        cy.get("#app > main > div > div > div > div.md\\:ml-4.md\\:pl-20.sm\\:col-span-3 > div.border-t-esg6.text-esg29.mb-8.grid.grid-cols-2.border-t-\\[1px\\].pt-7.px-4.lg\\:px-0 > div.text-right > a").click();

        cy.get("#question-32165 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32166 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('15')
        cy.get("#question-32166 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('3')
        cy.get("#question-32166 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('2')
        cy.get("#question-32167 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32168 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('225');
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('423');
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('1');
        cy.wait(2000)
        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('12');
        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('5');
        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('1');
        cy.wait(2000)
        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('2');
        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('5');
        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('0');
        cy.wait(2000)
        cy.get("#question-32224 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('3');
        cy.get("#question-32243 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32244 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1721')
        cy.get("#question-32245 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1432')
        cy.wait(2000)
        cy.get("#question-32248 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32249 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('87325')
        cy.get("#question-32250 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('25132')
        cy.wait(2000)
        cy.get("#question-32251 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32252 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('5')
        cy.get("#question-32252 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('2')
        cy.get("#question-32252 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('1')
        cy.wait(2000)
        cy.get("#question-32255 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32256 > div > div:nth-child(2) > div > p:nth-child(3) > label > input").check();
        cy.get("#question-32256 > div > div:nth-child(2) > div > p:nth-child(6) > label > input").check();
        cy.get("#question-32257 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32258 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();

        //next
        cy.get("#app > main > div > div > div > div.md\\:ml-4.md\\:pl-20.sm\\:col-span-3 > div.border-t-esg6.text-esg29.mb-8.grid.grid-cols-2.border-t-\\[1px\\].pt-7.px-4.lg\\:px-0 > div.text-right > a").click();

        cy.get("#question-32169 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32203 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32205 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32208 > div > div:nth-child(2) > div > label:nth-child(4) > input").check();
        cy.get("#question-32209 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('1')
        cy.get("#question-32209 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('4')
        cy.get("#question-32209 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('0')
        cy.get("#question-32210 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32213 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32219 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32220 > div > div:nth-child(2) > div > p:nth-child(2) > label > input").check();
        cy.get("#question-32220 > div > div:nth-child(2) > div > p:nth-child(7) > label > input").check();
        cy.get("#question-32221 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32222 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('25')
        cy.get("#question-32223 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('74')
        cy.wait(2000)
        cy.get("#question-32227 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32228 > div > div:nth-child(2) > div > p:nth-child(4) > label > input").check();
        cy.get("#question-32228 > div > div:nth-child(2) > div > p:nth-child(9) > label > input").check();
        cy.get("#question-32229 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32230 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.wait(2000)
        cy.get("#question-32234 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32238 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32239 > div > div:nth-child(2) > div > p:nth-child(4) > label > input").check();
        cy.get("#question-32239 > div > div:nth-child(2) > div > p:nth-child(8) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32246 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32247 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32253 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32254 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32259 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32260 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32261 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32262 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('10452.1')
        cy.wait(2000)
        cy.get("#question-32263 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32264 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32265 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32266 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1')
        cy.wait(2000)
        cy.get("#question-32267 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32268 > div > div:nth-child(2) > div > p:nth-child(4) > label > input").check();
        cy.get("#question-32268 > div > div:nth-child(2) > div > p:nth-child(5) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32269 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32271 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32272 > div > div:nth-child(2) > div > p:nth-child(8) > label > input").check();
        cy.get("#question-32272 > div > div:nth-child(2) > div > p:nth-child(14) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32273 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32274 > div > div:nth-child(2) > div > p:nth-child(3) > label > input").check();
        cy.get("#question-32274 > div > div:nth-child(2) > div > p:nth-child(6) > label > input").check();
        cy.get("#question-32274 > div > div:nth-child(2) > div > p:nth-child(7) > label > input").check();
        cy.wait(2000)
        cy.get("#question-32278 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('512412578');
        cy.get("#question-32279 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('23524257');
        cy.get("#question-32280 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('425788');
        cy.get("#question-32281 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('230542578');
        cy.get("#question-32282 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('457589');
        cy.get("#question-32283 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('5676859');
        cy.get("#question-32284 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('554765');
        cy.get("#question-32285 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('6571233');
        cy.get("#question-32286 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('5524267');
        cy.get("#question-32287 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('5225868');
        cy.get("#question-32288 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('3214215');
        cy.get("#question-32289 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('233267');
        cy.get("#question-32290 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('454215');
        cy.get("#question-32291 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1252389');
        cy.wait(2000)
        cy.get("#question-32292 > div > div:nth-child(2) > div > label:nth-child(2) > input").check();
        cy.get("#question-32293 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('98')
        cy.get("#question-32294 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1242517')
        cy.get("#question-32295 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > div.relative > input").type('22.25')
        cy.get("#question-32296 > div > div:nth-child(2) > div > p > label > input").check();

        // nav again to questionnaires page
        cy.wait(5000);
        this.navToQuestionnairesPage();

        //search by all questionnaires - go to last questionnaire and submit
        cy.get("#filterBarComponent > div.flex.flex-row.flex-wrap.gap-5.justify-items-center.mb-5 > div.flex.flex-row.items-center.h-\\[40px\\].w-fit > div > div.w-full.h-full.flex.flex-row.items-center.justify-between.ml-1 > a").click();
        cy.get("#tomselect-1-opt-1 > input[type=checkbox]").check();
        cy.get("#app > main > div > div > div:nth-child(4) > div > nav > div.hidden.sm\\:flex.sm\\:flex-1.sm\\:items-center.sm\\:justify-between > div:nth-child(2) > span > span:nth-child(5) > button").click();
        cy.get("#app > main > div > div > div:nth-child(4) > div > nav > div.hidden.sm\\:flex.sm\\:flex-1.sm\\:items-center.sm\\:justify-between > div:nth-child(2) > span > span:nth-child(5) > button").click();
        cy.contains("Submeter").should('be.visible').click();
        cy.get("#modal-container > div > div > div > div.mt-4.flex.justify-center.space-x-4.pb-5 > button.cursor-pointer.text-esg27.bg-esg5.ml-10.p-1\\.5.rounded.leading-3.text-uppercase.font-inter.font-bold.text-xs").click();
        cy.wait(3000);

        // //validate report
        cy.visit('https://test.esg-maturity.com/dashboards/333');

        // Get the SVG element -documentation
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(1) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(3) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(5) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(7) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(9) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(11) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(13) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(2) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });
        
        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(4) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(6) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(8) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(10) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(12) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(14) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasGreenColor).to.be.true;
        });

        //check canvas graphs
        
        //energy
        cy.get("#app > main > div > div > div > div:nth-child(4) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(2) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('4300');
        //residuos
        cy.get("#app > main > div > div > div > div:nth-child(4) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(3) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('180');
        //waste
        cy.get("#app > main > div > div > div > div:nth-child(4) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(4) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('150');
        //fired
        cy.get("#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(5) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('3');
        //lesoes
        cy.get("#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(6) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('74');

        //anual reports
        // Get the SVG element
        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(1) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasBlueColor).to.be.true;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(2) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasBlueColor).to.be.true;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(3) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(4) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(5) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(6) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.false;
            expect(hasBlueColor).to.be.true;
        });
    }

    createScreenQuestionnaire3(){
        cy.wait(1200);
        this.navToQuestionnairesPage();
        this.clickCreateQuestionnaireBtn();
        cy.get('#type').select("Screen");
        cy.get('[data-test=save-btn]').click();
        cy.wait(10000);

        cy.get("#app > main > div > div > div > div.flex-1.p-4 > div.w-full.justify-between.grid.grid-cols-1.gap-20.md\\:grid-cols-3 > div:nth-child(1) > h2 > a").click();

        cy.get("#question-32170 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32204 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32207 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32212 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32214 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32225 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32231 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32235 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32240 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        
        //next
        cy.get("#app > main > div > div > div > div.md\\:ml-4.md\\:pl-20.sm\\:col-span-3 > div.border-t-esg6.text-esg29.mb-8.grid.grid-cols-2.border-t-\\[1px\\].pt-7.px-4.lg\\:px-0 > div.text-right > a").click();

        cy.get("#question-32165 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('12');
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('1');
        cy.get("#question-32202 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('2');

        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('2');
        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('1');
        cy.get("#question-32206 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('1');

        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(2) > input").type('1');
        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(3) > input").type('2');
        cy.get("#question-32211 > div > div:nth-child(2) > div > div:nth-child(4) > input").type('0');

        cy.get("#question-32224 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1');

        cy.get("#question-32243 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32248 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32251 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32255 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();

        //next
        cy.get("#app > main > div > div > div > div.md\\:ml-4.md\\:pl-20.sm\\:col-span-3 > div.border-t-esg6.text-esg29.mb-8.grid.grid-cols-2.border-t-\\[1px\\].pt-7.px-4.lg\\:px-0 > div.text-right > a").click();

        cy.get("#question-32169 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32203 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32205 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32208 > div > div:nth-child(2) > div > label:nth-child(10) > input").check();
        cy.get("#question-32213 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32219 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32227 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32234 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32238 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32246 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32247 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32253 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32254 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32259 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32260 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32261 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32263 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32264 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32265 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32267 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32269 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32271 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32273 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32278 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('857246');
        cy.get("#question-32279 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('234311');
        cy.get("#question-32280 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('8547');
        cy.get("#question-32281 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('4567');
        cy.get("#question-32282 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('5247');
        cy.get("#question-32283 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('4225');
        cy.get("#question-32284 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('3298');
        cy.get("#question-32285 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('6547');
        cy.get("#question-32286 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('6598');
        cy.get("#question-32287 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('1245');
        cy.get("#question-32288 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2154');
        cy.get("#question-32289 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('5875');
        cy.get("#question-32290 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2145');
        cy.get("#question-32291 > div > div:nth-child(2) > div > div.w-full.flex.items-center.py-2 > input").type('2123');
        cy.get("#question-32292 > div > div:nth-child(2) > div > label:nth-child(3) > input").check();
        cy.get("#question-32296 > div > div:nth-child(2) > div > p > label > input").check();

        // nav again to questionnaires page
        cy.wait(5000);
        this.navToQuestionnairesPage();

        //search by all questionnaires - go to last questionnaire and submit
        cy.get("#filterBarComponent > div.flex.flex-row.flex-wrap.gap-5.justify-items-center.mb-5 > div.flex.flex-row.items-center.h-\\[40px\\].w-fit > div > div.w-full.h-full.flex.flex-row.items-center.justify-between.ml-1 > a").click();
        cy.get("#tomselect-1-opt-1 > input[type=checkbox]").check();
        cy.get("#app > main > div > div > div:nth-child(4) > div > nav > div.hidden.sm\\:flex.sm\\:flex-1.sm\\:items-center.sm\\:justify-between > div:nth-child(2) > span > span:nth-child(5) > button").click();
        cy.get("#app > main > div > div > div:nth-child(4) > div > nav > div.hidden.sm\\:flex.sm\\:flex-1.sm\\:items-center.sm\\:justify-between > div:nth-child(2) > span > span:nth-child(5) > button").click();
        cy.contains("Submeter").should('be.visible').click();
        cy.get("#modal-container > div > div > div > div.mt-4.flex.justify-center.space-x-4.pb-5 > button.cursor-pointer.text-esg27.bg-esg5.ml-10.p-1\\.5.rounded.leading-3.text-uppercase.font-inter.font-bold.text-xs").click();
        cy.wait(3000);

        //validate report
        cy.visit('https://test.esg-maturity.com/dashboards/304');

        // Get the SVG element -documentation
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(1) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(3) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(5) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(7) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(9) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(11) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(13) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(2) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });
        
        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(4) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(6) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(8) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(10) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(12) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        // Get the SVG element
        cy.get('#app > main > div > div > div > div:nth-child(3) > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(14) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasGreenColor = svgString.includes('fill="#99CA3C"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasGreenColor).to.be.false;
        });

        //check canvas graphs
        

        //fired
        cy.get("#app > main > div > div > div > div:nth-child(5) > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.print\\:-mt-10.nonavoid > div:nth-child(4) > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div.pl-4.pt-2 > p").should('be.visible').contains('1');

        //anual reports
        // Get the SVG element
        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(1) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(2) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(3) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(4) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(5) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });

        cy.get('#app > main > div > div > div > div.grid.grid-cols-1.md\\:grid-cols-2.gap-5.mt-10.mb-20.nonavoid > div > div.text-esg25.font-encodesans.text-5xl.font-bold.h-full.grid.place-content-center > div > div:nth-child(6) > div > svg') // Replace '#my-svg' with the appropriate selector for your SVG
            .then(($svg) => {
            // Convert the SVG DOM element to a string
            const svgString = new XMLSerializer().serializeToString($svg[0]);

            // Check if the SVG string contains the colors grey or green
            const hasGreyColor = svgString.includes('fill="#C4C4C4"');
            const hasBlueColor = svgString.includes('fill="#06A5B4"');

            // Assert the conditions
            expect(hasGreyColor).to.be.true;
            expect(hasBlueColor).to.be.false;
        });
    }
}