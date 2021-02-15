'use strict'

describe('Test login', () => {
    before(() => {
        cy.exec('npm run dbreset')
    })

    beforeEach(() => {
        cy.visit('/')
        cy.contains('div', 'Login').should('be.visible')
    })

   it('It must load register view', () => {
       cy.fixture('user.json').as('userData')

       cy.get('@userData').then((userData) => {
           cy.contains('Register').click()
           cy.get('#name').type(userData.name)
           cy.get('#email').type(userData.email)
           cy.get('#password').type(userData.password)
           cy.get('#password-confirm').type(userData.password)
           cy.contains('button', 'Register').click()
           cy.get('.invalid-feedback').should('not.exist')
       })
   })


   it('It must login', () => {
       cy.fixture('user.json').as('userData')

       cy.get('@userData').then((userData) => {
           cy.get('#email').type(userData.email)
           cy.get('#password').type(userData.password)
           cy.contains('button', 'Login').click()
       })
   })

    it('It must fail with wrong user', () => {
        cy.get('#email').type('noexiste@test.com')
        cy.get('#password').type('12345678')
        cy.contains('button', 'Login').click()
        cy.get('.invalid-feedback').should('be.visible')
    })

    after(() => {
        cy.log('Tests finished')
    })
});
