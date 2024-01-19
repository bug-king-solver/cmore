### Here's a guide on how to install and configure VcXsrv on WSL:

#### Download the VcXsrv installer from the SourceForge website: https://sourceforge.net/projects/vcxsrv/

Once the installation is complete, open a terminal window and run the following command to set the DISPLAY environment variable:

```
export DISPLAY=$(cat /etc/resolv.conf | grep nameserver | awk '{print $2; exit;}'):0.0
```

Edit the .bashrc file in your home directory by running the following command:

```
nano ~/.bashrc
```

Add the following line to the end of the file:

```
sudo /etc/init.d/dbus start &> /dev/null
```

Save and close the .bashrc file.

Run the following command to reload the .bashrc file:
```
source ~/.bashrc
```

Run the following command to edit the sudoers file:
```
sudo visudo -f /etc/sudoers.d/dbus
```

Add the following line to the file, replacing <your_username> with your actual username:
```
<your_username> ALL = (root) NOPASSWD: /etc/init.d/dbus
```
Save and close the sudoers file.


reference: https://nickymeuleman.netlify.app/blog/gui-on-wsl2-cypress

- Note: You dont need to install anything on your wsl .
- Dont run npm install and npm cy:open on wsl

That's it! You should now have VcXsrv installed and configured on your Ubuntu system.


### On windows , open XLaunch app

1. After open , click next ( without change anything )
2. Click next again
3. Click to  "Disable access control" and click next

#### on WSL

- Run : docker compose up -d
- XLaunch app will open a window
- Choose the browser and click next

#### GIT

- Some .mp4 files are tracked by git. To remove then :
- update your branch
- run: git rm --cached -r cypress/**/*.mp4
- run: git commit -m "remove mp4 files"
- run: git push origin <your_branch>

# E2E specs:

### Completed  ✅ : 

SignUp Page
  ✓ Registration with missing required fields 
  ✓ Registration with invalid email address 
  ✓ Registration with password that doesn't meet requirements 
  ✓ Registration with existing email address 

Login Page
  ✓ Login with incorrect username 
  ✓ Login with incorrect password 
  ✓ Login with missing username 
  ✓ Login with missing password 
  ✓ Successful Login 

Users Page
  ✓ User create a user successfully 
  ✓ User edits a user successfully 
  ✓ User delete a user successfully 
  ✓ User can create a new role 
  ✓ User can edit a role 
  ✓ User can delete a role 

Companies Page
  ✓ User create a company successfully
  ✓ User edits a company successfully 
  ✓ User views details of a created company 
  ✓ User delete a company successfully

Targets Page
  ✓ User can create a new target 
  ✓ User can edit target 
  ✓ User can create a task in a target 

Tasks Page
  ✓ User can create a new task 
  ✓ User can edit task 
  ✓ User can view task 
  ✓ User can delete target 

Tags Page
  ✓ User can create a new tag 
  ✓ User can edit tag 
  ✓ User can delete tag 
  ✓ User can restore a tag 

Permissions Page
  ✓ User with all permisions
  ✓ Tenants  without targets feature - access by url and menu
  ✓ Tenants  without tasks feature - access by url and menu
  ✓ Tenants  without tags feature - access by url and menu
  ✓ Tenants  without dynamic dashboard - access by url and menu
  ✓ Tenants  without reputation - access by url and menu
  ✓ Tenants  without compliance - access by url and menu
  ✓ User with Dashboard > All permission
  ✓ User with Dashboard > Only view, update and create permission
  ✓ User with Dashboard > Only view and update permission
  ✓ User with Dashboard > Only view permission
  ✓ User with Library > All permission
  ✓ User with Library > Only view, update and create permission
  ✓ User with Library > Only view and update permission
  ✓ User with Library > Only view permission
  ✓ User with Companies > All permission
  ✓ User with Companies > Only view, update and create permission
  ✓ User with Companies > Only view and update permission
  ✓ User with Companies > Only view permission
  ✓ User delete a company successfully