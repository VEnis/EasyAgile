Feature: Admin area login
   In order to access distribution admin interface
   As website administrator
   I need to be able to login to admin area and logout from it

   @javascript
   Scenario: Accessing login area
      Given I am on homepage      
      When I go to "/admin"      
      Then I should see "Username:"
      And I should see "Password:"
      And I should see "Remember me"
      
      When I fill in the following:
         | username | VEnis  |
         | password | 123qwe |
      And I press "Login"      
      Then I should see "Dashboard"
      When I follow "Logout"
      Then I should see "Username:"
      And I should see "Password:"
      And I should see "Remember me"
