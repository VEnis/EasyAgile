Base url
--------

api.easyagile.com

Resources
---------

 - User
 - Planing poker:
    - Session
    - Story
    - StoryEstimate

States (excluding login-related states)
---------------------------------------

List of sessions (/planing-poker/sessions). Collection
------------------------------------------------------

Returns links (or information) about sessions, available for user. There are two types of sessions: own and involved

Operations:
 -  GET - returns list of sessions. Possible filters: type(own/involved)
 -  POST - create new session

Links to:
 -  Session instance

Session instance (/planing-poker/sessions/1234). Instance
---------------------------------------------------------

Returns information about session

Links to:
 -  Sessions
