 <nav class="navbar">
     <div class="navbar-title">
         BUDGET NA UY
     </div>
     <div class="dropdown">

         <img src="data:image/jpeg;base64,<?php echo $profile_image_base64; ?>" class="profile-image"
             onclick="toggleDropdown()">

         <div class="dropdown-content">
             <div>
                 <a href="../prof_settings.php">Profile Settings</a>
                 <br>
                 <a href="../form/logout.php">Logout</a>
             </div>
         </div>
         <p id="pro_us"><?php echo $user['username']; ?></p>
     </div>
 </nav>