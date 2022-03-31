Chatter is a messaging website where you can find and search any user and message them or add them into a group. If you want to clone the website and test it out
you will have to first install xampp at https://www.apachefriends.org/index.html. After installation open the xampp <b>folder</b> which should be inside the C drive 
if your own windows. Inside the xampp folder open <b>htdocs</b> then create a new folder called <b>chatter1</b>. Inside chatter1 paste all the files from this repository.
Now you will have to setup the database which is fairly easy using xampp and phpmyadmin. Open the xampp application on your computer then start <b>mysql</b> after
starting mysql click admin next to it. phpmyadmin should open and you should be able to create an account (this might be different in the future). Make sure you remember
the details for the account. After making an account and clicking new on the sidebar phpmyadmin will make you create a table just click sql on the top navbar and paste this code

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo` longblob DEFAULT '',
  `about` varchar(535) NOT NULL DEFAULT 'No about...'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
  

click go and the table should be created. 

Now you have to create the messages table click ssql at the top again and paste in

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `reciever_id` int(11) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;


click go and now you are done creating the table. There is two more tables for this website for groupchats.

click on the database and then click sql then paste this code for the groupchat

CREATE TABLE `groupchat` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recievers_id` varchar(255) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `groupchat`
  ADD PRIMARY KEY (`id`);
  
  ALTER TABLE `groupchat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
  
  After this you have added 3/4 of the tables. For the last table pase this code
  
  CREATE TABLE `groupchats` (
  `id` int(11) NOT NULL,
  `founder` varchar(255) NOT NULL,
  `members` varchar(255) NOT NULL DEFAULT 'None',
  `name` varchar(255) NOT NULL DEFAULT 'None',
  `unique_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `groupchats`
  ADD PRIMARY KEY (`id`);
  
  ALTER TABLE `groupchats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
  
  now you have succesfully created all the tables and can close phpmyadmin. For the final step open database.php which you pasted earlier in C:\xampp\htdocs\chatter1
  inside the mysqli_connect type in the following
  mysqli_connect("localhost", "your username from phpmyadmin", "your password from phpmyadmin", "chatter1");
  now you have succesfully cloned chatter
  
  to open it open xampp and start apache and mysql. Click admin next to apache and paste this url http://localhost/chatter1/. If something did'nt work please contact me at <b>nabeel30march@gmail.com</b>
  

