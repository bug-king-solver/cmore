describe('Run all test cases', () => {
    // This hook runs before all tests in the current suite
    before(() => {
        // Code to run before the tests start
    });

    require('../specs/signup.cy');
    require('../specs/login.cy');
    require('../specs/targets.cy');
    require('../specs/tags.cy');


    //need to be mantained -There are new implementations on staging environment that break the following tests:
    //require('../specs/users.cy');
    //require('../specs/companies.cy');
    //require('../specs/tasks.cy');
    //require('../specs/permissions.cy');
    

    //new implementations - need to be completed
    //require('./passwordreset.cy');
    //require('./questionnaires.cy');
    //require('./report.cy');
    //require('../specs/dashboards.cy');
    //require('../specs/custom.cy');
    //require('../specs/questionnaires.cy');



    // This hook runs after all tests in the current suite
    after(() => {
        // Code to run after all tests finish
    });
});