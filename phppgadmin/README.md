# NOTE
This cartridge requires `phpPgAdmin` 5.0.x to be installed on the node on which your application is running.
This will be installed when this cartridge is, but this is not the case with OpenShift Online as of this writing.

If you are running your own installation of Origin, then I assume you know what you are doing
and that you can make use of this cartridge.

# OpenShift phpPgAdmin Cartridge

The `phppgadmin` cartridge provides [phpPgAdmin](http://phppgadmin.sourceforge.net/) on OpenShift.

Add this cartridge to an application that already has PostgreSQL:

    rhc cartridge add phppgadmin-5.0 -a APP

Admin user name and password will be displayed.

And access `/phppgadmin` on your application's site and log in with
the credentials given above.
