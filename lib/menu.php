/* written by xiao xiangjun*/
			<div id="header">
                <<h1 style="font-size:60px;">CorkBoardit Homepage</h1>
			</div>
			
			<div class="nav_bar">
				<ul>    
                    <li><a href="view_homepage.php" <?php if($current_filename=='view_homepage.php') echo "class='active'"; ?>>View Homepage</a></li>      
                    <li><a href="popular_tags.php"  <?php if($current_filename=='popular_tags.php')  echo "class='active'"; ?>>Popular Tags</a></li>  
                    <li><a href="popular_sites.php" <?php if($current_filename=='popular_sites.php') echo "class='active'"; ?>>Popular Sites</a></li>  
                    <li><a href="statistics.php"    <?php if($current_filename=='statistics.php')    echo "class='active'"; ?>>CorkBoard Statistics</a></li>  
                    <li><a href="logout.php"        <span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>              
				</ul>
			</div>
			
			<div>
			<form  action="pushpin_search.php"   method="GET">
            <input type="text" name="keyword" placeholder="preferable one key word" required>
            <input type="submit" value="PushPin Search">
            </form>
			</div>