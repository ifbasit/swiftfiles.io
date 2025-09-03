// app.class.php

	public function install_wordpress($user_path, $db_name, $db_user, $db_password, $wp_url, $wp_title, $wp_admin_user, $wp_admin_password, $wp_admin_email) {
	    $this->fix_apt_locks();

	    if($this->is_db_user_exists($db_user)){
	    	return false;
	    } else if($this->is_database_exists($db_name)){
	    	return false;
	    } else {
	    	$this->create_database($db_name, $db_user, $db_password);

	    	return $this->execute_command("

		        sudo apt install -y apache2 mysql-server php libapache2-mod-php php-mysql unzip wget &&
		        
		        sudo systemctl enable apache2 mysql &&
		        sudo systemctl start apache2 mysql &&
		        
		        cd /tmp &&
		        sudo wget -q https://wordpress.org/latest.zip &&
		        sudo unzip -q latest.zip &&
		        sudo rm latest.zip &&
		        sudo mv wordpress/* $user_path &&
		        sudo rm -rf wordpress &&
		        
		        sudo chown -R www-data:www-data $user_path &&
		        sudo chmod -R 755 $user_path &&
		        
		        sudo cp wp-config-sample.php wp-config.php &&
		        sudo sed -i \"s/database_name_here/$db_name/\" wp-config.php &&
		        sudo sed -i \"s/username_here/$db_user/\" wp-config.php &&
		        sudo sed -i \"s/password_here/$db_password/\" wp-config.php &&
		        
		        sudo systemctl restart apache2 &&
		        
		        sudo wget -q https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar &&
		        sudo chmod +x wp-cli.phar &&
		        sudo mv wp-cli.phar /usr/local/bin/wp &&
		        sudo -u www-data wp core install --path=$user_path --url='$wp_url' --title='$wp_title' --admin_user='$wp_admin_user' --admin_password='$wp_admin_password' --admin_email='$wp_admin_email'
		    ");
	    }

	    
	}


// requests.php
public function install_wp(){
		global $app;
		global $server;
		if (!isset($this->data)) {
	        return json_encode([
	        	'message' => 'Invalid request data.',
	        	'status' => 'error',

	        ]);
	    }

	    $db_name = isset($this->data['db-name']) ? trim($this->data['db-name']) : '';
	    $db_user = isset($this->data['db-user']) ? trim($this->data['db-user']) : '';
	    $db_password = isset($this->data['db-password']) ? trim($this->data['db-password']) : '';
	    
	    $wp_site_title = isset($this->data['wp-site-title']) ? trim($this->data['wp-site-title']) : '';
	    $wp_admin_username = isset($this->data['wp-admin-username']) ? trim($this->data['wp-admin-username']) : '';
	    $wp_admin_password = isset($this->data['wp-admin-password']) ? trim($this->data['wp-admin-password']) : '';
	    $wp_admin_email_address = isset($this->data['wp-admin-email-address']) ? trim($this->data['wp-admin-email-address']) : '';
	    
	    $app_uniqid = isset($this->data['app-id']) ? trim($this->data['app-id']) : '';
	    $server_uniqid = isset($this->data['server-id']) ? trim($this->data['server-id']) : '';

	    $the_app = $app->get_app_by_uniqid($app_uniqid);
		$the_server = $server->get_server_by_uniqid($server_uniqid);

		$system_user = $app->get_system_user_by_id($the_app['id'])[0]['system_user'];
		$app_name = $the_app['name'];

	    if(empty($db_name) || empty($db_user) || empty($db_password) || empty($app_uniqid) || empty($server_uniqid)
		|| empty($wp_site_title) || empty($wp_admin_username) || empty($wp_admin_password) || empty($wp_admin_email_address)){
	    	return json_encode([
				'message' => 'All fields are required.',
				'status' => 'error',
			]);
	    } else if(!Helper::is_valid_wp_title($wp_site_title)){
	    	return json_encode([
			    'message' => '<ul>
			                    <li>The WordPress site title must not exceed 100 characters.</li>
			                    <li>The WordPress site title cannot contain special characters such as < and >.</li>
			                  </ul>',
			    'status' => 'error',
			]);

	    } else if(!Helper::is_valid_wp_admin_username($wp_admin_username)){
	    	return json_encode([
			    'message' => '<ul>
			                    <li>The WordPress admin username must be at least 4 characters long.</li>
			                    <li>The WordPress admin username can only contain letters (A-Z, a-z), numbers (0-9), dots (.), underscores (_), and hyphens (-).</li>
			                  </ul>',
			    'status' => 'error',
			]);

	    } else if(!Helper::is_valid_wp_password($wp_admin_password)){
	    	return json_encode([
			    'message' => '<ul>
			                    <li>The WordPress password must be at least 6 characters long.</li>
			                  </ul>',
			    'status' => 'error',
			]);

	    } else if(!Helper::is_email($wp_admin_email_address)){
	    	return json_encode([
				'message' => 'Invalid E-mail address.',
				'status' => 'error',
			]);
	    } else if(!Helper::is_valid_db_name($db_name)){
	    	return json_encode([
				'message' => '<ul>
							    <li>Database name must start with a letter or underscore (_).</li>
							    <li>It can only contain letters (A-Z, a-z), numbers (0-9), underscores (_), and dollar signs ($).</li>
							    <li>Maximum length is 64 characters.</li>
							</ul>',
				'status' => 'error',
			]);
	    } else if(!Helper::is_valid_db_user($db_user)){
	    	return json_encode([
				'message' => '<ul>
							    <li>Username can only contain letters (A-Z, a-z), numbers (0-9), underscores (_), and hyphens (-).</li>
							    <li>It must be between 1 and 32 characters long.</li>
							</ul>',
				'status' => 'error',
			]);
	    } else if(!Helper::is_valid_db_password($db_password)){
	    	return json_encode([
				'message' => '<ul>
						    <li>Password must be at least 8 characters long.</li>
						    <li>Password cannot exceed 255 characters.</li>
						</ul>',
				'status' => 'error',
			]);
	    } else if($app->is_db_user_exists($db_user)){
	    	return json_encode([
				'message' => 'This DB user already exists.',
				'status' => 'error',
			]);
	    } else if($app->is_database_exists($db_name)){
	    	return json_encode([
					'message' => 'This DB name already exists.',
					'status' => 'error',
				]);
	    } else if(!$app->test_connection($the_server['ip_address'],'root', Helper::decrypt_password($the_server['password']))){
	    		return json_encode([
					'message' => 'Authentication Failed',
					'status' => 'error',
				]);
	    } else {
	    	$app->create_database($db_name, $db_user, $db_password);
	    	$db->insert('dbs', [
		            'db_name' => $db_name, 
		            'db_user' => $db_user, 
		            'db_password' => Helper::encrypt_password($db_password),
		            'app_id' => $the_app['id'], 
		            'server_id' => $the_server['id'],
		            'uniqid' => uniqid()
	        ]);

	    	$db_id = $this->db->get_last_inserted_id();

    		

	        $user_path = "/srv/users/".$system_user."/apps/".$app_name."/public";
	        if($the_app['is_ssl'] == 1){
	        	$wp_url = "https://".$the_app['domain'];
	        } else {
	        	$wp_url = "http://".$the_app['domain'];
	        }

	    	$app->install_wp(
	    		$user_path, 
	    		$db_name, 
	    		$db_user, 
	    		$db_password, 
	    		$wp_url, 
	    		$wp_site_title, 
	    		$wp_admin_username, 
	    		$wp_admin_password, 
				$wp_admin_email_address
	    	);

	    	$db->insert('wp', [
		            'app_id' => $the_app['id'], 
		            'user_id' => $the_app['user_id'], 
		            'db_id' => $db_id,
		            'server_id' => $the_server['id'], 
		            'wp_site_title' => $wp_site_title,
		            'wp_admin_username' => $wp_admin_username,
		            'wp_admin_password' => Helper::decrypt_password($wp_admin_password),
		            'wp_admin_email_address' => $wp_admin_email_address,
	        ]);
	    }


	}


	helper.class.php
		public static function is_valid_wp_title($title) {
	    return is_string($title) && strlen($title) > 0 && strlen($title) <= 100 && !preg_match('/[<>]/', $title);
	}
	public static function is_valid_wp_password($password) {
	    return is_string($password) && strlen($password) >= 6;
	}

	public static function is_valid_wp_admin_username($username) {
	    return is_string($username) && preg_match('/^[a-zA-Z0-9._-]{4,}$/', $username);
	}



	public static function is_valid_db_name($db_name) {
	    return preg_match('/^[a-zA-Z_][a-zA-Z0-9_$]{0,63}$/', $db_name);
	}

	public static function is_valid_db_user($user) {
	    return preg_match('/^[a-zA-Z0-9_-]{1,32}$/', $user);
	}

	public static function is_valid_db_password($password) {
	    