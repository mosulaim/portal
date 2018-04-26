
# About
 This is phppgadmin web admin console patched for Heroku.

# How to use
	# in your heroku php app.
	heroku addons:add shared-database
	git submodule add git://github.com/yandod/phppgadmin-heroku.git
	git add .
	git commit -m "Add phppgadmin-heroku"
	git push origin master

# Login

 You can see username:password combo from heroku config.
	