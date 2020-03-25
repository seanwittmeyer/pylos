# forge-lemur
Lemur is a silly playground for forging design automation apps for headless revit in the cloud

## the idea
This was written as a simple web app that simplifies the process of creating and updating forge design automation api v3 "activities," "appbundles," and running the apps as "workitems" with a gui as opposed to a more hands on code approach. It simply automates the postman sample autodesk provided. It has been adapted and tested for the revit 2020 engine.

## start
Start by initializing a session. This will access the authentication api and generate a token we can use to access forge, it currently uses a single account but can be adapted so that users sign in and use their own forge/cloud credits for runtime use.
You then load the pages of activities. You can see activities in lemur that have been archived for reuse, modify/update activities in lemur, or pull a list of what forge has access to (which should be a mirror of what lemur has). Lemur will push changes to forge.
Creating an activity will allow you to upload an appbundle for use with the activity and it will use the session's token to associate it for use if you choose to create a workitem and run the addin.

## using an addin in headless revit
Once an activity and associated appbundle are in lemur and ready to be beamed up to the enterprise, we find the revit resource we want to edit or use (currently assuming we will use those in bim360), and we create a workitem. Lemur will periodically check progress once one is running until it gets a success/failure response from forge. Results will be displayed.

## ch ch ch changes
This workflow is almost guarnateed to change, I felt we needed something on "paper" to move forward. -sw
