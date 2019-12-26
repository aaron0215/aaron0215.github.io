<!-- ######################     Main Navigation   ########################## -->
<nav id="final">
    <ol>
        <?php
        print '<li class="';
        if ($path_parts['filename'] == "home") {
            print ' activePage ';
        }
        print '">';
        print '<a href="home.php">Home</a>';
        print '</li>';

        print '<li class="';
        if ($path_parts['filename'] == "menu") {
            print ' activePage ';
        }
        print '">';
        print '<a href="menu.php">Menu</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "reserve") {
            print ' activePage ';
        }
        print '">';
        print '<a href="reserve.php">Reservation</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "aboutus") {
            print ' activePage ';
        }
        print '">';
        print '<a href="aboutus.php">About us</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "contact") {
            print ' activePage ';
        }
        print '">';
        print '<a href="contact.php">Contact us</a>';
        print '</li>';
        
        ?>
    </ol>
</nav>