Feature: Users administration
   In order to change registered users
   As website administrator
   I need to be able to manage users

   @javascript
   Scenario: Accessing users management
      Given I am logged in as "VEnis" with "123qwe" password
      When I follow "Users"
      Then I should see "Users"
      And I should see "VEnis"      
   