Feature: test
   In order to see distribution start page
   As website wisitor
   I need to be able to request start page
      
   Scenario: Homepage
      Given I am on homepage      
      Then I should see "Example page for Media"
      Then I should see "Example page for database twig templates"
      Then I should see "Example page for formatter"
      Then I should see "Example page for DifaneContentPartBundle"