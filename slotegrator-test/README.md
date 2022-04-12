<h2>Test Game</h2>

<h3>installation</h3>
<p>After cloning the repository, go to the project directory and run the commands: </p>
<code>php init</code>
<p>Create a database and write its name and user in the file - /common/config/main-local.php</p>
<p>Start database table migration:</p>
<code>php yii migrate</code>

<p>Through the frontend registration form, create a user with the name - "slotegrator-admin". This will be a user with admin rights. Or create any other user or users and add their logins to the file: /common/config/params.php These users will have admin rights.</p>
<code> 'admins' => ['slotegrator-admin'],
          'admins_id' => [1 ],</code>
<p>
Admin panel login: domain_name/admin
</p>

<h3>Usage</h3>
<p>In the admin panel, you can view all the prizes won and confirm (make) their sending.
For material prizes, this is a formal sending to the address, for cash prizes, it is credited to the account through the bank's app, and for bonus prizes, it is credited to the bonus account.
 You can also filter data for all fields.</p>

<p>using the console command, you can transfer money to the users account with a package of prizes:</p>
<code>php yii console/bank n</code>
<p>n - the number of prizes to be credited</p>
<p>The prize for winnings is in the - selected state. After entering the delivery data by the user, the status changes to confirmed. The operator in the admin panel, by clicking on the button - Send, transfers the prize to the state - sent. The user can see the list and status of his prizes in his personal account, and can also refuse the prize (until the time of sending).
The user in his account can convert the bonus prize into many prize</p>

<p>You can set the frequency of wins in the configuration file: /common/config/params.php</p>
<code> 'gameEmptyValueNumber' => 200,
           'Many.repeatabilityFrom' =>2,
           'Many.repeatabilityTo' =>200,
           'Bonus.repeatabilityFrom' =>20,
           'Bonus.repeatabilityTo' =>2000,
           'Item.repeatabilityFrom' =>2,
           'Item.repeatabilityTo' =>200,</code>



