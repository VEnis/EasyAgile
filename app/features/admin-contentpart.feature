Feature: Content Part administration
   In order to change textual content on website
   As website administrator
   I need to be able to manage content parts

   @javascript
   Scenario: Accessing content part management
      Given I am logged in as "VEnis" with "123qwe" password
      When I follow "Content"
      And  I follow "ContentPart"
      Then I should see "Content Part List"

   @javascript
   Scenario: Creating, editing and deleting new content part
      Given I am logged in as "VEnis" with "123qwe" password
      When I follow "Content"
      And  I follow "ContentPart"
      And follow "Add new"
      Then I should see "Content Part Create"
      When I fill in the following:
         | Name              | MyTestContentPart  |
         | Title             | Test content part  |
         | Raw Content       | * My List Item     |
      And I press "Create"
      Then I should see "Item has been successfully created."
      And I should see "Edit \"MyTestContentPart\""
      
      When I follow "Return to list"
      Then I should see "Content Part List"
      And I should see "MyTestContentPart"

      When I follow "MyTestContentPart"
      And I should see "Edit \"MyTestContentPart\""

      When I follow "Delete"
      Then I should see "Content Part Delete"
      And I should see "Confirm deletion"
      When I press "Yes, delete"
      Then I should see "Item has been deleted successfully."
      And I should see "Content Part List"
